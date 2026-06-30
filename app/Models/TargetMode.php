<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TargetMode extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_id',
        'name',
        'display_name',
        'description',
    ];

    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class);
    }

    public function marksmanshipSimulations(): HasMany
    {
        return $this->hasMany(MarksmanshipSimulation::class);
    }
}
