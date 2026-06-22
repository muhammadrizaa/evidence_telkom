<x-teamleader-layout>
    <style>
        .card { background:#fff; padding:24px; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.08); }
        .card-header { display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:2px solid #e5e7eb; margin-bottom:20px; }
        .card-header h2 { font-size:1.5rem; font-weight:700; color:#1f2937; margin:0; }
        .btn { display:inline-flex; align-items:center; padding:8px 16px; border-radius:8px; font-weight:600; font-size:0.875rem; text-decoration:none; color:white; border:none; cursor:pointer; transition:all 0.2s; }
        .btn i { margin-right:6px; }
        .btn-red { background:#dc2626; }
        .btn-red:hover { background:#b91c1c; }
        .btn-yellow { background:#d97706; }
        .btn-yellow:hover { background:#b45309; }
        .btn-sm { padding:6px 12px; font-size:0.8rem; }
        .alert-success { background:#d1fae5; border-left:4px solid #34d399; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:16px; }
        .table-wrapper { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        thead tr { background:#f9fafb; }
        th, td { padding:12px 16px; border-bottom:1px solid #e5e7eb; text-align:left; }
        tbody tr:hover { background:#f9fafb; }
    </style>

    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-map-marker-alt" style="color:#dc2626; margin-right:10px;"></i>Kelola Nama Area</h2>
            <a href="{{ route('teamleader.mapping.create') }}" class="btn btn-red">
                <i class="fa-solid fa-plus"></i> Tambah Area
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
                        <th>Nama Area</th>
                        <th>Kode Area</th>
                        <th>Dibuat</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mappings as $mapping)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $mapping->nama_area }}</td>
                        <td><span style="background:#f3f4f6; padding:4px 10px; border-radius:6px; font-size:0.8rem; font-weight:600;">{{ $mapping->kode_mapping }}</span></td>
                        <td style="color:#6b7280; font-size:0.8rem;">{{ $mapping->created_at ? $mapping->created_at->format('d M Y') : '-' }}</td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="{{ route('teamleader.mapping.edit', $mapping->id) }}" class="btn btn-yellow btn-sm">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('teamleader.mapping.destroy', $mapping->id) }}" method="POST" style="display:inline; margin-left:6px;" onsubmit="return confirm('Yakin hapus mapping ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-red btn-sm">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:40px; color:#6b7280;">Belum ada data mapping.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $mappings->links() }}</div>
    </div>
</x-teamleader-layout>