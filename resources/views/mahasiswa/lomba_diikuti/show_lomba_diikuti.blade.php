<x-layout>
    <x-slot:css>
        <style>
            .table-blue-striped tbody tr:nth-of-type(odd) {
                background-color: #007bff27;
            }

            .table-blue-striped tbody tr:nth-of-type(even) {
                background-color: #00ffd510;
            }
        </style>
    </x-slot:css>

    <x-slot:title>
        Detail Lomba: {{ $lomba->lomba_nama }}
    </x-slot:title>
    
    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title"><i class="fa fa-trophy"></i> {{ $lomba->lomba_nama }}
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
                                        <a href="{{ $lomba->link_website }}"
                                            target="_blank">{{ $lomba->link_website }}</a>
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

            {{-- Preview Pamflet --}}
            <div class="card mt-5">
                <div class="card-header-tab card-header">
                    <h3 class="card-title"><i class="fa fa-trophy"> Pamflet Lomba</i></h3>
                </div>
                <div class="card-body">
                    <div
                        style="position: relative; width: 100%; max-width: 100%; aspect-ratio: 16 / 9; overflow: hidden; background: #eee;">
                        <a href="{{ asset('storage/' . $lomba->foto_pamflet) }}" target="_blank">
                            <img id="preview-pamflet"
                                src="{{ file_exists(public_path('storage/' . $lomba->foto_pamflet)) ? asset('storage/' . $lomba->foto_pamflet) : asset('assets/images/broken-image.png') }}"
                                alt="Pamflet"
                                style="width: 100%; height: 100%; object-fit: contain; display: block; border: 1px solid #ccc; padding: 4px; border-radius: 8px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:modal>
        <div id="modal-lomba-diikuti" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-xs" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
    </x-slot:modal>

    <x-slot:js>
    </x-slot:js>
</x-layout>
