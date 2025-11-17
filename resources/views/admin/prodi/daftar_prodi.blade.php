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
        Daftar Prodi
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <h3 class="card-title">Daftar Prodi</h3>
            <div class="btn-actions-pane-right text-capitalize">
                <button onclick="modalAction('{{ url('/prodi/create') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah Prodi
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive w-100">
                <table class="table table-bordered table-sm table-striped table-hover w-100" id="table-prodi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Prodi</th>
                            <th>Kode Prodi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    <x-slot:modal>
        <div id="modal-prodi" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
    </x-slot:modal>

    <x-slot:js>
        <script>
            function modalAction(url) {
                $("#modal-crud .modal-content").html("");
                $.get(url, function (response) {
                    $("#modal-crud .modal-content").html(response);
                    $("#modal-crud").modal("show");
                });
            }

            $('#modal-crud').on('hidden.bs.modal', function () {
                $("#modal-crud .modal-content").html("");
            });

            var dataProdi;
            $(document).ready(function () {
                dataProdi = $('#table-prodi').DataTable({
                    serverSide: true,
                    ajax: {
                        url: "{{ url('prodi/list') }}",
                        type: "POST"
                    },
                    columns: [
                        { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                        { data: "info", orderable: false, searchable: true },
                        { data: "kode", orderable: false, searchable: true },
                        { data: "aksi", orderable: false, searchable: false }
                    ]
                });

                $('#prodi_id').on('change', function () {
                    dataProdi.ajax.reload();
                });
            });
        </script>
    </x-slot:js>
</x-layout>