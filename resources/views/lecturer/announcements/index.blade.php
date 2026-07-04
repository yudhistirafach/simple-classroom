@php
    $user = auth()->user();
    $isOwner = $class->isOwner($user);
    $activeAnnouncements = $class->announcements->filter(function($a) {
        return $a->isActive();
    });
@endphp

<div class="announcements-section">
    @if($isOwner)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-medium">Pengumuman</h5>
            <a href="{{ route('announcements.create', $class) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> Buat Pengumuman
            </a>
        </div>
    @else
        <h5 class="mb-3 fw-medium">Pengumuman</h5>
    @endif

    @if($activeAnnouncements->count() > 0)
        <div class="announcements-list">
            @foreach($activeAnnouncements as $announcement)
                <div class="card border-0 shadow-sm mb-3 announcement-card">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                            <div class="announcement-info flex-grow-1" style="min-width: 0;">
                                <h6 class="mb-1 fw-semibold announcement-title">{{ $announcement->title }}</h6>
                                @if($announcement->description)
                                    <p class="text-muted small mb-2 announcement-description">{{ $announcement->description }}</p>
                                @endif
                                <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                                    <span>
                                        <i class="far fa-calendar-alt me-1"></i>
                                        Diposting: {{ $announcement->created_at->format('d M Y H:i') }}
                                    </span>
                                    @if($announcement->expired_at)
                                        <span class="badge {{ $announcement->expired_at->isFuture() ? 'bg-warning' : 'bg-secondary' }} rounded-pill">
                                            {{ $announcement->expired_at->isFuture() ? 'Berakhir ' . $announcement->expired_at->format('d M Y') : 'Kadaluarsa' }}
                                        </span>
                                    @endif
                                    <span class="badge bg-success rounded-pill">Aktif</span>
                                </div>
                            </div>
                            
                            @if($isOwner)
                                <div class="announcement-actions d-flex gap-1 flex-shrink-0">
                                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 36px; height: 36px;" title="Edit Pengumuman">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 36px; height: 36px;" title="Hapus Pengumuman">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="text-muted opacity-25 mb-3">
                <i class="fas fa-bullhorn" style="font-size: 3rem;"></i>
            </div>
            <p class="text-muted mb-2">Belum ada pengumuman aktif di kelas ini.</p>
            @if($isOwner)
                <a href="{{ route('announcements.create', $class) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus me-1"></i> Buat Pengumuman Pertama
                </a>
            @endif
        </div>
    @endif

    @if($class->announcements->count() > $activeAnnouncements->count())
        <div class="text-center mt-3">
            <small class="text-muted">
                {{ $class->announcements->count() - $activeAnnouncements->count() }} pengumuman kadaluarsa tidak ditampilkan.
            </small>
        </div>
    @endif
</div>

@push('styles')
<style>
    .announcement-card {
        border-radius: 8px;
        transition: box-shadow 0.2s ease-in-out;
    }
    .announcement-card:hover {
        box-shadow: 0 4px 12px rgba(60,64,67,0.12) !important;
    }
    .announcement-title {
        color: #202124;
        font-size: 1rem;
    }
    .announcement-description {
        font-size: 0.875rem;
        line-height: 1.5;
        color: #5f6368;
        white-space: pre-wrap;
    }
    @media (max-width: 576px) {
        .announcement-actions {
            width: 100%;
            justify-content: flex-end;
            margin-top: 8px;
        }
    }
</style>
@endpush