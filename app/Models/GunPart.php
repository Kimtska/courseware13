<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GunPart extends Model
{
    protected $fillable = [
        'assessment_simulation_id', 'slug', 'name', 'description',
        'sort_order', 'z_order',
        'image_path', 'glow_image_path',
        'zone_x', 'zone_y', 'zone_w', 'zone_h',
    ];

    public function assessmentSimulation(): BelongsTo
    {
        return $this->belongsTo(AssessmentSimulation::class, 'assessment_simulation_id');
    }
}
