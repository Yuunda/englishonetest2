<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    // Ini kolom-kolom milik tabel tests
    protected $fillable = ['name', 'level', 'class', 'duration'];

    // Test PUNYA BANYAK Question
    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
