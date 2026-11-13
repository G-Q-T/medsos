@extends('layouts.app')

@section('title', 'Ubah Password - ConnectSpace')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7 col-sm-9">
        <div class="card p-4 p-md-5">
            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-subtle">
                <a href="{{ route('profile') }}" class="btn btn-sm btn-outline-secondary me-3" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Ubah Password</h4>
                    <p class="text-muted small mb-0">Perbarui kata sandi akun Anda demi keamanan.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-color); color: var(--text-muted);">
                            <i class="bi bi-key"></i>
                        </span>
                        <input type="password" class="form-control border-start-0 @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" placeholder="Masukkan password saat ini" required autofocus>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password Baru --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-color); color: var(--text-muted);">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                               id="password" name="password" placeholder="Minimal 6 karakter" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">Gunakan kombinasi minimal 6 karakter.</div>
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-color); color: var(--text-muted);">
                            <i class="bi bi-shield-check"></i>
                        </span>
                        <input type="password" class="form-control border-start-0"
                               id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
                    </div>
                </div>

                <div class="d-flex gap-2 pt-2 border-top border-subtle">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center px-4">
                        <i class="bi bi-check-lg me-1"></i> Simpan Password
                    </button>
                    <a href="{{ route('profile') }}" class="btn btn-secondary d-inline-flex align-items-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
