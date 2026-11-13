@extends('layouts.app')

@section('title', 'Login - ConnectSpace')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-lg-5 col-md-7 col-sm-9">
        <!-- {{-- Brand Header --}} -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center brand-badge mb-3" style="width: 54px; height: 54px; font-size: 1.6rem; border-radius: 14px;">
                <i class="bi bi-person-fill"></i>
            </div>
            <h2 class="fw-bold mb-1">
                Connect<span class="text-accent">Space</span>
            </h2>
            <p class="text-muted small">Selamat datang kembali! Masuk untuk terhubung.</p>
        </div>

        <!-- {{-- Login Card --}} -->
        <div class="card p-4 p-md-5">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-color); color: var(--text-muted);">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-color); color: var(--text-muted);">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                               id="password" name="password" placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mb-3 d-flex align-items-center justify-content-center">
                    <span>Masuk ke Akun</span>
                    <i class="bi bi-arrow-right ms-2"></i>
                </button>

                <div class="text-center pt-3 border-top border-subtle">
                    <p class="text-muted small mb-0">
                        Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar sekarang</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
