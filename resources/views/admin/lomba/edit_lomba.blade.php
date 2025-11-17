<form action="{{ url('/lomba/' . $lomba->lomba_id) }}" method="POST" id="form-edit-lomba" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal-header">
        <h5 class="modal-title">Edit Data lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Nama Lomba<span style="color: red;">*</span></label>
                    <input type="text" name="lomba_nama" id="lomba_nama" class="form-control"
                        value="{{ $lomba->lomba_nama }}" required>
                    <small id="error-lomba_nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="lomba_deskripsi">Deskripsi Lomba<span style="color: red;">*</span></label>
                    <textarea name="lomba_deskripsi" id="lomba_deskripsi" class="form-control" rows="5" required>{{ $lomba->lomba_deskripsi }}</textarea> {{-- Changed to textarea, value moved inside, added rows --}}
                    <small id="error-lomba_deskripsi" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Jumlah Anggota<span style="color: red;">*</span></label>
                    <input type="text" name="jumlah_anggota" id="jumlah_anggota" class="form-control"
                        value="{{ $lomba->jumlah_anggota }}" required>
                    <small id="error-jumlah_anggota" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Link Website Lomba<span style="color: red;">*</span></label>
                    <input type="text" name="link_website" id="link_website" class="form-control"
                        value="{{ $lomba->link_website }}" required>
                    <small id="error-link_website" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tingkat<span style="color: red;">*</span></label>
                    <select name="tingkat_lomba_id" id="tingkat_lomba_id" class="form-control" required>
                        <option value="">-- Pilih tingkat --</option>
                        @foreach ($tingkat as $k)
                            <option value="{{ $k->tingkat_lomba_id }}"
                                {{ $lomba->tingkat_lomba_id == $k->tingkat_lomba_id ? 'selected' : '' }}>
                                {{ $k->tingkat_lomba_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-tingkat_lomba__id" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bidang<span style="color: red;">*</span></label>
                    <select class="form-select" id="bidang_keahlian_id_edit" name="bidang_keahlian_id"
                        style="width: 100%">
                        <option value="" disabled>- Pilih bidang -</option>
                        @foreach ($bidang as $item)
                            <option value="{{ $item->bidang_keahlian_id }}"
                                {{ old('bidang_kelas_id', $lomba->bidang_keahlian_id) == $item->bidang_keahlian_id ? 'selected' : '' }}>
                                {{ $item->bidang_keahlian_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Penyelenggara<span style="color: red;">*</span></label>
                    <select class="form-select" id="penyelenggara_id" name="penyelenggara_id" style="width: 100%">
                        <option value="" disabled>- Pilih penyelenggara -</option>
                        @foreach ($penyelenggara as $item)
                            <option value="{{ $item->penyelenggara_id }}"
                                {{ old('penyelenggara_id', $lomba->penyelenggara_id) == $item->penyelenggara_id ? 'selected' : '' }}>
                                {{ $item->penyelenggara_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal Mulai<span style="color: red;">*</span></label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                        value="{{ $lomba->tanggal_mulai }}" required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal Selesai<span style="color: red;">*</span></label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                        value="{{ $lomba->tanggal_selesai }}" required>
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status Verifikasi <span style="color: red;">*</span></label><br>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status_verifikasi" id="verifikasi_1"
                            value="1" {{ $lomba->status_verifikasi == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="verifikasi_1">Terverifikasi</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status_verifikasi" id="verifikasi_0"
                            value="0" {{ $lomba->status_verifikasi == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="verifikasi_0">Ditolak</label>
                    </div>

                    <br>
                    <small id="error-status_verifikasi" class="error-text form-text text-danger"></small>
                </div>

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Foto Pamflet<span style="color: red;">*</span></h5>
                <!-- Gambar  -->
                <div
                    style="position: relative; width: 100%; max-width: auto; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                    <img id="preview-pamflet"
                        src="{{ file_exists(public_path('storage/' . $lomba->foto_pamflet)) ? asset('storage/' . $lomba->foto_pamflet) : asset('assets/images/broken-image.png') }}"
                        alt="Pamflet" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                </div>
                <div class="form-group mt-2">
                    <!-- Foto preview-pamflet -->
                    <input type="file" name="foto_pamflet" id="foto_pamflet" class="form-control"
                        accept="image/*" onchange="previewImage(event)" data-target="preview-pamflet">

                    <small class="form-text text-muted">Abaikan jika tidak ingin diubah</small>
                    <small id="error-foto_pamflet" class="error-text form-text text-danger"></small>
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

    function initSelect2() {
        // Hanya inisialisasi jika belum di-init
        $('#bidang_keahlian_id_edit, #penyelenggara_id').select2({
            theme: 'bootstrap-5',
            placeholder: "- Pilih Bidang -",
            width: '100%',
            dropdownParent: $('#modal-lomba') // ⬅️ INI PENTING!
        });
    }
    $(document).ready(function() {
        $('#modal-lomba').on('shown.bs.modal', function() {
            initSelect2();
        });
        // Tambahkan method kustom untuk membandingkan tanggal
        $.validator.addMethod("afterStartDate", function(value, element) {
            const startDate = $('#tanggal_mulai').val();
            if (!startDate || !value) return true; // biarkan validasi 'required' yang menangani
            return new Date(value) >= new Date(startDate);
        }, "Tanggal selesai harus setelah atau sama dengan tanggal mulai.");

        $("#form-edit-lomba").validate({
            rules: {
                lomba_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                lomba_deskripsi: {
                    required: true
                },
                jumlah_anggota: {
                    required: true
                },
                link_website: {
                    required: true
                },
                tingkat_lomba_id: {
                    required: true
                },
                bidang_keahlian_id: {
                    required: true
                },
                penyelenggara_id: {
                    required: true
                },
                tanggal_mulai: {
                    required: true
                },
                tanggal_selesai: {
                    required: true,
                    afterStartDate: true
                },
                status_verifikasi: {
                    required: true
                },
                foto_pamflet: {
                    required: false,
                    extension: "jpg|jpeg|png",
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
                            $('#modal-lomba').on('hidden.bs.modal', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataLomba.ajax.reload();
                                $(this).off(
                                    'hidden.bs.modal'
                                ); // penting untuk mencegah duplikasi
                            });

                            $('#modal-lomba').modal('hide');
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
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
