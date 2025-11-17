<form action="{{ url('/KategoriBidangKeahlian') }}" method="POST" id="form-tambah-KategoriBidangKeahlian">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Data Bidang Keahlian</h5>
        <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label for="kategori_bidang_keahlian_kode">Kode Bidang Keahlian<span style="color: red;">*</span></label>
            <input type="text" name="kategori_bidang_keahlian_kode" id="kategori_bidang_keahlian_kode" class="form-control">
            <small id="error-kategori_bidang_keahlian_kode" class="text-danger"></small>
        </div>

        <div class="form-group">
            <label for="kategori_bidang_keahlian_nama">Nama Bidang Keahlian<span style="color: red;">*</span></label>
            <input type="text" name="kategori_bidang_keahlian_nama" id="kategori_bidang_keahlian_nama" class="form-control">
            <small id="error-kategori_bidang_keahlian_nama" class="text-danger"></small>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function () {

        $("#form-tambah-KategoriBidangKeahlian").validate({
            rules: {
                kategori_bidang_keahlian_kode: { required: true, minlength: 3, maxlength: 255 },
                kategori_bidang_keahlian_nama: { required: true, minlength: 3, maxlength: 255 },
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#modal-KategoriBidangKeahlian').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                            });
                            dataKategoriBidangKeahlian.ajax.reload();
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