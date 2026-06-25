<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksmanshipScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'score_id',
        'student_profile_id',
        'student_id',
        'instructor_id',
        'weapon',
        'time_limit',
        'target_mode',
        'total_shots',
        'max_shots',
        'bullseye_count',
        'alpha_count',
        'bravo_count',
        'charlie_count',
        'delta_count',
        'miss_count',
        'total_score',
        'max_score',
        'accuracy',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function score()
    {
        return $this->belongsTo(StudentScore::class, 'score_id');
    }

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function student()
    {
        return $this->belongsTo(ManagedStudent::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
