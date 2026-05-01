<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    protected $test_id;

    public function __construct($test_id)
    {
        $this->test_id = $test_id;
    }

    public function model(array $row)
    {
        // ✅ skip row kosong
        if (!isset($row['soal']) || trim($row['soal']) === '') {
            return null;
        }
        // Pastikan kolom di return ini sesuai dengan kolom di database
        // dan key di $row['...'] sesuai dengan header di Excel nanti
        $type = strtolower(trim($row['type'] ?? 'mcq'));

        return new Question([
            
        'test_id'        => $this->test_id,
        'question_text'  => $row['soal'],
        'image'          => $row['image'] ?? null,
        'type'           => $type,
        'section'        => $row['section'] ?? 1,

        'option_a'       => $type === 'mcq' ? ($row['pilihan_a'] ?? null) : null,
        'option_b'       => $type === 'mcq' ? ($row['pilihan_b'] ?? null) : null,
        'option_c'       => $type === 'mcq' ? ($row['pilihan_c'] ?? null) : null,
        'option_d'       => $type === 'mcq' ? ($row['pilihan_d'] ?? null) : null,

        'correct_answer' => trim(strtolower($row['jawaban_benar'] ?? '')),
        ]);
    }
}