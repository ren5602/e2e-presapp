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
        Daftar prestasi
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title"> Daftar Prestasi <i class="fa fa-trophy"></i>
            </h3>
            <div class="btn-actions-pane-right text-capitalize">
                {{-- <button class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm">View All</button> --}}
                <button onclick="modalAction('{{ url('/prestasi/create') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        <div class="card-body">


            {{-- Filter --}}
            {{-- Filter --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-12 col-md-1 control-label col-form-label">Filter:</label>

                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <select class="form-select" id="tingkat_lomba_id" name="tingkat_lomba_id"
                                style="width: 100%">
                                <option value="">- Semua -</option>
                                @foreach ($tingkat_lomba as $item)
                                    <option value="{{ $item->tingkat_lomba_id }}">{{ $item->tingkat_lomba_nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter Tingkat Lomba</small>
                        </div>
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status_verifikasi"
                                        id="verifikasi_1" value="1">
                                    <label class="form-check-label" for="verifikasi_1">Terverifikasi</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status_verifikasi"
                                        id="verifikasi_2" value="2">
                                    <label class="form-check-label" for="verifikasi_2">Menunggu</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status_verifikasi"
                                        id="verifikasi_0" value="3">
                                    <label class="form-check-label" for="verifikasi_0">Ditolak</label>
                                </div>

                                <button type="button" class="btn btn-sm btn-secondary ms-2"
                                    onclick="clearStatusVerifikasi()">
                                    Clear
                                </button>
                            </div>

                            <!-- Keterangan dipindah ke bawah -->
                            <small class="form-text text-muted mt-1">Filter Status</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive w-100">
                <table style="width: 100%;" id="table-prestasi"
                    class="table table-hover table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Mahasiswa</th>
                            <th>Prestasi</th>
                            <th>Lomba</th>
                            <th>Juara</th>
                            <th>Tingkat</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>



    <x-slot:modal>
        <div id="modal-prestasi" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
    </x-slot:modal>

    <x-slot:js>
        <script>
            function clearStatusVerifikasi() {
                const radios = document.getElementsByName('status_verifikasi');
                radios.forEach(radio => radio.checked = false);
                dataPrestasi.ajax.reload();
            }

            function modalAction(url) {
                // Kosongkan modal sebelum memuat konten baru
                $("#modal-prestasi .modal-content").html("");

                // Panggil modal melalui AJAX
                $.get(url, function(response) {
                    $("#modal-prestasi .modal-content").html(response);
                    $("#modal-prestasi").modal("show");
                });
            }

            // Bersihkan isi modal setelah ditutup
            $('#modal-prestasi').on('hidden.bs.modal', function() {
                $("#modal-prestasi .modal-content").html("");
            });

            var dataPrestasi
            $(document).ready(function() {
                $('#tingkat_lomba_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: "- Semua -",
                    allowClear: true,
                    width: '100%' // Gunakan width penuh
                });



                dataPrestasi = $('#table-prestasi').DataTable({
                    serverSide: true,
                    // responsive: true, // <-- ini penting

                    ajax: {
                        url: "{{ url('prestasi/list') }}",
                        dataType: "json",
                        type: "POST",
                        data: function(d) {
                            d.tingkat_lomba_id = $('#tingkat_lomba_id').val();
                            d.status_verifikasi = $('input[name=status_verifikasi]:checked').val();
                        }
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "nim",
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "mahasiswa",
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "prestasi_nama",
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "lomba",
                            className: "",
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: "juara",
                            className: "",
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: "tingkat",
                            className: "",
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: "poin",
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "status_verifikasi",
                            className: "",
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: "aksi",
                            className: "",
                            orderable: false,
                            searchable: false
                        }
                    ]
                });


                $('#tingkat_lomba_id, input[name=status_verifikasi]').on('change', function() {
                    dataPrestasi.ajax.reload();
                });

            });
        </script>
    </x-slot:js>
</x-layout>
