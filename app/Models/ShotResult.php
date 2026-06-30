<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShotResult extends Model
{
    protected $fillable = [
        'marksmanship_simulation_id',
        'target_detail_id',
        'shot_number',
        'is_hit',
    ];

    protected $casts = [
        'is_hit' => 'boolean',
    ];

    public function marksmanshipSimulation(): BelongsTo
    {
        return $this->belongsTo(MarksmanshipSimulation::class);
    }

    public function targetDetail(): BelongsTo
    {
        return $this->belongsTo(TargetDetail::class);
    }
}
