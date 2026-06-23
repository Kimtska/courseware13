<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldStudent extends Model
{
    protected $table = 'old_students';

    protected $fillable = [
        'instructor_user_id',
        'student_id_number',
        'password',
        'status',
        'full_name',
        'course',
        'year_level',
        'section',
        'enrollment_status',
        'module_access_status',
        'current_activity_status',
        'archived_at',
        'verified_at',
        'metadata',
        'moved_at',
    ];

    protected function casts(): array
    {
        return [
            'archived_at' => 'datetime',
            'verified_at' => 'datetime',
            'moved_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
