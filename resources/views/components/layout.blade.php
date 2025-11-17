<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/presapp-logo.png') }}" />

    <title>PresApp</title>

    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{--
    <link rel="stylesheet" href="../assets/css/base.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/base.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{--
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    --}}


    {{-- Sweet Alert --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <x-css>{{ $css }}</x-css>


</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">

        <x-navbar></x-navbar>

        <!--THEME OPTIONS START-->
        {{-- <x-options></x-options> --}}
        <!--THEME OPTIONS END-->

        {{-- <x-main></x-main> --}}
        <div class="app-main" style="background-color: rgb(225, 242, 255);">
            @if (Auth::check() && in_array(Auth::user()->getRole(), ['ADM']))
                <x-sidebar-admin></x-sidebar-admin>
            @elseif (Auth::check() && in_array(Auth::user()->getRole(), ['MHS']))
                <x-sidebar-mahasiswa></x-sidebar-mahasiswa>
            @elseif (Auth::check() && in_array(Auth::user()->getRole(), ['DOS']))
                <x-sidebar-dosen></x-sidebar-dosen>
            @endif

            <div class="app-main__outer">

                <div class="app-main__inner">
                    <x-header>{{ $title }}</x-header>

                    <div class="tabs-animation">
                        {{ $slot }}
                    </div>
                </div>

                <x-footer></x-footer>

            </div>
        </div>
    </div>

    <!--DRAWER START-->
    <x-drawer></x-drawer>
    <!--DRAWER END-->


    {{-- Modal CRUD Container --}}
    <x-modal>{{ $modal }}</x-modal>




    <!--SCRIPTS INCLUDES-->

    <!--CORE-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
    <script src="{{ asset('assets/js/scripts-init/app.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/demo.js') }}"></script>

    <!--CHARTS-->

    <!--Apex Charts-->
    <script src="{{ asset('assets/js/vendors/charts/apex-charts.js') }}"></script>

    <script src="{{ asset('assets/js/scripts-init/charts/apex-charts.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/charts/apex-series.js') }}"></script>

    <!--Sparklines-->
    <script src="{{ asset('assets/js/vendors/charts/charts-sparklines.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/charts/charts-sparklines.js') }}"></script>

    <!--Chart.js')}}-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="{{ asset('assets/js/scripts-init/charts/chartsjs-utils.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/charts/chartjs.js') }}"></script>

    <!--FORMS-->

    <!--Clipboard-->
    <script src="{{ asset('assets/js/vendors/form-components/clipboard.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/form-components/clipboard.js') }}"></script>

    <!--Datepickers-->
    <script src="{{ asset('assets/js/vendors/form-components/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/form-components/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/form-components/moment.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/form-components/datepicker.js') }}"></script>

    <!--Multiselect-->
    <script src="{{ asset('assets/js/vendors/form-components/bootstrap-multiselect.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/scripts-init/form-components/input-select.js') }}"></script>

    <!--Form Validation-->
    <script src="{{ asset('assets/js/vendors/form-components/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/form-components/form-validation.js') }}"></script>

    <!--Form Wizard-->
    <script src="{{ asset('assets/js/vendors/form-components/form-wizard.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/form-components/form-wizard.js') }}"></script>

    <!--Input Mask-->
    <script src="{{ asset('assets/js/vendors/form-components/input-mask.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/form-components/input-mask.js') }}"></script>

    <!--RangeSlider-->
    <script src="{{ asset('assets/js/vendors/form-components/wnumb.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/form-components/range-slider.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/form-components/range-slider.js') }}"></script>

    <!--Textarea Autosize-->
    <script src="{{ asset('assets/js/vendors/form-components/textarea-autosize.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/form-components/textarea-autosize.js') }}"></script>

    <!--Toggle Switch -->
    <script src="{{ asset('assets/js/vendors/form-components/toggle-switch.js') }}"></script>


    <!--COMPONENTS-->

    <!--BlockUI -->
    <script src="{{ asset('assets/js/vendors/blockui.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/blockui.js') }}"></script>

    <!--Calendar -->
    <script src="{{ asset('assets/js/vendors/calendar.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/calendar.js') }}"></script>

    <!--Slick Carousel -->
    <script src="{{ asset('assets/js/vendors/carousel-slider.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/carousel-slider.js') }}"></script>

    <!--Circle Progress -->
    <script src="{{ asset('assets/js/vendors/circle-progress.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/circle-progress.js') }}"></script>

    <!--CountUp -->
    <script src="{{ asset('assets/js/vendors/count-up.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/count-up.js') }}"></script>

    <!--Cropper -->
    <script src="{{ asset('assets/js/vendors/cropper.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/jquery-cropper.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/image-crop.js') }}"></script>

    <!--Maps -->
    <script src="{{ asset('assets/js/vendors/gmaps.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/jvectormap.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/maps-word-map.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/maps.js') }}"></script>

    <!--Guided Tours -->
    <script src="{{ asset('assets/js/vendors/guided-tours.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/guided-tours.js') }}"></script>

    <!--Ladda Loading Buttons -->
    <script src="{{ asset('assets/js/vendors/ladda-loading.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/spin.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/ladda-loading.js') }}"></script>

    <!--Rating -->
    <script src="{{ asset('assets/js/vendors/rating.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/rating.js') }}"></script>

    <!--Perfect Scrollbar -->
    <script src="{{ asset('assets/js/vendors/scrollbar.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/scrollbar.js') }}"></script>

    <!--Toastr-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/scripts-init/toastr.js') }}"></script>

    <!--SweetAlert2-->
    {{--
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script src="{{ asset('assets/js/scripts-init/sweet-alerts.js')}}"></script> --}}
    <!-- Sweet alert2 -->

    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!--Tree View -->
    <script src="{{ asset('assets/js/vendors/treeview.js') }}"></script>
    <script src="{{ asset('assets/js/scripts-init/treeview.js') }}"></script>


    <!--TABLES -->
    <!--DataTables-->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.10.19/js/dataTables.bootstrap4.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js" crossorigin="anonymous">
    </script>

    <!--Bootstrap Tables-->
    <script src="{{ asset('assets/js/vendors/tables.js') }}"></script>

    <!--Tables Init-->
    <script src="{{ asset('assets/js/scripts-init/tables.js') }}"></script>

    <!-- jquery validation -->
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    {{-- CARI TEKS --}}



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector('.search-input');
            input.addEventListener('keydown', function(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // mencegah form submit jika ada
                    cariTeksDiHalaman();
                }
            });
        });

        function cariTeksDiHalaman() {
            resetHighlight(); // hapus highlight sebelumnya

            const input = document.querySelector('.search-input');
            const keyword = input.value.trim();
            if (!keyword) return;

            const regex = new RegExp(`(${keyword})`, 'gi');

            // Fungsi rekursif untuk cari dan highlight
            const highlightText = (node) => {
                if (node.nodeType === 3) { // text node
                    if (regex.test(node.nodeValue)) {
                        const span = document.createElement('span');
                        span.innerHTML = node.nodeValue.replace(regex, '<mark class="highlight">$1</mark>');
                        node.parentNode.replaceChild(span, node);
                    }
                } else if (node.nodeType === 1 && node.childNodes && !['SCRIPT', 'STYLE', 'MARK'].includes(node
                    .tagName)) {
                    Array.from(node.childNodes).forEach(highlightText);
                }
            };

            highlightText(document.body);

            // Scroll ke hasil pertama
            const pertama = document.querySelector('.highlight');
            if (pertama) {
                pertama.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        function resetHighlight() {
            // Kembalikan teks dari <mark> ke teks biasa
            const highlights = document.querySelectorAll('.highlight');
            highlights.forEach(el => {
                const text = document.createTextNode(el.textContent);
                el.parentNode.replaceChild(text, el);
            });
        }


        // Untuk mengirimkan token Laravel CSRF pada setiap request ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function modalLogoutAction(url) {
            $("#modal-logout .modal-content").html("");
            $.get(url, function(response) {
                $("#modal-logout .modal-content").html(response);
                $("#modal-logout").modal("show");
            });
        }

        $('#modal-logout').on('hidden.bs.modal', function() {
            $("#modal-logout .modal-content").html("");
        });
    </script>

    {{-- Js Tambahan --}}
    <x-js>{{ $js }}</x-js>
</body>

</html>
