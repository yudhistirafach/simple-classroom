@extends('layouts.main')

@section('title', 'Pengumuman Terbaru')
@section('page-title', 'Pengumuman Terbaru')
@section('page-subtitle', 'Semua pengumuman dari kelas yang Anda ikuti')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3">
    @if($announcements->count() > 0)
        <div class="announcements-list">
            @foreach($announcements as $announcement)
                <div class="card border-0 shadow-sm mb-4 announcement-card">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-start gap-3 mb-2">
                            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background-color: #7b1fa2; font-size: 0.9rem; flex-shrink: 0;">
                                {{ Str::upper(Str::substr($announcement->class->owner->fullname ?? 'D', 0, 1)) }}
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="text-dark fw-medium">{{ $announcement->class->owner->fullname }}</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">
                                        • {{ $announcement->created_at->diffForHumans() }}
                                    </span>
                                    <span class="badge bg-primary rounded-pill" style="font-size: 0.65rem;">
                                        {{ $announcement->class->name }}
                                    </span>
                                </div>
                                <h6 class="mb-1 fw-semibold mt-1">{{ $announcement->title }}</h6>
                            </div>
                        </div>
                        
                        @if($announcement->description)
                            <p class="text-muted mb-2 announcement-description">{{ $announcement->description }}</p>
                        @endif
                        
                        <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
                            @if($announcement->expired_at)
                                <span>
                                    <i class="far fa-calendar-alt me-1"></i>
                                    Berakhir: {{ $announcement->expired_at->format('d M Y') }}
                                </span>
                            @endif
                            <a href="{{ route('classes.show', $announcement->class) }}" class="text-primary text-decoration-none small fw-medium">
                                Lihat Kelas <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="d-flex flex-column align-items-center justify-content-center text-center my-5 py-5">
            <div class="mb-4 text-muted opacity-25">
                <i class="fas fa-bullhorn" style="font-size: 5rem;"></i>
            </div>
            <h5 class="text-dark fw-medium mb-2">Belum Ada Pengumuman</h5>
            <p class="text-secondary mx-auto small" style="max-width: 360px;">
                Pengumuman dari dosen Anda akan muncul di sini.
            </p>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .announcement-card {
        border-radius: 8px;
        transition: box-shadow 0.2s ease-in-out;
    }
    .announcement-card:hover {
        box-shadow: 0 4px 12px rgba(60,64,67,0.12) !important;
    }
    .announcement-description {
        font-size: 0.9rem;
        line-height: 1.6;
        color: #3c4043;
        white-space: pre-wrap;
    }
</style>
@endpush