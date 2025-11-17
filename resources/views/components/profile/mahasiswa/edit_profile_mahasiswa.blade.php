<form id="form-edit-profile" action="{{ route('profile.mahasiswa.update') }}" enctype="multipart/form-data" method="POST">
    {{-- @csrf --}}
    {{-- @method('PUT') --}}
    <div class="bg-light" style="height: 250px; overflow: hidden;">
        <img src="{{ asset('assets/images/gdungjti2.png') }}" class="w-100 h-100 object-fit-cover"
            style="object-fit: cover;">
    </div>

    <div class="text-center mt-n5">
        <img id="profileImage" class="rounded-circle border border-primary shadow bg-white img-thumbnail mb-3"
            style="width: 160px; height: 160px; object-fit: cover;"
            src="{{ auth()->user()->mahasiswa->foto_profile ? asset('storage/' . auth()->user()->mahasiswa->foto_profile) : asset('assets/images/user.png') }}"
            alt="Profile picture">
        <div class="mt-2">
            <input type="file" id="foto_profile" name="foto_profile" class="d-none" accept="image/*"
                onchange="previewImage(event)">
            <button type="button" onclick="document.getElementById('foto_profile').click()" class="btn btn-primary">
                Change Picture
            </button>
            <button type="button" onclick="removeImage()" class="btn btn-outline-danger">
                Delete Picture
            </button>
        </div>
        <input type="hidden" id="remove_picture" name="remove_picture" value="0">
    </div>

    <div class="card-body">
        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label">Nama Mahasiswa</label>
                <div type="text" class="form-control" style="background-color: #e9ecef">
                    {{ auth()->user()->mahasiswa->nama }}</div>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">NIM Mahasiswa</label>
                <div type="text" class="form-control" style="background-color: #e9ecef">
                    {{ auth()->user()->mahasiswa->nim }}</div>
            </div>
            <div class="mb-3 col-md-6">
                <div class="form-group">
                    <label>Username<span style="color: red;">*</span></label>
                    <input value="{{ auth()->user()->mahasiswa->user->username }}" type="text" name="username"
                        id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="mb-3 col-md-6">
                <div class="form-group">
                    <label class="form-label">Email<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="email" name="email"
                        value="{{ auth()->user()->mahasiswa->email }}">
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="mb-3 col-md-6">
                <div class="form-group">
                    <label class="form-label">No. Telepon<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="no_tlp" name="no_tlp"
                        value="{{ auth()->user()->mahasiswa->no_tlp }}">
                    <small id="error-no_tlp" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="mb-3 col-md-3">
                <label class="form-label">Kelas</label>
                <div type="text" class="form-control" style="background-color: #e9ecef">
                    {{ auth()->user()->mahasiswa->kelas->kelas_nama }}</div>
            </div>
            <div class="mb-3 col-md-3">
                <label class="form-label">Angkatan</label>
                <div type="text" class="form-control" style="background-color: #e9ecef">
                    {{ auth()->user()->mahasiswa->tahun_angkatan }}</div>
            </div>
            <div class="mb-3 col-md-12">
                <div class="form-group">
                    <label class="form-label">Alamat<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                        value="{{ auth()->user()->mahasiswa->alamat }}">
                    <small id="error-alamat" class="error-text form-text text-danger"></small>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button class="btn btn-warning" onclick="cancelEditProfile()">Batal</button>
            <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button>
        </div>

    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-edit-profile").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                email: {
                    required: true,
                    email: true
                },
                no_tlp: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                alamat: {
                    required: true,
                    minlength: 6,
                    maxlength: 200
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.hash =
                                    'tab-eg115-0'; // set hash dulu
                                    location.reload(); // reload halaman
                                }
                            });

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
</script>
