@extends('layouts.app')

@section('title', 'Feed - ConnectSpace')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">

        {{-- Create Post Card --}}
        <div class="card mb-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" id="create-post-form">
                    @csrf
                    <div class="d-flex align-items-start mb-3">
                        <a href="{{ route('profile') }}" class="text-decoration-none" title="Lihat Profil Saya">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="avatar-md me-3 mt-1">
                            @else
                                <span class="avatar-placeholder avatar-md me-3 mt-1">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </span>
                            @endif
                        </a>
                        <div class="flex-grow-1">
                            {{-- Textarea (disembunyikan saat memilih foto) --}}
                            <div id="post-text-area">
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          name="content" id="post-content-textarea" rows="3"
                                          placeholder="Apa yang sedang Anda pikirkan, {{ Auth::user()->name }}?">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Post Image Preview + Input Caption (muncul saat memilih foto) --}}
                            <div id="post-image-preview" class="d-none">
                                <div class="preview-box">
                                    <div class="position-relative d-inline-block">
                                        <img id="post-image-preview-img" src="" alt="Preview" class="rounded" style="max-height: 260px; max-width: 100%; border: 1px solid var(--border-color);">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle p-0 d-flex align-items-center justify-content-center"
                                                style="width: 26px; height: 26px;"
                                                onclick="removePostImage()" title="Hapus foto">
                                            <i class="bi bi-x-lg" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <input type="text" class="form-control form-control-sm @error('caption') is-invalid @enderror"
                                               id="post-caption-input" name="caption"
                                               placeholder="Tambahkan caption gambar...">
                                        @error('caption')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-subtle">
                        <div>
                            <label class="btn btn-outline-secondary btn-sm mb-0 d-inline-flex align-items-center" for="post-image" style="cursor: pointer;">
                                <i class="bi bi-image text-accent me-1"></i> Foto
                            </label>
                            <input type="file" class="d-none" id="post-image" name="image" accept="image/jpg,image/jpeg,image/png"
                                   onchange="previewPostImage(this)">
                            <span id="post-image-name" class="text-muted small ms-2"></span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span id="post-char-count" class="text-muted small">0/500</span>
                            <button type="submit" id="post-submit-btn" class="btn btn-primary btn-sm px-3 d-inline-flex align-items-center">
                                <i class="bi bi-send-fill me-1"></i> Posting
                            </button>
                        </div>
                    </div>
                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </form>
            </div>
        </div>

        {{-- Posts --}}
        @forelse ($posts as $post)
            @include('components.post-card', ['post' => $post])
        @empty
            <div class="card p-5 text-center my-4">
                <div class="mb-3">
                    <span class="brand-badge p-3" style="width: 60px; height: 60px; font-size: 1.8rem; border-radius: 16px;">
                        <i class="bi bi-chat-heart"></i>
                    </span>
                </div>
                <h5 class="fw-bold mb-1">Belum Ada Postingan</h5>
                <p class="text-muted mb-0 small">Jadilah yang pertama untuk membagikan status atau foto hari ini!</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if ($posts->hasPages())
            <div class="d-flex justify-content-center mt-4 mb-5">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updatePostCharCount() {
        const textarea = document.getElementById('post-content-textarea');
        const captionInput = document.getElementById('post-caption-input');
        const imagePreview = document.getElementById('post-image-preview');
        const submitBtn = document.getElementById('post-submit-btn');
        const charCountEl = document.getElementById('post-char-count');

        if (!submitBtn || !charCountEl) return;

        const isImageMode = imagePreview && !imagePreview.classList.contains('d-none');
        const activeInput = isImageMode ? captionInput : textarea;
        const length = activeInput ? activeInput.value.length : 0;
        const maxChars = 500;

        if (length > maxChars) {
            submitBtn.disabled = true;
            charCountEl.className = 'small text-danger fw-bold';
            charCountEl.textContent = `${length}/${maxChars} (Maks. 500)`;
        } else {
            submitBtn.disabled = false;
            charCountEl.className = 'small text-muted';
            charCountEl.textContent = `${length}/${maxChars}`;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('post-content-textarea');
        const captionInput = document.getElementById('post-caption-input');
        const postForm = document.getElementById('create-post-form');

        if (textarea) {
            textarea.addEventListener('input', updatePostCharCount);
            textarea.addEventListener('keyup', updatePostCharCount);
        }

        if (captionInput) {
            captionInput.addEventListener('input', updatePostCharCount);
            captionInput.addEventListener('keyup', updatePostCharCount);
        }

        if (postForm) {
            postForm.addEventListener('submit', function (e) {
                const imagePreview = document.getElementById('post-image-preview');
                const isImageMode = imagePreview && !imagePreview.classList.contains('d-none');
                const activeInput = isImageMode ? captionInput : textarea;
                const length = activeInput ? activeInput.value.length : 0;

                if (length > 500) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        updatePostCharCount();
    });
</script>
@endpush
