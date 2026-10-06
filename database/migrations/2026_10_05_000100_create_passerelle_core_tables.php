<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('city');
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sector');
            $table->string('city');
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('student_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('headline')->nullable();
            $table->text('biography')->nullable();
            $table->string('city')->nullable();
            $table->string('level_of_study')->nullable();
            $table->string('field_of_study')->nullable();
            $table->date('availability_date')->nullable();
            $table->boolean('profile_visibility')->default(false);
            $table->unsignedTinyInteger('completion_percentage')->default(0);
            $table->timestamps();
        });

        Schema::create('skills', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('project_url')->nullable();
            $table->string('repository_url')->nullable();
            $table->string('cover_path')->nullable();
            $table->boolean('visibility')->default(false);
            $table->timestamps();
        });

        Schema::create('job_offers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('city');
            $table->enum('work_mode', ['on_site', 'hybrid', 'remote'])->default('on_site');
            $table->enum('contract_type', ['internship', 'apprenticeship', 'first_job']);
            $table->string('required_level')->nullable();
            $table->date('application_deadline')->nullable();
            $table->enum('status', ['draft', 'pending_review', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('applications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['submitted', 'reviewed', 'shortlisted', 'interview', 'accepted', 'rejected'])->default('submitted');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            $table->unique(['job_offer_id', 'student_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
        Schema::dropIfExists('job_offers');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('schools');
    }
};
