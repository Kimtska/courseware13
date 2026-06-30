<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'lesson_detail_id');
    }
}
