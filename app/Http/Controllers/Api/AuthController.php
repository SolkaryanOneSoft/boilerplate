<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendVerificationEmail;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $userData['password'] = Hash::make($request->input('password'));

        $existingUser = User::where('email', $userData['email'])->first();

        if ($existingUser && !$existingUser->hasVerifiedEmail()) {
            $existingUser->delete();
        }

        $user = User::create($userData);

        $user->assignRole('user');

        $locale = app()->getLocale();

        SendVerificationEmail::dispatch($user, $locale);

        return $this->response201(['message' => __('auth.verification_url_sent_message')]);
    }

    public function verify($id, $hash): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            throw new CustomErrorException('user_not_found', 'auth', Response::HTTP_UNAUTHORIZED);
        }

        if (!hash_equals((string)$hash, sha1($user->getEmailForVerification()))) {
            throw new CustomErrorException('invalid_verification_url_message', 'auth', Response::HTTP_BAD_REQUEST);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->response200(['message' => __('auth.already_verified')]);
        }

        $user->markEmailAsVerified();

        Event::dispatch(new Verified($user));

        return $this->response200(['message' => __('successMessage.success')]);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $ip = $request->ip();
        $key = 'login-attempts:' . $ip;

        $maxAttempts = 100;
        $decayMinutes = 5;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            throw new CustomErrorException('too_many_attempts',
                'auth',
                Response::HTTP_TOO_MANY_REQUESTS,
                ['minutes' => ceil($seconds / 60) . ' minutes']);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $email = $request->input('email');
        $password = $request->input('password');
        $debug = $request->boolean('debug');

        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new CustomErrorException('user_not_found', 'auth', Response::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($password, $user->password)) {
            throw new CustomErrorException('incorrect_password', 'auth', Response::HTTP_UNAUTHORIZED);
        }

        RateLimiter::clear($key);

        $tokenRequest = HttpRequest::create('/oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => config('passport.client_id'),
            'client_secret' => config('passport.client_secret'),
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ]);

        $response = App::make(AccessTokenController::class)
            ->issueToken(app()->make(ServerRequestInterface::class)
                ->withParsedBody($tokenRequest->request->all()));

        $tokenData = json_decode($response->getContent(), true);

        if (!isset($tokenData['access_token'])) {
            throw new CustomErrorException('oauth_error', 'auth', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $accessTokenId = explode('|', $tokenData['access_token'])[0];

        if ($debug) {
            DB::table('oauth_access_tokens')
                ->where('id', $accessTokenId)
                ->update(['expires_at' => now()->addMinutes(1)]);

            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessTokenId)
                ->update(['expires_at' => now()->addMinutes(2)]);
        }

        $userRole = DB::table('model_has_roles')->where('model_id', $user->id)->value('role_id');

        return $this->response200([
            'access_token' => $tokenData['access_token'],
            'refresh_token' => $tokenData['refresh_token'],
            'expires_in' => $debug
                ? now()->addSeconds(15)->toDateTimeString()
                : now()->addSeconds($tokenData['expires_in'])->toDateTimeString(),
            'user' => $user,
            'role' => $userRole,
        ]);
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        $tokenRequest = HttpRequest::create('/oauth/token', 'POST', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->input('refresh_token'),
            'client_id' => config('passport.client_id'),
            'client_secret' => config('passport.client_secret'),
            'scope' => '',
        ]);

        $response = App::make(AccessTokenController::class)
            ->issueToken(app()->make(ServerRequestInterface::class)->withParsedBody($tokenRequest->request->all()));

        $tokenData = json_decode($response->getContent(), true);

        if (!isset($tokenData['access_token'])) {
            throw new CustomErrorException('unable_to_refresh', 'auth', Response::HTTP_UNAUTHORIZED);
        }

        return $this->response200($tokenData);
    }

    public function personalInfo(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            throw new CustomErrorException('unauthenticated', 'auth', Response::HTTP_UNAUTHORIZED);
        }

        return $this->response200(new UserResource($user));
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->provider) {
            throw new CustomErrorException('not_allowed_to_change_password_for_social_users', 'errorMessage', Response::HTTP_BAD_REQUEST);
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            throw new CustomErrorException('incorrect_password', 'errorMessage', Response::HTTP_BAD_REQUEST);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return $this->response200(['message' => __('successMessage.update')]);
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();

        $user->token()->revoke();

        return $this->response204();
    }

    public function index(IndexRequest $request): JsonResponse
    {
        $users = User::query();
        $offset = $request->input('offset');
        $limit = $request->input('limit');

        $total = $users->count();

        if (isset($offset) && isset($limit)) {
            $users->offset($offset)->limit($limit);
        }

        $users = $users->get();

        return $this->response200([
            'total' => $total,
            'users' => $users
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $authUser = Auth::user();

        if ($authUser->is($user)) {
            throw new CustomErrorException('not_allowed_to_delete_yourself', 'errorMessage', Response::HTTP_BAD_REQUEST);
        }

        if ($authUser->hasRole('super_admin')) {
            throw new CustomErrorException('not_allowed_to_delete_other_super_admins', 'errorMessage', Response::HTTP_BAD_REQUEST);
        }

        $user->delete();
        return $this->response204();
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $role = $userData['role'];
        unset($userData['role']);

        $userData['password'] = Hash::make($userData['password']);
        unset($userData['confirm_password']);

        $user = User::create($userData);

        switch ($role) {
            case 1;
                $user->assignRole('user');
                break;
            case 2;
                $user->assignRole('admin');
                break;
            case 3;
                $user->assignRole('super_admin');
                break;
        }

        return $this->response201([
            'message' => __('successMessage.create'),
            'data' => $user->load('roles')->makeHidden('roles')->toArray() + [
                    'role' => $user->roles->first()?->name
                ]
        ]);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->update($request->validated());
        return $this->response201(['message' => __('successMessage.update')]);
    }

    public function deleteAccount(): JsonResponse
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            throw new CustomErrorException('cannot_delete_super_admin', 'auth', Response::HTTP_FORBIDDEN);
        }

        $user->delete();
        return $this->response204();
    }

}
