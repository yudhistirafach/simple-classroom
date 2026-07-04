@extends('layouts.main')

@section('title', 'Kelas Saya')
@section('page-title', 'Kelas Saya')

@section('content')
<div class="classes-index">
    <!-- Header -->
    <div class="classes-header">
        <div class="classes-header-left">
            <p class="classes-subtitle">Kelas yang Anda ikuti</p>
        </div>
        <div class="classes-header-right">
            <button type="button" class="btn btn-primary btn-join-class" data-bs-toggle="modal" data-bs-target="#joinModal">
                <i class="fas fa-plus-circle"></i>
                <span class="d-none d-sm-inline">Gabung Kelas</span>
                <span class="d-inline d-sm-none">Gabung</span>
            </button>
        </div>
    </div>

    <!-- Modal Join -->
    <div class="modal fade" id="joinModal" tabindex="-1">
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
                            <label for="join_code" class="form-label">Kode Kelas</label>
                            <input type="text" class="form-control form-control-lg" id="join_code" name="join_code" placeholder="Contoh: ABC123" required autocomplete="off">
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

    <!-- Grid Kelas -->
    @if($classes->count() > 0)
        <div class="classes-grid">
            @foreach($classes as $class)
                <x-class-card :class="$class" :index="$loop->index" />
            @endforeach
        </div>
    @else
        <div class="classes-empty">
            <div class="classes-empty-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h4 class="classes-empty-title">Belum Ada Kelas</h4>
            <p class="classes-empty-text">Gabung ke kelas dengan kode yang diberikan dosen</p>
            <button type="button" class="btn btn-primary btn-join-class" data-bs-toggle="modal" data-bs-target="#joinModal">
                <i class="fas fa-plus-circle"></i> Gabung Kelas
            </button>
        </div>
    @endif
</div>

<!-- Toast / Alert sudah ditangani di layout -->
@endsection

@push('styles')
<style>
    .classes-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }
    .classes-subtitle {
        color: #5f6368;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    .btn-join-class {
        background: #1a73e8;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.15s ease;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        min-height: 44px;
        min-width: 44px;
        justify-content: center;
    }
    .btn-join-class:hover {
        background: #1557b0;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.12);
        color: white;
    }
    .btn-join-class:active {
        transform: translateY(0);
    }
    .classes-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }
    @media (min-width: 576px) {
        .classes-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }
    @media (min-width: 992px) {
        .classes-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
    }
    @media (min-width: 1400px) {
        .classes-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    .classes-empty {
        text-align: center;
        padding: 40px 16px;
    }
    .classes-empty-icon {
        font-size: 3rem;
        color: #dadce0;
        margin-bottom: 12px;
    }
    .classes-empty-title {
        font-weight: 700;
        color: #5f6368;
        margin-bottom: 8px;
        font-size: 1.1rem;
    }
    .classes-empty-text {
        color: #5f6368;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }
</style>
@endpush