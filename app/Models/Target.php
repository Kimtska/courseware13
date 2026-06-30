<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image_path',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(TargetDetail::class)->orderBy('sort_order');
    }

    public function modes(): HasMany
    {
        return $this->hasMany(TargetMode::class);
    }

    public function marksmanshipSimulations(): HasMany
    {
        return $this->hasMany(MarksmanshipSimulation::class);
    }
}
