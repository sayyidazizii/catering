@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
@endphp

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">👋 Selamat datang, {{ Auth::user()->name }}!</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <strong>📇 Informasi Profil</strong>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="bi bi-person-circle me-1"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">

            @if($user && $user->hasRole('merchant'))

            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <strong>⚡ Akses Cepat</strong>
                </div>
                <div class="card-body">
                    <a href="{{ route('merchant.menu.index') }}" class="btn btn-outline-success w-100 mb-2">
                        <i class="bi bi-list-ul me-1"></i>Kelola Menu
                    </a>
                    <a href="{{ route('merchant.profile.edit') }}" class="btn btn-outline-info w-100">
                        <i class="bi bi-shop me-1"></i>Profil Merchant
                    </a>
                </div>
            </div>
            @endif

            @if($user && $user->hasRole('customer'))

            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <strong>🍽️ Menu Utama</strong>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-outline-warning w-100 mb-2">
                        <i class="bi bi-book me-1"></i>Lihat Menu Catering
                    </a>
                    <a href="#" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-clock-history me-1"></i>Riwayat Pesanan
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>

    <div class="mt-5 text-muted text-center">
        <small>Laravel {{ Illuminate\Foundation\Application::VERSION }} </small>
    </div>
</div>
@endsection
