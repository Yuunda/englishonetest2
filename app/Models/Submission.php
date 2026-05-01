<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    // Nama tabel kita sekarang plural: submissions
    protected $fillable = ['user_id', 'test_id', 'test_name_snapshot', 'test_level_snapshot', 'test_class_snapshot', 'answers'];

    /**
     * Ini Bagian Paling Penting!
     * Kita "Cast" kolom answers agar Laravel tahu 
     * kalau ini adalah JSON yang harus diubah jadi Array.
     */
    protected $casts = [
        'answers' => 'array',
    ];

    // Relasi ke Murid
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
