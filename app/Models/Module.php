<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'module_key',
        'title',
        'description',
        'sort_order',
        'student_id',
        'status',
        'current_lesson_id',
        'current_page',
        'started_at',
        'completed_at',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'module_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(ManagedStudent::class, 'student_id');
    }
}
