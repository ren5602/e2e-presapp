<x-layout>
    <x-slot:css>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />

        <style>
            .table-blue-striped tbody tr:nth-of-type(odd) {
                background-color: #007bff27;
                /* Warna biru muda */
            }

            .table-blue-striped tbody tr:nth-of-type(even) {
                background-color: #00ffd510;
                /* Warna biru muda */
            }
        </style>
    </x-slot:css>

    <x-slot:title>
        Edit Lomba: {{ $lomba->lomba_nama }}
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title"><i class="fa fa-trophy"> {{ $lomba->lomba_nama }}</i>
                @if ($lomba->status_verifikasi === 1)
                    <span class="badge bg-success">Terverifikasi</span>
                @elseif ($lomba->status_verifikasi === 0)
                    <span class="badge bg-danger">Ditolak</span>
                @else
                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                @endif
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ url('/daftar_lomba/' . $lomba->lomba_id) }}" method="POST" enctype="multipart/form-data"
                id="form-edit-lomba">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Lomba<span style="color: red;">*</span></label>
                            <input type="text" name="lomba_nama" id="lomba_nama" class="form-control"
                                value="{{ $lomba->lomba_nama }}">
                            <small id="error-lomba_nama" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Deskripsi Lomba<span style="color: red;">*</span></label>
                            <textarea name="lomba_deskripsi" id="lomba_deskripsi" class="form-control" rows="3">{{ ltrim($lomba->lomba_deskripsi) }}</textarea>
                            <small id="error-lomba_deskripsi" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Link Website Lomba<span style="color: red;">*</span></label>
                            <input type="text" name="link_website" id="link_website" class="form-control"
                                value="{{ $lomba->link_website }}">
                            <small id="error-link_website" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tingkat<span style="color: red;">*</span></label>
                            <select name="tingkat_lomba_id" id="tingkat_lomba_id" class="form-control">
                                <option value="" disabled>- Pilih Tingkat -</option>
                                @foreach ($tingkat as $k)
                                    <option value="{{ $k->tingkat_lomba_id }}"
                                        {{ $lomba->tingkat_lomba_id == $k->tingkat_lomba_id ? 'selected' : '' }}>
                                        {{ $k->tingkat_lomba_nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-tingkat_lomba_id" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bidang Keahlian<span style="color: red;">*</span></label>
                            <select name="bidang_keahlian_id" id="bidang_keahlian_id_edit" class="form-control">
                                <option value="">-- Pilih Bidang --</option>
                                @foreach ($bidang as $item)
                                    <option value="{{ $item->bidang_keahlian_id }}"
                                        {{ old('bidang_kelas_id', $lomba->bidang_keahlian_id) == $item->bidang_keahlian_id ? 'selected' : '' }}>
                                        {{ $item->bidang_keahlian_nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-bidang_keahlian_id" class="text-danger"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jumlah Anggota<span style="color: red;">*</span></label>
                            <input type="text" name="jumlah_anggota" id="jumlah_anggota" class="form-control"
                                value="{{ $lomba->jumlah_anggota }}">
                            <small id="error-jumlah_anggota" class="error-text form-text text-danger"></small>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Penyelenggara<span style="color: red;">*</span></label>
                            <select name="penyelenggara_id" id="penyelenggara_id" class="form-control">
                                <option value="">-- Pilih Penyelenggara --</option>
                                <option value="other">Lainnya (Tambahkan Penyelenggara)</option>

                                @foreach ($penyelenggara as $item)
                                    <option value="{{ $item->penyelenggara_id }}"
                                        {{ old('penyelenggara_id', $lomba->penyelenggara_id) == $item->penyelenggara_id ? 'selected' : '' }}>
                                        {{ $item->penyelenggara_nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-penyelenggara_id" class="text-danger"></small>
                        </div>
                    </div>
                    <div id="tambah-penyelenggara" class="col-md-4" style="display: none;">
                        <div class="form-group">
                            <label for="penyelenggara_nama">Nama Penyelenggara<span
                                    style="color: red;">*</span></label>
                            <input type="text" name="penyelenggara_nama" id="penyelenggara_nama"
                                class="form-control">
                            <small id="error-penyelenggara_nama" class="text-danger"></small>
                        </div>
                    </div>
                    <div id="tambah-kota-penyelenggara" class="col-md-4" style="display: none;">
                        <div class="form-group">
                            <label for="kota_id">Kota<span style="color: red;">*</span></label>
                            <select name="kota_id" id="kota_id" class="form-control">
                                <option value="">- Pilih kota -</option>
                                @foreach ($kota as $k)
                                    @if ($k->provinsi->negara->negara_kode == 'ID')
                                        <option value="{{ $k->kota_id }}">{{ $k->kota_nama }}</option>
                                    @else
                                        <option value="{{ $k->kota_id }}">{{ $k->kota_nama }}
                                            ({{ $k->provinsi->negara->negara_nama }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small id="error-kota_id" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Foto Pamflet</h5>
                                <div
                                    style="position: relative; width: 100%; max-width: auto; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                    <img id="preview-pamflet"
                                        src="{{ file_exists('storage/' . $lomba->foto_pamflet) ? asset('storage/' . $lomba->foto_pamflet) : asset('assets/images/broken-image.png') }}"
                                        alt="Pamflet"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </div>
                                <div class="form-group mt-2">
                                    <div class="row col-md-6">
                                        <input type="file" name="foto_pamflet" id="foto_pamflet"
                                            class="form-control" accept="image/*" onchange="previewImage(event)"
                                            data-target="preview-pamflet">
                                    </div>


                                    <small class="form-text text-muted">Maksimal 2MB, Abaikan jika tidak ingin
                                        diubah</small>
                                    <small id="error-foto_pamflet" class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="mt-5 d-flex justify-content-end ">
                    <a href="{{ route('daftar_lomba.index') }}">
                        <button type="button" class="btn btn-warning mr-2" data-dismiss="modal">Batal</button>
                    </a>
                    <button type="submit" class="btn btn-primary ml-2">Simpan</button>
                </div>

            </form>
        </div>
    </div>


    <x-slot:modal>
        <div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="modalPreviewLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl ">
                <div class="modal-content bg-white p-3 rounded-3">
                    <div class="modal-body text-center p-0">
                        <img id="modalPreviewImg" src="" alt="Preview"
                            style="max-width: 100%; max-height: 90vh; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </x-slot:modal>



    <x-slot:js>

        <script>
            function initSelect2() {
                $('#bidang_keahlian_id_edit, #penyelenggara_id, #tingkat_lomba_id, #kota_id').select2({
                    theme: 'bootstrap-5',
                    // placeholder: "- Pilih -",
                    width: '100%',
                    // dropdownParent: $('#modal-lomba')
                });
            }

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

            $(document).ready(function() {
                initSelect2();

                function togglePenyelenggaraFields() {
                    const selected = $('#penyelenggara_id').val();
                    if (selected === 'other') {
                        $('#tambah-penyelenggara').show();
                        $('#tambah-kota-penyelenggara').show();
                        // Tambahkan aturan required
                        $('#penyelenggara_nama').rules('add', {
                            required: true,
                            messages: {
                                required: "Nama penyelenggara wajib diisi"
                            }
                        });
                        $('#kota_id').rules('add', {
                            required: true,
                            messages: {
                                required: "Kota wajib dipilih"
                            }
                        });
                    } else {
                        $('#tambah-penyelenggara').hide();
                        $('#tambah-kota-penyelenggara').hide();
                        // Hapus aturan required
                        if ($("#form-tambah-lomba").data('validator')) {
                            $('#penyelenggara_nama').rules('remove');
                            $('#kota_id').rules('remove');
                        }
                    }
                }

                // Panggil saat pertama kali load (jika form sedang diedit atau ada selected value)
                togglePenyelenggaraFields();

                // Event handler menggunakan jQuery karena Select2
                $('#penyelenggara_id').on('change', function() {
                    togglePenyelenggaraFields();
                });

                $("#form-edit-lomba").validate({
                    rules: {
                        lomba_kode: {
                            required: true,
                            minlength: 3,
                            maxlength: 255
                        },
                        lomba_nama: {
                            required: true,
                            minlength: 3,
                            maxlength: 255
                        },
                        lomba_deskripsi: {
                            required: true,
                            minlength: 10,
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
                            required: true
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
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href =
                                                "{{ route('daftar_lomba.index') }}";
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
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        </script>
    </x-slot:js>


</x-layout>
