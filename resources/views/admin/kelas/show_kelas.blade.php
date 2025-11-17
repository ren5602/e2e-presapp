@empty($kelas)
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
                <a href="{{ url('/kelas') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Detail Kelas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <table class="table table-sm table-bordered table-striped">
            <tr>
                <th class="text-right col-4">Kode Kelas :</th>
                <td class="col-8">{{ $kelas->kelas_kode }}</td>
            </tr>
            <tr>
                <th class="text-right col-4">Nama Kelas :</th>
                <td class="col-8">{{ $kelas->kelas_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-4">Program Studi :</th>
                <td class="col-8">{{ $kelas->prodi->prodi_nama ?? '-' }}</td>
            </tr>
        </table>
        <div class="modal-footer">
            <button onclick="modalAction('{{ url('/kelas/' . $kelas->kelas_id . '/edit') }}')" class="btn btn-success btn-sm">Edit</button>
            <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Close</button>
        </div>
    </div>
@endempty