<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_register_and_receives_a_verification_email(): void
    {
        Notification::fake();

        $response = $this->withSession(['_token' => 'test-token'])->post(route('register.store'), [
            '_token' => 'test-token',
            'first_name' => 'Awa',
            'last_name' => 'Kone',
            'email' => 'awa@example.ci',
            'password' => 'MotDePasse2026',
            'password_confirmation' => 'MotDePasse2026',
        ]);

        $user = User::query()->where('email', 'awa@example.ci')->firstOrFail();

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_a_verified_user_can_sign_in_with_email_and_password(): void
    {
        $user = User::factory()->create([
            'email' => 'verified@example.ci',
            'password' => 'MotDePasse2026',
            'email_verified_at' => now(),
        ]);

        $this->withSession(['_token' => 'test-token'])->post(route('login.store'), [
            '_token' => 'test-token',
            'email' => 'verified@example.ci',
            'password' => 'MotDePasse2026',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }
}
