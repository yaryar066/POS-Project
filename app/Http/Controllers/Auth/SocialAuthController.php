<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected array $allowedProviders = ['google', 'github'];

    /**
     * Redirect user to provider auth page.
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        if (!in_array($provider, $this->allowedProviders)) {
            return redirect()->route('login')->with('error', 'Unsupported login provider.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain user info from provider.
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        if (!in_array($provider, $this->allowedProviders)) {
            return redirect()->route('login')->with('error', 'Unsupported login provider.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            // Find existing user by provider_id or email
            $user = User::where('provider', $provider)
                        ->where('provider_id', $socialUser->getId())
                        ->first();

            if (!$user) {
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    // Update existing user with provider details
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $user->avatar ?? $socialUser->getAvatar(),
                    ]);
                } else {
                    // Create new user (Default Role = Staff)
                    $user = User::create([
                        'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Social User',
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(24)),
                        'role' => UserRole::STAFF,
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            }

            Auth::login($user, true);

            return redirect()->intended(route('dashboard'));

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Social login failed: ' . $e->getMessage());
        }
    }
}