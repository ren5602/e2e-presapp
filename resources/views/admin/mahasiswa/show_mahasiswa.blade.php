@empty($mahasiswa)
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
                <a href="{{ url('/mahasiswa') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Data mahasiswa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <div class="container">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img id="profileImage" class="img-thumbnail rounded-circle mb-3"
                                        style="width: 160px; height: 160px; object-fit: cover;"
                                        src="{{ $mahasiswa->foto_profile ? asset('storage/' . $mahasiswa->foto_profile) : asset('assets/images/user.png') }}"
                                        alt="Profile picture">
                                    <div class="mt-3">
                                        <h4>{{ $mahasiswa->nama }}</h4>
                                        <p class="text-secondary mb-1">{{ $mahasiswa->nim }} -
                                            {{ $mahasiswa->kelas->kelas_nama }}</p>
                                        <p class="text-muted font-size-sm">{{ $mahasiswa->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Username</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $mahasiswa->user->username }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $mahasiswa->nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">NIM</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $mahasiswa->nim }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Kelas</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $mahasiswa->kelas->kelas_nama }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">No. Telpon</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $mahasiswa->no_tlp }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $mahasiswa->email }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">IPK</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $mahasiswa->ipk }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Alamat</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $mahasiswa->alamat }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-info " onclick="modalAction('{{ url('/mahasiswa/' . $mahasiswa->mahasiswa_id . '/edit') }}')"
                                            href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @if (!$mahasiswa->prestasi->isEmpty())
            <div class="container">
                <h5 class="modal-title">Detail prestasi</h5>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Prestasi</th>
                            <th>Tanggal Perolehan Prestasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswa->prestasi as $i => $prestasiItem)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $prestasiItem->prestasi_nama ?? '-' }}</td>
                                <td>{{ $prestasiItem->tanggal_perolehan ?? '-' }}</td>
                                <td>
                                    <button
                                        onclick="modalAction('{{ url('/prestasi/' . $prestasiItem->prestasi_id . '/show') }}')"
                                        class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div>
            <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Close</button>
        </div>

    @endempty
