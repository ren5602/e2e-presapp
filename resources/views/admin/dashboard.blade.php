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
        Dashboard Admin
    </x-slot:title>

    <!-- Dashboard Lomba -->
    <h3 class="mb-3">Data Lomba</h3>
    <div class="row">
        <!-- Total Lomba -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('lomba.index') }}" class="text-decoration-none">
                <div class="card mb-3 widget-content bg-primary">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Total Lomba</div>
                            <div class="widget-subheading">Jumlah event lomba</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white">
                                <span id="total-lomba">{{ $totalLomba }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Lomba Terverifikasi -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('lomba.index') }}" class="text-decoration-none">
                <div class="card mb-3 widget-content bg-success">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Terverifikasi</div>
                            <div class="widget-subheading">Lomba disetujui</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white">
                                <span id="lomba-verifikasi">{{ $lombaVerifikasi }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Lomba Pending -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('lomba.index') }}" class="text-decoration-none">
                <div class="card mb-3 widget-content bg-warning">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Pending</div>
                            <div class="widget-subheading">Menunggu verifikasi</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white">
                                <span id="lomba-pending">{{ $lombaPending }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Lomba Ditolak -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('lomba.index') }}" class="text-decoration-none">
                <div class="card mb-3 widget-content bg-danger">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Ditolak</div>
                            <div class="widget-subheading">Lomba ditolak</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white">
                                <span id="lomba-ditolak">{{ $lombaDitolak }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Dashboard Prestasi -->
    <h3 class="mb-3">Data Prestasi</h3>
    <div class="row">
        <!-- Total Prestasi -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('prestasi.index') }}" class="text-decoration-none">
                <div class="card mb-3 widget-content bg-primary">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Total Prestasi</div>
                            <div class="widget-subheading">Jumlah event prestasi</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white">
                                <span id="total-prestasi">{{ $totalPrestasi }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Prestasi Terverifikasi -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('prestasi.index') }}" class="text-decoration-none">
                <div class="card mb-3 widget-content bg-success">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Terverifikasi</div>
                            <div class="widget-subheading">Prestasi disetujui</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white">
                                <span id="prestasi-verifikasi">{{ $prestasiVerifikasi }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Prestasi Pending -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('prestasi.index') }}" class="text-decoration-none">
                <div class="card mb-3 widget-content bg-warning">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Pending</div>
                            <div class="widget-subheading">Menunggu verifikasi</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white">
                                <span id="prestasi-pending">{{ $prestasiPending }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Prestasi Ditolak -->
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('prestasi.index') }}" class="text-decoration-none">
                <div class="card mb-3 widget-content bg-danger">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Ditolak</div>
                            <div class="widget-subheading">Prestasi ditolak</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white">
                                <span id="prestasi-ditolak">{{ $prestasiDitolak }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="row" style="align-items: stretch;">
        <!-- Grafik Lomba per Bulan -->
        <div class="col-md-6 d-flex">
            <div class="card mb-3 flex-fill d-flex flex-column">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize fw-normal text-truncate w-100">
                        Data Jumlah Lomba Setiap Bulan
                    </div>
                </div>
                <div class="card-body d-flex justify-content-center flex-grow-1">
                    <canvas id="chartJadwalLomba" width="700" height="500"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 Mahasiswa Peraih Prestasi -->
        <div class="col-md-6 d-flex">
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
                                $badgeClass = match($rank) {
                                    1 => 'bg-warning text-white fw-bold',
                                    2 => 'bg-secondary text-white fw-bold',
                                    3 => 'bg-bronze text-white fw-bold',
                                    default => 'bg-primary text-white'
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
                                        <span class="badge bg-light text-dark me-2" style="margin-right: 1rem ">{{ $rank }}</span>
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
        </div>
    </div>

    <!-- Grafik -->
    <div class="row">
        <!-- Grafik Lomba per Tingkat -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize fw-normal text-truncate w-100">
                        Data Lomba Berdasarkan Tingkat Lomba
                    </div>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="chartLombaTingkat" width="500" height="500"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Prestasi per Tingkat -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize fw-normal text-truncate w-100">
                        Data Prestasi Berdasarkan Tingkat Lomba
                    </div>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="chartPrestasiTingkat" width="500" height="500"></canvas>
                </div>
            </div>
        </div>
    </div>  

    <x-slot:modal>
        <!-- Tambahkan modal di sini jika ada -->
    </x-slot:modal>

    <x-slot:js>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctxLombaTingkat = document.getElementById('chartLombaTingkat').getContext('2d');
            const chartLombaTingkat = new Chart(ctxLombaTingkat, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($lombaPerTingkat->pluck('tingkat_lomba_nama')) !!},
                    datasets: [{
                        label: 'Jumlah Lomba per Tingkat',
                        data: {!! json_encode($lombaPerTingkat->pluck('total_lomba')) !!},
                        backgroundColor: [
                            '#007bff', '#28a745', '#ffc107', '#dc3545',
                            '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            const ctxPrestasiTingkat = document.getElementById('chartPrestasiTingkat').getContext('2d');
            const chartPrestasiTingkat = new Chart(ctxPrestasiTingkat, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($prestasiPerTingkat->pluck('tingkat_lomba_nama')) !!},
                    datasets: [{
                        label: 'Jumlah Prestasi per Tingkat',
                        data: {!! json_encode($prestasiPerTingkat->pluck('total_prestasi')) !!},
                        backgroundColor: [
                            '#007bff', '#28a745', '#ffc107', '#dc3545',
                            '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            const ctxJadwal = document.getElementById('chartJadwalLomba').getContext('2d');
            const labels = {!! json_encode($jadwalLombaPerBulan->pluck('bulan_format')) !!};
            const data = {!! json_encode($jadwalLombaPerBulan->pluck('total')) !!};
            const chartJadwalLomba = new Chart(ctxJadwal, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Lomba',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </x-slot:js>
</x-layout>