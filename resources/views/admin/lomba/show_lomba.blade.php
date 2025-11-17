@empty($lomba)
    {{-- This section is for when $lomba is empty (data not found) --}}
    <div id="modal-delete" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/lomba') }}" class="btn btn-warning">Kembali</a>
            </div>
            {{-- For the 'not found' case, the footer with a close button might not be strictly necessary if 'Kembali' sends them away --}}
            {{-- But if you want a close button here too, you'd add: --}}
            {{-- <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Close</button>
            </div> --}}
        </div>
    </div>
@else
    {{-- This section is for when $lomba is NOT empty (data found) --}}
    <div class="modal-header">
        <h5 class="modal-title">Data lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <div class="container mt-4">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Kode</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $lomba->lomba_kode }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama Lomba</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $lomba->lomba_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Link Website</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <a href="{{ $lomba->link_website }}" target="_blank">{{ $lomba->link_website }}</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Jumlah Anggota (Dalam 1 Tim)</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $lomba->jumlah_anggota }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Bidang Keahlian</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $lomba->bidang->bidang_keahlian_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Penyelenggara</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $lomba->penyelenggara->penyelenggara_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Tanggal Mulai</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ \Carbon\Carbon::parse($lomba->tanggal_mulai)->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Tanggal Selesai</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ \Carbon\Carbon::parse($lomba->tanggal_selesai)->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header-tab card-header">
                    <h3 class="card-title"><i class="fa fa-trophy"> Deskripsi Lomba</i></h3>
                </div>
                <div class="card-body">
                    <p class="card-text mb-0">
                        {{ $lomba->lomba_deskripsi }}
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Foto Pamflet</h5>
                            <div
                                style="position: relative; width: 100%; max-width: 100%; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                                <a href="{{ asset('storage/' . $lomba->foto_pamflet) }}" target="_blank">
                                    <img id="preview-pamflet"
                                        src="{{ file_exists(public_path('storage/' . $lomba->foto_pamflet)) ? asset('storage/' . $lomba->foto_pamflet) : asset('assets/images/broken-image.png') }}"
                                        alt="Pamflet"
                                        style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($lomba->rekomendasi->count() > 0)
            <h5 class="modal-title mt-5">Rekomendasi Mahasiswa</h5>
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lomba->rekomendasi as $i => $rekomendasi)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $rekomendasi->mahasiswa->nim ?? '-' }}</td>
                            <td>{{ $rekomendasi->mahasiswa->nama ?? '-' }}</td>
                            <td>
                                <button
                                    onclick="modalAction('{{ url('/mahasiswa/' . $rekomendasi->mahasiswa->mahasiswa_id . '/show') }}')"
                                    class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div> {{-- End of modal-body --}}

    {{-- The modal-footer should be directly inside modal-content, after modal-body --}}
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Close</button>
    </div>
@endempty
