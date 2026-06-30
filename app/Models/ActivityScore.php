<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityScore extends Model
{
    protected $fillable = [
        'score_id',
        'question_number',
        'selected_answer',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function score(): BelongsTo
    {
        return $this->belongsTo(StudentScore::class, 'score_id');
    }
}
