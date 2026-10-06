<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentJourneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_student_can_create_a_profile_and_apply_once_to_a_published_offer(): void
    {
        $student = User::factory()->create(['role' => 'student', 'email_verified_at' => now()]);
        $owner = User::factory()->create(['role' => 'company']);
        $company = Company::query()->create([
            'owner_id' => $owner->id, 'name' => 'Test CI', 'slug' => 'test-ci',
            'sector' => 'Numérique', 'city' => 'Abidjan',
        ]);
        $offer = JobOffer::query()->create([
            'company_id' => $company->id, 'title' => 'Stage développement', 'slug' => 'stage-developpement',
            'description' => 'Une mission complète de développement pour apprendre et contribuer à un produit utile.',
            'city' => 'Abidjan', 'work_mode' => 'hybrid', 'contract_type' => 'internship',
            'status' => 'published', 'published_at' => now(),
        ]);

        $this->actingAs($student)->withSession(['_token' => 'test-token'])->put(route('student.profile.update'), [
            '_token' => 'test-token', 'headline' => 'Développeur junior', 'biography' => 'Je construis des applications utiles pour apprendre chaque jour.',
            'city' => 'Abidjan', 'level_of_study' => 'Bac+3', 'field_of_study' => 'Informatique',
        ])->assertRedirect(route('student.profile.edit'));

        $profile = StudentProfile::query()->where('user_id', $student->id)->firstOrFail();
        $this->assertSame(100, $profile->completion_percentage);

        $this->actingAs($student)->withSession(['_token' => 'test-token'])->post(route('applications.store', $offer), [
            '_token' => 'test-token', 'cover_letter' => 'Je souhaite contribuer à cette mission et mettre mes compétences à votre service.',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseCount('applications', 1);
    }
}
