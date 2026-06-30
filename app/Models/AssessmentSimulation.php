<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentSimulation extends Model
{
    protected $fillable = [
        'score_id',
        'slug', 'name', 'type', 'caliber', 'mag_size', 'image_url', 'description',
        'status', 'started_at', 'completed_at', 'attempt', 'passed',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'passed' => 'boolean',
    ];

    public function parts(): HasMany
    {
        return $this->hasMany(GunPart::class)->orderBy('sort_order');
    }

    public function score(): BelongsTo
    {
        return $this->belongsTo(StudentScore::class, 'score_id');
    }

    public function marksmanshipSimulations(): HasMany
    {
        return $this->hasMany(MarksmanshipSimulation::class);
    }
}
