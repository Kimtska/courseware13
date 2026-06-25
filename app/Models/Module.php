<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'module_key',
        'title',
        'description',
        'sort_order',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'module_key', 'module_key');
    }
}
