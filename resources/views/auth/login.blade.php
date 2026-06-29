@extends('layouts.auth')

@section('title', 'Login')

@section('subtitle', 'Masuk ke akun Anda')

@section('content')
    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input id="password" type="password" class="form-control" name="password" required
                    autocomplete="current-password">
                <button class="btn btn-outline-secondary" type="button" data-password-toggle="password"
                    aria-label="Toggle password visibility">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </div>

        <p class="text-center text-muted mt-4 mb-0">
            Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar</a>
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
