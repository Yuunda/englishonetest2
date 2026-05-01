<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Submission;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Illuminate\Support\Facades\File;

class TestController extends Controller
{
    public function index()
{
    $tests = Test::all(); // Ambil data untuk tabel
    return view('teacher.manage_tests', compact('tests'));
}

    public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        'name' => 'required',
        'level' => 'required',
        'class' => 'required|integer',
        'duration' => 'required|integer',
        'file_excel' => 'required|mimes:xlsx,xls',
        'images_archive' => 'nullable|file|mimes:zip' // Pakai zip saja
    ]);

    // 2. Simpan test DULU (biar kita punya ID)
    $test = Test::create([
        'name' => $request->name,
        'level' => $request->level,
        'class' => $request->class,
        'duration' => $request->duration,
    ]);

    // 3. Import Excel
    Excel::import(new QuestionsImport($test->id), $request->file('file_excel'));

    // 4. Baru Extract gambar (kalau ada)
    if ($request->hasFile('images_archive')) {
        $this->extractImages($request->file('images_archive'));
    }

    return back()->with('success', 'Ujian dan Soal berhasil di-upload!');
}

    private function extractImages($file)
{
    $zip = new ZipArchive;
    $destination = public_path('images/soal');

    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    if ($zip->open($file->getRealPath()) === TRUE) {

        for ($i = 0; $i < $zip->numFiles; $i++) {

            $entry = $zip->getNameIndex($i);

            // skip folder
            if (str_ends_with($entry, '/')) continue;

            $stream = $zip->getStream($entry);
            if (!$stream) continue;

            $filename = basename($entry);
            $target = $destination . '/' . $filename;

            file_put_contents($target, stream_get_contents($stream));
            fclose($stream);
        }

        $zip->close();
    }
}

    public function destroy($id)
    {
    $test = Test::findOrFail($id);
    
    // Opsional: Hapus file gambar soal di storage jika ada (biar gak menuhin hosting)
    foreach($test->questions as $question) {
        if($question->image) {
            $imagePath = public_path('images/soal/' . $question->image);
            File::delete($imagePath); // Lebih aman dan tidak melempar error jika file tidak ada
        }
    }

    // Hapus test (Jika di migration pakai cascade, soal otomatis terhapus)
    $test->delete();

    return redirect()->back()->with('success', 'Test and all related questions deleted successfully!');
}

        public function availableTests($level, $class)
    {
        // Kita asumsikan 'class' disimpan di kolom 'level' atau 'name' 
        // Untuk sekarang kita ambil semua test dulu sesuai level
         $tests = Test::where('level', $level)
                 ->where('class', $class)
                 ->get();
        
        return view('student.tests_selection', compact('tests', 'level', 'class'));
    }

    public function startExam($id)
    {
        $test = Test::with('questions')->findOrFail($id);
        return view('student.exam', compact('test'));
    }

    public function submitExam(Request $request, $id)
{
    $test = Test::findOrFail($id);

    // Simpan semua jawaban murid ke kolom JSON 'answers'
    Submission::create([
        'user_id' => auth()->id(),
        'test_id' => $id,
        'test_name_snapshot' => $test->name, // Simpan namanya secara permanen
        'test_level_snapshot' => $test->level, // Titip level
        'test_class_snapshot' => $test->class, // Titip class
        'answers' => $request->input('answers'), // Menyimpan [question_id => pilihan]
    ]);

    return redirect()->route('student.exam.success');
}

    public function destroySubmission($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->delete();

        return redirect()->back()->with('success', 'Student submission deleted successfully!');
    }

    public function reviewResults()
    {
        $results = Submission::with(['user', 'test'])->latest()->get();
        return view('teacher.review', compact('results'));
    }

    // Tambahkan fungsi baru untuk melihat detail jawaban
    public function detailSubmission($id)
    {
        $submission = Submission::with(['user', 'test.questions'])->findOrFail($id);
        return view('teacher.detail_submission', compact('submission'));
    }

    public function pickClass($level)
    {
        $classMap = [
            'kinder' => 3,
            'elementary' => 6,
            'middle' => 3,
            'high' => 3,
            'adult' => 3,
        ];

        if (!isset($classMap[$level])) {
            abort(404);
        }

        $totalClass = $classMap[$level];

        return view('student.classes', compact('level', 'totalClass'));
    }
}
