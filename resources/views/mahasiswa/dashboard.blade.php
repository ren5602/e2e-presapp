<x-layout>
    <x-slot:css>
        <style>
            .bg-bronze {
                background-color: #b87333 !important;
                color: #fff !important;
            }
        </style>
    </x-slot:css>

    <x-slot:title>
        Dashboard Mahasiswa
        <div class="page-title-subheading">Selamat Datang di PresApp</div>
    </x-slot:title>

    <div class="row" style="align-items: stretch;">
        <!-- Daftar Lomba -->
        <div class="col-md-8 d-flex">
            <div class="card mb-3 flex-fill d-flex flex-column">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize fw-normal text-truncate w-100">
                        Daftar Lomba
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    @if ($daftarLomba->isEmpty())
                        <p class="text-center text-muted flex-grow-1 d-flex align-items-center justify-content-center">
                            Belum ada lomba.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($daftarLomba as $lomba)
                                <a href="{{ route('daftar_lomba.show', $lomba->lomba_id) }}">
                                    <li class="list-group-item d-flex align-items-center">
                                        <div style="position: relative; width: 60px; height: 60px; aspect-ratio: 1 / 1; border-radius: 8px; overflow: hidden;"
                                            class="me-3">
                                            @php
                                                $fotoPath = $lomba->foto_pamflet;
                                                $storageFullPath = storage_path('app/public/' . $fotoPath);
                                                $fotoUrl =
                                                    $fotoPath && file_exists($storageFullPath)
                                                        ? asset('storage/' . $fotoPath) .
                                                            '?v=' .
                                                            filemtime($storageFullPath)
                                                        : asset('assets/images/image-dummy.png');
                                            @endphp
                                            <img src="{{ $fotoUrl }}" alt="{{ $lomba->lomba_nama }}"
                                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                        </div>

                                        <div style="margin-left: 2rem;">
                                            <strong>{{ $lomba->lomba_nama }}</strong><br>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($lomba->tanggal_mulai)->translatedFormat('d M Y') }}</small>
                                        </div>
                                    </li>
                                </a>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('daftar_lomba.index') }}" class="btn btn-primary">
                                Lihat Semua Daftar Lomba
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top 5 Mahasiswa Peraih Prestasi -->
        <div class="col-md-4">
            <div class="card mb-3 flex-fill d-flex flex-column">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize fw-normal text-truncate w-100">
                        Ranking Mahasiswa Berdasarkan Poin Prestasi
                    </div>
                </div>
                <div class="card-body flex-grow-1 overflow-auto">
                    <ul class="list-group list-group-flush">
                        @foreach ($topMahasiswaPrestasi as $index => $mahasiswa)
                            @php
                                $rank = $index + 1;
                                $badgeClass = match ($rank) {
                                    1 => 'bg-warning text-white fw-bold',
                                    2 => 'bg-secondary text-white fw-bold',
                                    3 => 'bg-bronze text-white fw-bold',
                                    default => 'bg-primary text-white',
                                };
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if ($rank == 1)
                                        <span style="font-size: 2rem; margin-right: 1rem;">🥇</span>
                                    @elseif ($rank == 2)
                                        <span style="font-size: 2rem; margin-right: 1rem;">🥈</span>
                                    @elseif ($rank == 3)
                                        <span style="font-size: 2rem; margin-right: 1rem;">🥉</span>
                                    @else
                                        <span class="badge bg-light text-dark me-2"
                                            style="margin-right: 1rem ">{{ $rank }}</span>
                                    @endif
                                    <div>
                                        <strong>{{ $mahasiswa->nama }}</strong>
                                        <div class="text-muted" style="font-size: 0.9rem;">
                                            {{ $mahasiswa->kelas->prodi->prodi_nama ?? 'Program Studi Tidak Diketahui' }}
                                        </div>
                                    </div>
                                </div>
                                <span class="badge {{ $badgeClass }} rounded-pill">
                                    {{ $mahasiswa->total_prestasi }} Prestasi
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Prestasi Saya -->
            <div class="card mb-4 flex-fill d-flex flex-column">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize fw-normal text-truncate w-100">
                        Prestasi Saya
                    </div>
                </div>
                <div class="card-body flex-grow-1 overflow-auto">
                    @if ($prestasiSaya->isEmpty())
                        <p class="text-muted text-center">Belum ada prestasi</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($prestasiSaya as $prestasi)
                                <li class="list-group-item">
                                    <strong>{{ $prestasi->lomba->lomba_nama }}</strong><br>
                                    <small class="text-muted">
                                        Tanggal didapat :
                                        {{ \Carbon\Carbon::parse($prestasi->lomba->tanggal_selesai)->translatedFormat('d M Y') }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('mahasiswa.prestasi.index') }}" class="btn btn-primary">
                                Lihat Semua Prestasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-slot:modal>
        <!-- Tambahkan modal di sini jika ada -->
    </x-slot:modal>

    <x-slot:js>
        {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
        <script></script>
    </x-slot:js>
</x-layout>
