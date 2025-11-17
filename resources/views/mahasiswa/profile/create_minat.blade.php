<form action="{{ route('profile.mahasiswa.minat.store') }}" method="POST" id="form-create-keahlian">
    @csrf

    <div class="modal-header">
        <h5 class="modal-title">Tambah Data Minat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Pilih Bidang Keahlian<span style="color: red;">*</span></label>
            <select name="bidang_keahlian_id" id="bidang_keahlian_id" class="form-control">
                <option value="" disabled selected>- Pilih Bidang Keahlian -</option>
                @foreach ($bidangKeahlian as $item)
                    <option value="{{ $item->bidang_keahlian_id }}">
                        {{ $item->bidang_keahlian_nama }}
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
        $('#bidang_keahlian_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#modal-profile')
        });
    }

    $(document).ready(function() {
        initSelect2();

        $('#modal-profile').on('shown.bs.modal', function() {
            initSelect2();
        });

        $("#form-create-keahlian").validate({
            rules: {
                bidang_keahlian_id: {
                    required: true
                },
            },
            messages: {
                bidang_keahlian_id: {
                    required: "Pilih salah satu bidang."
                },
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            $('#modal-profile').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            minatMahasiswa.ajax.reload();
                        } else {
                            $('.error-danger').text('');
                            $.each(response.msgField, function(prefix, val) {
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
