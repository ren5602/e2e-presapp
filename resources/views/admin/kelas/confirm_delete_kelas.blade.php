@empty($kelas)
    <div id="modal-delete" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kelas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Data Kelas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
            Apakah Anda ingin menghapus data kelas seperti di bawah ini?
        </div>

        <table class="table table-sm table-bordered table-striped">
            <tr>
                <th class="text-right col-3">Kode Kelas :</th>
                <td class="col-9">{{ $kelas->kelas_kode }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Nama Kelas :</th>
                <td class="col-9">{{ $kelas->kelas_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Program Studi :</th>
                <td class="col-9">{{ $kelas->prodi->prodi_nama ?? '-' }}</td>
            </tr>
        </table>

        <form action="{{ url('/kelas/' . $kelas->kelas_id) }}" method="POST" id="form-delete-kelas">
            @csrf
            @method('DELETE')
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Batal</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $("#form-delete-kelas").validate({
                rules: {}, // Tidak ada input wajib, karena hanya tombol submit
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                $('#modal-kelas').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataKelas.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (prefix, val) {
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
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty