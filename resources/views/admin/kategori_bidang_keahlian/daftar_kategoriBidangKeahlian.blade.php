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
            <h3 class="card-title">Daftar Kategori Bidang Keahlian</h3>
            <div class="btn-actions-pane-right text-capitalize">
                <button onclick="modalAction('{{ url('/KategoriBidangKeahlian/create') }}')"
                    class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive w-100">
                <table class="table table-bordered table-sm table-striped table-hover w-100"
                    id="table-KategoriBidangKeahlian">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <x-slot:modal>
        <div id="modal-KategoriBidangKeahlian" class="modal fade animate shake" tabindex="-1" role="dialog"
            data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
    </x-slot:modal>
    
    <x-slot:js>
        <script>
            function modalAction(url) {
                $("#modal-KategoriBidangKeahlian .modal-content").html("");
                $.get(url, function (response) {
                    $("#modal-KategoriBidangKeahlian .modal-content").html(response);
                    $("#modal-KategoriBidangKeahlian").modal("show");
                });
            }

            $('#modal-KategoriBidangKeahlian').on('hidden.bs.modal', function () {
                $("#modal-KategoriBidangKeahlian .modal-content").html("");
            });

            var dataKategoriBidangKeahlian;
            $(document).ready(function () {
                dataKategoriBidangKeahlian = $('#table-KategoriBidangKeahlian').DataTable({
                    serverSide: true,
                    ajax: {
                        url: "{{ url('KategoriBidangKeahlian/list') }}",
                        dataType: "json",
                        type: "POST",
                        data: function (d) {
                            d.bidang_id = $('#filter_bidang_id').val();
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                        { data: 'kategori_bidang_keahlian_kode', orderable: true, searchable: true },
                        { data: 'kategori_bidang_keahlian_nama', orderable: true, searchable: true },
                        { data: "aksi", className: "", orderable: false, searchable: false }
                    ]
                });

                $('#KategoriBidangKeahlian_id').on('change', function () {
                    dataKategoriBidangKeahlian.ajax.reload();
                });
            });
        </script>
    </x-slot:js>
</x-layout>