@empty($prestasi)
    <div id="modal-delete" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/prestasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('prestasi.update_verifikasi', $prestasi->prestasi_id) }}" method="POST"
        id="form-edit-verifikasi-prestasi">
        @csrf
        @method('PUT')

        <div class="modal-header">
            <h5 class="modal-title">Data prestasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama Mahasiswa</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->mahasiswa->nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">NIM</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->mahasiswa->nim }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Prestasi</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->prestasi_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama Prestasi</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->lomba->lomba_nama }}
                                    </div>
                                </div>
                                <hr>
                                                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Tanggal Perolehan</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ \Carbon\Carbon::parse($prestasi->tanggal_perolehan)->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Juara</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->nama_juara }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Tingkat</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->lomba->tingkat->tingkat_lomba_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Poin</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->poin }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Status Verifikasi</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        @if ($prestasi->status_verifikasi == '1')
                                            <span class="badge badge-success">Terverifikasi</span>
                                        @elseif($prestasi->status_verifikasi == '0')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu Verifikasi</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Foto Sertifikat</h5>
                            <!-- Gambar Sertifikat -->
                            <div
                                style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                <a href="{{ asset('storage/' . $prestasi->file_sertifikat) }}" target="_blank">
                                    <img id="preview-sertifikat"
                                        src="{{ file_exists(public_path('storage/' . $prestasi->file_sertifikat)) ? asset('storage/' . $prestasi->file_sertifikat) : asset('assets/images/broken-image.png') }}"
                                        alt="Sertifikat"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </a>
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
                                <a href="{{ asset('storage/' . $prestasi->file_bukti_foto) }}" target="_blank">
                                    <img id="preview-bukti-foto"
                                        src="{{ file_exists(public_path('storage/' . $prestasi->file_bukti_foto)) ? asset('storage/' . $prestasi->file_bukti_foto) : asset('assets/images/broken-image.png') }}"
                                        alt="Bukti Foto"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Surat Tugas</h5>
                            <!-- Gambar Surat Tugas -->
                            <div
                                style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                <a href="{{ asset('storage/' . $prestasi->file_surat_tugas) }}" target="_blank">
                                    <img id="preview-surat_tugas"
                                        src="{{ file_exists(public_path('storage/' . $prestasi->file_surat_tugas)) ? asset('storage/' . $prestasi->file_surat_tugas) : asset('assets/images/broken-image.png') }}"
                                        alt="surat_tugas"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Surat Undangan</h5>
                            <!-- Gambar Surat Undangan -->
                            <div
                                style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                <a href="{{ asset('storage/' . $prestasi->file_surat_undangan) }}" target="_blank">
                                    <img id="preview-surat_undangan"
                                        src="{{ file_exists(public_path('storage/' . $prestasi->file_surat_undangan)) ? asset('storage/' . $prestasi->file_surat_undangan) : asset('assets/images/broken-image.png') }}"
                                        alt="surat_undangan"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </a>
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
                                    src="{{ $prestasi->file_proposal ? asset('storage/' . $prestasi->file_proposal) : '' }}"
                                    width="100%" height="100%" style="border: none;"></iframe>

                                @if (!$prestasi->file_proposal)
                                    <div
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                                                                                                                                                display: flex; align-items: center; justify-content: center;
                                                                                                                                                background-color: rgba(255, 255, 255, 0.85);">
                                        <p id="no-proposal" style="color: #666; font-size: 18px;">Tidak ada proposal</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Verifikasi Prestasi</h5>
                            <div class="row">
                                <div class="col-md-4 mt-2">
                                    <div class="form-group">
                                        <label>Status Verifikasi</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check form-check-inline"
                                                style="background-color: rgba(0, 255, 85, 0.144); padding: 8px; border-radius: 5px;">
                                                <input class="form-check-input" type="radio" name="status_verifikasi"
                                                    id="verifikasi_1" value="1"
                                                    {{ $prestasi->status_verifikasi === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="verifikasi_1">Terverifikasi</label>
                                            </div>
                                            <div class="form-check form-check-inline"
                                                style="background-color: rgba(255, 0, 0, 0.144); padding: 8px; border-radius: 5px;">
                                                <input class="form-check-input" type="radio" name="status_verifikasi"
                                                    id="verifikasi_0" value="0"
                                                    {{ $prestasi->status_verifikasi === 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="verifikasi_0">Ditolak</label>
                                            </div>
                                        </div>
                                        <small id="error-status_verifikasi"
                                            class="error-text form-text text-danger"></small>
                                    </div>

                                </div>
                                <div class="col-md-8 mt-2">
                                    <div class="form-group">
                                        <label for="message">Pesan Untuk Mahasiswa</label>
                                        <textarea name="message" id="message" rows="4" class="form-control"
                                            placeholder="Tambahkan pesan untuk mahasiswa jika perlu...">{{ $prestasi->message ?? '' }}</textarea>
                                        <small id="error-message" class="error-text form-text text-danger"></small>
                                    </div>
                                </div>
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
            $('#prestasi_status_verifikasi').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $('#modal-prestasi')
            });
        }

        $(document).ready(function() {
            initSelect2();

            $('#modal-prestasi').on('shown.bs.modal', function() {
                initSelect2();
            });
            $("#form-edit-verifikasi-prestasi").validate({
                rules: {
                    status_verifikasi: {
                        required: false
                    },
                    message: {
                        required: false
                    }
                },
                messages: {
                    status_verifikasi: {
                        required: "Masukkan status verifikasi"
                    }
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
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
@endempty
