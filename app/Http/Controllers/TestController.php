<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Submission;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    // --- GURU: Manajemen Test ---
    public function index()
    {
        // Ambil level yang di-assign ke guru ini
        $assignedLevels = Auth::user()->getAssignedLevelsAttribute();

        // Hanya tampilkan tes yang level-nya diizinkan
        $tests = Test::whereIn('level', $assignedLevels)->get();

        return view('teacher.manage-tests', compact('tests'));
    }

    public function store(Request $request)
    {
        // Security: Cek apakah guru berhak buat test di level ini
        if (!Auth::user()->hasAccess($request->level)) {
            return back()->withErrors(['level' => 'Anda tidak memiliki izin untuk mengelola tes di level ini.']);
        }

        $request->validate([
            'name' => 'required|string|in:Pre-Test,Mid-Test,Post-Test',
            'level' => 'required|in:kinder,elementary,middle,high,adult',
            'class' => 'required|integer',
            'duration' => 'required|integer|min:5|max:180',
            'file_excel' => 'required|mimes:xlsx,xls',
            'images_archive' => 'nullable|file|mimes:zip'
        ]);

        $test = Test::create([
            'name' => $request->name,
            'level' => $request->level,
            'class' => $request->class,
            'duration' => $request->duration,
        ]);

        Excel::import(new QuestionsImport($test->id), $request->file('file_excel'));

        if ($request->hasFile('images_archive')) {
            $this->extractImages($request->file('images_archive'));
        }

        return back()->with('success', 'Ujian dan Soal berhasil di-upload!');
    }

    public function destroy($id)
    {
        $test = Test::findOrFail($id);
        
        // Security: Hanya bisa hapus jika guru berhak akses level tersebut
        if (!Auth::user()->hasAccess($test->level)) {
            abort(403, 'Unauthorized');
        }

        foreach($test->questions as $question) {
            if($question->image) {
                File::delete(public_path('images/soal/' . $question->image));
            }
        }

        $test->delete();
        return redirect()->back()->with('success', 'Test deleted successfully!');
    }

    // --- MURID: Halaman Kelas & Tes ---
    public function pickClass($level)
    {
        // Proteksi: Cek akses level
        if (!Auth::user()->hasAccess($level)) {
            abort(403, 'Anda tidak memiliki akses ke level kelas ini.');
        }

        $classMap = ['kinder' => 3, 'elementary' => 6, 'middle' => 3, 'high' => 3, 'adult' => 3];
        $totalClass = $classMap[$level] ?? 0;

        return view('student.classes', compact('level', 'totalClass'));
    }

    public function availableTests($level, $class)
    {
        // Proteksi
        if (!Auth::user()->hasAccess($level)) {
            abort(403, 'Anda tidak memiliki akses ke ujian di kelas ini.');
        }

        $tests = Test::where('level', $level)->where('class', $class)->get();
        return view('student.tests_selection', compact('tests', 'level', 'class'));
    }

    public function startExam($id)
    {
        $test = Test::findOrFail($id);

        // Security: Pastikan murid hanya bisa buka tes yang levelnya dia punya
        if (!Auth::user()->hasAccess($test->level)) {
            abort(403, 'Anda tidak diizinkan mengikuti ujian ini.');
        }

        return view('student.exam', compact('test'));
    }

    public function submitExam(Request $request, $id)
    {
        $test = Test::findOrFail($id);
        
        // Security: Validasi ulang saat submit
        if (!Auth::user()->hasAccess($test->level)) {
            abort(403, 'Unauthorized');
        }

        Submission::create([
            'user_id' => auth()->id(),
            'test_id' => $id,
            'test_name_snapshot' => $test->name,
            'test_level_snapshot' => $test->level,
            'test_class_snapshot' => $test->class,
            'answers' => $request->input('answers'),
        ]);

        return redirect()->route('student.exam.success');
    }

    // --- GURU: Review Hasil ---
    public function reviewResults()
    {
        $assignedLevels = Auth::user()->getAssignedLevelsAttribute();
        
        // Hanya lihat hasil dari level yang diajarkan guru
        $results = Submission::whereHas('test', function($query) use ($assignedLevels) {
            $query->whereIn('level', $assignedLevels);
        })->latest()->get();

        return view('teacher.review', compact('results'));
    }

    public function detailSubmission($id)
    {
        $submission = Submission::with(['user', 'test.questions'])->findOrFail($id);
        
        // Security: Guru hanya bisa lihat jika level tes-nya sesuai
        if (!Auth::user()->hasAccess($submission->test->level)) {
            abort(403, 'Unauthorized');
        }

        return view('teacher.detail_submission', compact('submission'));
    }

    public function destroySubmission($id)
    {
        $submission = Submission::findOrFail($id);
        
        if (!Auth::user()->hasAccess($submission->test->level)) {
            abort(403, 'Unauthorized');
        }

        $submission->delete();
        return redirect()->back()->with('success', 'Student submission deleted successfully!');
    }

    // --- Private Utilities ---
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
}