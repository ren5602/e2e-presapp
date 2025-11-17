<x-layout>
    <x-slot:css>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />
    </x-slot:css>

    <x-slot:title>
        Daftar Admin
    </x-slot:title>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Admin</h3>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped table-hover" id="table-admin">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Info</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Container --}}
    <div id="modal-crud" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>

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

            var dataAdmin;
            $(document).ready(function () {
                dataAdmin = $('#table-admin').DataTable({
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/list') }}",
                        type: "POST"
                    },
                    columns: [
                        { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                        { data: "info", orderable: false, searchable: true },
                        { data: "aksi", className: "text-center", orderable: false, searchable: false }
                    ]
                });
            });
        </script>
    </x-slot:js>
</x-layout>
