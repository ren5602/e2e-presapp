<form action="{{ route('mahasiswa_lomba.destroy', $mahasiswa_lomba->mahasiswa_lomba_id) }}" method="POST"
    id="form-tambah-mahasiswa_lomba">
    @csrf
    @method('DELETE')
    <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="main-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                Apakah Anda ingin menghapus data Bidang Keahlian seperti di bawah ini?
            </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Hapus</button>
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
