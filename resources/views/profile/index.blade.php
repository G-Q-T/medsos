@extends('layouts.app')

@section('title', ($isOwnProfile ? 'Profil Saya' : 'Profil ' . $user->name) . ' - ConnectSpace')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        {{-- Profile Header Card --}}
        <div class="card mb-4 overflow-hidden">
            <div style="height: 90px; background: linear-gradient(135deg, rgba(41, 199, 30, 0.3) 0%, rgba(25, 26, 24, 0.8) 100%); border-bottom: 1px solid var(--border-color);"></div>
            <div class="card-body px-4 pb-4 pt-0 text-center" style="margin-top: -55px;">
                <div class="mb-3 d-inline-block">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-lg">
                    @else
                        <span class="avatar-placeholder avatar-lg">
                            <i class="bi bi-person-fill" style="font-size: 3.5rem;"></i>
                        </span>
                    @endif
                </div>

                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-2">
                    <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                </p>

                @if ($user->bio)
                    <p class="mb-3 mx-auto" style="max-width: 500px; line-height: 1.6; font-size: 0.95rem;">{{ $user->bio }}</p>
                @else
                    <p class="text-muted small fst-italic mb-3">Belum ada bio singkat.</p>
                @endif

                <div class="d-flex justify-content-center gap-2 mb-2 flex-wrap">
                    @if ($isOwnProfile)
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center px-3">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profil
                        </a>
                        <a href="{{ route('password.edit') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center px-3">
                            <i class="bi bi-key me-1"></i> Ubah Password
                        </a>
                    @else
                        <a href="{{ route('feed') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center px-3">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Feed
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- User's Posts Section --}}
        <div class="d-flex align-items-center justify-content-between mb-3 px-1">
            <h5 class="fw-bold mb-0 d-flex align-items-center">
                <i class="bi bi-grid-fill text-accent me-2"></i> {{ $isOwnProfile ? 'Postingan Saya' : 'Postingan ' . $user->name }}
            </h5>
            <span class="badge rounded-pill text-dark" style="background-color: var(--color-primary); font-size: 0.8rem;">
                {{ $posts->count() }} Post
            </span>
        </div>

        @forelse ($posts as $post)
            @include('components.post-card', ['post' => $post])
        @empty
            <div class="card p-5 text-center my-3">
                <div class="mb-3">
                    <span class="brand-badge p-3" style="width: 54px; height: 54px; font-size: 1.5rem; border-radius: 14px;">
                        <i class="bi bi-journal-plus"></i>
                    </span>
                </div>
                @if ($isOwnProfile)
                    <h6 class="fw-bold mb-1">Belum Ada Postingan</h6>
                    <p class="text-muted small mb-3">Ceritakan momen seru atau bagikan foto Anda sekarang.</p>
                    <div>
                        <a href="{{ route('feed') }}" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-plus-lg me-1"></i> Buat Postingan Baru
                        </a>
                    </div>
                @else
                    <h6 class="fw-bold mb-1">Belum Ada Postingan</h6>
                    <p class="text-muted small mb-0">{{ $user->name }} belum membagikan postingan apa pun.</p>
                @endif
            </div>
        @endforelse
    </div>
</div>
@endsection
