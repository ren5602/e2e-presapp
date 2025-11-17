<x-layout>
    <x-slot:css>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />
        {{--
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
        --}}

    </x-slot:css>
    <x-slot:title>
        Daftar Prestasi
        <div class="page-title-subheading">Semua prestasi terbaikmu tampil disini</div>
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title mt-2 mb-2"> Daftar Prestasi <i class="fa fa-trophy"></i>
            </h3>
            <div class="btn-actions-pane-right text-capitalize">
                {{-- <button class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm">View All</button> --}}
                <a href="{{ route('mahasiswa.prestasi.create') }}">
                    <button class="btn btn-sm btn-success mt-2 mb-2">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                </a>

            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ url('prestasiku') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <select class="form-select" id="tingkat_lomba_id" name="tingkat_lomba_id"
                                    style="width: 100%">
                                    <option value="">- Semua -</option>
                                    @foreach ($tingkat_lomba as $item)
                                        <option value="{{ $item->tingkat_lomba_id }}"
                                            {{ request('tingkat_lomba_id') == $item->tingkat_lomba_id ? 'selected' : '' }}>
                                            {{ $item->tingkat_lomba_nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Filter Tingkat Lomba</small>
                            </div>
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <select class="form-select" id="status_verifikasi" name="status_verifikasi"
                                    style="width: 100%">
                                    <option value="">- Semua -</option>
                                    <option value="1" {{ request('status_verifikasi') == '1' ? 'selected' : '' }}>
                                        Terverifikasi
                                    </option>
                                    <option value="2" {{ request('status_verifikasi') == '2' ? 'selected' : '' }}>
                                        Menunggu
                                    </option>
                                    <option value="0" {{ request('status_verifikasi') == '0' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                                <small class="form-text text-muted">Filter Status Verifikasi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari prestasi..."
                                value="{{ request('search') }}">
                            <button class="btn btn-primary ml-1" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <span class="badge bg-warning">Menunggu Verifikasi</span>
                        <span class="badge bg-success">Terverifikasi</span>
                        <span class="badge bg-danger">Ditolak</span>
                    </div>
                </div>
            </form>



            <hr>
            <div class="row mt-4">
                @if ($prestasi->count())
                    @foreach ($prestasi as $pres)
                        @php
                            if ($pres->status_verifikasi == '1') {
                                $bgColor = 'rgba(0, 255, 85, 0.144)'; // Hijau
                            } elseif ($pres->status_verifikasi == '0') {
                                $bgColor = 'rgba(255, 0, 0, 0.144)'; // Merah
                            } else {
                                $bgColor = 'rgba(255, 255, 0, 0.144)'; // Kuning
                            }
                        @endphp
                        <div class="col-md-6">
                            <div class="card mb-3"
                                style="max-width: 100%; border-radius: 16px; background-color: {{ $bgColor }};">
                                <div class="row g-0">
                                    <div class="col-md-5">
                                        <div
                                            style="position: relative; width: 100%; height: 100%; aspect-ratio: 1 / 1; border-radius: 16px 16px 16px 16px; overflow: hidden;">
                                            <a href="{{ route('mahasiswa.prestasi.show', $pres->prestasi_id) }}">
                                                <img src="{{ file_exists(public_path('storage/' . $pres->file_bukti_foto)) ? asset('storage/' . $pres->file_bukti_foto) : asset('assets/images/broken-image.png') }}"
                                                    alt="Foto Lomba"
                                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                            </a>

                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="card-body d-flex flex-column">
                                            <div
                                                class="d-flex justify-content-between align-items-center mt-auto mb-3 pb-2 border-bottom">
                                                <p class="card-text mb-0">
                                                    <small class="text-body-secondary">
                                                        {{ \Carbon\Carbon::parse($pres->updated_at)->locale('id')->diffForHumans() }}
                                                    </small>
                                                </p>
                                                <div class="d-flex">
                                                    <a href="{{ route('mahasiswa.prestasi.edit', $pres->prestasi_id) }}"
                                                        class="btn btn-sm btn-warning mr-1"><i class="fa fa-edit"></i>
                                                        Edit</a>
                                                    <button
                                                        onclick="modalDelete('{{ route('mahasiswa.prestasi.confirm', $pres->prestasi_id) }}')"
                                                        class="btn btn-sm btn-danger ml-1"><i class="fa fa-trash"></i>
                                                        Hapus</button>
                                                </div>
                                            </div>

                                            <a href="{{ route('mahasiswa.prestasi.show', $pres->prestasi_id) }}">
                                                <h5 class="card-title">
                                                    {{ \Illuminate\Support\Str::words($pres->prestasi_nama, 5, '...') }}
                                                </h5>
                                            </a>
                                            <table class="mb-0" style="font-size: 14px;">
                                                <tr>
                                                    <th style="padding: 4px 8px; white-space: nowrap; width: 1%;">Lomba
                                                    </th>
                                                    <td style="padding: 4px 4px;">:</td>
                                                    <td style="padding: 4px 8px;">{{ $pres->lomba->lomba_nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="padding: 4px 8px; white-space: nowrap; width: 1%;">Juara
                                                    </th>
                                                    <td style="padding: 4px 4px;">:</td>
                                                    <td style="padding: 4px 8px;">{{ $pres->nama_juara }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="padding: 4px 8px; white-space: nowrap; width: 1%;">
                                                        Tingkat</th>
                                                    <td style="padding: 4px 4px;">:</td>
                                                    <td style="padding: 4px 8px;">
                                                        {{ $pres->lomba->tingkat->tingkat_lomba_nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="padding: 4px 8px; white-space: nowrap; width: 1%;">
                                                        Penyelenggara
                                                    </th>
                                                    <td style="padding: 4px 4px;">:</td>
                                                    <td style="padding: 4px 8px;">
                                                        {{ $pres->lomba->penyelenggara->penyelenggara_nama }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center mt-4 mb-4">
                        <h5 class="text-muted">Prestasi tidak ditemukan.</h5>
                    </div>
                @endif



            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 mr-4">
            {{ $prestasi->links() }}
        </div>

    </div>

    <x-slot:modal>
        <div id="modal-delete" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" aria-hidden="true">
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

            // var dataPrestasi
            $(document).ready(function() {
                $('#tingkat_lomba_id, #status_verifikasi').select2({
                    theme: 'bootstrap-5',
                    placeholder: "- Semua -",
                    allowClear: true,
                    width: '100%' // Gunakan width penuh
                });

                $('#tingkat_lomba_id, #status_verifikasi').on('change', function() {
                    $(this).closest('form').submit();
                });
            });
        </script>
    </x-slot:js>
</x-layout>
