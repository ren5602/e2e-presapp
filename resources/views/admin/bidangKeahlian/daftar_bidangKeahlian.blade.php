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
        Daftar Bidang Keahlian
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title">Daftar Bidang Keahlian</h3>
            <div class="btn-actions-pane-right text-capitalize">
                <button onclick="modalAction('{{ url('/bidangKeahlian/create') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-12 col-md-1 control-label col-form-label">Filter:</label>
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <select class="form-control" id="filter_kategori_id" name="kategori_bidang_keahlian_id">
                                <option value="">- Semua -</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item->kategori_bidang_keahlian_id }}">{{ $item->kategori_bidang_keahlian_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter Kategori</small>
                        </div>
                    </div>
                </div>
            </div>
                <div class="table-responsive w-100">
                    <table class="table table-bordered table-sm table-striped table-hover w-100"
                        id="table-bidangKeahlian">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <x-slot:modal>
            <div id="modal-bidangKeahlian" class="modal fade animate shake" tabindex="-1" role="dialog"
                data-backdrop="static" data-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content"></div>
                </div>
            </div>
        </x-slot:modal>

        <x-slot:js>
            <script>
                function modalAction(url) {
                    $("#modal-bidangKeahlian .modal-content").html("");
                    $.get(url, function(response) {
                        $("#modal-bidangKeahlian .modal-content").html(response);
                        $("#modal-bidangKeahlian").modal("show");
                    });
                }

                $('#modal-bidangKeahlian').on('hidden.bs.modal', function() {
                    $("#modal-bidangKeahlian .modal-content").html("");
                });

                var dataBidangKeahlian;
                $(document).ready(function() {
                    dataBidangKeahlian = $('#table-bidangKeahlian').DataTable({
                        serverSide: true,
                        ajax: {
                            url: "{{ url('bidangKeahlian/list') }}",
                            dataType: "json",
                            type: "POST",
                            data: function(d) {
                                d.kategori_bidang_keahlian_id = $('#filter_kategori_id').val();
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                className: "text-center",
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'bidang_keahlian_kode',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'bidang_keahlian_nama',
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: 'kategori',
                                orderable: true,
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

                    $('#filter_kategori_id').on('change', function() {
                        dataBidangKeahlian.ajax.reload();
                    });
                });
            </script>
        </x-slot:js>
</x-layout>
