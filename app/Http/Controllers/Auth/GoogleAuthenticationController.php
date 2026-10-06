<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthenticationController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('login')->withErrors([
                'google' => 'La connexion Google sera disponible dès que la configuration sécurisée du projet sera ajoutée.',
            ]);
        }

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()->route('login')->withErrors([
                'google' => 'La connexion Google n’a pas pu être finalisée. Réessayez dans quelques instants.',
            ]);
        }

        $names = $this->splitName($googleUser->getName());

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'first_name' => $names['first_name'],
                'last_name' => $names['last_name'],
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar_path' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'status' => 'active',
                'password' => Str::password(40),
            ]);
        } else {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'avatar_path' => $googleUser->getAvatar() ?: $user->avatar_path,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        }

        Auth::login($user, true);

        return $user->role
            ? redirect()->route('dashboard')
            : redirect()->route('onboarding.role');
    }

    /** @return array{first_name: string, last_name: string} */
    private function splitName(?string $name): array
    {
        $parts = preg_split('/\s+/', trim($name ?: 'Utilisateur')) ?: ['Utilisateur'];

        return [
            'first_name' => $parts[0],
            'last_name' => count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '',
        ];
    }
}
