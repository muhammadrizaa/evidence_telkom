<x-teamleader-layout>
    <style>
        .card { background:#fff; padding:24px; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.08); }
        .card-header { display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:2px solid #e5e7eb; margin-bottom:20px; }
        .card-header h2 { font-size:1.5rem; font-weight:700; color:#1f2937; margin:0; }
        .btn { display:inline-flex; align-items:center; padding:8px 16px; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; color:white; border:none; cursor:pointer; transition:all 0.2s; }
        .btn i { margin-right:6px; }
        .btn-blue { background:#2563eb; }
        .btn-blue:hover { background:#1d4ed8; }
        .btn-yellow { background:#d97706; }
        .btn-yellow:hover { background:#b45309; }
        .btn-red { background:#dc2626; }
        .btn-red:hover { background:#b91c1c; }
        .btn-sm { padding:6px 12px; font-size:0.8rem; }
        .alert-success { background:#d1fae5; border-left:4px solid #34d399; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:16px; }
        .table-wrapper { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        thead tr { background:#f9fafb; }
        th, td { padding:12px 16px; border-bottom:1px solid #e5e7eb; text-align:left; }
        tbody tr:hover { background:#f9fafb; }
        .badge { display:inline-flex; padding:4px 10px; border-radius:9999px; font-size:0.75rem; font-weight:600; }
        .badge-green { background:#d1fae5; color:#065f46; }
        .badge-gray { background:#f3f4f6; color:#4b5563; }
    </style>

    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-user-check" style="color:#2563eb; margin-right:10px;"></i>Kelola Assignment</h2>
            <a href="{{ route('teamleader.assignment.create') }}" class="btn btn-blue">
                <i class="fa-solid fa-plus"></i> Tambah Assignment
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Karyawan</th>
                        <th>Project/Lokasi</th>
                        <th>Mapping Area</th>
                        <th>Tgl Penugasan</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $assignment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $assignment->user->name ?? '-' }}</td>
                        <td>{{ $assignment->project->lokasi ?? '-' }}</td>
                        <td>{{ $assignment->mapping->nama_area ?? '-' }}</td>
                        <td>{{ $assignment->tgl_penugasan ? \Carbon\Carbon::parse($assignment->tgl_penugasan)->format('d M Y') : '-' }}</td>
                        <td>
                            @if($assignment->status_tugas === 'aktif')
                                <span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:0.6rem; margin-right:4px;"></i> Aktif</span>
                            @else
                                <span class="badge badge-gray">Selesai</span>
                            @endif
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="{{ route('teamleader.assignment.edit', $assignment->id) }}" class="btn btn-yellow btn-sm">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('teamleader.assignment.destroy', $assignment->id) }}" method="POST" style="display:inline; margin-left:6px;" onsubmit="return confirm('Yakin hapus assignment ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-red btn-sm">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:#6b7280;">Belum ada assignment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $assignments->links() }}</div>
    </div>
</x-teamleader-layout>