<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'student_training_session_id',
        'instructor_user_id',
        'module_key',
        'activity_type',
        'activity_status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(ManagedStudent::class, 'student_id');
    }
}