<x-karyawan-layout>
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(1, 1fr); gap: 20px; margin-bottom: 24px; }
        @media (min-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }

        .stat-card { background-color: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; flex-direction: row; align-items: center; gap: 16px; min-height: 90px; }
        .stat-card .icon { width: 52px; height: 52px; min-width: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-card .icon i { font-size: 1.3rem; color: #fff; }
        .stat-card .info h4 { font-size: 0.72rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 4px 0; }
        .stat-card .info p { font-size: 1.8rem; font-weight: 700; color: #1f2937; margin: 0; line-height: 1; }
        .icon-total { background-color: #1f2937; }
        .icon-pending { background-color: #f59e0b; }
        .icon-approved { background-color: #10b981; }
        .icon-rejected { background-color: #ef4444; }

        .card { background-color: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px; }
        .card h3 { font-size: 1.1rem; font-weight: 600; color: #1f2937; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; margin: 0 0 16px 0; }
        .styled-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .styled-table thead tr { text-align: left; color: #374151; font-size: 0.75rem; text-transform: uppercase; background: #f9fafb; }
        .styled-table th, .styled-table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; }
        .styled-table tbody tr:last-child td { border-bottom: none; }
        .styled-table tbody tr:hover { background: #f9fafb; }

        .badge { padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background-color: #fef9c3; color: #a16207; }
        .badge-approved { background-color: #dcfce7; color: #166534; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; }
        .badge-aktif { background-color: #dbeafe; color: #1e40af; }
        .badge-selesai { background-color: #f3f4f6; color: #4b5563; }

        .btn { display: inline-flex; align-items: center; padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 0.8rem; text-decoration: none; color: white; border: none; cursor: pointer; transition: all 0.2s; }
        .btn i { margin-right: 6px; }
        .btn-red { background: #dc2626; }
        .btn-red:hover { background: #b91c1c; }

        .dashboard-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
        @media (min-width: 1024px) { .dashboard-grid { grid-template-columns: 1fr 1fr; } }
    </style>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon icon-total"><i class="fa-solid fa-file-lines"></i></div>
            <div class="info"><h4>Total Evidence Saya</h4><p>{{ $totalEvidence }}</p></div>
        </div>
        <div class="stat-card">
            <div class="icon icon-pending"><i class="fa-solid fa-clock"></i></div>
            <div class="info"><h4>Menunggu Persetujuan</h4><p>{{ $pendingCount }}</p></div>
        </div>
        <div class="stat-card">
            <div class="icon icon-approved"><i class="fa-solid fa-check-circle"></i></div>
            <div class="info"><h4>Disetujui</h4><p>{{ $approvedCount }}</p></div>
        </div>
        <div class="stat-card">
            <div class="icon icon-rejected"><i class="fa-solid fa-times-circle"></i></div>
            <div class="info"><h4>Ditolak</h4><p>{{ $rejectedCount }}</p></div>
        </div>
    </div>

    <div class="dashboard-grid">
        {{-- Tabel Assignment --}}
        <div class="card">
            <h3><i class="fa-solid fa-user-check" style="color:#dc2626; margin-right:8px;"></i>Penugasan Saya</h3>
            <div style="overflow-x: auto;">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Project/Lokasi</th>
                            <th>Mapping Area</th>
                            <th>Tgl Penugasan</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                            <tr>
                                <td style="font-weight:600;">{{ $assignment->project->lokasi ?? '-' }}</td>
                                <td>{{ $assignment->mapping->nama_area ?? '-' }}</td>
                                <td style="color:#6b7280; font-size:0.8rem;">
                                    {{ $assignment->tgl_penugasan ? \Carbon\Carbon::parse($assignment->tgl_penugasan)->format('d M Y') : '-' }}
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge badge-{{ $assignment->status_tugas }}">
                                        {{ ucfirst($assignment->status_tugas) }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    @if($assignment->status_tugas === 'aktif')
                                        <a href="{{ route('karyawan.evidence.create') }}" class="btn btn-red">
                                            <i class="fa-solid fa-upload"></i> Upload
                                        </a>
                                    @else
                                        <span style="color:#9ca3af; font-size:0.8rem;">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:24px; color:#6b7280;">
                                    Belum ada penugasan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Evidence Terbaru --}}
        <div class="card">
            <h3><i class="fa-solid fa-clock-rotate-left" style="color:#dc2626; margin-right:8px;"></i>Evidence Terbaru (7 Hari)</h3>
            <div style="overflow-x: auto;">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Tanggal Upload</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentEvidences as $evidence)
                            <tr>
                                <td>{{ $evidence->created_at->format('d M Y') }}</td>
                                <td style="text-align:center;">
                                    <span class="badge badge-{{ $evidence->status_laporan }}">
                                        {{ ucfirst($evidence->status_laporan) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align:center; padding:24px; color:#6b7280;">
                                    Tidak ada evidence dalam 7 hari terakhir.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-karyawan-layout>