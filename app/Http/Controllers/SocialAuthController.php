<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomErrorException;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

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
        $password = config('passport.social_default_password');

        $user = User::where('email', $socialUser->getEmail())
            ->orWhere('provider_id', $socialUser->getId())
            ->first();

        if (!$user) {
            $fullName = $socialUser->getName() ?? 'NoName Unknown';
            $nameParts = explode(' ', $fullName, 2);
            $name = $nameParts[0];

            $user = User::create([
                'name' => $name,
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
            $user->assignRole('user');
        } else {
            $updateData = [
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ];

            if (!Hash::check($password, $user->password)) {
                $updateData['password'] = Hash::make($password);
            }

            $user->update($updateData);
        }

        $tokenData = $this->getOAuthTokens($user->email, $password);

        $userRole = DB::table('model_has_roles')->where('model_id', $user->id)->value('role_id');

        return $this->response200([
            'access_token' => $tokenData['access_token'],
            'refresh_token' => $tokenData['refresh_token'],
            'expires_in' => now()->addSeconds($tokenData['expires_in'])->toDateTimeString(),
            'user' => $user,
            'role' => $userRole,
        ]);
    }

    private function getOAuthTokens(string $email, string $password): array
    {
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        if (!$client) {
            throw new CustomErrorException('user_not_found', 'auth', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $tokenRequest = Request::create('/oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ]);

        $tokenResponse = app()->handle($tokenRequest);
        $tokenData = json_decode($tokenResponse->getContent(), true);

        if (!isset($tokenData['access_token'])) {
            throw new CustomErrorException('oauth_error', 'auth', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $tokenData;
    }

}
