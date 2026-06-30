<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $table = 'activity';

    protected $fillable = [
        'lesson_detail_id',
        'question_number',
        'question_text',
        'options',
        'correct_answer',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'integer',
    ];

    public function lessonDetail(): BelongsTo
    {
        return $this->belongsTo(LessonPage::class, 'lesson_detail_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(StudentScore::class, 'activity_id');
    }
}
