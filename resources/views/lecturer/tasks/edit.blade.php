@extends('layouts.main')

@section('title', 'Edit Tugas')
@section('page-title', 'Edit Tugas')
@section('page-subtitle', 'Untuk kelas: ' . $class->name)

@section('content')
<div class="container-fluid d-flex justify-content-center mt-4">
    <div class="card border-0 shadow-sm" style="max-width: 600px; width: 100%; border-radius: 8px;">
        <div class="card-body p-4 p-md-5">
            <h4 class="mb-4 text-dark" style="font-weight: 500;">Edit Tugas</h4>
            <p class="text-muted small mb-4">Perbarui informasi tugas di kelas <strong>{{ $class->name }}</strong></p>
            
            <form action="{{ route('tasks.update', $task) }}" method="POST" id="editTaskForm">
                @csrf
                @method('PUT')

                <div class="form-floating mb-4">
                    <input type="text" 
                           class="form-control gc-input @error('title') is-invalid @enderror" 
                           id="title" name="title" 
                           placeholder="Judul Tugas (wajib)" 
                           value="{{ old('title', $task->title) }}" required>
                    <label for="title" class="text-secondary">Judul Tugas (wajib)</label>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input type="text" 
                        class="form-control gc-input datetime-picker @error('deadline_at') is-invalid @enderror" 
                        id="deadline_at" name="deadline_at" 
                        placeholder="Pilih tanggal dan waktu" 
                        value="{{ old('deadline_at', $task->deadline_at->format('Y-m-d H:i')) }}" required>
                    <label for="deadline_at" class="text-secondary">Deadline (wajib)</label>
                    @error('deadline_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Pilih tanggal dan waktu deadline (minimal setelah sekarang).</small>
                </div>

                <div class="form-floating mb-4">
                    <input type="datetime-local" 
                        class="form-control gc-input @error('deadline_at') is-invalid @enderror" 
                        id="deadline_at" name="deadline_at" 
                        value="{{ old('deadline_at', $task->deadline_at->format('Y-m-d\TH:i')) }}" required>
                    <label for="deadline_at" class="text-secondary">Deadline (wajib)</label>
                    @error('deadline_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: Tanggal dan waktu (minimal setelah sekarang)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary">Status Saat Ini</label>
                    <div>
                        <span class="badge {{ $task->deadline_at->isPast() ? 'bg-danger' : 'bg-success' }} rounded-pill fs-6 px-3 py-2">
                            {{ $task->deadline_at->isPast() ? 'Expired' : 'Active' }}
                        </span>
                        <small class="text-muted ms-2">(Status dihitung otomatis berdasarkan deadline)</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('classes.show', $class) }}" class="btn text-secondary fw-medium text-decoration-none py-2 px-3">Batal</a>
                    <button type="submit" class="btn btn-primary fw-medium px-4 py-2" style="border-radius: 4px;">Perbarui Tugas</button>
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