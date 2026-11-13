@extends('layouts.app')

@section('title', 'Edit Profil - ConnectSpace')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card p-4 p-md-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-subtle">
                <a href="{{ route('profile') }}" class="btn btn-sm btn-outline-secondary me-3" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Edit Profil</h4>
                    <p class="text-muted small mb-0">Perbarui identitas dan foto profil akun Anda.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Avatar Section --}}
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block mb-3">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" id="avatar-preview-img" alt="Avatar" class="avatar-lg">
                        @else
                            <span class="avatar-placeholder avatar-lg" id="avatar-placeholder">
                                <i class="bi bi-person-fill" style="font-size: 3.5rem;"></i>
                            </span>
                            <img src="" id="avatar-preview-img" alt="Avatar" class="avatar-lg d-none">
                        @endif
                    </div>

                    <div>
                        <label for="avatar" class="btn btn-outline-secondary btn-sm mb-1 d-inline-flex align-items-center" style="cursor: pointer;">
                            <i class="bi bi-camera text-accent me-1"></i> Pilih Foto Baru
                        </label>
                        <input type="file" class="d-none @error('avatar') is-invalid @enderror"
                               id="avatar" name="avatar" accept="image/jpg,image/jpeg,image/png"
                               onchange="previewProfileAvatar(this)">
                        <div class="form-text">Mendukung format JPG, JPEG, PNG. Maksimal 2MB.</div>
                        @error('avatar')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Bio --}}
                <div class="mb-4">
                    <label for="bio" class="form-label">Bio / Deskripsi Singkat</label>
                    <textarea class="form-control @error('bio') is-invalid @enderror"
                              id="bio" name="bio" rows="3" maxlength="500"
                              placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                    <div class="form-text">Maksimal 500 karakter.</div>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 pt-2 border-top border-subtle">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center px-4">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
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

@push('scripts')
<script>
    function previewProfileAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('avatar-preview-img');
                const placeholder = document.getElementById('avatar-placeholder');
                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('d-none');
                }
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
