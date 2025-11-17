<form action="{{ url('/prodi/' . $prodi->prodi_id) }}" method="POST" id="form-edit-prodi">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title">Edit Data Prodi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Nama Prodi</label>
            <input type="text" name="prodi_nama" id="prodi_nama" class="form-control" value="{{ $prodi->prodi_nama }}">
            <small id="error-info" class="text-danger"></small>
        </div>
        
        <div class="form-group">
            <label>Kode Prodi</label>
            <input type="text" name="prodi_kode" id="prodi_kode" class="form-control" value="{{ $prodi->prodi_kode }}">
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
    $("#form-edit-prodi").validate({
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
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        dataProdi.ajax.reload();
                    } else {
                        $('.text-danger').text('');
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
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>