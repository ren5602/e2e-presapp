<x-layout>
    <x-slot:css>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />

        <style>
            .table-blue-striped tbody tr:nth-of-type(odd) {
                background-color: #007bff27;
                /* Warna biru muda */
            }

            .table-blue-striped tbody tr:nth-of-type(even) {
                background-color: #00ffd510;
                /* Warna biru muda */
            }
        </style>
    </x-slot:css>

    <x-slot:title>
        Tambah Prestasi
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title"><i class="fa fa-trophy"> Tambah Prestasi</i>
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('mahasiswa.prestasi.store') }}" method="POST" enctype="multipart/form-data"
                id="form-edit-prestasi">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Prestasi<span style="color: red;">*</span></label>
                            <input type="text" name="prestasi_nama" id="prestasi_nama" class="form-control">
                            <small id="error-prestasi_nama" class="error-text form-text text-danger"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Lomba<span style="color: red;">*</span></label>
                            <select name="lomba_id" id="prestasi_lomba" class="form-control">
                                <option value="" disabled selected>- Pilih Lomba -</option>
                                @foreach ($lomba as $l)
                                    <option value="{{ $l->lomba_id }}">
                                        {{ $l->lomba_nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-lomba_id" class="text-danger"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dosen Pembimbing<span style="color: red;">*</span></label>
                            <select name="dosen_id" id="prestasi_dosbim" class="form-control">
                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->dosen_id }}">
                                        ({{ $d->nidn }})
                                        {{ $d->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-dosen_id" class="text-danger"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Perolehan<span style="color: red;">*</span></label>
                            <input type="date" name="tanggal_perolehan" id="tanggal_perolehan" class="form-control">
                            <small id="error-tanggal_perolehan" class="error-text form-text text-danger"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Juara<span style="color: red;">*</span></label>
                            <select name="juara" id="prestasi_juara" class="form-control">
                                <option value="" disabled selected>- Pilih Juara -</option>
                                <option value="1">Juara 1</option>
                                <option value="2">Juara 2</option>
                                <option value="3">Juara 3</option>
                                <option value="4">Kategori Lain
                                </option>
                            </select>
                            <small id="error-juara" class="error-text form-text text-danger"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" id="juara_lain">
                            <label>Nama Kategori Juara <span style="color: red;">*</span></label>
                            <input type="text" name="nama_juara" id="nama_juara" class="form-control"
                                placeholder="Contoh: Best Writer">
                            <small id="error-nama_juara" class="error-text form-text text-danger"></small>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Foto Sertifikat<span style="color: red;">*</span></h5>
                                <!-- Gambar Sertifikat -->
                                <div
                                    style="position: relative; width: 100%; max-width: auto; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                    <img id="preview-sertifikat" src="{{ asset('assets/images/image-dummy.png') }}"
                                        alt="Sertifikat"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </div>
                                <div class="form-group mt-2">
                                    <!-- Sertifikat -->
                                    <input type="file" name="file_sertifikat" id="file_sertifikat"
                                        class="form-control" accept="image/*" onchange="previewImage(event)"
                                        data-target="preview-sertifikat">

                                    <small id="error-file_sertifikat" class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">Bukti Foto<span style="color: red;">*</span></h5>
                                <!-- Gambar Bukti Foto -->
                                <div
                                    style="position: relative; width: 100%; max-width: auto; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                    <img id="preview-bukti" src="{{ asset('assets/images/image-dummy.png') }}"
                                        class="card-img-top" alt="Bukti Foto"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </div>

                                <div class="form-group mt-2">
                                    <!-- Bukti Foto -->
                                    <input type="file" name="file_bukti_foto" id="file_bukti_foto"
                                        class="form-control" accept="image/*" onchange="previewImage(event)"
                                        data-target="preview-bukti">

                                    <small id="error-file_bukti_foto"
                                        class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Tugas<span style="color: red;">*</span></h5>
                                <!-- Gambar Surat Tugas -->
                                <div
                                    style="position: relative; width: 100%; max-width: auto; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                    <img id="preview-surat_tugas" src="{{ asset('assets/images/image-dummy.png') }}"
                                        class="card-img-top" alt="Surat Tugas"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </div>
                                <div class="form-group mt-2">
                                    <!-- Bukti Foto -->
                                    <input type="file" name="file_surat_tugas" id="file_surat_tugas"
                                        class="form-control" accept="image/*" onchange="previewImage(event)"
                                        data-target="preview-surat_tugas">

                                    <small id="error-file_surat_tugas"
                                        class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Undangan<span style="color: red;">*</span></h5>
                                <!-- Gambar Surat Undangan -->
                                <div
                                    style="position: relative; width: 100%; max-width: auto; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                    <img id="preview-surat_undangan"
                                        src="{{ asset('assets/images/image-dummy.png') }}" class="card-img-top"
                                        alt="Surat Undangan"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </div>

                                <div class="form-group mt-2">
                                    <!-- Bukti Foto -->
                                    <input type="file" name="file_surat_undangan" id="file_surat_undangan"
                                        class="form-control" accept="image/*" onchange="previewImage(event)"
                                        data-target="preview-surat_undangan">

                                    <small id="error-file_surat_undangan"
                                        class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">File Proposal</h5>
                                <div style="position: relative; width: 100%; height: 500px; border: 1px solid #ccc;">
                                    <iframe id="preview-proposal" src="" width="100%" height="100%"
                                        style="border: none;"></iframe>

                                    <div
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255, 255, 255, 0.85);">
                                        <p id="no-proposal" style="color: #666; font-size: 18px;">Tambahkan
                                            proposal jika ada</p>
                                    </div>
                                </div>



                                <div class="form-group mt-2">
                                    <input type="file" name="file_proposal" id="file_proposal"
                                        class="form-control" accept="application/pdf"
                                        onchange="previewProposal(event)">

                                    <small id="error-file_proposal" class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="mt-5 d-flex justify-content-end ">
                    <a href="{{ route('mahasiswa.prestasi.index') }}">
                        <button type="button" class="btn btn-warning mr-2" data-dismiss="modal">Batal</button>
                    </a>
                    <button type="submit" class="btn btn-primary ml-2">Simpan</button>
                </div>

            </form>
        </div>
    </div>


    <x-slot:modal>
        <div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="modalPreviewLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl ">
                <div class="modal-content bg-white p-3 rounded-3">
                    <div class="modal-body text-center p-0">
                        <img id="modalPreviewImg" src="" alt="Preview"
                            style="max-width: 100%; max-height: 90vh; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </x-slot:modal>



    <x-slot:js>

        <script>
            function initSelect2() {
                $('#prestasi_lomba, #prestasi_dosbim, #prestasi_juara').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
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

            function toggleJuaraLainEdit() {
                const selected = document.getElementById('prestasi_juara').value;
                const juaraLainGroup = document.getElementById('juara_lain');
                const input = document.getElementById('nama_juara');

                if (selected === '4') {
                    juaraLainGroup.style.display = 'block';
                    input.setAttribute('required', 'required');
                } else {
                    juaraLainGroup.style.display = 'none';
                    input.removeAttribute('required');
                    input.value = '';
                }
            }

            $(document).ready(function() {
                initSelect2();
                toggleJuaraLainEdit();
                $('#prestasi_juara').on('change', toggleJuaraLainEdit);

                $("#form-edit-prestasi").validate({
                    rules: {
                        prestasi_nama: {
                            required: true,
                            minlength: 3,
                            maxlength: 255
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
                        tanggal_perolehan: {
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
                        prestasi_nama: {
                            required: "Masukkan nama prestasi",
                        },
                        lomba_id: {
                            required: "Pilih lomba"
                        },
                        mahasiswa_id: {
                            required: "Pilih mahasiswa"
                        },
                        dosen_id: {
                            required: "Pilih dosen"
                        },
                        tanggal_perolehan: {
                            required: "Pilih tanggal perolehan"
                        },
                        juara: {
                            required: "Pilih juara"
                        },
                        file_sertifikat: {
                            required: "Silakan pilih file sertifikat terlebih dahulu.",
                            extension: "File harus berformat .jpg, .jpeg, atau .png"
                        },
                        file_bukti_foto: {
                            required: "Silakan pilih file bukti foto terlebih dahulu.",
                            extension: "File harus berformat .jpg, .jpeg, atau .png"
                        },
                        file_surat_tugas: {
                            required: "Silakan pilih file surat tugas terlebih dahulu.",
                            extension: "File harus berformat .jpg, .jpeg, atau .png"
                        },
                        file_surat_undangan: {
                            required: "Silakan pilih file surat undangan terlebih dahulu.",
                            extension: "File harus berformat .jpg, .jpeg, atau .png"
                        },
                        file_proposal: {
                            extension: "File harus berformat .pdf"
                        },
                        nama_juara: {
                            required: "Masukkan nama kategori juara jika memilih 'Kategori Lain'"
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
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href =
                                                "{{ route('mahasiswa.prestasi.index') }}";
                                        }
                                    });
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

                        if (element.attr('type') === 'file') {
                            // Letakkan error pada <small id="error-[id]>
                            let target = '#error-' + element.attr('id');
                            $(target).text(error.text());
                        } else {
                            element.closest('.form-group').append(error);
                        }
                    },
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                        if ($(element).attr('type') === 'file') {
                            $('#error-' + element.id).text('');
                        }
                    }
                });
            });
        </script>
    </x-slot:js>


</x-layout>
