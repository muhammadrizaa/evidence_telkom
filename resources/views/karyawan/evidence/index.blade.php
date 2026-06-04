<x-karyawan-layout>
    <style>
        .card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .card-header { font-size: 1.25rem; font-weight: 600; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 24px; }
        .alert-success-custom { background-color: #ecfdf5; border-left: 4px solid #10b981; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; color: #065f46; font-weight: 500; font-size: 0.95rem; }
        .table-wrapper { overflow-x: auto; margin-top: 24px; }
        .styled-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .styled-table thead tr { background-color: #f9fafb; text-align: left; color: #374151; text-transform: uppercase; font-size: 0.75rem; }
        .styled-table th, .styled-table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
        .styled-table tbody tr:hover { background-color: #f3f4f6; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background-color: #fef9c3; color: #a16207; }
        .badge-approved { background-color: #dcfce7; color: #166534; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; }
        .btn { display: inline-flex; align-items: center; padding: 8px 14px; border-radius: 6px; font-weight: 500; font-size: 0.85rem; text-decoration: none; color: white; border: none; cursor: pointer; transition: all 0.2s; margin: 0 2px; }
        .btn i { margin-right: 6px; }
        .btn-blue { background-color: #2563eb; } .btn-blue:hover { background-color: #1d4ed8; }
        .btn-red { background-color: #ef4444; } .btn-red:hover { background-color: #dc2626; }
        .btn-lihat { background-color: #3b82f6; } .btn-lihat:hover { background-color: #2563eb; }
        .btn-gray { background-color: #6b7280; } .btn-gray:hover { background-color: #4b5563; }
        .pagination { margin-top: 16px; }
        .modal-overlay { position: fixed; inset: 0; background-color: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 50; overflow-y: auto; padding: 20px; }
        .modal-content { background-color: #fff; padding: 24px; border-radius: 8px; max-width: 95%; width: 95%; max-height: 90vh; display: flex; flex-direction: column; }
        .modal-header-clean { display: flex; justify-content: space-between; align-items: center; padding-bottom: 15px; margin-bottom: 15px; border-bottom: 2px solid #3b82f6; }
        .modal-header-clean h3 { font-size: 1.3rem; font-weight: 700; color: #1f2937; }
        .modal-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; overflow-y: auto; max-height: calc(90vh - 200px); padding: 10px 5px; }
        .image-preview-item { background-color: #fff; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px; display: flex; flex-direction: column; align-items: center; }
        .image-preview-item img { width: 100%; height: 160px; object-fit: cover; border-radius: 6px; margin-bottom: 8px; cursor: pointer; transition: transform 0.2s; }
        .image-preview-item img:hover { transform: scale(1.05); }
        .image-caption { font-size: 0.8rem; color: #374151; text-align: center; word-break: break-word; }
        [x-cloak] { display: none !important; }
    </style>

    <div class="card" x-data="{ modalOpen: false, evidenceFiles: [], evidenceLocation: '' }">
        <h2 class="card-header">Riwayat Evidence Anda</h2>

        @if(session('success'))
            <div class="alert-success-custom" id="success-alert">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successMessage = localStorage.getItem('successMessage');
                const totalFiles = localStorage.getItem('totalFiles');
                if (successMessage) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert-success-custom';
                    let msg = `✓ ${successMessage}`;
                    if (totalFiles && parseInt(totalFiles) > 0) msg += ` (${totalFiles} foto)`;
                    alertDiv.textContent = msg;
                    const cardHeader = document.querySelector('.card-header');
                    if (cardHeader) cardHeader.insertAdjacentElement('afterend', alertDiv);
                    localStorage.removeItem('successMessage');
                    localStorage.removeItem('totalFiles');
                    setTimeout(() => {
                        alertDiv.style.opacity = '0';
                        alertDiv.style.transition = 'opacity 0.4s ease';
                        setTimeout(() => alertDiv.remove(), 400);
                    }, 6000);
                }
                const sessionAlert = document.getElementById('success-alert');
                if (sessionAlert) {
                    setTimeout(() => {
                        sessionAlert.style.opacity = '0';
                        sessionAlert.style.transition = 'opacity 0.4s ease';
                        setTimeout(() => sessionAlert.remove(), 400);
                    }, 6000);
                }
            });
        </script>

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Lokasi / Waktu</th>
                        <th>Nomor PO</th>
                        <th>Tematik</th>
                        <th>Waspang</th>
                        <th>Foto</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($evidences as $evidence)
                        @php
                            $raw = $evidence->file_path;
                            if (is_string($raw)) {
                                $files = json_decode($raw, true) ?? [];
                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?? [];
                                }
                            } else {
                                $files = $raw ?? [];
                            }
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $evidence->assignment->project->lokasi ?? '-' }}</strong><br>
                                <small style="color:#6b7280;">{{ $evidence->created_at->format('d M Y H:i') }}</small>
                            </td>
                            <td>{{ $evidence->purchaseOrder->no_po ?? 'N/A' }}</td>
                            <td>{{ $evidence->tematik->nama_tematik ?? 'N/A' }}</td>
                            <td>{{ $evidence->waspang->nama_waspang ?? 'N/A' }}</td>
                            <td>
                                <button onclick="openModal{{ $evidence->id }}()" class="btn btn-lihat">
                                    <i class="fa-solid fa-folder-open"></i> Lihat ({{ count($files) }})
                                </button>
                                <script>
                                    function openModal{{ $evidence->id }}() {
                                        const data = {!! json_encode($files) !!};
                                        const component = document.querySelector('[x-data]').__x.$data;
                                        component.evidenceFiles = data;
                                        component.evidenceLocation = '{{ addslashes($evidence->assignment->project->lokasi ?? '-') }}';
                                        component.modalOpen = true;
                                    }
                                </script>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-{{ $evidence->status_laporan }}">
                                    {{ ucfirst($evidence->status_laporan) }}
                                </span>
                            </td>
                            <td style="text-align:center; white-space:nowrap;">
                                @if($evidence->status_laporan != 'approved')
                                    <a href="{{ route('karyawan.evidence.edit', $evidence->id) }}" class="btn btn-blue">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('karyawan.evidence.destroy', $evidence->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus evidence ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-red">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="btn btn-gray">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:32px; color:#6b7280;">
                                <i class="fa-solid fa-folder-open" style="font-size:2rem; opacity:0.3; display:block; margin-bottom:8px;"></i>
                                Anda belum memiliki riwayat evidence.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $evidences->links() }}</div>

        <!-- Modal Foto -->
        <div x-show="modalOpen" class="modal-overlay" x-cloak>
            <div class="modal-content" @click.away="modalOpen = false">
                <div class="modal-header-clean">
                    <h3 x-text="'Evidence: ' + evidenceLocation"></h3>
                    <span style="font-size:0.9rem; color:#6b7280;">
                        Total <strong x-text="evidenceFiles.length"></strong> Foto
                    </span>
                </div>
                <div class="modal-gallery">
                    <template x-for="(file, index) in evidenceFiles" :key="index">
                        <div class="image-preview-item">
                            <img
                                :src="'{{ asset('storage') }}/' + encodeURIComponent(file.path).replace(/%2F/g, '/')"
                                :alt="'Foto ' + (index + 1)"
                                @click="window.open('{{ asset('storage') }}/' + file.path, '_blank')"
                                onerror="this.src='{{ asset('images/no-image.png') }}'; this.style.objectFit='contain';">
                            <p class="image-caption" x-text="file.caption || ('Foto ' + (index + 1))"></p>
                        </div>
                    </template>
                </div>
                <div style="text-align:right; margin-top:20px;">
                    <button @click="modalOpen = false" class="btn btn-red">
                        <i class="fa-solid fa-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-karyawan-layout>