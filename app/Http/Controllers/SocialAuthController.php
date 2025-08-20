<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomErrorException;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Socialite\Facades\Socialite;
use Psr\Http\Message\ServerRequestInterface;

class SocialAuthController extends Controller
{
    use ApiResponse;

    public function redirect(string $provider): RedirectResponse
    {
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
        return redirect()->away($url);
    }

    public function callback(string $provider): JsonResponse
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();
        return $this->handleSocialLogin($socialUser, $provider);
    }

    private function handleSocialLogin($socialUser, string $provider): JsonResponse
    {
        $fullName = $socialUser->getName() ?? 'NoName Unknown';
        $nameParts = explode(' ', $fullName, 2);

        $name = $nameParts[0];
        $password = config('passport.social_default_password');
//        $surname = $nameParts[1] ?? 'Unknown';

        $user = User::where('email', $socialUser->getEmail())
            ->orWhere('provider_id', $socialUser->getId())
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
//                'surname' => $surname,
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
            $user->assignRole('user');
        } else {
            if (!$user->provider_id) {
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            }
        }

        $tokenRequest = Request::create('/oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => config('passport.client_id'),
            'client_secret' => config('passport.client_secret'),
            'username' => $user->email,
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

        $userRole = DB::table('model_has_roles')->where('model_id', $user->id)->value('role_id');

        return $this->response200([
            'access_token' => $tokenData['access_token'],
            'refresh_token' => $tokenData['refresh_token'],
            'expires_in' => now()->addSeconds($tokenData['expires_in'])->toDateTimeString(),
            'user' => $user,
            'role' => $userRole,
        ]);
    }

}
