<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarksmanshipSimulation extends Model
{
    protected $fillable = [
        'assessment_simulation_id',
        'assessment_score_id',
        'target_id',
        'target_mode_id',
        'target_detail_id',
        'status',
        'started_at',
        'completed_at',
        'attempt',
        'passed',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'passed' => 'boolean',
        'metadata' => 'array',
    ];

    public function assessmentSimulation(): BelongsTo
    {
        return $this->belongsTo(AssessmentSimulation::class);
    }

    public function assessmentScore(): BelongsTo
    {
        return $this->belongsTo(AssessmentScore::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class);
    }

    public function targetMode(): BelongsTo
    {
        return $this->belongsTo(TargetMode::class);
    }

    public function targetDetail(): BelongsTo
    {
        return $this->belongsTo(TargetDetail::class);
    }

    public function shotResults(): HasMany
    {
        return $this->hasMany(ShotResult::class);
    }
}
