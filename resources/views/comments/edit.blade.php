@extends('layouts.app')

@section('title', 'Edit Komentar - ConnectSpace')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card p-4 p-md-5">
            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-subtle">
                <a href="{{ route('feed') }}" class="btn btn-sm btn-outline-secondary me-3" title="Kembali ke Feed">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Edit Komentar</h4>
                    <p class="text-muted small mb-0">Perbarui teks atau gambar komentar Anda.</p>
                </div>
            </div>

            {{-- Postingan yang Dikomentari --}}
            <div class="comment-item mb-4">
                <div class="small fw-bold mb-1">
                    <i class="bi bi-reply-fill text-accent me-1"></i>
                    Mengomentari postingan {{ $comment->post->user->name }}
                </div>
                <p class="text-muted small mb-0 text-truncate">
                    {{ $comment->post->content ?: 'Postingan gambar' }}
                </p>
            </div>

            <form method="POST" action="{{ route('comments.update', $comment) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Isi Komentar --}}
                <div class="mb-3">
                    <label for="comment" class="form-label">Isi Komentar</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror"
                              id="comment" name="comment" rows="3"
                              placeholder="Tulis komentar...">{{ old('comment', $comment->comment) }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Keterangan Lokasi --}}
                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between align-items-center mb-1">
                        <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> Lokasi Komentar (Opsional)</span>
                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2"
                                id="btn-detect-edit-loc" onclick="detectEditCommentLocation()" style="font-size: 0.75rem;">
                            <i class="bi bi-crosshair me-1"></i> Deteksi GPS
                        </button>
                    </label>
                    <input type="text" class="form-control mb-2 @error('location_name') is-invalid @enderror"
                           id="edit-location-name" name="location_name"
                           placeholder="Nama tempat / kota..."
                           value="{{ old('location_name', $comment->location_name) }}">
                    @error('location_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text text-muted" style="font-size: 0.75rem;">Latitude</span>
                                <input type="number" step="any" class="form-control form-control-sm @error('latitude') is-invalid @enderror"
                                       id="edit-latitude" name="latitude"
                                       placeholder="-6.2088"
                                       value="{{ old('latitude', $comment->latitude) }}">
                            </div>
                            @error('latitude')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text text-muted" style="font-size: 0.75rem;">Longitude</span>
                                <input type="number" step="any" class="form-control form-control-sm @error('longitude') is-invalid @enderror"
                                       id="edit-longitude" name="longitude"
                                       placeholder="106.8456"
                                       value="{{ old('longitude', $comment->longitude) }}">
                            </div>
                            @error('longitude')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

              {{-- bagian gambar comment --}}
                {{-- @if ($comment->image)
                    <div id="current-edit-comment-image-box" class="mb-3">
                        <label class="form-label d-block">Gambar Saat Ini</label>
                        <div class="preview-box">
                            <img id="current-edit-comment-image-img"
                                 src="{{ asset('storage/' . $comment->image) }}"
                                 alt="Comment image"
                                 class="rounded mb-2"
                                 style="max-height: 220px; max-width: 100%; border: 1px solid var(--border-color); transition: opacity 0.2s ease;">
                             <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox"
                                       id="remove_image" name="remove_image" value="1"
                                       {{ old('remove_image') ? 'checked' : '' }}
                                       onchange="toggleRemoveExistingImage(this, 'current-edit-comment-image-img')">
                                <label class="form-check-label small text-danger fw-semibold" for="remove_image">
                                    <i class="bi bi-trash3 me-1"></i> Hapus gambar ini
                                </label>
                            </div> 
                        </div>
                    </div>
                @endif   --}}

                {{-- Preview Gambar Baru --}}
                {{-- <div id="edit-comment-image-preview" class="mb-3 d-none">
                    <label class="form-label d-block">Preview Gambar Baru</label>
                    <div class="preview-box position-relative d-inline-block">
                        <img id="edit-comment-image-preview-img" src="" alt="Preview"
                             class="rounded"
                             style="max-height: 220px; max-width: 100%; border: 1px solid var(--border-color);">
                        <button type="button"
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle p-0 d-flex align-items-center justify-content-center"
                                style="width: 26px; height: 26px;"
                                onclick="removeEditImage('edit-comment')" title="Batalkan gambar baru">
                            <i class="bi bi-x-lg" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>
                </div> --}}

                {{-- Input Ganti / Tambah Gambar --}}
                {{-- <div class="mb-4">
                    <label for="edit-comment-image" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center" style="cursor: pointer;">
                        <i class="bi bi-image text-accent me-1"></i>
                        {{ $comment->image ? 'Ganti Gambar' : 'Tambah Gambar' }}
                    </label>
                    <input type="file" class="d-none @error('image') is-invalid @enderror"
                           id="edit-comment-image" name="image" accept="image/jpg,image/jpeg,image/png"
                           onchange="previewEditImage(this, 'edit-comment')">
                    <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div> --}}

                <div class="d-flex gap-2 pt-3 border-top border-subtle">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center px-4">
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
    function detectEditCommentLocation() {
        const latInput = document.getElementById('edit-latitude');
        const lngInput = document.getElementById('edit-longitude');
        const nameInput = document.getElementById('edit-location-name');
        const btn = document.getElementById('btn-detect-edit-loc');

        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda.');
            return;
        }

        if (btn) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mendeteksi...';
            btn.disabled = true;
        }

        navigator.geolocation.getCurrentPosition(
            async function (position) {
                const lat = Number(position.coords.latitude.toFixed(7));
                const lng = Number(position.coords.longitude.toFixed(7));

                if (latInput) latInput.value = lat;
                if (lngInput) lngInput.value = lng;

                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
                        headers: { 'Accept-Language': 'id,en' }
                    });
                    const data = await res.json();
                    const address = data.address || {};
                    const neighborhood = address.neighbourhood || address.suburb || address.village || '';
                    const city = address.city || address.town || address.county || '';
                    const state = address.state || '';
                    const parts = [neighborhood, city, state].filter(Boolean);
                    const placeName = parts.length > 0 ? parts.join(', ') : (data.display_name ? data.display_name.split(',')[0] : '');

                    if (nameInput) {
                        nameInput.value = placeName || `Lat: ${lat}, Lng: ${lng}`;
                    }
                } catch (e) {
                    if (nameInput && !nameInput.value) {
                        nameInput.value = `Lat: ${lat}, Lng: ${lng}`;
                    }
                } finally {
                    if (btn) {
                        btn.innerHTML = '<i class="bi bi-crosshair me-1"></i> Deteksi GPS';
                        btn.disabled = false;
                    }
                }
            },
            function (err) {
                if (btn) {
                    btn.innerHTML = '<i class="bi bi-crosshair me-1"></i> Deteksi GPS';
                    btn.disabled = false;
                }
                alert('Tidak dapat mendeteksi lokasi: ' + err.message);
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    }
</script>
@endpush

