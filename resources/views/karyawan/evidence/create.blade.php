<x-karyawan-layout>
    <head>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    </head>

    <style>
        .card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-header { font-size: 1.25rem; font-weight: 600; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 24px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 0.875rem; font-family: inherit; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #dc2626; outline: none; box-shadow: 0 0 0 2px rgba(220,38,38,0.1); }
        .btn-submit { display: block; width: 100%; padding: 0.875rem; border: none; border-radius: 8px; background-color: #dc2626; color: white; font-weight: 600; cursor: pointer; font-size: 1rem; }
        .btn-submit:disabled { background-color: #fca5a5; cursor: not-allowed; }
        .alert { padding: 1rem; border-radius: 8px; font-weight: 500; margin-bottom: 1.5rem; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .dropzone { border: 2px dashed #dc2626; border-radius: 12px; background: #fff5f5; padding: 15px; min-height: 150px; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; cursor: pointer; }
        .dropzone .dz-message { color: #b91c1c; cursor: pointer; text-align: center; }
        .dropzone .dz-preview { background: #fff; border-radius: 14px; border: 1px solid #e5e7eb; padding: 12px; margin: 12px; width: 220px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: center; position: relative; }
        .dropzone .dz-preview .dz-image { width: 100%; height: 140px; margin-bottom: 10px; }
        .dropzone .dz-preview .dz-image img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
        .caption-input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px; background: #f9fafb; text-align: center; font-size: 0.85rem; color: #1f2937; margin-bottom: 10px; box-sizing: border-box; }
        .remove-btn { position: absolute; top: -10px; right: -10px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; font-weight: bold; cursor: pointer; font-size: 1rem; }
    </style>

    <div class="card">
        <h2 class="card-header">Upload Evidence</h2>

        <div id="notification-area" style="display:none;"></div>

        <form action="{{ route('karyawan.evidence.store') }}" id="evidence-form" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" id="tematik_id" name="tematik_id" value="{{ $selectedAssignment->project->tematik_id ?? '' }}">

            {{-- Assignment --}}
            <div class="form-group">
                <label>Pilih Penugasan <span style="color:#dc2626;">*</span></label>
                <select id="assignment_id" name="assignment_id" required onchange="fillAssignmentData(this)">
                    <option value="">-- Pilih Penugasan --</option>
                    @forelse ($assignment_list as $assignment)
                        <option value="{{ $assignment->id }}"
                            data-tematik-id="{{ $assignment->project->tematik_id ?? '' }}"
                            data-waspang-id="{{ $assignment->project->waspang_id ?? '' }}"
                            data-po-id="{{ $assignment->po_id ?? '' }}"
                            {{ isset($selectedAssignment) && $selectedAssignment->id == $assignment->id ? 'selected' : '' }}>
                            {{ $assignment->project->nama_project ?? '' }} — {{ $assignment->project->lokasi ?? 'Penugasan #'.$assignment->id }}
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada penugasan aktif</option>
                    @endforelse
                </select>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label>Deskripsi Umum <span style="color:#9ca3af;">(opsional)</span></label>
                <textarea name="deskripsi" rows="3" placeholder="Catatan tambahan..."></textarea>
            </div>

            {{-- Waspang --}}
            <div class="form-group">
                <label>Waspang <span style="color:#dc2626;">*</span></label>
                <select id="waspang_id" name="waspang_id" required>
                    <option value="">-- Pilih Waspang --</option>
                    @foreach ($waspang_list as $waspang)
                        <option value="{{ $waspang->id }}"
                            {{ isset($selectedAssignment) && $selectedAssignment->project->waspang_id == $waspang->id ? 'selected' : '' }}>
                            {{ $waspang->nama_waspang }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PO --}}
            <div class="form-group">
                <label>Nomor Purchase Order (PO) <span style="color:#dc2626;">*</span></label>
                <select id="po_id" name="po_id" required>
                    <option value="">-- Pilih Nomor PO --</option>
                    @foreach ($po_list as $po)
                        <option value="{{ $po->id }}"
                            {{ isset($selectedAssignment) && $selectedAssignment->po_id == $po->id ? 'selected' : '' }}>
                            {{ $po->no_po }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropzone --}}
            <div class="form-group">
                <label>File Evidence <span style="color:#dc2626;">*</span></label>
                <div id="evidence-dropzone" class="dropzone">
                    <div class="dz-message" data-dz-message>
                        <i class="fa-solid fa-cloud-upload-alt" style="font-size:2rem; margin-bottom:8px; display:block;"></i>
                        <span>Seret foto ke sini atau klik untuk memilih</span>
                    </div>
                </div>
            </div>

            <button type="button" id="submit-button" class="btn-submit" style="margin-top:1rem;">
                <i class="fa-solid fa-upload"></i> Upload Evidence
            </button>
        </form>
    </div>

    <script>
        function fillAssignmentData(select) {
            const option    = select.options[select.selectedIndex];
            const tematikId = option.dataset.tematikId || '';
            const waspangId = option.dataset.waspangId || '';
            const poId      = option.dataset.poId      || '';

            document.getElementById('tematik_id').value = tematikId;

            const waspangSelect = document.getElementById('waspang_id');
            if (waspangSelect && waspangId) waspangSelect.value = waspangId;

            const poSelect = document.getElementById('po_id');
            if (poSelect && poId) poSelect.value = poId;
        }

        // Jalankan auto-fill saat halaman load jika ada selectedAssignment
        document.addEventListener('DOMContentLoaded', function () {
            const assignmentSelect = document.getElementById('assignment_id');
            if (assignmentSelect && assignmentSelect.value) {
                fillAssignmentData(assignmentSelect);
            }
        });
    </script>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.querySelector("#evidence-dropzone").dropzone) {
                Dropzone.forElement("#evidence-dropzone").destroy();
            }

            const previewTemplate = `
                <div class="dz-preview dz-file-preview">
                    <div class="dz-image"><img data-dz-thumbnail /></div>
                    <input type="text" name="caption[]" class="caption-input" placeholder="Deskripsi foto (opsional)...">
                    <button type="button" data-dz-remove class="remove-btn">✕</button>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                </div>
            `;

            Dropzone.autoDiscover = false;
            let myDropzone = new Dropzone("#evidence-dropzone", {
                url: "{{ route('karyawan.evidence.store') }}",
                paramName: "file",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 100,
                maxFiles: null,
                maxFilesize: null,
                acceptedFiles: 'image/*',
                addRemoveLinks: false,
                previewTemplate: previewTemplate,
                clickable: true,

                init: function() {
                    const self             = this;
                    const form             = document.querySelector("#evidence-form");
                    const submitButton     = document.querySelector("#submit-button");
                    const notificationArea = document.querySelector("#notification-area");

                    this.on("addedfile", function(file) {
                        let captionInput = file.previewElement.querySelector('.caption-input');
                        if (captionInput) {
                            captionInput.value = file.name.replace(/\.[^/.]+$/, "");
                        }
                    });

                    submitButton.addEventListener("click", function(e) {
                        e.preventDefault();

                        const assignmentSelect = form.querySelector('#assignment_id');
                        if (!assignmentSelect || !assignmentSelect.value) {
                            notificationArea.innerHTML = `<div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> Pilih penugasan terlebih dahulu!</div>`;
                            notificationArea.style.display = 'block';
                            return;
                        }
                        if (self.getQueuedFiles().length === 0) {
                            notificationArea.innerHTML = `<div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> Mohon pilih minimal satu file gambar!</div>`;
                            notificationArea.style.display = 'block';
                            return;
                        }

                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengupload...';
                        self.processQueue();
                    });

                    this.on("sendingmultiple", function(files, xhr, formData) {
                        formData.append("_token", form.querySelector('input[name="_token"]').value);
                        formData.append("assignment_id", form.querySelector('#assignment_id').value);
                        formData.append("tematik_id", document.getElementById('tematik_id').value);

                        const deskripsi = form.querySelector('[name="deskripsi"]');
                        formData.append("deskripsi", deskripsi ? deskripsi.value : '');
                        formData.append("waspang_id", form.querySelector('#waspang_id').value);
                        formData.append("po_id", form.querySelector('#po_id').value);

                        files.forEach(function(file) {
                            let captionInput = file.previewElement.querySelector('.caption-input');
                            formData.append("caption[]", captionInput ? captionInput.value : '');
                        });

                        notificationArea.style.display = 'none';
                    });

                    this.on("successmultiple", function(files, response) {
                        localStorage.setItem('successMessage', response.message || 'Upload berhasil');
                        localStorage.setItem('totalFiles', response.total_files || files.length);
                        window.location.href = response.redirect || '{{ route("karyawan.evidence.index") }}';
                    });

                    this.on("errormultiple", function(files, response) {
                        let errorMessage = response.message || "Gagal mengupload file.";
                        if (response.errors) {
                            errorMessage = Object.values(response.errors).flat().join('<br>');
                        }
                        notificationArea.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
                        notificationArea.style.display = 'block';
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class="fa-solid fa-upload"></i> Upload Evidence';
                    });
                }
            });
        });
    </script>
    @endpush
</x-karyawan-layout>