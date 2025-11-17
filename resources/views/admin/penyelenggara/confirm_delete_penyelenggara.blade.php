@empty($penyelenggara)
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
                <a href="{{ url('/penyelenggara') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Data Penyelenggara</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
            Apakah Anda ingin menghapus data penyelenggara seperti di bawah ini?
        </div>
        <table class="table table-sm table-bordered table-striped">
            <tr>
                <th class="text-right col-3">Nama Penyelenggara :</th>
                <td class="col-9">{{ $penyelenggara->penyelenggara_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Kota :</th>
                <td class="col-9">{{ $penyelenggara->kota->kota_nama ?? '-' }}</td>
            </tr>
        </table>
        <form action="{{ url('/penyelenggara/' . $penyelenggara->penyelenggara_id) }}" method="POST" id="form-delete">
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
                                $('#modal-penyelenggara').modal('hide');
                                Swal.fire({ 
                                    icon: 'success', 
                                    title: 'Berhasil', 
                                    text: response.message 
                                });
                                dataPenyelenggara.ajax.reload();
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