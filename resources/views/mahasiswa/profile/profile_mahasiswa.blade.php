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
        Profil
        <div class="page-title-subheading">Profil Mahasiswa</div>
    </x-slot:title>

    <div class="mb-3 card">
        <div class="card-header card-header-tab-animation">
            <ul class="nav nav-justified">
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-0" class="active nav-link"
                        style="font-weight: bold;">Profile</a></li>
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-1" class="nav-link"
                        style="font-weight: bold;">Keahlian</a></li>
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-2" class="nav-link"
                        style="font-weight: bold;">Minat</a></li>
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-3" class="nav-link"
                        style="font-weight: bold;">Organisasi</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab-eg115-0" role="tabpanel">
                    <div class="tab-profile ">
                        <x-profile.mahasiswa.profile_mahasiswa></x-profile.mahasiswa.profile_mahasiswa>
                    </div>

                </div>
                <div class="tab-keahlian tab-pane" id="tab-eg115-1" role="tabpanel">
                    <div class="mb-3 d-flex justify-content-end">
                        <button onclick="modalProfile('{{ route('profile.mahasiswa.keahlian.create') }}')"
                            class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
                    <div class="table-responsive w-100">
                        <table class="table table-bordered table-sm table-striped table-hover w-100"
                            id="table-keahlian">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Keahlian Kamu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-minat tab-pane" id="tab-eg115-2" role="tabpanel">
                    <div class="mb-3 d-flex justify-content-end">
                        <button onclick="modalProfile('{{ route('profile.mahasiswa.minat.create') }}')"
                            class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
                    <div class="table-responsive w-100">
                        <table class="table table-bordered table-sm table-striped table-hover w-100" id="table-minat">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Minat Kamu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-organisasi tab-pane" id="tab-eg115-3" role="tabpanel">
                    <div class="mb-3 d-flex justify-content-end">
                        <button onclick="modalProfile('{{ route('profile.mahasiswa.organisasi.create') }}')"
                            class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
                    <div class="table-responsive w-100">
                        <table class="table table-bordered table-sm table-striped table-hover w-100"
                            id="table-organisasi">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Organisasi Yang Diikuti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:modal>
        <div id="modal-profile" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
        <div id="modal-password" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-xs" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
    </x-slot:modal>





    <template id="template-edit-profile">
        <x-profile.mahasiswa.edit_profile_mahasiswa></x-profile.mahasiswa.edit_profile_mahasiswa>
    </template>


    <x-slot:js>
        <script>
            function modalProfile(url) {
                $("#modal-profile .modal-content").html("");
                $.get(url, function(response) {
                    $("#modal-profile .modal-content").html(response);
                    $("#modal-profile").modal("show");
                });
            }

            $('#modal-profile').on('hidden.bs.modal', function() {
                $("#modal-profile .modal-content").html("");
            });
            function modalPassword(url) {
                $("#modal-password .modal-content").html("");
                $.get(url, function(response) {
                    $("#modal-password .modal-content").html(response);
                    $("#modal-password").modal("show");
                });
            }

            $('#modal-password').on('hidden.bs.modal', function() {
                $("#modal-password .modal-content").html("");
            });

            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('profileImage');
                    output.src = reader.result;
                }
                reader.readAsDataURL(event.target.files[0]);
                document.getElementById('remove_picture').value = "0";
            }

            function removeImage() {
                document.getElementById('profileImage').src = '/../assets/images/user.png';
                document.getElementById('foto_profile').value = '';
                document.getElementById('remove_picture').value = "1";
            }

            // Simpan isi awal .tab-profile
            let originalProfileContent;

            // Simpan isi awal saat halaman pertama kali dimuat
            window.addEventListener('DOMContentLoaded', () => {
                const container = document.querySelector('.tab-profile');
                originalProfileContent = container.innerHTML;
            });

            function editProfile() {
                const template = document.getElementById('template-edit-profile');
                const clone = document.importNode(template.content, true);
                const container = document.querySelector('.tab-profile');

                // Hapus isi lama
                container.innerHTML = '';
                container.appendChild(clone);

                // Dapatkan token dari meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Tambahkan input CSRF baru (jika belum ada atau untuk memastikan fresh)
                const form = document.getElementById('form-edit-profile');
                let csrfInput = form.querySelector('input[name="_token"]');

                if (csrfInput) {
                    csrfInput.value = csrfToken; // Perbarui jika sudah ada
                } else {
                    // Tambahkan jika tidak ada
                    csrfInput = document.createElement('input');
                    csrfInput.setAttribute('type', 'hidden');
                    csrfInput.setAttribute('name', '_token');
                    csrfInput.setAttribute('value', csrfToken);
                    form.prepend(csrfInput);
                }
            }


            function cancelEditProfile() {
                const container = document.querySelector('.tab-profile');
                container.innerHTML = originalProfileContent;
            }



            var minatMahasiswa;
            var keahlianMahasiswa;
            var organisasiMahasiswa;

            $(document).ready(function() {
                // Cek jika ada hash di URL (misal: #tab-eg115-0)
                var hash = window.location.hash;
                if (hash) {
                    $('.nav a[href="' + hash + '"]').tab('show');
                }

                // Optional: update hash saat tab diklik
                $('.nav a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    history.replaceState(null, null, $(e.target).attr('href'));
                });

                minatMahasiswa = $('#table-minat').DataTable({
                    serverSide: true,
                    ajax: {
                        url: "{{ url('profile/mahasiswa/list_minat') }}",
                        dataType: "json",
                        type: "POST",
                        data: function(d) {
                            d.bidang_id = $('#filter_bidang_id').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'bidang_keahlian_nama',
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
                keahlianMahasiswa = $('#table-keahlian').DataTable({
                    serverSide: true,
                    ajax: {
                        url: "{{ url('profile/mahasiswa/list_keahlian') }}",
                        dataType: "json",
                        type: "POST",
                        data: function(d) {
                            d.bidang_id = $('#filter_bidang_id').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'bidang_keahlian_nama',
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
                organisasiMahasiswa = $('#table-organisasi').DataTable({
                    serverSide: true,
                    ajax: {
                        url: "{{ url('profile/mahasiswa/list_organisasi') }}",
                        dataType: "json",
                        type: "POST",
                        data: function(d) {
                            d.bidang_id = $('#filter_bidang_id').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'organisasi_nama',
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

                // $('#bidangKeahlian_id').on('change', function() {
                //     dataBidangKeahlian.ajax.reload();
                // });
            });
        </script>
    </x-slot:js>
</x-layout>
