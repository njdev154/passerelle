<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('job_offer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('supervisor_name')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->enum('status', ['planned', 'agreement_pending', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->text('objectives')->nullable();
            $table->text('school_notes')->nullable();
            $table->timestamps();
            $table->unique(['school_id', 'student_profile_id', 'company_id', 'starts_at'], 'school_student_company_start_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_records');
    }
};
