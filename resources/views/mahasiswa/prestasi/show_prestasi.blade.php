<x-layout>
    <x-slot:css>
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
        Prestasi: {{ $prestasi->prestasi_nama }}
    </x-slot:title>

    @php
        if ($prestasi->status_verifikasi == '1') {
            $bgColor = 'rgba(0, 255, 85, 0.144)'; // Hijau
        } elseif ($prestasi->status_verifikasi == '0') {
            $bgColor = 'rgba(255, 0, 0, 0.144)'; // Merah
        } else {
            $bgColor = 'rgba(255, 251, 0, 0.144)'; // Kuning
        }
    @endphp

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title"><i class="fa fa-trophy"> {{ $prestasi->prestasi_nama }}</i>
            </h3>
        </div>

        <div class="card-body">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama Mahasiswa</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->mahasiswa->nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">NIM</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->mahasiswa->nim }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Prestasi</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->prestasi_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama Prestasi</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->lomba->lomba_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Tanggal Perolehan</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ \Carbon\Carbon::parse($prestasi->tanggal_perolehan)->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Juara</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->nama_juara }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Tingkat</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->lomba->tingkat->tingkat_lomba_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Poin</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $prestasi->poin }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Status Verifikasi</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        @if ($prestasi->status_verifikasi == '1')
                                            <span class="badge badge-success">Terverifikasi</span>
                                        @elseif($prestasi->status_verifikasi == '0')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu Verifikasi</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Sertifikat</h5>
                            <div
                                style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                <img src="{{ file_exists(public_path('storage/' . $prestasi->file_sertifikat)) ? asset('storage/' . $prestasi->file_sertifikat) : asset('assets/images/broken-image.png') }}"
                                    alt="Sertifikat" class="img-click-preview"
                                    style="width: 100%; height: 100%; object-fit: contain; display: block; cursor: pointer;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Bukti Foto</h5>
                            <div
                                style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                <img id="preview-sertifikat"
                                    src="{{ file_exists(public_path('storage/' . $prestasi->file_bukti_foto)) ? asset('storage/' . $prestasi->file_bukti_foto) : asset('assets/images/broken-image.png') }}"
                                    alt="Bukti Foto" class="img-click-preview"
                                    style="width: 100%; height: 100%; object-fit: contain; display: block;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Surat Tugas</h5>
                            <div
                                style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                <img id="preview-sertifikat"
                                    src="{{ file_exists(public_path('storage/' . $prestasi->file_surat_tugas)) ? asset('storage/' . $prestasi->file_surat_tugas) : asset('assets/images/broken-image.png') }}"
                                    alt="Surat Tugas" class="img-click-preview"
                                    style="width: 100%; height: 100%; object-fit: contain; display: block;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Surat Undangan</h5>
                            <div
                                style="position: relative; width: 100%; max-width: 600px; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                <img id="preview-sertifikat" class="img-click-preview"
                                    src="{{ file_exists(public_path('storage/' . $prestasi->file_surat_undangan)) ? asset('storage/' . $prestasi->file_surat_undangan) : asset('assets/images/broken-image.png') }}"
                                    alt="Surat Undangan"
                                    style="width: 100%; height: 100%; object-fit: contain; display: block;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">File Proposal</h5>
                            <div style="position: relative; width: 100%; height: 70vh; border: 1px solid #ccc;">
                                <iframe id="preview-proposal"
                                    src="{{ $prestasi->file_proposal && file_exists(public_path('storage/' . $prestasi->file_proposal)) ? asset('storage/' . $prestasi->file_proposal) : '' }}"
                                    width="100%" height="100%" style="border: none;"></iframe>

                                @if (!$prestasi->file_proposal)
                                    <div
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255, 255, 255, 0.85);">
                                        <p id="no-proposal" style="color: #666; font-size: 18px;">Tidak ada proposal
                                        </p>
                                    </div>
                                @endif
                                @if (!file_exists(public_path('storage/' . $prestasi->file_proposal)))
                                    <div
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255, 255, 255, 0.85);">
                                        <p id="no-proposal" style="color: #666; font-size: 18px;">File proposal tidak
                                            ditemukan</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pesan Dari Admin</h5>
                            <div class="card" style="background-color: {{ $bgColor }}">
                                <div class="card-body">
                                    <p class="card-text mb-0"
                                        style="{{ empty(trim($prestasi->message)) ? 'font-style: italic; color: #6c757d;' : '' }}">
                                        {{ empty(trim($prestasi->message)) ? 'Tidak ada pesan' : $prestasi->message }}
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-primary mr-2" data-dismiss="modal"><i
                        class="fa fa-arrow-left"></i>
                    Kembali</button>
                <button onclick="modalDelete('{{ route('mahasiswa.prestasi.confirm', $prestasi->prestasi_id) }}')"
                    type="button" class="btn btn-danger mr-2" data-dismiss="modal"><i class="fa fa-trash"></i>
                    Hapus</button>
                <button type="button" class="btn btn-success mr-2" data-dismiss="modal"><i class="fa fa-edit"></i>
                    Edit</button>
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
            <div id="modal-delete" class="modal fade animate shake" tabindex="-1" role="dialog"
                data-backdrop="static" data-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-xs" role="document">
                    <div class="modal-content"></div>
                </div>
            </div>
        </x-slot:modal>



        <x-slot:js>
            <script>
                function modalDelete(url) {
                    // Kosongkan modal sebelum memuat konten baru
                    $("#modal-delete .modal-content").html("");

                    // Panggil modal melalui AJAX
                    $.get(url, function(response) {
                        $("#modal-delete .modal-content").html(response);
                        $("#modal-delete").modal("show");
                    });
                }

                // Bersihkan isi modal setelah ditutup
                $('#modal-delete').on('hidden.bs.modal', function() {
                    $("#modal-delete .modal-content").html("");
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const previewImgs = document.querySelectorAll('.img-click-preview');
                    const modalImg = document.getElementById('modalPreviewImg');
                    const modal = new bootstrap.Modal(document.getElementById('modalPreview'));

                    previewImgs.forEach(img => {
                        img.addEventListener('click', function() {
                            modalImg.src = this.src;
                            modal.show();
                        });
                    });
                });
            </script>
        </x-slot:js>


</x-layout>
