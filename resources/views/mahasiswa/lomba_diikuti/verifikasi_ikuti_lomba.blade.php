@empty($mahasiswa_lomba)
    <div id="modal-lomba-diikuti" class="modal-dialog modal-lg" role="document">
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
                    Data yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/lomba_diikuti') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('lomba_diikuti.update_verifikasi_from_mhs', $mahasiswa_lomba->mahasiswa_lomba_id) }}"
        method="POST" id="form-lomba-diikuti">
        @csrf
        @method('PUT')

        <div class="modal-header">
            <h5 class="modal-title">Data Lomba</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h5><strong>Konfirmasi</strong>, Apakah anda menerima untuk mengikuti lomba:</h5>
            <h5 class="mt-3"><strong>{{ $mahasiswa_lomba->lomba->lomba_nama }}</strong></h5>

            <div class="mt-4 d-flex justify-content-center">
                <div class="form-group">
                    {{-- <label>Status Verifikasi</label> --}}
                    <div class="d-flex gap-3">
                        <div class="form-check form-check-inline"
                            style="background-color: rgba(0, 255, 85, 0.144); padding: 8px; border-radius: 5px;">
                            <input class="form-check-input" type="radio" name="status_verifikasi" id="verifikasi_1"
                                value="1" {{ $mahasiswa_lomba->status_verifikasi_from_mhs === 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="verifikasi_1"
                                style="font-size: 1.25rem; font-weight: 500;">Terima/Ikuti</label>
                        </div>
                        <div class="form-check form-check-inline"
                            style="background-color: rgba(255, 0, 0, 0.144); padding: 8px; border-radius: 5px;">
                            <input class="form-check-input" type="radio" name="status_verifikasi" id="verifikasi_0"
                                value="0" {{ $mahasiswa_lomba->status_verifikasi_from_mhs === 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="verifikasi_0"
                                style="font-size: 1.25rem; font-weight: 500;">Tolak/Tidak Ikut</label>
                        </div>
                    </div>
                    <small id="error-status_verifikasi" class="error-text form-text text-danger"></small>
                </div>
            </div>

            <div class="form-group">
                <label for="message">Pesan Untuk Admin</label>
                <textarea name="message" id="message" rows="4" class="form-control"
                    placeholder="Tambahkan pesan untuk admin jika perlu...">{{ $mahasiswa_lomba->message ?? '' }}</textarea>
                <small id="error-message" class="error-text form-text text-danger"></small>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Batal</button>
            <button type="submit" class="btn btn-success btn-sm">Simpan</button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#form-lomba-diikuti").validate({
                rules: {
                    status_verifikasi: {
                        required: true,
                    },
                    message: {
                        required: false
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#modal-lomba-diikuti').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href =
                                            '/lomba_diikuti'; // redirect ke halaman prestasiku
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
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
