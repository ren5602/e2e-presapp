<!--Header START-->
@php
    $nama = ''; // Default
    $keterangan = ''; // Default
    $foto_profile = ''; // Default foto profile

    if (Auth::check()) {
        $role = Auth::user()->getRole();

        if ($role === 'MHS') {
            $nama = Auth::user()->mahasiswa->nama;
            $keterangan = Auth::user()->mahasiswa->nim;
            $foto_profile = Auth::user()->mahasiswa->foto_profile
                ? 'storage/' . Auth::user()->mahasiswa->foto_profile
                : 'assets/images/user.png';
        } elseif ($role === 'ADM') {
            $nama = Auth::user()->admin->nama;
            $keterangan = Auth::user()->level->level_nama;
            $foto_profile = Auth::user()->admin->foto_profile
                ? 'storage/' . Auth::user()->admin->foto_profile
                : 'assets/images/user.png';
        } elseif ($role === 'DOS') {
            $nama = Auth::user()->dosen->nama;
            $keterangan = Auth::user()->dosen->nidn;
            $foto_profile = Auth::user()->dosen->foto_profile
                ? 'storage/' . Auth::user()->dosen->foto_profile
                : 'assets/images/user.png';
        }

    }
@endphp



<div class="app-header header-shadow" style="background-color: rgb(147, 200, 243);">
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
            <button type="button"
                class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav"
                style="background-color: transparent; color: #3f6ad8; border: none;">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="app-header__content">
        <div class="app-header-left">
            <div class="search-wrapper">
                <div class="input-holder">
                    <input type="text" class="search-input" placeholder="Cari di halaman">
                    <button class="search-icon" onclick="cariTeksDiHalaman()"><span></span></button>
                </div>
                <button class="close" onclick="resetHighlight()"></button>
            </div>

        </div>
        <div class="app-header-right">
            @if (Auth::check() && in_array(Auth::user()->getRole(), ['MHS']))
                <x-notification.mhs_notification></x-notification.mhs_notification>
            {{-- @elseif (Auth::check() && in_array(Auth::user()->getRole(), ['DOS']))
                <x-notification.dos_notification></x-notification.dos_notification>
            @elseif (Auth::check() && in_array(Auth::user()->getRole(), ['ADM']))
                <x-notification.adm_notification></x-notification.adm_notification> --}}
            @endif

            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <img width="42" height="42" class="rounded-circle"
                                        src="{{{asset($foto_profile)}}}" alt="" style="object-fit: cover;">
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-info">
                                            <div class="menu-header-image opacity-2"
                                                style="background-image: url('{{asset('assets/images/gdungjti2.png')}}');">
                                            </div>
                                            <div class="menu-header-content text-left">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            <img width="42" height="42" class="rounded-circle"
                                                                src="{{asset($foto_profile)}}" alt=""
                                                                style="object-fit: cover;">
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">
                                                                {{$nama}}
                                                            </div>
                                                            <div class="widget-subheading opacity-8">{{$keterangan}}
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-right mr-2">
                                                            <button onclick="modalLogoutAction('{{ url('/logout') }}')"
                                                                class="btn-pill btn-shadow btn-shine btn btn-focus">Logout
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="scroll-area-xs" style="height: 80px;">
                                        <div class="scrollbar-container ps">
                                            <ul class="nav flex-column">
                                                <li class="nav-item-header nav-item">Menu
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{route('profile')}}" class="nav-link">Profile Saya
                                                    </a>
                                                </li>
                                                {{-- <li class="nav-item">
                                                    <a href="javascript:void(0);" class="nav-link">Pesan
                                                        <div class="ml-auto badge badge-pill badge-info">1
                                                        </div>
                                                    </a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left ml-3 header-user-info">
                            <div class="widget-heading">
                                {{$nama}}
                            </div>
                            <div class="widget-subheading">
                                {{$keterangan}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Header END-->