<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleAccessControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_key',
        'is_unlocked',
        'last_action_by_user_id',
        'locked_at',
        'unlocked_at',
    ];

    protected $casts = [
        'is_unlocked' => 'boolean',
        'locked_at' => 'datetime',
        'unlocked_at' => 'datetime',
    ];

    public function lastActionBy()
    {
        return $this->belongsTo(User::class, 'last_action_by_user_id');
    }
}