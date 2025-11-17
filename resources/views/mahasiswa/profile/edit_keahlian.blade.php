<form action="{{ url('/profile/mahasiswa/keahlian/' . $keahlian->keahlian_mahasiswa_id) }}" method="POST"
    id="form-edit-keahlian">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title">Edit Data Bidang Keahlian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Kategori Bidang Keahlian<span style="color: red;">*</span></label>
            <select name="bidang_keahlian_id" id="bidang_keahlian_id" class="form-control">
                <option value="" disabled>- Pilih Kategori Bidang Keahlian -</option>
                @foreach ($bidangKeahlian as $item)
                    <option value="{{ $item->bidang_keahlian_id }}"
                        {{ $item->bidang_keahlian_id == $keahlian->bidang_keahlian_id ? 'selected' : '' }}>
                        {{ $item->bidang_keahlian_nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Foto Sertifikat</h5>
                <!-- Gambar Sertifikat -->
                <div
                    style="position: relative; width: 100%; max-width: full; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                    <img id="preview-sertifikat"
                        src="{{ file_exists(public_path('storage/' . $keahlian->file_sertifikat)) ? asset('storage/' . $keahlian->file_sertifikat) : asset('assets/images/broken-image.png') }}"
                        alt="Sertifikat" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                </div>
                <div class="form-group mt-2">
                    <!-- Sertifikat -->
                    <input type="file" name="file_sertifikat" id="file_sertifikat" class="form-control"
                        accept="image/*" onchange="previewImage(event)" data-target="preview-sertifikat">

                    <!-- Custom upload button -->
                    {{-- <button type="button" class="btn btn-primary"
                        onclick="document.getElementById('file_sertifikat').click()"><i class="fa fa-upload"></i> Ganti
                        Sertifikat</button> --}}

                    <small class="form-text text-muted">Abaikan jika tidak ingin diubah</small>
                    <small id="error-file_sertifikat" class="error-text form-text text-danger"></small>
                </div>
            </div>
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
        // Tambahkan custom rule dulu
        $.validator.addMethod('filesize', function(value, element, param) {
            if (element.files.length == 0) return true; // valid kalau kosong (tidak wajib)
            return element.files[0].size <= param;
        }, 'Ukuran file maksimal 2 MB.');

        initSelect2();

        $('#modal-profile').on('shown.bs.modal', function() {
            initSelect2();
        });

        $("#form-edit-keahlian").validate({
            rules: {
                bidang_keahlian_id: {
                    required: true
                },
                file_sertifikat: {
                    extension: "jpg|jpeg|png",
                    filesize: 2048000 // 2MB
                }
            },
            messages: {
                bidang_keahlian_id: {
                    required: "Pilih salah satu bidang keahlian."
                },
                file_sertifikat: {
                    extension: "Format file harus JPG, JPEG, atau PNG.",
                    filesize: "Ukuran file maksimal 2 MB."
                }
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
                            keahlianMahasiswa.ajax.reload();
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

    function previewImage(event) {
        const fileInput = event.target;
        const targetId = fileInput.getAttribute('data-target');
        const image = document.getElementById(targetId);
        const file = fileInput.files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Kosongkan pesan error jika valid
            const errorElement = fileInput.nextElementSibling?.nextElementSibling;
            if (errorElement) errorElement.textContent = '';
        } else {
            const errorElement = fileInput.nextElementSibling?.nextElementSibling;
            if (errorElement) errorElement.textContent = "File bukan gambar yang valid.";
        }
    }
</script>
