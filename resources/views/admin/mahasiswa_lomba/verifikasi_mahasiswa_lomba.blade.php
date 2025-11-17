<form action="{{ route('mahasiswa_lomba.update-verifikasi', $mahasiswa_lomba->mahasiswa_lomba_id) }}" method="POST" id="form-tambah-mahasiswa_lomba">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Daftarkan Mahasiswa ke Lomba</h5>
        <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Lomba</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $mahasiswa_lomba->lomba->lomba_nama }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Mahasiswa</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $mahasiswa_lomba->mahasiswa->nama }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Status Verifikasi</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    @if ($mahasiswa_lomba->status_verifikasi == '1')
                                        <span class="badge badge-success">Terverifikasi</span>
                                    @elseif($mahasiswa_lomba->status_verifikasi == '0')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu Verifikasi</span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <div class="form-group">
                                        <label>Status Verifikasi</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check form-check-inline"
                                                style="background-color: rgba(0, 255, 85, 0.144); padding: 8px; border-radius: 5px;">
                                                <input class="form-check-input" type="radio" name="status_verifikasi"
                                                    id="verifikasi_1" value="1"
                                                    {{ $mahasiswa_lomba->status_verifikasi === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="verifikasi_1">Terverifikasi</label>
                                            </div>
                                            <div class="form-check form-check-inline"
                                                style="background-color: rgba(255, 0, 0, 0.144); padding: 8px; border-radius: 5px;">
                                                <input class="form-check-input" type="radio" name="status_verifikasi"
                                                    id="verifikasi_0" value="0"
                                                    {{ $mahasiswa_lomba->status_verifikasi === 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="verifikasi_0">Ditolak</label>
                                            </div>
                                        </div>
                                        <small id="error-status_verifikasi"
                                            class="error-text form-text text-danger"></small>
                                    </div>
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
    $(document).ready(function() {

        $("#form-tambah-mahasiswa_lomba").validate({
            rules: {
                lomba_id: {
                    required: true,
                },
                mahasiswa_id: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-mahasiswa_lomba').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                            });
                            data_mahasiswa_lomba.ajax.reload();
                        } else {
                            $('.text-danger').text('');
                            $.each(response.msgField, function(key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
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
