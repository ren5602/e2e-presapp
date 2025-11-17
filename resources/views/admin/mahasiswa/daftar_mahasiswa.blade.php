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
        Daftar Mahasiswa
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title"> Portfolio Performance
            </h3>
            <div class="btn-actions-pane-right text-capitalize">
                {{-- <button class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm">View All</button> --}}
                <button onclick="modalAction('{{ url('/mahasiswa/create') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah
                </button>
                <button onclick="modalAction('{{ url('/mahasiswa/import') }}')" class="btn btn-sm btn-primary mt-1">
                    <i class="fa fa-plus"></i> Import
                </button>
                <a href="{{ url('/mahasiswa/export') }}" class="btn btn-info mt-1"><i class="fa fa-file-excel"></i>
                    Export</a>
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
                            <select class="form-select" id="mhs-prodi_id" name="prodi_id" style="width: 100%">
                                <option value="">- Semua -</option>
                                @foreach($prodi as $item)
                                    <option value="{{ $item->prodi_id }}">{{ $item->prodi_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter Prodi</small>
                        </div>
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <select class="form-select" id="mhs-kelas_id" name="kelas_id" style="width: 100%">
                                <option value="">- Semua -</option>
                                @foreach($kelas as $item)
                                    <option value="{{ $item->kelas_id }}" data-prodi-id="{{ $item->prodi_id }}">
                                        {{ $item->kelas_nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter Kelas</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive w-100">
                <!-- Mahasiswa Table -->
                <table class="table table-bordered table-sm table-striped table-hover w-100" id="table-mahasiswa">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Info</th>
                            <th>Kelas</th>
                            <th>Alamat</th>
                            <th>Total Poin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                </table>
            </div>

        </div>
    </div>




    <x-slot:modal>
        <div id="modal-mahasiswa" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
    </x-slot:modal>

    <x-slot:js>
        <script>
            function modalAction(url) {
                // Kosongkan modal sebelum memuat konten baru
                $("#modal-mahasiswa .modal-content").html("");

                // Panggil modal melalui AJAX
                $.get(url, function (response) {
                    $("#modal-mahasiswa .modal-content").html(response);
                    $("#modal-mahasiswa").modal("show");
                });
            }

            // Bersihkan isi modal setelah ditutup
            $('#modal-mahasiswa').on('hidden.bs.modal', function () {
                $("#modal-mahasiswa .modal-content").html("");
            });

            var dataMahasiswa
            $(document).ready(function () {
                handleKelasFilterByProdi('#mhs-prodi_id', '#mhs-kelas_id');

                $('#mhs-prodi_id, #mhs-kelas_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: "- Semua -",
                    allowClear: true,
                    width: '100%' // Gunakan width penuh
                });



                dataMahasiswa = $('#table-mahasiswa').DataTable({
                    serverSide: true,
                    // responsive: true, // <-- ini penting

                    ajax: {
                        url: "{{ url('mahasiswa/list') }}",
                        dataType: "json",
                        type: "POST",
                        data: function (d) {
                            d.prodi_id = $('#mhs-prodi_id').val();
                            d.kelas_id = $('#mhs-kelas_id').val();
                        }
                    },
                    columns: [
                        { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                        { data: "nim", className: "", orderable: true, searchable: true },
                        { data: "info", className: "", orderable: true, searchable: true },
                        { data: "kelas", className: "", orderable: true, searchable: true },
                        { data: "alamat", className: "", orderable: false, searchable: true },
                        { data: "poin_prestasi", className: "", orderable: true, searchable: true },
                        { data: "aksi", className: "", orderable: false, searchable: false }
                    ]
                });


                $('#mhs-prodi_id, #mhs-kelas_id').on('change', function () {
                    dataMahasiswa.ajax.reload();
                });

            });

            function handleKelasFilterByProdi(prodiSelector, kelasSelector) {
                const $prodi = $(prodiSelector);
                const $kelas = $(kelasSelector);

                const allOptions = $kelas.find('option').clone(); // simpan semua opsi awal

                $kelas.prop('disabled', true);

                $prodi.on('change', function () {
                    const selectedProdiId = $(this).val();

                    if (selectedProdiId) {
                        // Filter opsi sesuai prodi
                        const filteredOptions = allOptions.filter(function () {
                            const prodiId = $(this).data('prodi-id');
                            return !prodiId || prodiId == selectedProdiId || $(this).val() === ""; // biarkan option kosong tetap ada
                        });

                        $kelas.empty().append(filteredOptions); // update opsi
                        $kelas.prop('disabled', false).val('');

                        // Refresh Select2
                        if ($kelas.hasClass("select2-hidden-accessible")) {
                            $kelas.trigger('change.select2');
                        }
                    } else {
                        $kelas.prop('disabled', true).val('');

                        if ($kelas.hasClass("select2-hidden-accessible")) {
                            $kelas.trigger('change.select2');
                        }
                    }
                });
            }
        </script>
    </x-slot:js>
</x-layout>