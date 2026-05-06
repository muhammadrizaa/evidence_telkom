<x-teamleader-layout>
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(1, 1fr); gap: 24px; }
        @media (min-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }
        .stat-card { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); display: flex; align-items: center; }
        .stat-card .icon { padding: 12px; border-radius: 9999px; margin-right: 16px; display: flex; align-items: center; justify-content: center; }
        .stat-card .icon i { font-size: 1.5rem; color: #fff; width: 28px; height: 28px; text-align: center; line-height: 28px; }
        .stat-card .info h4 { font-size: 0.875rem; font-weight: 500; color: #6b7280; text-transform: uppercase; margin: 0 0 4px; }
        .stat-card .info p { font-size: 1.875rem; font-weight: 700; color: #1f2937; margin: 0; line-height: 1; }
        .icon-total { background-color: #1d4ed8; }
        .icon-aktif { background-color: #10b981; }
        .icon-pending { background-color: #f59e0b; }
        .icon-selesai { background-color: #6b7280; }
        .recent-card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-top: 24px; }
        .recent-card h3 { font-size: 1.25rem; font-weight: 600; color: #1f2937; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; margin-bottom: 16px; }
        .styled-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .styled-table thead tr { text-align: left; color: #374151; font-size: 0.75rem; text-transform: uppercase; }
        .styled-table th, .styled-table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; }
        .styled-table tbody tr:last-child td { border-bottom: none; }
        .badge { padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .badge-pending { background-color: #fef9c3; color: #a16207; }
        .badge-aktif { background-color: #dcfce7; color: #166534; }
        .badge-selesai { background-color: #e5e7eb; color: #374151; }
    </style>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon icon-total"><i class="fa-solid fa-diagram-project"></i></div>
            <div class="info"><h4>Total Project</h4><p>{{ $totalProject }}</p></div>
        </div>
        <div class="stat-card">
            <div class="icon icon-aktif"><i class="fa-solid fa-circle-check"></i></div>
            <div class="info"><h4>Project Aktif</h4><p>{{ $projectAktif }}</p></div>
        </div>
        <div class="stat-card">
            <div class="icon icon-pending"><i class="fa-solid fa-clock"></i></div>
            <div class="info"><h4>Project Pending</h4><p>{{ $projectPending }}</p></div>
        </div>
        <div class="stat-card">
            <div class="icon icon-selesai"><i class="fa-solid fa-flag-checkered"></i></div>
            <div class="info"><h4>Project Selesai</h4><p>{{ $projectSelesai }}</p></div>
        </div>
    </div>

    {{-- Recent Projects --}}
    <div class="recent-card">
        <h3>Project Terbaru</h3>
        <div style="overflow-x: auto;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Nama Project</th>
                        <th>Lokasi</th>
                        <th>Admin</th>
                        <th>Waspang</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentProjects as $project)
                        <tr>
                            <td>{{ $project->nama_project }}</td>
                            <td>{{ $project->lokasi }}</td>
                            <td>{{ $project->admin->name ?? '-' }}</td>
                            <td>{{ $project->waspang->nama_waspang ?? '-' }}</td>
                            <td style="text-align: center;">
                                <span class="badge badge-{{ $project->status }}">
                                    {{ $project->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 16px;">
                                Belum ada project yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-teamleader-layout>