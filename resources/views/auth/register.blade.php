@extends('layouts.auth')

@section('title', 'Register')

@section('subtitle', 'Buat akun baru')

@section('content')
    <form method="POST" action="{{ route('register.process') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required
                autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input id="password" type="password" class="form-control" name="password" required
                    autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" data-password-toggle="password"
                    aria-label="Toggle password visibility">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="input-group">
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" data-password-toggle="password_confirmation"
                    aria-label="Toggle confirmation password visibility">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3 mt-4">
            <label class="form-label">Peran Anda</label>
            <div class="btn-group w-100" role="group" aria-label="Pilih peran">
                <input type="radio" class="btn-check" name="role" id="role_student" value="student" autocomplete="off"
                    {{ old('role') == 'student' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary" for="role_student">Mahasiswa</label>

                <input type="radio" class="btn-check" name="role" id="role_lecturer" value="lecturer"
                    autocomplete="off" {{ old('role') == 'lecturer' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary" for="role_lecturer">Dosen</label>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Daftar</button>
        </div>

        <p class="text-center text-muted mt-4 mb-0">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Masuk</a>
        </p>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-password-toggle]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-password-toggle');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (!input || !icon) {
                        return;
                    }

                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    icon.classList.toggle('fa-eye', !isPassword);
                    icon.classList.toggle('fa-eye-slash', isPassword);
                });
            });
        });
    </script>
@endpush
