<form action="{{ url('/mahasiswa_lomba') }}" method="POST" id="form-tambah-mahasiswa_lomba">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Daftarkan Mahasiswa ke Lomba</h5>
        <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Lomba<span style="color: red;">*</span></label>
            <select class="form-select" id="craete_mhs_lomba-lomba_id" name="lomba_id" style="width: 100%">
                <option value="" disabled selected>- Pilih Lomba -</option>
                @foreach ($lomba as $item)
                    <option value="{{ $item->lomba_id }}">
                        {{ $item->lomba_nama }}
                    </option>
                @endforeach
            </select>

        </div>
        <div class="form-group">
            <label>Mahasiswa<span style="color: red;">*</span></label>
            <select class="form-select" id="craete_mhs_lomba-mahasiswa_id" name="mahasiswa_id" style="width: 100%">
                <option value="" disabled selected>- Pilih mahasiswa -</option>
                @foreach ($mahasiswa as $item)
                    <option value="{{ $item->mahasiswa_id }}">
                        ({{$item->nim}}) {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    function initSelect2() {
        $('#craete_mhs_lomba-lomba_id, #craete_mhs_lomba-mahasiswa_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#modal-mahasiswa_lomba')
        });
    }
    $(document).ready(function() {
        initSelect2();

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
