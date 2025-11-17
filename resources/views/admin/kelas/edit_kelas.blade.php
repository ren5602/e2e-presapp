<form action="{{ url('/kelas/' . $kelas->kelas_id) }}" method="POST" id="form-edit-kelas">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title">Edit Data Kelas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Kode Kelas<span style="color: red;">*</span></label>
            <input type="text" name="kelas_kode" id="kelas_kode" class="form-control" value="{{ $kelas->kelas_kode }}" required>
            <small id="error-kelas_kode" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Nama Kelas<span style="color: red;">*</span></label>
            <input type="text" name="kelas_nama" id="kelas_nama" class="form-control" value="{{ $kelas->kelas_nama }}" required>
            <small id="error-kelas_nama" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Program Studi<span style="color: red;">*</span></label>
            <select name="prodi_id" id="prodi_id" class="form-control" required>
                <option value="">-- Pilih Program Studi --</option>
                @foreach($prodi as $p)
                    <option value="{{ $p->prodi_id }}" {{ $kelas->prodi_id == $p->prodi_id ? 'selected' : '' }}>
                        {{ $p->prodi_nama }}
                    </option>
                @endforeach
            </select>
            <small id="error-prodi_id" class="error-text form-text text-danger"></small>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
$(document).ready(function () {
    $("#form-edit-kelas").validate({
        rules: {
            kelas_kode: { required: true, maxlength: 50 },
            kelas_nama: { required: true, minlength: 3, maxlength: 255 },
            prodi_id: { required: true }
        },
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
                        dataKelas.ajax.reload();  // Pastikan kamu punya objek DataTables bernama dataKelas
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