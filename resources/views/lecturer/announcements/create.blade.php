@extends('layouts.main')

@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman Baru')
@section('page-subtitle', 'Untuk kelas: ' . $class->name)

@section('content')
<div class="container-fluid d-flex justify-content-center mt-4">
    <div class="card border-0 shadow-sm" style="max-width: 600px; width: 100%; border-radius: 8px;">
        <div class="card-body p-4 p-md-5">
            <h4 class="mb-4 text-dark" style="font-weight: 500;">Buat Pengumuman</h4>
            <p class="text-muted small mb-4">Tambahkan pengumuman baru untuk kelas <strong>{{ $class->name }}</strong></p>
            
            <form action="{{ route('announcements.store', $class) }}" method="POST">
                @csrf

                <div class="form-floating mb-4">
                    <input type="text" 
                           class="form-control gc-input @error('title') is-invalid @enderror" 
                           id="title" name="title" 
                           placeholder="Judul Pengumuman (wajib)" 
                           value="{{ old('title') }}" required>
                    <label for="title" class="text-secondary">Judul Pengumuman (wajib)</label>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <textarea class="form-control gc-input @error('description') is-invalid @enderror" 
                              id="description" name="description" 
                              placeholder="Deskripsi" 
                              style="height: 150px">{{ old('description') }}</textarea>
                    <label for="description" class="text-secondary">Deskripsi</label>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input type="date" 
                           class="form-control gc-input @error('expired_at') is-invalid @enderror" 
                           id="expired_at" name="expired_at" 
                           value="{{ old('expired_at') }}">
                    <label for="expired_at" class="text-secondary">Tanggal Kadaluarsa (opsional)</label>
                    @error('expired_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Kosongkan jika pengumuman tidak memiliki batas waktu.</small>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('classes.show', $class) }}" class="btn text-secondary fw-medium text-decoration-none py-2 px-3">Batal</a>
                    <button type="submit" class="btn btn-primary fw-medium px-4 py-2" style="border-radius: 4px;">Buat Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .gc-input {
        background-color: #f1f3f4;
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 4px 4px 0 0;
        transition: all 0.2s ease-in-out;
    }
    .gc-input:focus {
        background-color: #e8eaed;
        border-bottom: 2px solid #0d6efd;
        box-shadow: none;
    }
    .gc-input:hover:not(:focus) {
        background-color: #e8eaed;
        border-bottom: 2px solid #5f6368;
    }
    .form-floating > label {
        color: #5f6368;
    }
</style>
@endsection