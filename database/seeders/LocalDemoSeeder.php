<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Application;
use App\Models\InternshipRecord;
use App\Models\JobOffer;
use App\Models\Project;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class LocalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'Passerelle2026!';

        $student = User::query()->updateOrCreate(
            ['email' => 'etudiant@passerelle.test'],
            ['first_name' => 'Awa', 'last_name' => 'Koné', 'password' => $password, 'role' => 'student', 'status' => 'active', 'email_verified_at' => now()]
        );

        $profile = StudentProfile::query()->updateOrCreate(
            ['user_id' => $student->id],
            ['headline' => 'Étudiante en développement web', 'biography' => 'Profil de démonstration pour tester les parcours étudiants et établissements.', 'city' => 'Abidjan', 'level_of_study' => 'Bac+3', 'field_of_study' => 'Informatique', 'completion_percentage' => 100, 'profile_visibility' => true]
        );

        Project::query()->updateOrCreate(
            ['student_profile_id' => $profile->id, 'title' => 'Plateforme de suivi de dons'],
            ['description' => 'Conception et développement d’une interface pour organiser les besoins, les donneurs et le suivi des demandes.', 'project_url' => 'https://example.test/projet-dons', 'repository_url' => 'https://github.com/example/projet-dons', 'visibility' => true]
        );
        Project::query()->updateOrCreate(
            ['student_profile_id' => $profile->id, 'title' => 'Carnet de dépenses étudiant'],
            ['description' => 'Application web responsive pour suivre un budget mensuel et visualiser les catégories de dépenses.', 'project_url' => 'https://example.test/carnet-budget', 'visibility' => true]
        );

        $companyOwner = User::query()->updateOrCreate(
            ['email' => 'entreprise@passerelle.test'],
            ['first_name' => 'Koffi', 'last_name' => 'Yao', 'password' => $password, 'role' => 'company', 'status' => 'active', 'email_verified_at' => now()]
        );

        $company = Company::query()->updateOrCreate(
            ['slug' => 'atelier-numerique-ci'],
            ['owner_id' => $companyOwner->id, 'name' => 'Atelier Numérique CI', 'sector' => 'Services numériques', 'city' => 'Abidjan', 'description' => 'Entreprise locale de démonstration pour tester le dépôt et la validation des offres.', 'verification_status' => 'verified', 'verified_at' => now()]
        );

        $secondCompanyOwner = User::query()->updateOrCreate(
            ['email' => 'rh@horizon-logistique.test'],
            ['first_name' => 'Mariam', 'last_name' => 'Traoré', 'password' => $password, 'role' => 'company', 'status' => 'active', 'email_verified_at' => now()]
        );
        $secondCompany = Company::query()->updateOrCreate(
            ['slug' => 'horizon-logistique'],
            ['owner_id' => $secondCompanyOwner->id, 'name' => 'Horizon Logistique', 'sector' => 'Transport et logistique', 'city' => 'Abidjan', 'description' => 'Données de démonstration pour le catalogue local de Passerelle.', 'verification_status' => 'verified', 'verified_at' => now()]
        );

        $offer = JobOffer::query()->updateOrCreate(
            ['slug' => 'stage-developpement-web'],
            ['company_id' => $company->id, 'title' => 'Stagiaire développement web', 'description' => 'Vous rejoindrez une petite équipe produit pour contribuer à des interfaces web utiles. Vous travaillerez sur des maquettes, des pages responsives et l’amélioration d’outils internes.', 'city' => 'Abidjan', 'work_mode' => 'hybrid', 'contract_type' => 'internship', 'required_level' => 'Bac+2 à Bac+5', 'application_deadline' => now()->addWeeks(3)->toDateString(), 'status' => 'published', 'published_at' => now()->subDays(4)]
        );
        JobOffer::query()->updateOrCreate(
            ['slug' => 'assistant-operations-logistique'],
            ['company_id' => $secondCompany->id, 'title' => 'Assistant opérations logistique', 'description' => 'Participez au suivi quotidien des opérations : préparation des tableaux de bord, coordination des demandes et amélioration de la circulation des informations.', 'city' => 'Abidjan', 'work_mode' => 'on_site', 'contract_type' => 'apprenticeship', 'required_level' => 'Bac+2 ou Bac+3', 'application_deadline' => now()->addWeeks(2)->toDateString(), 'status' => 'published', 'published_at' => now()->subDays(2)]
        );
        JobOffer::query()->updateOrCreate(
            ['slug' => 'charge-communication-digitale'],
            ['company_id' => $company->id, 'title' => 'Chargé·e de communication digitale', 'description' => 'Créez des contenus, préparez un calendrier éditorial et aidez une équipe locale à mieux présenter ses activités en ligne.', 'city' => 'Abidjan', 'work_mode' => 'hybrid', 'contract_type' => 'first_job', 'required_level' => 'Bac+3 minimum', 'application_deadline' => now()->addWeeks(4)->toDateString(), 'status' => 'published', 'published_at' => now()->subDay()]
        );

        Application::query()->updateOrCreate(
            ['job_offer_id' => $offer->id, 'student_profile_id' => $profile->id],
            ['cover_letter' => 'Je souhaite mettre à profit mes projets web, apprendre auprès d’une équipe produit et contribuer avec sérieux à cette mission.', 'status' => 'interview', 'submitted_at' => now()->subDays(2)]
        );

        $schoolOwner = User::query()->updateOrCreate(
            ['email' => 'etablissement@passerelle.test'],
            ['first_name' => 'Nadia', 'last_name' => 'Diallo', 'password' => $password, 'role' => 'school', 'status' => 'active', 'email_verified_at' => now()]
        );

        $school = School::query()->updateOrCreate(
            ['slug' => 'institut-passerelle-ci'],
            ['owner_id' => $schoolOwner->id, 'name' => 'Institut Passerelle CI', 'city' => 'Abidjan', 'description' => 'Établissement de démonstration pour le suivi des stages.', 'verification_status' => 'verified']
        );

        InternshipRecord::query()->updateOrCreate(
            ['school_id' => $school->id, 'student_profile_id' => $profile->id, 'company_id' => $company->id, 'starts_at' => now()->subWeek()->toDateString()],
            ['supervisor_name' => 'Koffi Yao', 'ends_at' => now()->addMonths(2)->toDateString(), 'status' => 'in_progress', 'objectives' => 'Participer à la conception d’interfaces, documenter les choix réalisés et présenter un bilan de fin de stage.', 'school_notes' => 'Premier point de suivi effectué : bonne intégration dans l’équipe.']
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@passerelle.test'],
            ['first_name' => 'Admin', 'last_name' => 'Passerelle', 'password' => $password, 'role' => 'admin', 'status' => 'active', 'email_verified_at' => now()]
        );
    }
}
