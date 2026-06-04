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

        .dashboard-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
        @media (min-width: 1024px) { .dashboard-grid { grid-template-columns: 1fr 1fr; } }

        .card { background-color: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .card h3 { font-size: 1rem; font-weight: 700; color: #1f2937; padding-bottom: 14px; border-bottom: 1px solid #e5e7eb; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px; }
        .card h3 i { color: #dc2626; }

        .styled-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .styled-table thead tr { background-color: #f9fafb; text-align: left; color: #374151; font-size: 0.75rem; text-transform: uppercase; }
        .styled-table th, .styled-table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
        .styled-table tbody tr:last-child td { border-bottom: none; }
        .styled-table tbody tr:hover { background: #f9fafb; }

        .badge { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background-color: #fef9c3; color: #a16207; }
        .badge-approved { background-color: #dcfce7; color: #166534; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; }
        .badge-aktif { background-color: #dbeafe; color: #1e40af; }
        .badge-selesai { background-color: #f3f4f6; color: #4b5563; }

        .btn { display: inline-flex; align-items: center; padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 0.8rem; text-decoration: none; color: white; border: none; cursor: pointer; transition: all 0.2s; }
        .btn i { margin-right: 6px; }
        .btn-red { background: #dc2626; } .btn-red:hover { background: #b91c1c; }

        .empty-state { text-align: center; padding: 24px; color: #9ca3af; }
        .empty-state i { font-size: 2rem; opacity: 0.3; display: block; margin-bottom: 8px; }
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
            <h3><i class="fa-solid fa-user-check"></i> Penugasan Saya</h3>
            <div style="overflow-x: auto;">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Project/Lokasi</th>
                            <th>No. PO</th>
                            <th>Tgl Penugasan</th>
                            <th>Deadline</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                        @php
                            $deadline = $assignment->deadline ? \Carbon\Carbon::parse($assignment->deadline) : null;
                            $diff     = $deadline ? \Carbon\Carbon::now()->diffInDays($deadline, false) : null;
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ $assignment->project->nama_project ?? '-' }}</div>
                                <small style="color:#6b7280;">{{ $assignment->project->lokasi ?? '' }}</small>
                            </td>
                            <td>{{ $assignment->purchaseOrder->no_po ?? '-' }}</td>
                            <td style="color:#6b7280; font-size:0.8rem;">
                                {{ $assignment->tgl_penugasan ? \Carbon\Carbon::parse($assignment->tgl_penugasan)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                @if($deadline)
                                    <span style="font-size:0.8rem; color:{{ $diff < 0 ? '#dc2626' : ($diff <= 3 ? '#d97706' : '#374151') }}; font-weight:600;">
                                        {{ $deadline->format('d M Y') }}
                                    </span>
                                    @if($diff < 0)
                                        <br><small style="color:#dc2626; font-size:0.7rem;">Terlambat {{ abs((int)$diff) }} hari</small>
                                    @elseif($diff <= 3)
                                        <br><small style="color:#d97706; font-size:0.7rem;">{{ (int)$diff }} hari lagi</small>
                                    @endif
                                @else
                                    <span style="color:#9ca3af;">-</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-{{ $assignment->status_tugas }}">
                                    {{ ucfirst($assignment->status_tugas) }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                @if($assignment->status_tugas === 'aktif')
                                    <a href="{{ route('karyawan.evidence.create', ['assignment_id' => $assignment->id]) }}" class="btn btn-red">
                                        <i class="fa-solid fa-upload"></i> Upload
                                    </a>
                                @else
                                    <span style="color:#9ca3af; font-size:0.8rem;">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-user-check"></i>
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
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Evidence Terbaru (7 Hari)</h3>
            <div style="overflow-x: auto;">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Lokasi</th>
                            <th>Tanggal Upload</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentEvidences as $evidence)
                        <tr>
                            <td>{{ $evidence->assignment->project->lokasi ?? '-' }}</td>
                            <td style="color:#6b7280; font-size:0.8rem;">{{ $evidence->created_at->format('d M Y H:i') }}</td>
                            <td style="text-align:center;">
                                <span class="badge badge-{{ $evidence->status_laporan }}">
                                    {{ ucfirst($evidence->status_laporan) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                Tidak ada evidence dalam 7 hari terakhir.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px; text-align:right;">
                <a href="{{ route('karyawan.evidence.index') }}" class="btn btn-red">
                    <i class="fa-solid fa-arrow-right"></i> Lihat Semua
                </a>
            </div>
        </div>
    </div>
</x-karyawan-layout>