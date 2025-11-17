<form action="{{ url('/prestasi/') }}" method="POST" enctype="multipart/form-data" id="form_create">
    @csrf
    {{-- @method('PUT') --}}
    <div class="modal-header">
        <h5 class="modal-title">Tambah Data prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Prestasi<span style="color: red;">*</span></label>
                    <input value="" type="text" name="prestasi_nama" id="prestasi_nama" class="form-control"
                        required>
                    <small id="error-prestasi_nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pilih Lomba<span style="color: red;">*</span></label>
                    <select class="form-select" id="prestasi_lomba" name="lomba_id" style="width: 100%">
                        <option value="" disabled selected>- Pilih Lomba -</option>
                        @foreach ($lomba as $item)
                            <option value="{{ $item->lomba_id }}">{{ $item->lomba_nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pilih Mahasiswa<span style="color: red;">*</span></label>
                    <select class="form-select" id="prestasi_mahasiswa" name="mahasiswa_id" style="width: 100%">
                        <option value="" disabled selected>- Pilih Mahasiswa -</option>
                        @foreach ($mahasiswa as $item)
                            <option value="{{ $item->mahasiswa_id }}">({{ $item->nim }}) {{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pilih Dosen Pembimbing<span style="color: red;">*</span></label>
                    <select class="form-select" id="prestasi_dosbim" name="dosen_id" style="width: 100%">
                        <option value="" disabled selected>- Pilih Dosen -</option>
                        @foreach ($dosen as $item)
                            <option value="{{ $item->dosen_id }}">({{ $item->nidn }}) {{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal Perolehan<span style="color: red;">*</span></label>
                    <input value="" type="date" name="tanggal_perolehan" id="tanggal_perolehan"
                        class="form-control" required>
                    <small id="error-file_bukti_foto" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Juara<span style="color: red;">*</span></label>
                    <select class="form-select" id="prestasi_juara" name="juara" style="width: 100%">
                        <option value="" disabled selected>- Pilih Juara -</option>
                        <option value="1">Juara 1</option>
                        <option value="2">Juara 2</option>
                        <option value="3">Juara 3</option>
                        <option value="4">Kategori Lain</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="juara_lain">
                    <label>Nama Kategori Juara<span style="color: red;">*</span></label>
                    <input value="" type="text" name="nama_juara" id="juara_lain" class="form-control"
                        placeholder="Contoh : Best Writer">
                    <small id="error-nama_juara" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Foto Sertifikat</h5>
                        <!-- Gambar Sertifikat -->
                        <div
                            style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                            <img id="preview-sertifikat"
                                src="{{ asset('assets/images/image-dummy.png') }}"
                                alt="Sertifikat"
                                style="width: 100%; height: 100%; object-fit: contain; display: block;">
                        </div>
                        <div class="form-group mt-2">
                            <!-- Sertifikat -->
                            <input type="file" name="file_sertifikat" id="file_sertifikat" class="form-control"
                                accept="image/*" onchange="previewImage(event)" data-target="preview-sertifikat">

                            <small class="form-text text-muted">Abaikan jika tidak ingin diubah</small>
                            <small id="error-file_sertifikat" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">Bukti Foto</h5>
                        <!-- Gambar Bukti Foto -->
                        <div
                            style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                            <img id="preview-bukti"
                                src="{{ asset('assets/images/image-dummy.png') }}"
                                class="card-img-top" alt="Bukti Foto"
                                style="width: 100%; height: 100%; object-fit: contain; display: block;">
                        </div>

                        <div class="form-group mt-2">
                            <!-- Bukti Foto -->
                            <input type="file" name="file_bukti_foto" id="file_bukti_foto" class="form-control"
                                accept="image/*" onchange="previewImage(event)" data-target="preview-bukti">

                            <small class="form-text text-muted">Abaikan jika tidak ingin diubah</small>
                            <small id="error-file_bukti_foto" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Surat Tugas</h5>
                        <!-- Gambar Surat Tugas -->
                        <div
                            style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                            <img id="preview-surat_tugas"
                                src="{{ asset('assets/images/image-dummy.png') }}"
                                class="card-img-top" alt="Surat Tugas"
                                style="width: 100%; height: 100%; object-fit: contain; display: block;">
                        </div>
                        <div class="form-group mt-2">
                            <!-- Bukti Foto -->
                            <input type="file" name="file_surat_tugas" id="file_surat_tugas" class="form-control"
                                accept="image/*" onchange="previewImage(event)" data-target="preview-surat_tugas">

                            <small class="form-text text-muted">Abaikan jika tidak ingin diubah</small>
                            <small id="error-file_surat_tugas" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Surat Undangan</h5>
                        <!-- Gambar Surat Undangan -->
                        <div
                            style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                            <img id="preview-surat_undangan"
                                src="{{ asset('assets/images/image-dummy.png') }}"
                                class="card-img-top" alt="Surat Undangan"
                                style="width: 100%; height: 100%; object-fit: contain; display: block;">
                        </div>

                        <div class="form-group mt-2">
                            <!-- Bukti Foto -->
                            <input type="file" name="file_surat_undangan" id="file_surat_undangan" class="form-control"
                                accept="image/*" onchange="previewImage(event)" data-target="preview-surat_undangan">

                            <small class="form-text text-muted">Abaikan jika tidak ingin diubah</small>
                            <small id="error-file_surat_undangan" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">File Proposal</h5>
                                <div style="position: relative; width: 100%; height: 500px; border: 1px solid #ccc;">
                                    <iframe id="preview-proposal"
                                        width="100%" height="100%" style="border: none;"></iframe>
                                        <div
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255, 255, 255, 0.85);">
                                            <p id="no-proposal" style="color: #666; font-size: 18px;">Tambahkan Proposal Jika Ada</p>
                                        </div>
                                </div>

                                <div class="form-group mt-2">
                                    <input type="file" name="file_proposal" id="file_proposal" class="form-control"
                                        accept="application/pdf" onchange="previewProposal(event)">

                                    <small class="form-text text-muted">Abaikan jika tidak ingin diubah</small>
                                    <small id="error-file_proposal" class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>


        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    function initSelect2() {
        $('#prestasi_lomba, #prestasi_mahasiswa, #prestasi_dosbim, #prestasi_juara').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#modal-prestasi')
        });
    }

    function previewImage(event) {
        const fileInput = event.target;
        const targetId = fileInput.getAttribute('data-target');
        const image = document.getElementById(targetId);
        const file = fileInput.files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Kosongkan pesan error jika valid
            const errorElement = fileInput.nextElementSibling?.nextElementSibling;
            if (errorElement) errorElement.textContent = '';
        } else {
            const errorElement = fileInput.nextElementSibling?.nextElementSibling;
            if (errorElement) errorElement.textContent = "File bukan gambar yang valid.";
        }
    }

    function previewProposal(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        const embed = document.getElementById('preview-proposal');

        if (file && file.type === 'application/pdf') {
            const reader = new FileReader();
            reader.onload = function(e) {
                embed.src = e.target.result;

                // Hapus elemen "Tidak ada proposal" jika ada
                const noProposalText = document.getElementById('no-proposal');
                if (noProposalText) {
                    noProposalText.parentElement.remove(); // atau gunakan noProposalText.remove();
                }
            };
            reader.readAsDataURL(file);

            const errorElement = document.getElementById('error-file_proposal');
            if (errorElement) errorElement.textContent = '';
        } else {
            const errorElement = document.getElementById('error-file_proposal');
            if (errorElement) errorElement.textContent = "File harus berupa PDF.";
        }
    }

    function toggleJuaraLain() {
        const selected = document.getElementById('prestasi_juara').value;
        const juaraLainGroup = document.querySelector('#juara_lain');
        const juaraLainInput = juaraLainGroup.querySelector('input');

        if (selected === '4') {
            juaraLainGroup.style.display = 'block';
            juaraLainInput.setAttribute('required', 'required');
        } else {
            juaraLainGroup.style.display = 'none';
            juaraLainInput.removeAttribute('required');
            juaraLainInput.value = '';
        }
    }

    $(document).ready(function() {
        initSelect2();
        toggleJuaraLain();

        $('#prestasi_juara').on('change', function() {
            toggleJuaraLain();
        });

        $('#modal-prestasi').on('shown.bs.modal', function() {
            initSelect2();
        });

        $("#form_create").validate({
            rules: {
                prestasi_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                lomba_id: {
                    required: true
                },
                mahasiswa_id: {
                    required: true
                },
                dosen_id: {
                    required: true
                },
                juara: {
                    required: true
                },
                nama_juara: {
                    required: function() {
                        return $('#prestasi_juara').val() === '4';
                    },
                    minlength: 3
                },
                file_sertifikat: {
                    required: true,
                    extension: "jpg|jpeg|png"
                },
                file_bukti_foto: {
                    required: true,
                    extension: "jpg|jpeg|png"
                },
                file_surat_tugas: {
                    required: true,
                    extension: "jpg|jpeg|png"
                },
                file_surat_undangan: {
                    required: true,
                    extension: "jpg|jpeg|png"
                },
                file_proposal: {
                    required: false,
                    extension: "pdf"
                }
            },

            messages: {
                nama_juara: {
                    required: "Masukkan nama kategori jika memilih 'Kategori Lain'"
                },
                file_sertifikat: {
                    extension: "File harus berupa gambar (jpg, jpeg, png, gif)"
                },
                file_bukti_foto: {
                    extension: "File harus berupa gambar (jpg, jpeg, png, gif)"
                },
                file_surat_tugas: {
                    extension: "File harus berupa gambar (jpg, jpeg, png, gif)"
                },
                file_surat_undangan: {
                    extension: "File harus berupa gambar (jpg, jpeg, png, gif)"
                }
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            $('#modal-prestasi').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataPrestasi.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },

            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
