<form action="{{ url('/penyelenggara') }}" method="POST" id="form-tambah-penyelenggara">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Data Penyelenggara</h5>
        <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label for="penyelenggara_nama">Nama Penyelenggara<span style="color: red;">*</span></label>
            <input type="text" name="penyelenggara_nama" id="penyelenggara_nama" class="form-control">
            <small id="error-penyelenggara_nama" class="text-danger"></small>
        </div>

        <div class="form-group">
            <label for="kota_id">Kota<span style="color: red;">*</span></label>
            <select name="kota_id" id="kota_id" class="form-control">
                <option value="">- Pilih kota -</option>
                @foreach($kota as $k)
                    @if ($k->provinsi->negara->negara_kode == 'ID')
                        <option value="{{ $k->kota_id }}">{{ $k->kota_nama }}</option>
                    @else
                        <option value="{{ $k->kota_id }}">{{ $k->kota_nama }} ({{ $k->provinsi->negara->negara_nama }})</option>
                    @endif
                @endforeach
            </select>
            <small id="error-kota_id" class="text-danger"></small>
        </div>

        {{-- <div class="form-group">
            <label for="kota_id">Kota</label>
            <small id="warning-kota-non-indonesia" class="text-warning d-none">( Kota hanya dapat dipilih jika negaranya
                Indonesia. )</small>
            <select name="kota_id" id="kota_id" class="form-control" disabled>
                <option value="">- Pilih Kota -</option>
                @foreach($kota as $k)
                <option value="{{ $k->kota_id }}">{{ $k->kota_nama }}</option>
                @endforeach
            </select>
            <small id="error-kota_id" class="text-danger"></small>
        </div> --}}
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    function initSelect2() {
        $('#kota_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#modal-penyelenggara')
        });
    }

    $(document).ready(function () {
        initSelect2();

        $('#modal-penyelenggara').on('shown.bs.modal', function () {
            initSelect2();
        });

        $("#form-tambah-penyelenggara").validate({
            rules: {
                penyelenggara_nama: { required: true, minlength: 3, maxlength: 255 },
                kota_id: { required: true },
                negara_id: { required: true }
            },
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
                                text: response.message,
                            });
                            dataPenyelenggara.ajax.reload();
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
        // Aktifkan kota hanya jika negara Indonesia (negara_id = 92) dipilih
        // $('#negara_id').on('change', function () {
        //     let negaraId = $(this).val();
        //     if (negaraId == '92') {
        //         $('#kota_id').prop('disabled', false);
        //         $('#warning-kota-non-indonesia').addClass('d-none');
        //     } else {
        //         $('#kota_id').val('').prop('disabled', true);
        //         $('#warning-kota-non-indonesia').removeClass('d-none');
        //     }
        // });
    });
</script>