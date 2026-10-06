<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobOffersTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_published_offers_are_visible_to_the_public(): void
    {
        $owner = User::factory()->create(['role' => 'company']);
        $company = Company::query()->create([
            'owner_id' => $owner->id,
            'name' => 'Entreprise de test',
            'slug' => 'entreprise-de-test',
            'sector' => 'Technologie',
            'city' => 'Abidjan',
        ]);

        JobOffer::query()->create([
            'company_id' => $company->id,
            'title' => 'Stage publié',
            'slug' => 'stage-publie',
            'description' => 'Une mission de test suffisamment détaillée pour être affichée dans le catalogue public.',
            'city' => 'Abidjan',
            'work_mode' => 'hybrid',
            'contract_type' => 'internship',
            'status' => 'published',
            'published_at' => now(),
        ]);

        JobOffer::query()->create([
            'company_id' => $company->id,
            'title' => 'Offre non vérifiée',
            'slug' => 'offre-non-verifiee',
            'description' => 'Cette annonce est volontairement en attente afin de tester le filtre de publication.',
            'city' => 'Abidjan',
            'work_mode' => 'on_site',
            'contract_type' => 'internship',
            'status' => 'pending_review',
        ]);

        $this->get(route('offers.index'))
            ->assertOk()
            ->assertSee('Stage publié')
            ->assertDontSee('Offre non vérifiée');
    }

    public function test_company_routes_require_authentication(): void
    {
        $this->get(route('company.create'))->assertRedirect(route('login'));
    }
}
