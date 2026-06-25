<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firearm extends Model
{
    protected $fillable = [
        'slug', 'name', 'type', 'caliber', 'mag_size', 'image_url', 'description'
    ];
}
