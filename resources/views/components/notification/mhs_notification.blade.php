@php
    use App\Http\Controllers\NotificationController;
    $notifikasi = NotificationController::getMahasiswaNotification();
@endphp

<div class="header-dots">
    <div class="dropdown">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown"
            class="p-0 mr-2 btn btn-link">
            <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                <span class="icon-wrapper-bg bg-primary"></span>
                <i class="icon text-primary ion-android-notifications"></i>
                <span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
            </span>
        </button>
        <div tabindex="-1" role="menu" aria-hidden="true"
            class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
            <div class="dropdown-menu-header mb-0">
                <div class="dropdown-menu-header-inner bg-deep-blue">
                    <div class="menu-header-image opacity-1"
                        style="background-image: url('{{ asset('assets/images/gdungjti2.png') }}');"></div>
                    <div class="menu-header-content text-dark">
                        <h5 class="menu-header-title">Notifikasi Mahasiswa</h5>
                        <h6 class="menu-header-subtitle">Notifikasi <b>Terbaru</b></h6>
                    </div>
                </div>
            </div>
            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                <li class="nav-item">
                    <a role="tab" class="nav-link active" data-toggle="tab" href="#tab-messages-header">
                        <span>Prestasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a role="tab" class="nav-link" data-toggle="tab" href="#tab-events-header">
                        <span>Rekomendasi Lomba</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-messages-header" role="tabpanel">
                    <div class="scroll-area-sm">
                        <div class="scrollbar-container">
                            <div class="p-3">
                                <div class="notifications-box">
                                    <div
                                        class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                        <div class="vertical-timeline-item dot-success vertical-timeline-element">
                                            <div>
                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                <div class="vertical-timeline-element-content bounce-in">
                                                    <h4 class="timeline-title"><span
                                                            class="badge bg-success">{{ $notifikasi->jmlPrestasi }}</span>
                                                        prestasi sudah diverifikasi</h4><span
                                                        class="vertical-timeline-element-date"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="vertical-timeline-item dot-warning vertical-timeline-element">
                                            <div>
                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                <div class="vertical-timeline-element-content bounce-in">
                                                    <h4 class="timeline-title"><span
                                                            class="badge bg-warning">{{ $notifikasi->jmlPrestasiPending }}</span>
                                                        prestasi masih menunggu verifikasi</h4><span
                                                        class="vertical-timeline-element-date"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="vertical-timeline-item dot-danger vertical-timeline-element">
                                            <div>
                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                <div class="vertical-timeline-element-content bounce-in">
                                                    <h4 class="timeline-title"><span
                                                            class="badge bg-danger">{{ $notifikasi->jmlPrestasiDitolak }}</span>
                                                        prestasi sudah ditolak</h4><span
                                                        class="vertical-timeline-element-date"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-events-header" role="tabpanel">
                    <div class="scroll-area-sm">
                        <div class="scrollbar-container">
                            <div class="p-3">

                                <div
                                    class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                    @foreach ($notifikasi->rekomendasi as $lomba)
                                        <a href="{{ route('daftar_lomba.show', $lomba->lomba_id) }}">
                                            <div class="vertical-timeline-item vertical-timeline-element">
                                                <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                            class="badge badge-dot badge-dot-xl badge-success">
                                                        </i></span>
                                                    <div class="vertical-timeline-element-content bounce-in">
                                                        <h4 class="timeline-title">{{ $lomba->lomba_nama }}</h4>
                                                        <p>{{ Str::words($lomba->lomba_deskripsi, 5, '...') }}</p><span
                                                            class="vertical-timeline-element-date"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
