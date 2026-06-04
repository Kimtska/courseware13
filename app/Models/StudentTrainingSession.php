<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'instructor_user_id',
        'module_key',
        'session_type',
        'status',
        'started_at',
        'ended_at',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(ManagedStudent::class, 'student_id');
    }
}