@extends('layouts.app')

@section('title', 'Edit Postingan - ConnectSpace')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card p-4 p-md-5">
            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-subtle">
                <a href="{{ route('feed') }}" class="btn btn-sm btn-outline-secondary me-3" title="Kembali ke Feed">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Edit Postingan</h4>
                    <p class="text-muted small mb-0">Perbarui teks atau gambar postingan Anda.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Isi Postingan --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="content" class="form-label mb-0">Isi / Caption Postingan</label>
                        <span id="edit-post-char-count" class="text-muted small">0/500</span>
                    </div>
                    <textarea class="form-control @error('content') is-invalid @enderror"
                              id="content" name="content" rows="4"
                              placeholder="Tulis isi atau caption postingan...">{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gambar Saat Ini
                @if ($post->image)
                    <div id="current-edit-post-image-box" class="mb-3">
                        <label class="form-label d-block">Gambar Saat Ini</label>
                        <div class="preview-box">
                            <img id="current-edit-post-image-img"
                                 src="{{ asset('storage/' . $post->image) }}"
                                 alt="Post image"
                                 class="rounded mb-2"
                                 style="max-height: 280px; max-width: 100%; border: 1px solid var(--border-color); transition: opacity 0.2s ease;"> --}}
                            {{-- <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox"
                                       id="remove_image" name="remove_image" value="1"
                                       {{ old('remove_image') ? 'checked' : '' }}
                                       onchange="toggleRemoveExistingImage(this, 'current-edit-post-image-img')">
                                <label class="form-check-label small text-danger fw-semibold" for="remove_image">
                                    <i class="bi bi-trash3 me-1"></i> Hapus gambar ini
                                </label>
                            </div> --}}
                        {{-- </div>
                    </div>
                @endif --}}

                {{-- Preview Gambar Baru --}}
                {{-- <div id="edit-post-image-preview" class="mb-3 d-none">
                    <label class="form-label d-block">Preview Gambar Baru</label>
                    <div class="preview-box position-relative d-inline-block">
                        <img id="edit-post-image-preview-img" src="" alt="Preview"
                             class="rounded"
                             style="max-height: 260px; max-width: 100%; border: 1px solid var(--border-color);">
                        <button type="button"
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle p-0 d-flex align-items-center justify-content-center"
                                style="width: 26px; height: 26px;"
                                onclick="removeEditImage('edit-post')" title="Batalkan gambar baru">
                            <i class="bi bi-x-lg" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>
                </div> --}}

                {{-- Input Ganti / Tambah Gambar --}}
                {{-- <div class="mb-4">
                    <label for="edit-post-image" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center" style="cursor: pointer;">
                        <i class="bi bi-image text-accent me-1"></i>
                        {{ $post->image ? 'Ganti Gambar' : 'Tambah Gambar' }}
                    </label>
                    <input type="file" class="d-none @error('image') is-invalid @enderror"
                           id="edit-post-image" name="image" accept="image/jpg,image/jpeg,image/png"
                           onchange="previewEditImage(this, 'edit-post')">
                    <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div> --}}

                <div class="d-flex gap-2 pt-3 border-top border-subtle">
                    <button type="submit" id="edit-post-submit-btn" class="btn btn-primary d-inline-flex align-items-center px-4">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('feed') }}" class="btn btn-secondary d-inline-flex align-items-center">
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
    function updateEditPostCharCount() {
        const textarea = document.getElementById('content');
        const submitBtn = document.getElementById('edit-post-submit-btn');
        const charCountEl = document.getElementById('edit-post-char-count');

        if (!textarea || !submitBtn || !charCountEl) return;

        const length = textarea.value.length;
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
        const textarea = document.getElementById('content');
        if (textarea) {
            textarea.addEventListener('input', updateEditPostCharCount);
            textarea.addEventListener('keyup', updateEditPostCharCount);
        }
        updateEditPostCharCount();
    });
</script>
@endpush
