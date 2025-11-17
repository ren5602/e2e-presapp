@empty($bidangKeahlian)
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
                <a href="{{ url('/bidangKeahlian') }}" class="btn btn-warning">Kembali</a>
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
        <div class="row gutters-sm">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Kode Bidang Keahlian</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $bidangKeahlian->bidang_keahlian_kode }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Nama Bidang Keahlian</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $bidangKeahlian->bidang_keahlian_nama }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Kategori Bidang Keahlian</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $bidangKeahlian->kategoriBidangKeahlian->kategori_bidang_keahlian_nama }}
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="modalAction('{{ url('/bidangKeahlian/' . $bidangKeahlian->bidang_keahlian_id . '/edit') }}')"
                class="btn btn-success btn-sm">Edit</button>
            <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Close</button>
        </div>
    </div>
@endempty
