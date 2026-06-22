<x-teamleader-layout>
    <style>
        .card { background:#fff; padding:24px; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.08); max-width:600px; margin:0 auto; }
        .card-header { font-size:1.25rem; font-weight:700; color:#1f2937; border-bottom:2px solid #e5e7eb; padding-bottom:16px; margin-bottom:24px; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-weight:600; color:#374151; margin-bottom:6px; }
        .form-group input, .form-group select { width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:0.875rem; box-sizing:border-box; }
        .form-group input:focus, .form-group select:focus { border-color:#dc2626; outline:none; box-shadow:0 0 0 3px rgba(220,38,38,0.1); }
        .error-msg { color:#dc2626; font-size:0.8rem; margin-top:4px; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .btn { display:inline-flex; align-items:center; padding:10px 20px; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; color:white; border:none; cursor:pointer; }
        .btn i { margin-right:6px; }
        .btn-red { background:#dc2626; } .btn-red:hover { background:#b91c1c; }
        .btn-gray { background:#6b7280; } .btn-gray:hover { background:#4b5563; }
    </style>

    <div class="card">
        <div class="card-header"><i class="fa-solid fa-user-plus" style="color:#dc2626; margin-right:8px;"></i>Tambah Assignment</div>
        <form method="POST" action="{{ route('teamleader.assignment.store') }}">
            @csrf
            <div class="form-group">
                <label>Karyawan</label>
                <select name="user_id" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach ($karyawans as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->username }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Project/Lokasi</label>
                <select name="project_id" required>
                    <option value="">-- Pilih Project --</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->nama_project }} - {{ $project->lokasi }}
                        </option>
                    @endforeach
                </select>
                @error('project_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Area</label>
                <select name="mapping_id" required>
                    <option value="">-- Pilih Area --</option>
                    @foreach ($mappings as $mapping)
                        <option value="{{ $mapping->id }}" {{ old('mapping_id') == $mapping->id ? 'selected' : '' }}>
                            {{ $mapping->nama_area }} ({{ $mapping->kode_mapping }})
                        </option>
                    @endforeach
                </select>
                @error('mapping_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Tematik / Jenis Pekerjaan</label>
                <select name="tematik_id" required>
                    <option value="">-- Pilih Tematik --</option>
                    @foreach ($tematiKs as $tematik)
                        <option value="{{ $tematik->id }}" {{ old('tematik_id') == $tematik->id ? 'selected' : '' }}>
                            {{ $tematik->nama_tematik }}
                        </option>
                    @endforeach
                </select>
                @error('tematik_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Nomor Purchase Order (PO)</label>
                <select name="po_id" required>
                    <option value="">-- Pilih Nomor PO --</option>
                    @foreach ($po_list as $po)
                        <option value="{{ $po->id }}" {{ old('po_id') == $po->id ? 'selected' : '' }}>
                            {{ $po->no_po }}
                        </option>
                    @endforeach
                </select>
                @error('po_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Penugasan</label>
                    <input type="date" name="tgl_penugasan" value="{{ old('tgl_penugasan') }}" required>
                    @error('tgl_penugasan') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}" required>
                    @error('deadline') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group">
                <label>Status Tugas</label>
                <select name="status_tugas" required>
                    <option value="aktif" {{ old('status_tugas') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ old('status_tugas') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status_tugas') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn btn-red"><i class="fa-solid fa-save"></i> Simpan</button>
                <a href="{{ route('teamleader.assignment.index') }}" class="btn btn-gray"><i class="fa-solid fa-arrow-left"></i> Batal</a>
            </div>
        </form>
    </div>
</x-teamleader-layout>