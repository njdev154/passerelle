<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolInternshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_school_can_create_and_update_a_student_internship_follow_up(): void
    {
        $owner = User::factory()->create(['role' => 'school', 'email_verified_at' => now()]);
        $school = School::query()->create(['owner_id' => $owner->id, 'name' => 'Institut test', 'slug' => 'institut-test', 'city' => 'Abidjan']);
        $student = User::factory()->create(['role' => 'student', 'email' => 'student@example.ci']);
        StudentProfile::query()->create(['user_id' => $student->id, 'city' => 'Abidjan', 'level_of_study' => 'Bac+3', 'field_of_study' => 'Informatique']);

        $this->actingAs($owner)->withSession(['_token' => 'test-token'])->post(route('school.internships.store'), [
            '_token' => 'test-token', 'student_email' => 'student@example.ci', 'status' => 'agreement_pending',
            'objectives' => 'Mettre en pratique les compétences de développement et suivre les objectifs pédagogiques.',
        ])->assertRedirect(route('school.dashboard'));

        $record = $school->internships()->firstOrFail();
        $this->actingAs($owner)->withSession(['_token' => 'test-token'])->patch(route('school.internships.update', $record), [
            '_token' => 'test-token', 'status' => 'in_progress', 'school_notes' => 'Premier point de suivi effectué.',
        ])->assertRedirect();

        $this->assertDatabaseHas('internship_records', ['id' => $record->id, 'status' => 'in_progress']);
    }
}
