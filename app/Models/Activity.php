<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activity';

    protected $fillable = [
        'module_id',
        'question_number',
        'question_text',
        'options',
        'correct_answer',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'integer',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
