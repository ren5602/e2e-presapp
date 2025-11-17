<form action="{{ url('/prodi') }}" method="POST" id="form-tambah-prodi">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Data Prodi</h5>
        <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label for="info">Nama Prodi</label>
            <input type="text" name="prodi_nama" id="prodi_nama" class="form-control">
            <small id="error-info" class="text-danger"></small>
        </div>

        <div class="form-group">
            <label for="prodi_kode">Kode Prodi</label>
            <input type="text" name="prodi_kode" id="prodi_kode" class="form-control">
            <small id="error-prodi_kode" class="text-danger"></small>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#form-tambah-prodi").validate({
            rules: {
                prodi_nama: { required: true, minlength: 3, maxlength: 255 },
                prodi_kode: { required: true, minlength: 3, maxlength: 255 }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#modal-prodi').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                            });
                            dataProdi.ajax.reload();
                        } else {
                            $('.text-danger').text('');
                            $.each(response.msgField, function (key, val) {
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
            }
        });
    });
</script>