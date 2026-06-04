<x-teamleader-layout>
    <style>
        .stats-grid { display:grid; grid-template-columns:repeat(1,1fr); gap:20px; margin-bottom:24px; }
        @media (min-width:768px) { .stats-grid { grid-template-columns:repeat(2,1fr); } }
        @media (min-width:1024px) { .stats-grid { grid-template-columns:repeat(3,1fr); } }

        .stat-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); display:flex; flex-direction:row; align-items:center; gap:16px; min-height:90px; }
        .stat-card .icon { width:52px; height:52px; min-width:52px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .stat-card .icon i { font-size:1.3rem; color:#fff; }
        .stat-card .info h4 { font-size:0.72rem; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 4px 0; }
        .stat-card .info p { font-size:1.8rem; font-weight:700; color:#1f2937; margin:0; line-height:1; }
        .icon-total { background:#1d4ed8; }
        .icon-aktif { background:#10b981; }
        .icon-pending { background:#f59e0b; }
        .icon-selesai { background:#6b7280; }
        .icon-assignment { background:#8b5cf6; }
        .icon-deadline { background:#ef4444; }

        .dashboard-grid { display:grid; grid-template-columns:1fr; gap:24px; }
        @media (min-width:1024px) { .dashboard-grid { grid-template-columns:1fr 1fr; } }

        .card { background:#fff; padding:24px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
        .card h3 { font-size:1rem; font-weight:700; color:#1f2937; padding-bottom:14px; border-bottom:1px solid #e5e7eb; margin:0 0 16px 0; display:flex; align-items:center; gap:8px; }
        .card h3 i { color:#dc2626; }

        .styled-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .styled-table thead tr { background:#f9fafb; text-align:left; color:#374151; font-size:0.75rem; text-transform:uppercase; }
        .styled-table th, .styled-table td { padding:10px 14px; border-bottom:1px solid #e5e7eb; vertical-align:middle; }
        .styled-table tbody tr:last-child td { border-bottom:none; }
        .styled-table tbody tr:hover { background:#f9fafb; }

        .badge { display:inline-block; padding:4px 10px; border-radius:9999px; font-size:0.75rem; font-weight:600; }
        .badge-pending { background:#fef9c3; color:#a16207; }
        .badge-aktif { background:#dcfce7; color:#166534; }
        .badge-selesai { background:#e5e7eb; color:#374151; }
        .badge-danger { background:#fee2e2; color:#991b1b; }
        .badge-warning { background:#fef3c7; color:#92400e; }

        .btn { display:inline-flex; align-items:center; padding:6px 14px; border-radius:8px; font-weight:600; font-size:0.8rem; text-decoration:none; color:white; border:none; cursor:pointer; transition:all 0.2s; }
        .btn i { margin-right:6px; }
        .btn-red { background:#dc2626; } .btn-red:hover { background:#b91c1c; }
        .btn-blue { background:#2563eb; } .btn-blue:hover { background:#1d4ed8; }

        .empty-state { text-align:center; padding:32px; color:#9ca3af; }
        .empty-state i { font-size:2rem; opacity:0.3; display:block; margin-bottom:8px; }
    </style>

    {{-- Stats Grid --}}
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
        <div class="stat-card">
            <div class="icon icon-assignment"><i class="fa-solid fa-user-check"></i></div>
            <div class="info"><h4>Total Assignment</h4><p>{{ $totalAssignment }}</p></div>
        </div>
        <div class="stat-card">
            <div class="icon icon-deadline"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="info"><h4>Deadline ≤ 7 Hari</h4><p>{{ $deadlineDekat }}</p></div>
        </div>
    </div>

    {{-- Dashboard Grid --}}
    <div class="dashboard-grid">

        {{-- Tabel Project Terbaru --}}
        <div class="card">
            <h3><i class="fa-solid fa-diagram-project"></i> Project Terbaru</h3>
            <div style="overflow-x:auto;">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Nama Project</th>
                            <th>Lokasi</th>
                            <th>Tematik</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentProjects as $project)
                        <tr>
                            <td style="font-weight:600;">{{ $project->nama_project }}</td>
                            <td style="color:#6b7280; font-size:0.8rem;">{{ $project->lokasi }}</td>
                            <td>{{ $project->tematik->nama_tematik ?? '-' }}</td>
                            <td style="text-align:center;">
                                <span class="badge badge-{{ $project->status }}">{{ ucfirst($project->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fa-solid fa-diagram-project"></i>
                                Belum ada project.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px; text-align:right;">
                <a href="{{ route('teamleader.project.index') }}" class="btn btn-red">
                    <i class="fa-solid fa-arrow-right"></i> Lihat Semua
                </a>
            </div>
        </div>

        {{-- Tabel Assignment Deadline Dekat --}}
        <div class="card">
            <h3><i class="fa-solid fa-triangle-exclamation"></i> Assignment Deadline Dekat</h3>
            <div style="overflow-x:auto;">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Project</th>
                            <th>No. PO</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nearDeadlineAssignments as $assignment)
                        @php
                            $deadline = \Carbon\Carbon::parse($assignment->deadline);
                            $diff     = \Carbon\Carbon::now()->diffInDays($deadline, false);
                        @endphp
                        <tr>
                            <td style="font-weight:600;">{{ $assignment->user->name ?? '-' }}</td>
                            <td style="color:#6b7280; font-size:0.8rem;">{{ $assignment->project->lokasi ?? '-' }}</td>
                            <td>{{ $assignment->purchaseOrder->no_po ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $diff < 0 ? 'badge-danger' : 'badge-warning' }}">
                                    {{ $deadline->format('d M Y') }}
                                </span>
                                <br>
                                <small style="color:{{ $diff < 0 ? '#dc2626' : '#d97706' }}; font-size:0.7rem;">
                                    {{ $diff < 0 ? 'Terlambat '.abs((int)$diff).' hari' : (int)$diff.' hari lagi' }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fa-solid fa-circle-check"></i>
                                Tidak ada deadline mendekat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px; text-align:right;">
                <a href="{{ route('teamleader.assignment.index') }}" class="btn btn-red">
                    <i class="fa-solid fa-arrow-right"></i> Lihat Semua
                </a>
            </div>
        </div>

    </div>
</x-teamleader-layout>