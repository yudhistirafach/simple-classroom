@extends('layouts.main')

@section('title', 'Dashboard Dosen')

@section('content')
    <div>
        <i class="fa-solid fa-chalkboard-teacher fa-2x text-primary mb-4"></i>
        <h1 class="display-6 mb-3">Dosen</h1>
        <p class="text-muted">Selamat datang, {{ $user->fullname ?? ($user->name ?? $user->email) }}.</p>
    </div>
