<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Ini kolom-kolom milik tabel questions
    protected $fillable = [
        'test_id', 
        'question_text', 
        'image',
        'type',
        'section',
        'option_a', 
        'option_b', 
        'option_c', 
        'option_d', 
        'correct_answer',
    ];

    // Question ADALAH MILIK sebuah Test
    public function test() {
        return $this->belongsTo(Test::class);
    }
}
