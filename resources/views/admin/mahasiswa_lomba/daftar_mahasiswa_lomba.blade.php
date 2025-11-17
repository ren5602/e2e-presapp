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
        Mahasiswa yang Terdaftar Lomba
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title">Daftar mahasiswa_lomba Mahasiswa</h3>
            <div class="btn-actions-pane-right text-capitalize">
                <button onclick="modalAction('{{ url('/mahasiswa_lomba/create') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus" aria-hidden="true"></i> Tambah Data
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">

                        <label class="col-12 col-md-1 control-label col-form-label">Filter:</label>
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <select class="form-select" id="lomba_id" name="lomba_id" style="width: 100%">
                                <option value="">- Semua -</option>
                                @foreach ($lomba as $item)
                                    <option value="{{ $item->lomba_id }}">{{ $item->lomba_nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter Lomba</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive w-100 mt-4">
                <table class="table table-bordered table-sm table-striped table-hover w-100" id="table-mahasiswa_lomba">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lomba</th>
                            <th>Mahasiswa yang Terdaftar</th>
                            <th>NIM Mahasiswa</th>
                            <th>Status Verifikasi</th>
                            <th>Status dari Mahasiswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <x-slot:modal>
        <div id="modal-mahasiswa_lomba" class="modal fade animate shake" tabindex="-1" role="dialog"
            data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
    </x-slot:modal>

    <x-slot:js>
        <script>
            function modalAction(url) {
                $("#modal-mahasiswa_lomba .modal-content").html("");
                $.get(url, function(response) {
                    $("#modal-mahasiswa_lomba .modal-content").html(response);
                    $("#modal-mahasiswa_lomba").modal("show");
                });
            }

            $('#modal-mahasiswa_lomba').on('hidden.bs.modal', function() {
                $("#modal-mahasiswa_lomba .modal-content").html("");
            });

            function intiSelect2() {
                $('#lomba_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: "- Semua -",
                    allowClear: true,
                    width: '100%' // Gunakan width penuh
                });
            }

            var data_mahasiswa_lomba;
            $(document).ready(function() {
                intiSelect2();
                data_mahasiswa_lomba = $('#table-mahasiswa_lomba').DataTable({
                    serverSide: true,
                    ajax: {
                        url: "{{ url('mahasiswa_lomba/list') }}",
                        dataType: "json",
                        type: "POST",
                        data: function(d) {
                            d.lomba_id = $('#lomba_id').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_lomba',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'mahasiswa_terdaftar',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'nim',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'status_verifikasi',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status_verifikasi_from_mhs',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'aksi',
                            orderable: false,
                            searchable: false
                        },

                    ]
                });

                $('#lomba_id').on('change', function() {
                    data_mahasiswa_lomba.ajax.reload();
                });
            });
        </script>
    </x-slot:js>
</x-layout>
