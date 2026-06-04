<x-teamleader-layout>
    <style>
        .card { background:#fff; padding:24px; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.08); max-width:600px; margin:0 auto; }
        .card-header { font-size:1.25rem; font-weight:700; color:#1f2937; border-bottom:2px solid #e5e7eb; padding-bottom:16px; margin-bottom:24px; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-weight:600; color:#374151; margin-bottom:6px; }
        .form-group input, .form-group textarea, .form-group select { width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:0.875rem; box-sizing:border-box; background:#fff; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color:#dc2626; outline:none; box-shadow:0 0 0 3px rgba(220,38,38,0.1); }
        .admin-info { background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:10px 14px; font-size:0.875rem; color:#0369a1; }
        .error-msg { color:#dc2626; font-size:0.8rem; margin-top:4px; }
        .btn { display:inline-flex; align-items:center; padding:10px 20px; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; color:white; border:none; cursor:pointer; }
        .btn i { margin-right:6px; }
        .btn-red { background:#dc2626; } .btn-red:hover { background:#b91c1c; }
        .btn-gray { background:#6b7280; } .btn-gray:hover { background:#4b5563; }
    </style>

    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-plus" style="color:#dc2626; margin-right:8px;"></i>Tambah Project Baru
        </div>
        <form method="POST" action="{{ route('teamleader.project.store') }}">
            @csrf

            <div class="form-group">
                <label>Nama Project</label>
                <input type="text" name="nama_project" value="{{ old('nama_project') }}" placeholder="Contoh: Pembangunan Jaringan Fiber" required>
                @error('nama_project') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Lokasi Project</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: STO Banjarmasin" required>
                @error('lokasi') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Tematik / Jenis Pekerjaan</label>
                <select name="tematik_id" required>
                    <option value="">-- Pilih Tematik --</option>
                    @foreach($tematiKs as $tematik)
                        <option value="{{ $tematik->id }}" {{ old('tematik_id') == $tematik->id ? 'selected' : '' }}>
                            {{ $tematik->nama_tematik }}
                        </option>
                    @endforeach
                </select>
                @error('tematik_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Admin Pengawal (Ondesk)</label>
                <div class="admin-info">
                    <i class="fa-solid fa-user-tie" style="margin-right:6px;"></i>
                    {{ $admin ? $admin->name : 'Belum ada admin terdaftar' }}
                </div>
            </div>

            <div class="form-group">
                <label>Waspang (Lapangan)</label>
                <select name="waspang_id" required>
                    <option value="">-- Pilih Waspang --</option>
                    @foreach($waspangs as $waspang)
                        <option value="{{ $waspang->id }}" {{ old('waspang_id') == $waspang->id ? 'selected' : '' }}>
                            {{ $waspang->nama_waspang }} — {{ $waspang->nik_waspang }}
                        </option>
                    @endforeach
                </select>
                @error('waspang_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="aktif"   {{ old('status') == 'aktif'   ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Deskripsi <span style="color:#9ca3af;">(opsional)</span></label>
                <textarea name="deskripsi" rows="4" placeholder="Deskripsi project...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn btn-red"><i class="fa-solid fa-save"></i> Simpan</button>
                <a href="{{ route('teamleader.project.index') }}" class="btn btn-gray"><i class="fa-solid fa-arrow-left"></i> Batal</a>
            </div>
        </form>
    </div>
</x-teamleader-layout>