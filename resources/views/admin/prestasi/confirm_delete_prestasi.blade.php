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
                    Data yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/prestasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Data Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
            Apakah Anda ingin menghapus data prestasi seperti di bawah ini?
        </div>
        <table class="table table-sm table-bordered table-striped">
            <tr>
                <th class="text-right col-3">NIM :</th>
                <td class="col-9">{{ $prestasi->mahasiswa->nim }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Mahasiswa :</th>
                <td class="col-9">{{ $prestasi->mahasiswa->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Prestasi :</th>
                <td class="col-9">{{ $prestasi->prestasi_nama ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Lomba :</th>
                <td class="col-9">{{ $prestasi->lomba->lomba_nama ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Juara :</th>
                <td class="col-9">{{ $prestasi->nama_juara ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Tingkat :</th>
                <td class="col-9">{{ $prestasi->lomba->tingkat->tingkat_lomba_nama ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Poin :</th>
                <td class="col-9">{{ $prestasi->poin ?? '-' }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Status Verifikasi :</th>
                <td class="col-9">
                    @if ($prestasi->status_verifikasi === 1)
                        Terverifikasi
                    @elseif ($prestasi->status_verifikasi === 0)
                        Ditolak
                    @else
                        Menunggu Verifikasi
                    @endif
                </td>
            </tr>
        </table>
        <form action="{{ url('/prestasi/' . $prestasi->prestasi_id) }}" method="POST" id="form-delete">
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
            $("#form-delete").validate({
                rules: {},
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
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
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: response.message });
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
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty