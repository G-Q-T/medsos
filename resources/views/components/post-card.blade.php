<div class="card post-card mb-4">
    <div class="card-body p-4">
        {{-- Post Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">
                    @if ($post->user->avatar)
                        <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="Avatar" class="avatar-md me-3">
                    @else
                        <span class="avatar-placeholder avatar-md me-3">
                            <i class="bi bi-person-fill fs-5"></i>
                        </span>
                    @endif
                </a>
                <div>
                    <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none text-reset">
                        <h6 class="mb-0 fw-bold">{{ $post->user->name }}</h6>
                    </a>
                    <small class="text-muted d-flex align-items-center mt-1">
                        <i class="bi bi-clock me-1" style="font-size: 0.75rem;"></i>
                        {{ $post->created_at->diffForHumans() }}
                        @if ($post->updated_at && $post->created_at && $post->updated_at->gt($post->created_at))
                            <span class="ms-1">· Diedit</span>
                        @endif
                    </small>
                </div>
            </div>

            @if ($post->user_id === Auth::id())
                <div class="d-flex gap-2">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary" title="Edit postingan">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus postingan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus postingan">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Post Content --}}
        @if ($post->content)
            <p class="mb-3" style="line-height: 1.65; font-size: 1rem; white-space: pre-line;">{{ $post->content }}</p>
        @endif

        @if ($post->image)
            <div class="mb-3">
                <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="post-image">
            </div>
        @endif

        {{-- Like & Comment Counts & Actions --}}
        <div class="post-action-row d-flex justify-content-between align-items-center">
            {{-- Like Button --}}
            <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                @csrf
                @php
                    $isLiked = $post->likes->contains('user_id', Auth::id());
                @endphp
                <button type="submit" class="btn btn-sm {{ $isLiked ? 'btn-primary' : 'btn-outline-primary' }} d-inline-flex align-items-center">
                    <i class="bi {{ $isLiked ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                    <span>{{ $post->likes_count }} Like</span>
                </button>
            </form>

            <span class="text-muted small d-inline-flex align-items-center">
                <i class="bi bi-chat-text me-1 text-accent"></i>
                <span class="fw-semibold">{{ $post->comments_count }}</span>&nbsp;Komentar
            </span>
        </div>

        {{-- Comments Section --}}
        @if ($post->comments->count() > 0)
            <div class="mb-3 pt-2">
                @foreach ($post->comments as $comment)
                    @include('components.comment-item', ['comment' => $comment, 'post' => $post])
                @endforeach
            </div>
        @endif

        {{-- Add Comment Form --}}
        <form method="POST" action="{{ route('posts.comments.store', $post) }}" enctype="multipart/form-data" id="comment-form-{{ $post->id }}" class="mt-3">
            @csrf
            <div class="d-flex align-items-start">
                @if (Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="avatar-sm me-2 mt-1">
                @else
                    <span class="avatar-placeholder avatar-sm me-2 mt-1">
                        <i class="bi bi-person-fill small"></i>
                    </span>
                @endif
                <div class="flex-grow-1">
                    {{-- Textarea untuk komentar teks (disembunyikan saat gambar dipilih) --}}
                    <div id="comment-text-area-{{ $post->id }}">
                        <textarea class="form-control form-control-sm mb-2" name="comment" rows="2"
                                  placeholder="Tulis komentar...">{{ old('comment') }}</textarea>
                    </div>

                    {{-- Image Preview + Keterangan (muncul saat gambar dipilih) --}}
                    <div id="comment-preview-{{ $post->id }}" class="mb-2 d-none">
                        <div class="preview-box">
                            <div class="position-relative d-inline-block">
                                <img id="comment-preview-img-{{ $post->id }}" src="" alt="Preview" class="rounded" style="max-height: 160px; max-width: 100%; border: 1px solid var(--border-color);">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-0 d-flex align-items-center justify-content-center"
                                        style="width: 24px; height: 24px;"
                                        onclick="removeCommentImage({{ $post->id }})" title="Hapus foto">
                                    <i class="bi bi-x-lg" style="font-size: 0.75rem;"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm mt-2"
                                   id="comment-caption-{{ $post->id }}" name="caption"
                                   placeholder="Tambahkan ket...">
                        </div>
                    </div>

                    {{-- Input Hidden Lokasi Otomatis (Geolocation API) --}}
                    <input type="hidden" name="latitude" id="comment-lat-{{ $post->id }}" value="">
                    <input type="hidden" name="longitude" id="comment-lng-{{ $post->id }}" value="">
                    <input type="hidden" name="location_name" id="comment-location-name-{{ $post->id }}" value="">

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <label class="btn btn-outline-secondary btn-sm mb-0 d-inline-flex align-items-center" for="comment-image-{{ $post->id }}" style="cursor: pointer;">
                                <i class="bi bi-image text-accent me-1"></i> Foto
                            </label>
                            <input type="file" class="d-none" id="comment-image-{{ $post->id }}"
                                   name="image" accept="image/jpg,image/jpeg,image/png"
                                   onchange="previewCommentImage(this, {{ $post->id }})">

                            {{-- Tombol Lokasi dinonaktifkan sementara --}}
                            {{-- <button type="button" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center"
                                    id="btn-toggle-loc-{{ $post->id }}"
                                    onclick="toggleCommentLocation({{ $post->id }})" title="Sertakan / sesuaikan lokasi">
                                <i class="bi bi-geo-alt text-danger me-1"></i> Lokasi
                            </button> --}}

                            {{-- Badge Lokasi Otomatis dari Geolocation API --}}
                            {{-- <span id="comment-active-loc-badge-{{ $post->id }}" class="badge bg-light text-muted border d-none align-items-center py-1 px-2" style="font-size: 0.72rem;">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                <span id="comment-active-loc-name-{{ $post->id }}" class="text-truncate" style="max-width: 140px;"></span>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1 border-0"
                                        onclick="clearSingleCommentLocation({{ $post->id }})" title="Kirim komentar ini tanpa lokasi" style="line-height: 1;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </span> --}}
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                            <i class="bi bi-send-fill me-1"></i> Kirim
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
