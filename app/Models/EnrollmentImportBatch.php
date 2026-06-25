<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentImportBatch extends Model
{
    use HasFactory;

    protected $table = 'student_import_batches';

    protected $fillable = [
        'instructor_user_id',
        'file_name',
        'file_type',
        'total_uploaded',
        'successfully_imported',
        'duplicate_records',
        'invalid_entries',
        'status',
        'summary',
    ];

    protected $casts = [
        'summary' => 'array',
    ];
}