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
        Daftar Dosen
    </x-slot:title>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Dosen</h3>
            <div class="btn-actions-pane-right text-capitalize">
                {{-- <button class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm">View All</button> --}}
                <button onclick="modalAction('{{ url('/dosen/create') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah
                </button>
                <button onclick="modalAction('{{ url('/dosen/import') }}')" class="btn btn-sm btn-primary mt-1">
                    <i class="fa fa-plus"></i> Import
                </button>
                <a href="{{ url('/dosen/export') }}" class="btn btn-info mt-1"><i class="fa fa-file-excel"></i>
                    Export</a>
            </div>
        </div>

        <div class="card-body">

            {{-- Filter --}}
            {{-- Filter --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                    </div>
                </div>
            </div>

            <div class="table-responsive w-100">
                <!-- Dosen Table -->
                <table class="table table-bordered table-sm table-striped table-hover w-100" id="table-dosen">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIDN</th>
                            <th>Info</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                </table>
            </div>

        </div>
    </div>

    <x-slot:modal>
        <div id="modal-dosen" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
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
                $("#modal-dosen .modal-content").html("");

                // Panggil modal melalui AJAX
                $.get(url, function (response) {
                    $("#modal-dosen .modal-content").html(response);
                    $("#modal-dosen").modal("show");
                });
            }

            // Bersihkan isi modal setelah ditutup
            $('#modal-dosen').on('hidden.bs.modal', function () {
                $("#modal-dosen .modal-content").html("");
            });


            var dataDosen
            $(document).ready(function () {
                $('#dosen_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: "- Semua -",
                    allowClear: true,
                    width: '100%' // Gunakan width penuh
                });



                dataDosen = $('#table-dosen').DataTable({
                    serverSide: true,
                    // responsive: true, // <-- ini penting

                    ajax: {
                        url: "{{ url('dosen/list') }}",
                        dataType: "json",
                        type: "POST",
                        data: function (d) {
                            d.dosen_id = $('#dosen_id').val();
                        }
                    },
                    columns: [
                        { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                        { data: "nidn", className: "", orderable: true, searchable: true },
                        { data: "info", className: "", orderable: true, searchable: true },
                        { data: "aksi", className: "", orderable: false, searchable: false }
                    ]
                });


                $('#dosen_id').on('change', function () {
                    dataDosen.ajax.reload();
                });

            });
        </script>
    </x-slot:js>
</x-layout>