@extends('layouts.main')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div>
        <i class="fa-solid fa-user-graduate fa-2x text-primary mb-4"></i>
        <h1 class="display-6 mb-3">Mahasiswa</h1>
        <p class="text-muted">Selamat datang, {{ $user->fullname ?? ($user->name ?? $user->email) }}.</p>
    </div>
