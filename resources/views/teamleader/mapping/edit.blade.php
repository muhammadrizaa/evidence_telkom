<x-teamleader-layout>
    <style>
        .card { background:#fff; padding:24px; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.08); max-width:500px; margin:0 auto; }
        .card-header { font-size:1.25rem; font-weight:700; color:#1f2937; border-bottom:2px solid #e5e7eb; padding-bottom:16px; margin-bottom:24px; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-weight:600; color:#374151; margin-bottom:6px; }
        .form-group input { width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:0.875rem; box-sizing:border-box; }
        .form-group input:focus { border-color:#dc2626; outline:none; box-shadow:0 0 0 3px rgba(220,38,38,0.1); }
        .error-msg { color:#dc2626; font-size:0.8rem; margin-top:4px; }
        .btn { display:inline-flex; align-items:center; padding:10px 20px; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; color:white; border:none; cursor:pointer; }
        .btn i { margin-right:6px; }
        .btn-red { background:#dc2626; }
        .btn-red:hover { background:#b91c1c; }
        .btn-gray { background:#6b7280; }
        .btn-gray:hover { background:#4b5563; }
    </style>

    <div class="card">
        <div class="card-header"><i class="fa-solid fa-edit" style="color:#dc2626; margin-right:8px;"></i>Edit Mapping: {{ $mapping->nama_area }}</div>
        <form method="POST" action="{{ route('teamleader.mapping.update', $mapping->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nama Area</label>
                <input type="text" name="nama_area" value="{{ old('nama_area', $mapping->nama_area) }}" required>
                @error('nama_area') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Kode Mapping</label>
                <input type="text" name="kode_mapping" value="{{ old('kode_mapping', $mapping->kode_mapping) }}" required>
                @error('kode_mapping') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn btn-red"><i class="fa-solid fa-save"></i> Simpan</button>
                <a href="{{ route('teamleader.mapping.index') }}" class="btn btn-gray"><i class="fa-solid fa-arrow-left"></i> Batal</a>
            </div>
        </form>
    </div>
</x-teamleader-layout>