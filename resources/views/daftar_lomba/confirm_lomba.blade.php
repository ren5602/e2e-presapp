@empty($lomba)
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
                <a href="{{ url('/lomba') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Data Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
            Apakah Anda ingin menghapus data lomba seperti di bawah ini?
        </div>
        <table class="table table-sm table-bordered table-striped">
            <tr>
                <th class="text-right col-3">Kode lomba :</th>
                <td class="col-9">{{ $lomba->lomba_kode }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">nama lomba :</th>
                <td class="col-9">{{ $lomba->lomba_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">tingkat lomba :</th>
                <td class="col-9">{{ $lomba->tingkat->tingkat_lomba_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">bidang lomba :</th>
                <td class="col-9">{{ $lomba->bidang->bidang_keahlian_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">penyelanggara lomba :</th>
                <td class="col-9">{{ $lomba->penyelenggara->penyelenggara_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">jumlah anggota :</th>
                <td class="col-9">{{ $lomba->jumlah_anggota }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">tanggal mulai  :</th>
                <td class="col-9">{{ $lomba->tanggal_mulai }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">tanggal_selesai :</th>
                <td class="col-9">{{ $lomba->tanggal_selesai }}</td>
            </tr>
        </table>
        <form action="{{route('daftar_lomba.destroy', $lomba->lomba_id)}}" method="POST" id="form-delete">
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
                                $('#modal-delete').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '/daftar_lomba'; // redirect ke halaman prestasiku
                                    }
                                });


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