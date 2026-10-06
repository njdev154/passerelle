<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['company_id', 'title', 'slug', 'description', 'city', 'work_mode', 'contract_type', 'required_level', 'application_deadline', 'status', 'published_at'])]
class JobOffer extends Model
{
    public function contractLabel(): string
    {
        return match ($this->contract_type) {
            'internship' => 'Stage',
            'apprenticeship' => 'Alternance',
            default => 'Premier emploi',
        };
    }

    public function workModeLabel(): string
    {
        return match ($this->work_mode) {
            'on_site' => 'Sur site',
            'hybrid' => 'Hybride',
            default => 'À distance',
        };
    }

    protected function casts(): array
    {
        return [
            'application_deadline' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
