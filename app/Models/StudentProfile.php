<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'school_id', 'headline', 'biography', 'city', 'level_of_study', 'field_of_study', 'availability_date', 'profile_visibility', 'completion_percentage'])]
class StudentProfile extends Model
{
    protected function casts(): array
    {
        return ['availability_date' => 'date', 'profile_visibility' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function internships(): HasMany
    {
        return $this->hasMany(InternshipRecord::class);
    }
}
