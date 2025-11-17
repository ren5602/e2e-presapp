@empty($keahlian)
    <div id="modal-delete" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/profile/mahasiswa#tab-eg115-1') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Detail Bidang Keahlian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <table class="table table-sm table-bordered table-striped">
            <tr>
                <th class="text-right col-4">Keahlian :</th>
                <td class="col-8">{{ $keahlian->bidang_keahlian->bidang_keahlian_nama }}</td>
            </tr>
        </table>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Foto Sertifikat</h5>
                <!-- Gambar Sertifikat -->
                <div
                    style="position: relative; width: 100%; max-width: full; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                    <a href="{{ asset('storage/' . $keahlian->file_sertifikat) }}" target="_blank">
                        <img id="preview-sertifikat" src="{{ asset('storage/' . $keahlian->file_sertifikat) }}"
                            alt="Sertifikat" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                    </a>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button
                onclick="modalAction('{{ url('/profile/mahasiswa/keahlian/' . $keahlian->keahlian_mahasiswa_id . '/edit') }}')"
                class="btn btn-success btn-sm">Edit</button>
            <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Close</button>
        </div>
    </div>
@endempty
