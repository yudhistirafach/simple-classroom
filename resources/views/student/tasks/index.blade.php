@extends('layouts.main')

@section('title', 'Daftar Tugas')

@section('content')
<div class="container-fluid px-2 px-md-4 py-4" style="max-width: 800px;">
    
    <!-- Header Halaman ala Google Classroom -->
    <div class="d-flex align-items-center mb-4 px-2">
        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
            <i class="fas fa-clipboard-list text-primary fs-4"></i>
        </div>
        <div>
            <h3 class="fw-normal mb-0 text-dark" style="letter-spacing: -0.3px;">Daftar Tugas</h3>
            <p class="text-secondary small mb-0">Tenggat waktu terdekat yang perlu Anda selesaikan</p>
        </div>
    </div>

    @if($tasks->count() > 0)
        <!-- Grid List Tugas -->
        <div class="d-flex flex-column gap-2">
            @foreach($tasks as $task)
                <!-- Kartu dibungkus dengan tag <a> agar seluruh area dapat diklik -->
                <a href="{{ route('classes.show', $task->class) }}" class="card gc-task-card text-decoration-none">
                    <div class="card-body p-3 d-flex align-items-sm-center flex-column flex-sm-row gap-3">
                        
                        <!-- Bagian Kiri: Ikon & Informasi Utama -->
                        <div class="d-flex align-items-start align-items-sm-center gap-3 flex-grow-1 min-w-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" 
                                 style="width: 40px; height: 40px; background-color: #1a73e8;">
                                <i class="fas fa-file-alt fs-5"></i>
                            </div>
                            <div class="text-truncate" style="min-width: 0;">
                                <h6 class="text-dark mb-1 fw-medium text-truncate" style="font-size: 0.95rem;">
                                    {{ $task->title }}
                                </h6>
                                <div class="text-secondary small text-truncate">
                                    {{ $task->class->name }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bagian Kanan: Tenggat Waktu & Status (Otomatis turun di layar kecil) -->
                        <div class="d-flex flex-row flex-sm-column align-items-center align-items-sm-end justify-content-between flex-shrink-0 ms-sm-3 mt-2 mt-sm-0 pt-2 pt-sm-0 border-top border-sm-0">
                            <span class="text-danger fw-medium mb-sm-1" style="font-size: 0.8rem;">
                                Tenggat: {{ $task->deadline_at->format('d M H:i') }}
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium" style="font-size: 0.7rem; border: 1px solid rgba(25,135,84,0.2);">
                                Active
                            </span>
                        </div>

                    </div>
                </a>
            @endforeach
        </div>
    @else
        <!-- Empty State ala Google UI -->
        <div class="d-flex flex-column align-items-center justify-content-center text-center py-5 mt-4">
            <div class="mb-4 text-muted opacity-25">
                <i class="fas fa-clipboard-check" style="font-size: 6rem;"></i>
            </div>
            <h5 class="text-dark fw-medium mb-2">Hore, tidak ada tugas!</h5>
            <p class="text-secondary small">Semua tugas Anda sudah selesai atau belum ada tugas baru yang diberikan.</p>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .gc-task-card {
        border: 1px solid #dadce0;
        border-radius: 8px;
        background-color: #ffffff;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }
    
    .gc-task-card:hover {
        background-color: #f8f9fa;
        border-color: #c3c7cb;
    }
    
    @media (min-width: 576px) {
        .border-sm-0 { 
            border: none !important; 
        }
    }
</style>
@endpush