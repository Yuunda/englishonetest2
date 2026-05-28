@extends('layouts.navbar')

@section('title', 'Manage Tests')

@section('content')
    <h2 style="margin-bottom: 30px;">Manage Your Tests</h2>

    <div style="background: #f9f9f9; padding: 25px; border-radius: 15px; display: inline-block; text-align: left; border: 1px solid #ddd;">
        <h3 style="margin-top: 0;">Create New Test (Excel)</h3>

        @if(session('success'))
            <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
        @endif

        <form action="{{ route('test.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label>Test Name:</label><br>
                <select name="name" style="width: 100%; padding: 8px;" required>
                    <option value="">-- Select Test Type --</option>
                    <option value="Pre-Test">Pre-Test</option>
                    <option value="Mid-Test">Mid-Test</option>
                    <option value="Post-Test">Post-Test</option>
                    <!-- Tambahkan opsi lain di sini jika Ms. Septi butuh tipe tes tambahan -->
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Level:</label><br>
                <select name="level" style="width: 100%; padding: 8px;" required>
                <option value="">-- Select Level --</option>
                @foreach(['kinder', 'elementary', 'middle', 'high', 'adult'] as $level)
                    @if(auth()->user()->hasAccess($level))
                        <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                    @endif
                @endforeach
            </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Class:</label><br>
                <select name="class" id="classSelect" style="width: 100%; padding: 8px;" required>
                    <option value="">-- Select Class --</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Duration (Minutes):</label><br>
                <!-- Tambahkan limit min="5" dan max="180" (misal maksimal 3 jam) -->
                <input type="number" name="duration" placeholder="Ex: 60" min="5" max="180" style="width: 100%; padding: 8px;" required>
                <small style="color: #666; font-size: 12px;">*Batas durasi: 5 - 180 menit</small>
            </div>

            <div style="margin-bottom: 20px;">
                <label>Choose Excel File:</label><br>
                <input type="file" name="file_excel" accept=".xlsx, .xls" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label>Upload Question Images (optional):</label><br>
                <input type="file" name="images_archive" accept=".zip,.rar,.gz,.tar,.tar.gz" multiple>
            </div>

            <button type="submit" style="background-color: #007497; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%;">
                Save Test & Import Questions
            </button>
        </form>
    </div>

    <hr style="margin: 40px 0; border: 0; border-top: 1px solid #eee;">

    <h3>Existing Tests</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 50px;">
        <tr style="background-color: #f2f2f2;">
            <th>Name</th>
            <th>Level</th>
            <th>Class</th>
            <th>Duration</th>
            <th>Questions</th>
            <th>Action</th>
        </tr>
        @foreach($tests as $t)
        <tr>
            <td>{{ $t->name }}</td>
            <td>{{ ucfirst($t->level) }}</td>
            <td>{{ $t->class }}-Class</td>
            <td>{{ $t->duration }} min</td>
            <td>{{ $t->questions->count() }} items</td>
            <td>
            <form action="{{ route('test.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this test and all its questions?')">
                @csrf
                @method('DELETE')
                <button type="submit" style="color: #ff4d4d; border: 1px solid #ff4d4d; background: white; cursor: pointer; padding: 5px 10px; border-radius: 5px; font-weight: bold; transition: 0.3s;">
                    Delete
                </button>
            </form>
        </td>
        </tr>
        @endforeach
    </table>

    <script>
    const levelSelect = document.querySelector('select[name="level"]');
    const classSelect = document.getElementById('classSelect');

    const classMap = {
        kinder: 3,
        elementary: 6,
        middle: 3,
        high: 3,
        adult: 3
    };

    levelSelect.addEventListener('change', function () {
        classSelect.innerHTML = '<option value="">-- Select Class --</option>';

        const total = classMap[this.value];
        if (!total) return;

        for (let i = 1; i <= total; i++) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i + '-Class';
            classSelect.appendChild(opt);
        }
    });
</script>
@endsection