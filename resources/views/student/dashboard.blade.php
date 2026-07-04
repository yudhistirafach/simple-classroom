@extends('layouts.main')

@section('title', 'Beranda')
@section('page-title', 'Beranda')
@section('page-subtitle', 'Dashboard')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3">

    <div class="card border-0 shadow-sm mb-4 position-relative" style="border-radius: 8px; overflow: hidden; min-height: 140px;">
        <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, #1a73e8, #1557b0); z-index: 1;"></div>
        
        <div class="card-body position-relative d-flex flex-column justify-content-end p-4 p-md-5 z-3" style="z-index: 2;">
            <h2 class="text-white fw-medium mb-1" style="letter-spacing: 0.3px;">Selamat datang kembali, {{ auth()->user()->fullname ?? 'Pengguna' }}!</h2>
            <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                Pantau jadwal kelas dan tenggat waktu tugas Anda hari ini.
            </p>
        </div>
    </div>

    <div class="alert bg-white border shadow-sm d-flex align-items-center mb-4 py-3" style="border-radius: 8px; border-left: 4px solid #1a73e8 !important;" role="alert">
        <i class="fas fa-info-circle fs-5 text-primary me-3"></i>
        <div class="text-secondary" style="font-size: 0.9rem;">
                Pastikan Anda menyelesaikan tugas sebelum (<i>deadline</i>).
        </div>
    </div>

    <div class="row g-3 g-md-4">
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm gc-stat-card" style="border-radius: 8px;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #e8f0fe; color: #1a73e8;">
                        <i class="fas fa-chalkboard-teacher fs-4"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-0 fw-medium" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Kelas Diikuti</p>
                        <h2 class="mb-0 fw-bold text-dark lh-1 mt-1">{{ $totalClasses ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm gc-stat-card" style="border-radius: 8px;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #fce8e6; color: #d93025;">
                        <i class="fas fa-clipboard-list fs-4"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-0 fw-medium" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Tugas Mendatang</p>
                        <h2 class="mb-0 fw-bold text-dark lh-1 mt-1">{{ $totalUpcomingTasks ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm gc-stat-card" style="border-radius: 8px;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #fef7e0; color: #f9ab00;">
                        <i class="fas fa-bullhorn fs-4"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-0 fw-medium" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Pengumuman</p>
                        <h2 class="mb-0 fw-bold text-dark lh-1 mt-1">{{ $totalAnnouncements ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .gc-stat-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .gc-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(60,64,67,0.12) !important;
    }
</style>
@endpush