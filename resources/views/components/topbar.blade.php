@php
    $user = auth()->user();
    $unreadCount = $user ? $user->unreadNotifications->count() : 0;
    $avatarColor = $user && $user->role === 'lecturer' ? '#1a73e8' : '#0f9d58';
    $notifications = $user ? $user->notifications()->latest()->limit(10)->get() : collect();
@endphp

<header class="topbar">
    <div class="topbar-left">
        <button class="topbar-toggle" id="sidebarToggle" aria-label="Buka menu">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="topbar-title">
            <span class="topbar-title-text">
                @yield('page-title', 'Simple Classroom')
            </span>
            @hasSection('page-subtitle')
                <span class="topbar-title-separator">›</span>
                <span class="topbar-title-sub">@yield('page-subtitle')</span>
            @endif
        </div>
    </div>

    <div class="topbar-right">
        <!-- Dropdown Buat/Gabung Kelas -->
        <div class="dropdown topbar-dropdown">
            <button class="topbar-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Buat atau gabung kelas">
                <i class="fas fa-plus"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 topbar-dropdown-menu">
                <li>
                    <button class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#joinClassModal">
                        <i class="fas fa-sign-in-alt me-2"></i>Gabung ke kelas
                    </button>
                </li>
                @if($user && $user->role === 'lecturer')
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('classes.create') }}">
                            <i class="fas fa-plus-circle me-2"></i>Buat kelas
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Dropdown Notifikasi -->
        <div class="dropdown topbar-dropdown">
            <button class="topbar-icon-btn position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                <i class="fas fa-bell"></i>
                @if($unreadCount > 0)
                    <span class="topbar-badge">{{ $unreadCount }}</span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 topbar-dropdown-menu" style="min-width: 360px; max-height: 480px; overflow-y: auto;">
                @if($notifications->count() > 0)
                    @foreach($notifications as $notification)
                        <li class="dropdown-item p-3 border-bottom {{ $notification->read_at ? '' : 'bg-notification-unread' }}" style="white-space: normal;">
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="fw-semibold small">{{ $notification->data['title'] ?? 'Notifikasi' }}</span>
                                    <span class="text-muted small" style="font-size: 0.65rem;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-0">{{ $notification->data['description'] ?? '' }}</p>
                                <div class="d-flex justify-content-end gap-2 mt-1">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size: 0.65rem;">Tandai dibaca</button>
                                        </form>
                                    @endif
                                    <a href="{{ $notification->data['url'] ?? '/announcements' }}" class="btn btn-sm btn-primary rounded-pill" style="font-size: 0.65rem;">
                                        Lihat
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    <li class="dropdown-item text-center py-2">
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill">Tandai semua telah dibaca</button>
                        </form>
                    </li>
                @else
                    <li class="dropdown-item text-center py-4 text-muted">
                        <i class="fas fa-bell-slash mb-2 d-block" style="font-size: 1.5rem;"></i>
                        Tidak ada notifikasi
                    </li>
                @endif
            </ul>
        </div>

        <!-- Dropdown Profil -->
        <div class="dropdown topbar-dropdown">
            <button class="topbar-avatar" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: {{ $avatarColor }};">
                {{ Str::upper(Str::substr($user->fullname ?? 'U', 0, 1)) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 topbar-dropdown-menu">
                <li class="topbar-profile-header">
                    <div class="topbar-profile-name">{{ $user->fullname ?? 'Guest User' }}</div>
                    <div class="topbar-profile-email">{{ $user->email ?? 'guest@example.com' }}</div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Modal Gabung Kelas -->
<div class="modal fade" id="joinClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('classes.join') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Gabung Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="join_code_modal" class="form-label">Kode Kelas</label>
                        <input type="text" class="form-control form-control-lg" id="join_code_modal" name="join_code" placeholder="Contoh: ABC123" required autocomplete="off">
                        <small class="text-muted">Masukkan kode yang diberikan oleh dosen Anda.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Gabung</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .topbar-dropdown-menu {
        border-radius: 12px;
        padding: 0;
    }
    .topbar-dropdown-menu .dropdown-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f1f3f4;
    }
    .topbar-dropdown-menu .dropdown-item:last-child {
        border-bottom: none;
    }
    .topbar-dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .topbar-dropdown-menu::-webkit-scrollbar {
        width: 4px;
    }
    .topbar-dropdown-menu::-webkit-scrollbar-thumb {
        background: #dadce0;
        border-radius: 4px;
    }
</style>
@endpush