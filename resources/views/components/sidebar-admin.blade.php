<div class="app-sidebar sidebar-shadow" style="background-color: rgb(255, 255, 255);">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu</li>
                <li class="">
                    <a href="{{ url('dashboard') }}" class="{{ request()->is('dashboard') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-home"></i> Dashboard
                    </a>
                </li>
                <li class="">
                    <a href="#"
                        class="{{ request()->routeIs('mahasiswa.*', 'dosen.*', 'admin.*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-users"></i> Manajemen Pengguna
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ request()->routeIs('mahasiswa.*', 'dosen.*', 'admin.*') ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('mahasiswa.index') }}"
                                class="{{ request()->routeIs('mahasiswa.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i> Mahasiswa
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dosen.index') }}"
                                class="{{ request()->routeIs('dosen.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i> Dosen
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('admin.index') }}"
                                class="{{ request()->routeIs('admin.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i> Admin
                            </a>
                        </li> --}}
                    </ul>
                </li>
                <li>
                    <a href="#"
                        class="{{ request()->routeIs('kategoriBidangKeahlian.*', 'bidangKeahlian.*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-light"></i> Bidang Keahlian
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul
                        class="{{ request()->routeIs('kategoriBidangKeahlian.*', 'bidangKeahlian.*') ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('kategoriBidangKeahlian.index') }}"
                                class="{{ request()->routeIs('kategoriBidangKeahlian.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i>Kategori Bidang Keahlian
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bidangKeahlian.index') }}"
                                class="{{ request()->routeIs('bidangKeahlian.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i>Bidang Keahlian
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"
                        class="{{ request()->routeIs('lomba.*', 'penyelenggara.*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-flag"></i> Data Lomba
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul
                        class="{{ request()->routeIs('lomba.*', 'penyelenggara.*', 'rekomendasi.*', 'mahasiswa_lomba.*') ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('lomba.index') }}"
                                class="{{ request()->routeIs('lomba.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i>Daftar Lomba
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('penyelenggara.index') }}"
                                class="{{ request()->routeIs('penyelenggara.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i> Daftar Penyelenggara
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rekomendasi.index') }}"
                                class="{{ request()->routeIs('rekomendasi.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i> Rekomendasi Mahasiswa
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('mahasiswa_lomba.index') }}"
                                class="{{ request()->routeIs('mahasiswa_lomba.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i> Mahasiswa Terdaftar
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="{{ request()->routeIs('prestasi.*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-star"></i> Data Prestasi
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ request()->routeIs('prestasi.*') ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('prestasi.index') }}"
                                class="{{ request()->routeIs('prestasi.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i>Prestasi Mahasiswa
                            </a>
                        </li>
                        {{-- <li>
                            <a href="mailbox.html">
                                <i class="metismenu-icon">
                                </i>Data lomba
                            </a>
                            </a>
                        </li> --}}
                    </ul>
                </li>
                <li>
                    <a href="#" class="{{ request()->routeIs('periode.*', 'prodi.*', 'kelas.*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-study"></i>Data Akademik
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ request()->routeIs('periode.*', 'prodi.*', 'kelas.*') ? 'mm-show' : '' }}">
                        {{-- <li>
                            <a href="mailbox.html">
                                <i class="metismenu-icon">
                                </i>Periode Semester
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('prodi.index') }}"
                                class="{{ request()->routeIs('prodi.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i> Program Studi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kelas.index') }}"
                                class="{{ request()->routeIs('kelas.index') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon">
                                </i>Daftar Kelas
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-graph3">
                        </i>Laporan & Analisis
                    </a>
                </li> --}}
            </ul>
        </div>
    </div>
</div>