<div class="modal-header">
    <h5 class="modal-title">Konfirmasi Hapus</h5>
    <button type="button" class="close" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="main-body">
        <div class="row gutters-sm">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Lomba</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $mahasiswa_lomba->lomba->lomba_nama }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Mahasiswa</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $mahasiswa_lomba->mahasiswa->nama }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Status Verifikasi</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                @if ($mahasiswa_lomba->status_verifikasi == '1')
                                    <span class="badge badge-success">Terverifikasi</span>
                                @elseif($mahasiswa_lomba->status_verifikasi == '0')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Status Dari Mahasiswa</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                @if ($mahasiswa_lomba->status_verifikasi_from_mhs == '1')
                                    <span class="badge badge-success">Diterima</span>
                                @elseif($mahasiswa_lomba->status_verifikasi_from_mhs == '0')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pesan Dari Mahasiswa</h5>
                <div class="card" style="background-color: rgb(243, 243, 255)">
                    <div class="card-body">
                        <p class="card-text mb-0"
                            style="{{ empty(trim($mahasiswa_lomba->message)) ? 'font-style: italic; color: #6c757d;' : '' }}">
                            {{ empty(trim($mahasiswa_lomba->message)) ? 'Tidak ada pesan' : $mahasiswa_lomba->message }}
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">Kembali</button>
</div>
