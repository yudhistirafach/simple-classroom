@extends('layouts.main')

@section('title', $class->name)
@section('page-title', $class->name)

@section('content')
@php
    $user = auth()->user();
    $isOwner = $class->isOwner($user);
    $isParticipant = $class->isParticipant($user);
    
    $bgColors = ['#1a73e8', '#1e8e3e', '#f9ab00', '#d93025', '#8e24aa', '#00acc1'];
    $themeColor = $bgColors[$class->id % count($bgColors)];
    
    $upcomingTask = $class->tasks->filter(function($task) {
        return $task->deadline_at->isFuture() && $task->deadline_at->diffInHours(now()) <= 24;
    })->first();
    
    $activeAnnouncements = $class->announcements->filter(function($a) {
        return $a->isActive();
    });
    
    $activeAnnouncementsCount = $activeAnnouncements->count();
@endphp

<div class="container-fluid px-2 px-md-4 py-3" style="max-width: 1000px;">
    
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-3 text-white position-relative overflow-hidden" 
         style="background-color: {{ $themeColor }}; border-radius: 8px; min-height: 120px;">
        
        <div class="position-absolute end-0 bottom-0 opacity-25 d-none d-md-block p-4" style="font-size: 5rem;">
            <i class="fas fa-graduation-cap"></i>
        </div>

        <div class="card-body p-3 p-md-4 d-flex flex-column justify-content-end h-100 position-relative z-2">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div style="flex: 1; min-width: 0;">
                    <h3 class="fw-medium mb-1 text-truncate text-wrap-md" style="letter-spacing: 0.2px; font-size: calc(1.2rem + 0.6vw);">
                        {{ $class->name }}
                    </h3>
                    @if($class->description)
                        <p class="text-white-50 small mb-2 text-truncate" style="max-width: 85%;">
                            {{ $class->description }}
                        </p>
                    @endif
                    <div class="d-flex align-items-center gap-2 mt-1 text-white-50 small">
                        <span><i class="fas fa-user-circle me-1"></i> {{ $class->owner->fullname }}</span>
                        <span class="mx-1">•</span>
                        <span><i class="fas fa-users me-1"></i> {{ $class->participants->count() }} Peserta</span>
                    </div>
                </div>
                @if($isOwner)
                    <div class="d-flex gap-2 flex-shrink-0">
                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-light btn-sm rounded-pill px-3" style="color: #202124;">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini? Semua data akan hilang.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm rounded-pill px-3" style="color: #d93025;">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabs (sticky) -->
    <div class="border-bottom mb-4 bg-white sticky-top shadow-sm-mobile" style="top: 64px; z-index: 1020; margin-left: -8px; margin-right: -8px; padding-left: 8px; padding-right: 8px;">
        <ul class="nav nav-tabs gc-tabs border-0 flex-nowrap overflow-x-auto text-nowrap" id="classTab" role="tablist" style="-webkit-overflow-scrolling: touch; scrollbar-width: none;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-3 py-2-5" id="stream-tab" data-bs-toggle="tab" data-bs-target="#stream-panel" type="button" role="tab">
                    Forum
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-3 py-2-5" id="classwork-tab" data-bs-toggle="tab" data-bs-target="#classwork-panel" type="button" role="tab">
                    Tugas Kelas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-3 py-2-5" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements-panel" type="button" role="tab">
                    Pengumuman
                    <span class="badge bg-secondary rounded-pill ms-1">{{ $activeAnnouncementsCount }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-3 py-2-5" id="people-tab" data-bs-toggle="tab" data-bs-target="#people-panel" type="button" role="tab">
                    Anggota
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="classTabContent">
        
        <!-- ===== STREAM TAB ===== -->
        <div class="tab-pane fade show active" id="stream-panel" role="tabpanel">
            <div class="row g-3">
                
                <!-- Sidebar Kiri (Info & Mendatang) -->
                <div class="col-12 col-md-3">
                    <div class="d-flex flex-column gap-3">
                        
                        @if($isOwner)
                            <div class="card border bg-white shadow-sm" style="border-radius: 8px;">
                                <div class="card-body p-3">
                                    <div class="text-secondary small fw-medium mb-1">Kode Kelas</div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fs-5 fw-bold text-primary" style="letter-spacing: 1px;">{{ $class->join_code }}</span>
                                        <button class="btn btn-link btn-sm p-1 text-secondary rounded-circle" onclick="navigator.clipboard.writeText('{{ $class->join_code }}')" title="Salin Kode">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card border bg-white shadow-sm" style="border-radius: 8px;">
                            <div class="card-body p-3">
                                <div class="text-dark small fw-medium mb-2">Mendatang</div>
                                
                                @if($upcomingTask)
                                    <div class="text-secondary small mb-1">
                                        <i class="fas fa-clock text-warning me-1"></i>
                                        {{ $upcomingTask->title }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        Deadline: {{ $upcomingTask->deadline_at->format('d M H:i') }}
                                        ({{ $upcomingTask->deadline_at->diffForHumans() }})
                                    </div>
                                @else
                                    <p class="text-secondary small mb-0">Tidak ada tugas yang perlu segera dikumpulkan.</p>
                                @endif
                                
                                @if($class->tasks->count() > 0)
                                    <a href="#" class="text-primary text-decoration-none small fw-medium d-inline-block mt-2" onclick="event.preventDefault(); document.getElementById('classwork-tab').click();">
                                        Lihat semua tugas
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feed Stream -->
                <div class="col-12 col-md-9">
                    <div class="d-flex flex-column gap-3">
                        
                        <!-- Posting Baru (untuk dosen) -->
                        @if($isOwner)
                            <div class="card border bg-white shadow-sm style-pointer text-decoration-none d-block" 
                                style="border-radius: 8px; cursor: pointer !important; transition: background-color 0.15s; position: relative; z-index: 10;"
                                onclick="window.location.href='{{ route('announcements.create', $class) }}'">
                                <div class="card-body p-3 d-flex align-items-center gap-3">
                                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" 
                                        style="width: 36px; height: 36px; background-color: {{ $themeColor }}; font-size: 0.9rem; flex-shrink: 0;">
                                        {{ Str::upper(Str::substr($user->fullname ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-secondary small">Umumkan sesuatu ke kelas Shit...</span>
                                </div>
                            </div>
                        @endif

                        <!-- Daftar Pengumuman Aktif -->
                        @forelse($activeAnnouncements as $announcement)
                            <div class="card border bg-white shadow-sm" style="border-radius: 8px;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" 
                                             style="width: 36px; height: 36px; background-color: #7b1fa2; font-size: 0.9rem; flex-shrink: 0;">
                                            {{ Str::upper(Str::substr($class->owner->fullname ?? 'D', 0, 1)) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-dark small fw-medium text-truncate">{{ $class->owner->fullname }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                {{ $announcement->created_at->diffForHumans() }}
                                                @if($announcement->expired_at)
                                                    • Berakhir {{ $announcement->expired_at->format('d M Y') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="text-dark fw-medium mb-1">{{ $announcement->title }}</h6>
                                    <p class="text-dark small mb-0">{{ $announcement->description }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="card border bg-white shadow-sm" style="border-radius: 8px;">
                                <div class="card-body p-3 text-center">
                                    <p class="text-muted small mb-0">Belum ada pengumuman di kelas ini.</p>
                                    @if($isOwner)
                                        <a href="{{ route('announcements.create', $class) }}" class="btn btn-primary btn-sm rounded-pill mt-2">
                                            <i class="fas fa-plus me-1"></i> Buat Pengumuman
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                        
                        @if($class->announcements->count() > $activeAnnouncementsCount)
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    {{ $class->announcements->count() - $activeAnnouncementsCount }} pengumuman kadaluarsa tidak ditampilkan.
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== CLASSWORK TAB ===== -->
        <div class="tab-pane fade" id="classwork-panel" role="tabpanel">
            <div class="d-flex flex-column gap-2">
                @if($isOwner)
                    <div class="mb-3">
                        <a href="{{ route('tasks.create', $class) }}" class="btn btn-primary px-3 rounded-pill fw-medium btn-sm d-inline-flex align-items-center gap-2">
                            <i class="fas fa-plus"></i> Buat Tugas
                        </a>
                    </div>
                @endif

                @if($class->tasks->count() > 0)
                    @foreach($class->tasks as $task)
                        <div class="card border bg-white shadow-sm list-group-item-action style-pointer mb-2" style="border-radius: 8px;">
                            <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div class="d-flex align-items-center gap-3 min-w-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                         style="width: 36px; height: 36px; background-color: {{ $themeColor }}; flex-shrink: 0;">
                                        <i class="fas fa-clipboard-list fs-5"></i>
                                    </div>
                                    <div class="text-truncate" style="min-width: 0;">
                                        <h6 class="text-dark mb-0 small fw-medium text-truncate">{{ $task->title }}</h6>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <span class="text-muted" style="font-size: 0.75rem;">
                                                Diposting {{ $task->created_at->diffForHumans() }}
                                            </span>
                                            <span class="badge {{ $task->deadline_at->isPast() ? 'bg-danger' : 'bg-success' }} rounded-pill" style="font-size: 0.65rem;">
                                                {{ $task->deadline_at->isPast() ? 'Expired' : 'Active' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                    <span class="text-secondary" style="font-size: 0.75rem; white-space: nowrap;">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $task->deadline_at->format('d M H:i') }}
                                    </span>
                                    @if($isOwner)
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 32px; height: 32px;" title="Edit">
                                                <i class="fas fa-edit" style="font-size: 0.75rem;"></i>
                                            </a>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 32px; height: 32px;" title="Hapus">
                                                    <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card border bg-white shadow-sm" style="border-radius: 8px;">
                        <div class="card-body p-3 text-center py-4">
                            <p class="text-muted mb-2">Belum ada tugas di kelas ini.</p>
                            @if($isOwner)
                                <a href="{{ route('tasks.create', $class) }}" class="btn btn-primary btn-sm rounded-pill">
                                    <i class="fas fa-plus me-1"></i> Buat Tugas Pertama
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ===== ANNOUNCEMENTS TAB ===== -->
        <div class="tab-pane fade" id="announcements-panel" role="tabpanel">
            @include('lecturer.announcements.index', ['class' => $class])
        </div>

        <!-- ===== PEOPLE TAB ===== -->
        <div class="tab-pane fade" id="people-panel" role="tabpanel">
            
            <div class="mb-4">
                <h4 class="text-primary border-bottom pb-2 mb-3" style="font-weight: 400; font-size: 1.4rem; border-color: {{ $themeColor }} !important;">Pengajar</h4>
                <div class="d-flex align-items-center gap-3 py-2 px-1">
                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 32px; height: 32px; background-color: #512da8; font-size: 0.85rem;">
                        {{ Str::upper(Str::substr($class->owner->fullname ?? 'D', 0, 1)) }}
                    </div>
                    <span class="text-dark small fw-medium">{{ $class->owner->fullname }}</span>
                    @if($isOwner)
                        <span class="badge bg-primary ms-2">Pemilik</span>
                    @endif
                </div>
            </div>

            <div>
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3" style="border-color: #dadce0;">
                    <h4 class="mb-0" style="font-weight: 400; font-size: 1.4rem; color: {{ $themeColor }};">Teman Sekelas</h4>
                    <span class="text-secondary small">{{ $class->participants->count() }} mahasiswa</span>
                </div>

                @if($class->participants->count() > 0)
                    <div class="d-flex flex-column divide-y">
                        @foreach($class->participants as $participant)
                            <div class="d-flex align-items-center gap-3 py-2-5 px-1 border-bottom border-light">
                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" 
                                     style="width: 32px; height: 32px; background-color: #0288d1; font-size: 0.85rem;">
                                    {{ Str::upper(Str::substr($participant->fullname ?? 'M', 0, 1)) }}
                                </div>
                                <span class="text-dark small">{{ $participant->fullname }}</span>
                                @if($participant->id === $user->id)
                                    <span class="badge bg-secondary ms-2">Anda</span>
                                @endif
                                <span class="text-muted ms-auto small" style="font-size: 0.7rem;">
                                    Bergabung {{ \Carbon\Carbon::parse($participant->pivot->joined_at)->format('d M Y') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4 small">Belum ada peserta yang bergabung di kelas ini.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .gc-tabs .nav-link {
        color: #5f6368;
        font-weight: 500;
        font-size: 0.9rem;
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.15s ease-in-out;
        border-radius: 0;
        padding: 0.6rem 0.75rem;
    }
    @media (min-width: 576px) {
        .gc-tabs .nav-link {
            padding: 0.6rem 1rem;
        }
    }
    .gc-tabs .nav-link:hover {
        color: #202124;
        background: transparent;
        border-bottom-color: #dadce0;
    }
    .gc-tabs .nav-link.active {
        color: {{ $themeColor }} !important;
        background: transparent;
        border-bottom-color: {{ $themeColor }} !important;
    }
    .py-2-5 {
        padding-top: 0.65rem !important;
        padding-bottom: 0.65rem !important;
    }
    .style-pointer {
        cursor: pointer;
        transition: background-color 0.15s;
    }
    .style-pointer:hover {
        background-color: #f8f9fa !important;
    }
    
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }
    .overflow-x-auto {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    @media (max-width: 767.98px) {
        .shadow-sm-mobile {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    }
    
    .btn-outline-primary.btn-sm.rounded-circle,
    .btn-outline-danger.btn-sm.rounded-circle {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50% !important;
    }
    
    @media (max-width: 576px) {
        .card-body.p-3 {
            padding: 0.75rem !important;
        }
        .d-flex.flex-wrap.align-items-center {
            gap: 0.5rem !important;
        }
        .text-truncate {
            max-width: 180px;
        }
    }
</style>
@endpush