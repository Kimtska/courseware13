<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssessmentScore extends Model
{
    protected $fillable = [
        'score_id',
        'score_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function score(): BelongsTo
    {
        return $this->belongsTo(StudentScore::class, 'score_id');
    }

    public function marksmanshipSimulation(): HasOne
    {
        return $this->hasOne(MarksmanshipSimulation::class);
    }
}
