@extends('layouts.main')

@section('title', 'Kelas Saya')
@section('page-title', 'Kelas Saya')

@section('content')
    @php
        $user = auth()->user();
    @endphp

    <div class="container-fluid px-3 px-md-4 py-3">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <div>
                <p class="text-secondary mb-0" style="font-size: 0.875rem; letter-spacing: 0.2px;">
                    @if ($user && $user->role === 'lecturer')
                        Mengelola semua kelas kuliah yang Anda ampu
                    @else
                        Daftar ruang kelas yang Anda ikuti semester ini
                    @endif
                </p>
            </div>

            @if ($user && $user->role === 'lecturer')
                <div class="d-block d-md-none">
                    <a href="{{ route('classes.create') }}"
                        class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 40px; height: 40px;" title="Buat Kelas">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            @endif
        </div>

        @if ($classes->count() > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-3 g-md-4">
                @foreach ($classes as $class)
                    <div class="col">
                        <div class="card h-100 gc-card border shadow-sm position-relative"
                            style="border-radius: 8px; overflow: hidden; transition: box-shadow 0.2s ease-in-out;">

                            @php
                                $bgColors = ['#1a73e8', '#1e8e3e', '#f9ab00', '#d93025', '#8e24aa', '#00acc1'];
                                $currentBg = $bgColors[$loop->index % count($bgColors)];
                            @endphp
                            <div class="p-3 text-white position-relative d-flex flex-column justify-content-between"
                                style="background-color: {{ $currentBg }}; height: 100px;">
                                <div class="pe-5">
                                    <a href="{{ route('classes.show', $class) }}"
                                        class="text-white text-decoration-none fw-medium fs-5 lh-sm d-block text-truncate gc-card-title"
                                        title="{{ $class->name }}">
                                        {{ $class->name }}
                                    </a>
                                    <small class="text-white-50 d-block text-truncate mt-1" style="font-size: 0.8rem;">
                                        {{ $class->description ?? 'Tidak ada deskripsi' }}
                                    </small>
                                </div>

                                <small class="text-white-50 text-truncate"
                                    style="font-size: 0.8rem; font-weight: 400; max-width: 75%;">
                                    {{ $class->owner->fullname ?? $user->fullname }}
                                </small>
                            </div>

                            <div class="position-absolute" style="right: 16px; top: 72px; z-index: 10;">
                                <div class="rounded-circle border border-white border-2 text-white fw-bold d-flex align-items-center justify-content-center shadow"
                                    style="width: 52px; height: 52px; background-color: #7b1fa2; font-size: 1.1rem;">
                                    {{ Str::upper(Str::substr($class->owner->fullname ?? 'U', 0, 1)) }}
                                </div>
                            </div>

                            <div class="card-body p-3 d-flex flex-column justify-content-between"
                                style="min-height: 120px; background-color: #ffffff;">
                                <div class="text-muted small">
                                    @if ($class->schedule_day)
                                        @php
                                            $schedules = is_array($class->schedule_day)
                                                ? $class->schedule_day
                                                : json_decode($class->schedule_day, true);
                                        @endphp
                                        @if (is_array($schedules) && count($schedules) > 0)
                                            <div class="mb-1 text-truncate">
                                                <i class="far fa-calendar-alt me-1 text-secondary"></i>
                                                @foreach ($schedules as $day => $time)
                                                    <span
                                                        class="text-capitalize text-dark fw-medium">{{ $day }}</span>
                                                    ({{ $time }})
                                                    {{ !$loop->last ? ',' : '' }}
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif

                                    <div class="mt-3 pt-2 border-top text-truncate" style="font-size: 0.775rem;">
                                        <span class="text-secondary">Tenggat terdekat:</span>
                                        @php
                                            $nearestTask = $class->tasks->first();
                                        @endphp
                                        @if ($nearestTask)
                                            <span class="text-danger fw-medium ms-1">
                                                {{ \Carbon\Carbon::parse($nearestTask->deadline_at)->diffForHumans() }}
                                                ({{ \Carbon\Carbon::parse($nearestTask->deadline_at)->format('H:i') }})
                                            </span>
                                        @else
                                            <span class="text-muted fw-medium ms-1">Tidak ada tugas</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-top-0 px-3 py-2 d-flex justify-content-end gap-1">
                                <a href="{{ route('classes.show', $class) }}#tasks"
                                    class="btn btn-link text-secondary p-2 rounded-circle border-0 d-flex align-items-center justify-content-center"
                                    style="width: 36px; height: 36px;" title="Buka Tugas Kuliah">
                                    <i class="fas fa-clipboard-list fs-6"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="d-flex flex-column align-items-center justify-content-center text-center my-5 py-5">
                <div class="mb-4 text-muted opacity-25">
                    <i class="fas fa-graduation-cap" style="font-size: 5rem;"></i>
                </div>
                <h5 class="text-dark fw-medium mb-2">Belum ada kelas yang terdaftar</h5>
                <p class="text-secondary mx-auto mb-4 small" style="max-width: 360px;">
                    @if ($user && $user->role === 'lecturer')
                        Mulai bagikan ilmu dengan menekan tombol di bawah untuk menyusun kurikulum kelas baru Anda.
                    @else
                        Mintalah kode akses kelas (`join_code`) dari dosen pengampu Anda untuk bergabung ke dalam
                        perkuliahan.
                    @endif
                </p>
                @if ($user && $user->role === 'lecturer')
                    <a href="{{ route('classes.create') }}" class="btn btn-primary fw-medium px-4 py-2"
                        style="border-radius: 4px; font-size: 0.9rem;">
                        <i class="fas fa-plus me-2"></i>Buat Kelas Pertama
                    </a>
                @else
                    <button type="button" class="btn btn-outline-primary fw-medium px-4 py-2"
                        style="border-radius: 4px; font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#joinModal">
                        <i class="fas fa-sign-in-alt me-2"></i>Gabung ke Kelas
                    </button>
                @endif
            </div>
        @endif
    </div>

    <div class="modal fade" id="joinModal" tabindex="-1" aria-hidden="true">
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
                            <input type="text" class="form-control form-control-lg" id="join_code_modal" name="join_code"
                                placeholder="Contoh: ABC123" required autocomplete="off">
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
@endsection

@push('styles')
    <style>
        .gc-card:hover {
            box-shadow: 0 4px 12px rgba(60, 64, 67, 0.15) !important;
        }

        .gc-card-title:hover {
            text-decoration: underline !important;
        }

        .btn-link:hover {
            background-color: rgba(95, 99, 104, 0.08);
            color: #1a73e8 !important;
        }
    </style>
@endpush
