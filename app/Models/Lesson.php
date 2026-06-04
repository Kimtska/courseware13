<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'key',
        'title',
        'description',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(LessonPage::class);
    }
}
