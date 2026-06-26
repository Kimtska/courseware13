<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonPage extends Model
{
    protected $table = 'lesson_details';

    protected $fillable = [
        'lesson_id',
        'lesson_index',
        'page_index',
        'title',
        'body_html',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
