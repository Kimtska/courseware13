<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentScore extends Model
{
    use HasFactory;

    protected $table = 'scores';

    protected $fillable = [
        'student_id',
        'activity_id',
        'score',
        'max_score',
        'recorded_at',
        'metadata',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(ManagedStudent::class, 'student_id');
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function assessmentScore(): HasOne
    {
        return $this->hasOne(AssessmentScore::class, 'score_id');
    }

    public function activityScores(): HasMany
    {
        return $this->hasMany(ActivityScore::class, 'score_id');
    }

    public function assessmentSimulations(): HasMany
    {
        return $this->hasMany(AssessmentSimulation::class, 'score_id');
    }
}
