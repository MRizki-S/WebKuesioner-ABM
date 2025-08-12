<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    protected $fillable = ['question_id', 'label', 'value', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
