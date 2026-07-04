@php
    $user = auth()->user();
    $teachingClasses = $user && $user->role === 'lecturer' ? $user->ownClasses()->limit(5)->get() : collect();
    $enrolledClasses = $user && $user->role === 'student' ? $user->joinedClasses()->limit(5)->get() : collect();

    $totalActiveAnnouncements = 0;
    if ($user && $user->role === 'student') {
        $totalActiveAnnouncements = \App\Models\Announcement::whereHas('class', function($q) use ($user) {
            $q->whereHas('participants', function($q2) use ($user) {
                $q2->where('user_id', $user->id);
            });
        })->where(function($q) {
            $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
        })->count();
    }
@endphp

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Simple Classroom</span>
        </div>
        <button class="btn-close sidebar-close d-md-none" id="sidebarClose" aria-label="Tutup sidebar"></button>
    </div>

    <!-- Menu utama -->
    <div class="sidebar-menu">
        <div class="sidebar-section">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('classes.index') }}" class="sidebar-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Kelas Saya</span>
            </a>
        </div>

        <!-- ===== DOSEN ===== -->
        @if($user && $user->role === 'lecturer')
            <div class="sidebar-divider"></div>
            <div class="sidebar-label">KELAS SAYA</div>
            <div class="sidebar-section">
                @forelse($teachingClasses as $class)
                    <a href="{{ route('classes.show', $class) }}" class="sidebar-link sidebar-class">
                        <span class="sidebar-class-avatar bg-primary">{{ Str::upper(Str::substr($class->name, 0, 1)) }}</span>
                        <span class="sidebar-class-name">{{ $class->name }}</span>
                    </a>
                @empty
                    <div class="sidebar-empty">Belum ada kelas yang diajar.</div>
                @endforelse
            </div>
        @endif

        <!-- ===== MAHASISWA ===== -->
        @if($user && $user->role === 'student')
            <div class="sidebar-divider"></div>
            <div class="sidebar-label">TERDAFTAR</div>
            
            <div class="sidebar-section">
                <a href="{{ route('student.tasks.index') }}" class="sidebar-link {{ request()->routeIs('student.tasks.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Daftar Tugas</span>
                </a>

                <a href="{{ route('announcements.index') }}" class="sidebar-link {{ request()->routeIs('announcements.index') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Pengumuman Terbaru</span>
                    <span class="sidebar-badge">{{ $totalActiveAnnouncements }}</span>
                </a>

                @forelse($enrolledClasses as $class)
                    <a href="{{ route('classes.show', $class) }}" class="sidebar-link sidebar-class">
                        <span class="sidebar-class-avatar bg-success">{{ Str::upper(Str::substr($class->name, 0, 1)) }}</span>
                        <span class="sidebar-class-name">{{ $class->name }}</span>
                    </a>
                @empty
                    <div class="sidebar-empty">Belum ada kelas yang diikuti.</div>
                @endforelse
            </div>
        @endif

        <div class="sidebar-divider"></div>
        <div class="sidebar-section sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                @csrf
                <button type="submit" class="sidebar-link" style="background:none; border:none; width:100%; text-align:left; padding:10px 12px; display:flex; align-items:center; gap:12px; color:#d93025; cursor:pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>

    <div class="sidebar-footer d-md-none">
        <div class="sidebar-user">
            <span class="sidebar-user-avatar">{{ Str::upper(Str::substr($user->fullname ?? 'U', 0, 1)) }}</span>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $user->fullname ?? 'Guest' }}</div>
                <div class="sidebar-user-email">{{ $user->email ?? '' }}</div>
            </div>
        </div>
    </div>
</aside>

@push('styles')
<style>
    /* Mobile-first sidebar styles - defined in main.css */
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.getElementById('sidebarClose');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });
</script>
@endpush