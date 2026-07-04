<header class="topbar">
    <div>
        <h1 class="page-title">
            @yield('page-title', 'Dashboard')
            @hasSection('page-subtitle')
                <small class="page-subtitle">@yield('page-subtitle')</small>
            @endif
        </h1>
    </div>

    <div class="user-area">
        <!-- Notification Bell (placeholder) -->
        <div class="notification-bell" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell"></i>
            <span class="badge">0</span>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown">
            <div class="avatar" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                {{ Str::limit(auth()->user()->fullname ?? 'U', 2, '') }}
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="border:none; background:none; width:100%; text-align:left;">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>