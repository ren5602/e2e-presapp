<form action="{{ url('/kelas') }}" method="POST" id="form-tambah-kelas">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Data Kelas</h5>
        <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label for="kelas_kode">Kode Kelas<span style="color: red;">*</span></label>
            <input type="text" name="kelas_kode" id="kelas_kode" class="form-control">
            <small id="error-kelas_kode" class="text-danger"></small>
        </div>

        <div class="form-group">
            <label for="kelas_nama">Nama Kelas<span style="color: red;">*</span></label>
            <input type="text" name="kelas_nama" id="kelas_nama" class="form-control">
            <small id="error-kelas_nama" class="text-danger"></small>
        </div>

        <div class="form-group">
            <label for="prodi_id">Program Studi<span style="color: red;">*</span></label>
            <select name="prodi_id" id="prodi_id" class="form-control">
                <option value="">- Pilih Program Studi -</option>
                @foreach($prodi as $p)
                    <option value="{{ $p->prodi_id }}">{{ $p->prodi_nama }}</option>
                @endforeach
            </select>
            <small id="error-prodi_id" class="text-danger"></small>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#form-tambah-kelas").validate({
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
                                text: response.message,
                            });
                            dataKelas.ajax.reload();  // Pastikan variabel dataKelas sudah didefinisikan sebagai DataTables instance
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