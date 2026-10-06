<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyApplicationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_owner_can_update_an_application_but_another_company_cannot(): void
    {
        $owner = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $company = Company::query()->create(['owner_id' => $owner->id, 'name' => 'Entreprise une', 'slug' => 'entreprise-une', 'sector' => 'Numérique', 'city' => 'Abidjan']);
        $offer = JobOffer::query()->create(['company_id' => $company->id, 'title' => 'Stage UX', 'slug' => 'stage-ux', 'description' => 'Mission de conception détaillée pour participer au développement de produits numériques.', 'city' => 'Abidjan', 'work_mode' => 'hybrid', 'contract_type' => 'internship', 'status' => 'published']);
        $student = User::factory()->create(['role' => 'student']);
        $profile = StudentProfile::query()->create(['user_id' => $student->id, 'city' => 'Abidjan', 'level_of_study' => 'Bac+3', 'field_of_study' => 'Design', 'completion_percentage' => 100]);
        $application = Application::query()->create(['job_offer_id' => $offer->id, 'student_profile_id' => $profile->id, 'status' => 'submitted', 'submitted_at' => now()]);
        $otherOwner = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        Company::query()->create(['owner_id' => $otherOwner->id, 'name' => 'Entreprise deux', 'slug' => 'entreprise-deux', 'sector' => 'Conseil', 'city' => 'Abidjan']);

        $this->actingAs($otherOwner)->withSession(['_token' => 'test-token'])->patch(route('company.applications.update', $application), ['_token' => 'test-token', 'status' => 'accepted'])->assertForbidden();
        $this->actingAs($owner)->withSession(['_token' => 'test-token'])->patch(route('company.applications.update', $application), ['_token' => 'test-token', 'status' => 'interview'])->assertRedirect();

        $this->assertDatabaseHas('applications', ['id' => $application->id, 'status' => 'interview']);
    }
}
