<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['student_profile_id', 'title', 'description', 'project_url', 'repository_url', 'cover_path', 'visibility'])]
class Project extends Model
{
    protected function casts(): array
    {
        return ['visibility' => 'boolean'];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }
}
