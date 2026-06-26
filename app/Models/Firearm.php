<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Firearm extends Model
{
    protected $fillable = [
        'slug', 'name', 'type', 'caliber', 'mag_size', 'image_url', 'description'
    ];

    public function parts(): HasMany
    {
        return $this->hasMany(GunPart::class)->orderBy('sort_order');
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_firearm');
    }
}
