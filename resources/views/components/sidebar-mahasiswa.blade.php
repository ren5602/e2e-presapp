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
                    <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-home"></i> Dashboard
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('mahasiswa.prestasi.index') }}"
                                class="{{ request()->routeIs('mahasiswa.prestasi.*' ) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-medal"></i> Prestasi Saya
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('daftar_lomba.index') }}"
                                class="{{ request()->routeIs('daftar_lomba.*' ) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-flag"></i> Daftar Lomba
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('lomba_diikuti.index') }}"
                                class="{{ request()->routeIs('lomba_diikuti.*' ) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-flag"></i> Lomba Yang Diikuti
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>