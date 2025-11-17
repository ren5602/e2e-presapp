<form action="{{ url('/prestasi/import') }}" method="POST" id="form-import-prestasi" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Import Data Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Download Template</label>
            <a href="{{ asset('template_prestasi.xlsx') }}" class="btn btn-info btn-sm" download>
                <i class="fa fa-file-excel"></i> Download
            </a>
            <small id="error-template" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Pilih File Excel</label>
            <input type="file" name="file_prestasi" id="file_prestasi" class="form-control" required>
            <small id="error-file_prestasi" class="error-text form-text text-danger"></small>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
        <button type="submit" class="btn btn-primary">Upload</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#form-import-prestasi").validate({
            rules: {
                file_prestasi: {
                    required: true,
                    extension: "xlsx"
                }
            },
            messages: {
                file_prestasi: {
                    required: "Silakan pilih file Excel terlebih dahulu.",
                    extension: "File harus berformat .xlsx"
                }
            },
            submitHandler: function (form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status) {
                            $('#modal-prestasi').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataprestasi.ajax.reload(); // Reload DataTable Prestasi
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
