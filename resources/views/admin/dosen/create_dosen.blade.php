<form action="{{ url('/dosen/') }}" method="POST" enctype="multipart/form-data" id="form_create">
    @csrf
    {{-- @method('PUT') --}}
    <div class="modal-header">
        <h5 class="modal-title">Data dosen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <div class="row">
            <div class="col-md-6">
                <div class="text-center">
                    <img id="profileImage" class="img-thumbnail rounded-circle mb-3"
                        style="width: 160px; height: 160px; object-fit: cover;"
                        src="{{ asset('assets/images/user.png') }}" alt="Profile picture">

                    <div class="mt-2">
                        <input type="file" id="foto_profile" name="foto_profile" class="d-none" accept="image/*"
                            onchange="previewImage(event)">
                        <button type="button" onclick="document.getElementById('foto_profile').click()"
                            class="btn btn-primary">
                            Change Picture
                        </button>
                        <button type="button" onclick="removeImage()" class="btn btn-outline-danger">
                            Delete Picture
                        </button>
                    </div>
                </div>
                <input type="hidden" id="remove_picture" name="remove_picture" value="0">
            </div>

            {{-- <input type="hidden" id="remove_picture" name="remove_picture" value="0"> --}}

            <div class="col-md-6">
                <div class="form-group">
                    <label>Username<span style="color: red;">*</span></label>
                    <input value="" type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIDN<span style="color: red;">*</span></label>
                    <input value="" type="text" name="nidn" id="nidn" class="form-control" required>
                    <small id="error-nidn" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama<span style="color: red;">*</span></label>
                    <input value="" type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email<span style="color: red;">*</span></label>
                    <input value="" type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>No. Tlp<span style="color: red;">*</span></label>
                    <input value="" type="text" name="no_tlp" id="no_tlp" class="form-control" required>
                    <small id="error-no_tlp" class="error-text form-text text-danger"></small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Password</label>
                    <input value="" type="password" name="password" id="password" class="form-control">
                    <small id="error-password" class="error-text form-text text-danger"></small>
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
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profileImage');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        document.getElementById('remove_picture').value = "0";
    }

    function removeImage() {
        document.getElementById('profileImage').src = '/../assets/images/user.png';
        document.getElementById('foto_profile').value = '';
        document.getElementById('remove_picture').value = "1";
    }

    // function initSelect2() {
    //     // Hanya inisialisasi jika belum di-init
    //     if (!$('#mahasiswa_prodi').hasClass("select2-hidden-accessible")) {
    //         $('#mahasiswa_prodi').select2({
    //             theme: 'bootstrap-5',
    //             placeholder: "- Pilih Prodi -",
    //             width: '100%',
    //             dropdownParent: $('#modal-mahasiswa') // ⬅️ INI PENTING!
    //         });
    //     }

    //     if (!$('#mahasiswa_kelas').hasClass("select2-hidden-accessible")) {
    //         $('#mahasiswa_kelas').select2({
    //             theme: 'bootstrap-5',
    //             placeholder: "- Pilih Kelas -",
    //             width: '100%',
    //             dropdownParent: $('#modal-mahasiswa') // ⬅️ INI PENTING!
    //         });
    //     }
    // }


    $(document).ready(function() {
        // handleKelasFilterByProdi('#mahasiswa_prodi', '#mahasiswa_kelas');

        // Inisialisasi Select2 saat modal dibuka
        $('#modal-dosen').on('shown.bs.modal', function() {
            // initSelect2();
            // handleKelasFilterByProdi('#mahasiswa_prodi', '#mahasiswa_kelas');
        });

        $("#form_create").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                password: {
                    minlength: 6,
                    maxlength: 20
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(form); // Gunakan FormData untuk menangani file
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false, // Penting: Agar jQuery tidak memproses data
                    contentType: false, // Penting: Agar tidak diubah menjadi application/x-www-form-urlencoded
                    success: function(response) {
                        if (response.status) {
                            $('#modal-dosen').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataDosen.ajax.reload();
                        } else {
                            $('.error-text').text('');
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
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    // function handleKelasFilterByProdi(prodiSelector, kelasSelector) {
    //     const $prodi = $(prodiSelector);
    //     const $kelas = $(kelasSelector);

    //     const allOptions = $kelas.find('option').clone(); // simpan semua opsi awal

    //     $kelas.prop('disabled', true);

    //     $prodi.on('change', function() {
    //         const selectedProdiId = $(this).val();

    //         if (selectedProdiId) {
    //             // Filter opsi sesuai prodi
    //             const filteredOptions = allOptions.filter(function() {
    //                 const prodiId = $(this).data('prodi-id');
    //                 return !prodiId || prodiId == selectedProdiId || $(this).val() ===
    //                     ""; // biarkan option kosong tetap ada
    //             });

    //             $kelas.empty().append(filteredOptions); // update opsi
    //             $kelas.prop('disabled', false).val('');

    //             // Refresh Select2
    //             if ($kelas.hasClass("select2-hidden-accessible")) {
    //                 $kelas.trigger('change.select2');
    //             }
    //         } else {
    //             $kelas.prop('disabled', true).val('');

    //             if ($kelas.hasClass("select2-hidden-accessible")) {
    //                 $kelas.trigger('change.select2');
    //             }
    //         }
    //     });
    // }
</script>
