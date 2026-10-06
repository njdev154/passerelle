<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ContactMessage;
use App\Models\User;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_accessible(): void
    {
        foreach (['public.companies', 'public.about', 'public.advice', 'public.faq', 'public.contact', 'public.privacy', 'public.terms'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_contact_messages_are_stored(): void
    {
        $this->withSession(['_token' => 'test-token'])->post(route('public.contact.store'), [
            '_token' => 'test-token',
            'name' => 'Awa Koné',
            'email' => 'awa@example.ci',
            'subject' => 'Question sur Passerelle',
            'message' => 'Je souhaite en savoir plus sur la manière dont les entreprises vérifient les offres publiées.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_messages', ['email' => 'awa@example.ci', 'status' => 'new']);
    }

    public function test_an_admin_can_mark_a_contact_message_as_read(): void
    {
        $message = ContactMessage::query()->create(['name' => 'Awa', 'email' => 'awa@example.ci', 'subject' => 'Question', 'message' => 'Voici un message suffisamment long pour être stocké.', 'status' => 'new']);
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $this->actingAs($admin)->withSession(['_token' => 'test-token'])->patch(route('admin.messages.read', $message), ['_token' => 'test-token'])->assertRedirect();
        $this->assertDatabaseHas('contact_messages', ['id' => $message->id, 'status' => 'read']);
    }
}
