<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'key',
        'module_id',
        'title',
        'description',
        'sort_order',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(LessonPage::class)->orderBy('page_index');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
