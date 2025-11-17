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
        <div class="page-title-subheading">Profil admin</div>
    </x-slot:title>

    <div class="mb-3 card">
        <div class="tab-profile ">
            <x-profile.admin.profile_admin></x-profile.admin.profile_admin>
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
        <x-profile.admin.edit_profile_admin></x-profile.admin.edit_profile_admin>
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

        </script>
    </x-slot:js>
</x-layout>
