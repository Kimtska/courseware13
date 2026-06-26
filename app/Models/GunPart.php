<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GunPart extends Model
{
    protected $fillable = [
        'firearm_id', 'slug', 'name', 'description',
        'sort_order', 'z_order',
        'image_path', 'glow_image_path',
        'zone_x', 'zone_y', 'zone_w', 'zone_h',
    ];

    public function firearm(): BelongsTo
    {
        return $this->belongsTo(Firearm::class);
    }
}
