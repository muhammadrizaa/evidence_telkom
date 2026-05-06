<x-teamleader-layout>
    <style>
        .card { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.08); }
        .card-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 2px solid #e5e7eb; margin-bottom: 20px; }
        .card-header h2 { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0; }
        .btn { display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 0.875rem; text-decoration: none; color: white; border: none; cursor: pointer; transition: all 0.2s; }
        .btn i { margin-right: 6px; }
        .btn-blue { background-color: #2563eb; }
        .btn-blue:hover { background-color: #1d4ed8; }
        .btn-yellow { background-color: #d97706; }
        .btn-yellow:hover { background-color: #b45309; }
        .btn-red { background-color: #dc2626; }
        .btn-red:hover { background-color: #b91c1c; }
        .btn-sm { padding: 6px 12px; font-size: 0.8rem; }
        .alert-success { background: #d1fae5; border-left: 4px solid #34d399; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        thead tr { background: #f9fafb; }
        th, td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        tbody tr:hover { background: #f9fafb; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-aktif    { background: #d1fae5; color: #065f46; }
        .badge-selesai  { background: #dbeafe; color: #1e40af; }
    </style>

    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-diagram-project" style="color:#2563eb; margin-right:10px;"></i>Kelola Project</h2>
            <a href="{{ route('teamleader.project.create') }}" class="btn btn-blue">
                <i class="fa-solid fa-plus"></i> Tambah Project
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
                        <th>Nama Project</th>
                        <th>Lokasi</th>
                        <th>Waspang</th>
                        <th>Admin Ondesk</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $project->nama_project }}</td>
                        <td>{{ $project->lokasi }}</td>
                        <td>
                            @if($project->waspang)
                                {{ $project->waspang->nama_waspang }}
                                <br><small style="color:#6b7280;">{{ $project->waspang->nik_waspang }}</small>
                            @else
                                <span style="color:#9ca3af;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($project->admin)
                                {{ $project->admin->name }}
                            @else
                                <span style="color:#9ca3af;">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = match($project->status) {
                                    'aktif'   => 'badge-aktif',
                                    'selesai' => 'badge-selesai',
                                    default   => 'badge-pending',
                                };
                                $badgeLabel = match($project->status) {
                                    'aktif'   => 'Aktif',
                                    'selesai' => 'Selesai',
                                    default   => 'Pending',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </td>
                        <td>{{ $project->deskripsi ?? '-' }}</td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="{{ route('teamleader.project.edit', $project->id) }}" class="btn btn-yellow btn-sm">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('teamleader.project.destroy', $project->id) }}" method="POST" style="display:inline; margin-left:6px;" onsubmit="return confirm('Yakin hapus project ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-red btn-sm">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:#6b7280;">Belum ada project.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $projects->links() }}</div>
    </div>
</x-teamleader-layout>