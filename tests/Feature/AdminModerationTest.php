<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_an_admin_can_publish_an_offer_from_a_verified_company(): void
    {
        $owner = User::factory()->create(['role' => 'company']);
        $company = Company::query()->create([
            'owner_id' => $owner->id, 'name' => 'Entreprise fiable', 'slug' => 'entreprise-fiable',
            'sector' => 'Numérique', 'city' => 'Abidjan', 'verification_status' => 'verified', 'verified_at' => now(),
        ]);
        $offer = JobOffer::query()->create([
            'company_id' => $company->id, 'title' => 'Stage front-end', 'slug' => 'stage-front-end',
            'description' => 'Une offre destinée à être vérifiée avant publication dans le catalogue public.',
            'city' => 'Abidjan', 'work_mode' => 'on_site', 'contract_type' => 'internship', 'status' => 'pending_review',
        ]);
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);

        $this->actingAs($student)->withSession(['_token' => 'test-token'])->patch(route('admin.offers.publish', $offer), ['_token' => 'test-token'])->assertForbidden();
        $this->actingAs($admin)->withSession(['_token' => 'test-token'])->patch(route('admin.offers.publish', $offer), ['_token' => 'test-token'])->assertRedirect();

        $this->assertDatabaseHas('job_offers', ['id' => $offer->id, 'status' => 'published']);
    }
}
