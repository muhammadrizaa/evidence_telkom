<x-teamleader-layout>
    <style>
        .card { background:#fff; padding:24px; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.08); max-width:600px; margin:0 auto; }
        .card-header { font-size:1.25rem; font-weight:700; color:#1f2937; border-bottom:2px solid #e5e7eb; padding-bottom:16px; margin-bottom:24px; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-weight:600; color:#374151; margin-bottom:6px; }
        .form-group input, .form-group textarea { width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:0.875rem; box-sizing:border-box; }
        .form-group input:focus, .form-group textarea:focus { border-color:#2563eb; outline:none; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
        .error-msg { color:#dc2626; font-size:0.8rem; margin-top:4px; }
        .btn { display:inline-flex; align-items:center; padding:10px 20px; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; color:white; border:none; cursor:pointer; }
        .btn i { margin-right:6px; }
        .btn-blue { background:#2563eb; }
        .btn-blue:hover { background:#1d4ed8; }
        .btn-gray { background:#6b7280; }
        .btn-gray:hover { background:#4b5563; }
    </style>

    <div class="card">
        <div class="card-header"><i class="fa-solid fa-edit" style="color:#2563eb; margin-right:8px;"></i>Edit Project</div>
        <form method="POST" action="{{ route('teamleader.project.update', $project->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Lokasi Project</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $project->lokasi) }}" required>
                @error('lokasi') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Deskripsi <span style="color:#9ca3af;">(opsional)</span></label>
                <textarea name="deskripsi" rows="4">{{ old('deskripsi', $project->deskripsi) }}</textarea>
                @error('deskripsi') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn btn-blue"><i class="fa-solid fa-save"></i> Simpan</button>
                <a href="{{ route('teamleader.project.index') }}" class="btn btn-gray"><i class="fa-solid fa-arrow-left"></i> Batal</a>
            </div>
        </form>
    </div>
</x-teamleader-layout>