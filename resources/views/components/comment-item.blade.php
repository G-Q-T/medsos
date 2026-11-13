@php
    $isCommentOwner = $comment->user_id === Auth::id();
    $isPostOwner = isset($post) && $comment->user_id === $post->user_id;
    $isCommentLiked = $comment->isLikedBy(Auth::user());
    $commentLikesCount = $comment->likes->count();
@endphp

<div class="comment-item d-flex align-items-start mb-2">
    <a href="{{ route('profile.show', $comment->user) }}" class="text-decoration-none">
        @if ($comment->user->avatar)
            <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar" class="avatar-sm me-2 mt-1 flex-shrink-0">
        @else
            <span class="avatar-placeholder avatar-sm me-2 mt-1 flex-shrink-0">
                <i class="bi bi-person-fill small"></i>
            </span>
        @endif
    </a>
    <div class="flex-grow-1">
        <div class="d-flex align-items-center justify-content-between mb-1 gap-2">
            <div class="d-flex align-items-baseline gap-2 flex-wrap">
                <a href="{{ route('profile.show', $comment->user) }}" class="text-decoration-none text-reset">
                    <span class="fw-bold small text-truncate d-inline-block" style="max-width: 220px;">{{ $comment->user->name }}</span>
                </a>
                @if ($isPostOwner)
                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.68rem; padding: 2px 6px;">Penulis Post</span>
                @endif
                <small class="text-muted" style="font-size: 0.75rem;">
                    {{ $comment->created_at->diffForHumans() }}
                    @if ($comment->updated_at && $comment->created_at && $comment->updated_at->gt($comment->created_at))
                        <span>· Diedit</span>
                    @endif
                </small>
                @if ($comment->displayLocation())
                    @if ($comment->mapsUrl())
                        <a href="{{ $comment->mapsUrl() }}" target="_blank" rel="noopener noreferrer"
                           class="text-decoration-none text-muted d-inline-flex align-items-center"
                           style="font-size: 0.75rem;" title="Lihat di Google Maps (Lat: {{ $comment->latitude }}, Lng: {{ $comment->longitude }})">
                            <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 0.72rem;"></i>
                            <span>{{ $comment->displayLocation() }}</span>
                        </a>
                    @else
                        <small class="text-muted d-inline-flex align-items-center" style="font-size: 0.75rem;">
                            <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 0.72rem;"></i>
                            <span>{{ $comment->displayLocation() }}</span>
                        </small>
                    @endif
                @endif
            </div>
            @if ($isCommentOwner)
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('comments.edit', $comment) }}"
                       class="btn btn-sm btn-link text-secondary p-0 border-0"
                       title="Edit komentar" style="line-height: 1;">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" title="Hapus komentar" style="line-height: 1;">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
            @endif
        </div>
        @if ($comment->comment)
            <p class="mb-1 small" style="line-height: 1.5; white-space: pre-line;">{{ $comment->comment }}</p>
        @endif
        @if ($comment->image)
            <div class="mb-1">
                <img src="{{ asset('storage/' . $comment->image) }}" alt="Comment image" class="comment-image">
            </div>
        @endif

        {{-- Actions: Suka & Balas Komentar --}}
        <div class="d-flex align-items-center gap-3 mt-1" style="font-size: 0.78rem;">
            <form action="{{ route('comments.like', $comment) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm p-0 border-0 text-decoration-none {{ $isCommentLiked ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.78rem; line-height: 1;">
                    <i class="bi {{ $isCommentLiked ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                    <span>{{ $commentLikesCount > 0 ? $commentLikesCount . ' ' : '' }}Suka</span>
                </button>
            </form>

            <button type="button" class="btn btn-sm p-0 border-0 text-muted text-decoration-none fw-semibold"
                    style="font-size: 0.78rem; line-height: 1;"
                    onclick="toggleReplyForm({{ $comment->id }})">
                <i class="bi bi-reply-fill me-1"></i> Balas
            </button>
        </div>

        {{-- Inline Reply Box --}}
        <div id="reply-form-box-{{ $comment->id }}" class="mt-2 d-none">
            <form action="{{ route('posts.comments.store', $post ?? $comment->post) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateReplySubmit({{ $comment->id }})">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <input type="hidden" name="latitude" id="reply-lat-{{ $comment->id }}" value="">
                <input type="hidden" name="longitude" id="reply-lng-{{ $comment->id }}" value="">
                <input type="hidden" name="location_name" id="reply-loc-name-{{ $comment->id }}" value="">

                {{-- Preview Foto Balasan --}}
                <div id="reply-preview-{{ $comment->id }}" class="mb-2 ms-4 ps-2 d-none">
                    <div class="position-relative d-inline-block">
                        <img id="reply-preview-img-{{ $comment->id }}" src="" alt="Preview" class="rounded"
                             style="max-height: 120px; max-width: 100%; border: 1px solid var(--border-color);">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-0 d-flex align-items-center justify-content-center"
                                style="width: 20px; height: 20px;"
                                onclick="removeReplyImage({{ $comment->id }})" title="Hapus foto">
                            <i class="bi bi-x" style="font-size: 0.85rem;"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="avatar-xs flex-shrink-0">
                    @else
                        <span class="avatar-placeholder avatar-xs flex-shrink-0">
                            <i class="bi bi-person-fill" style="font-size: 0.7rem;"></i>
                        </span>
                    @endif
                    <div class="input-group input-group-sm flex-grow-1">
                        <input type="text" name="comment" id="reply-input-{{ $comment->id }}"
                               class="form-control form-control-sm"
                               placeholder="Balas {{ $comment->user->name }}...">

                        {{-- Tombol Tambah Foto Balasan --}}
                        <label class="btn btn-outline-secondary btn-sm mb-0 d-inline-flex align-items-center"
                               for="reply-image-{{ $comment->id }}"
                               title="Sematkan foto pada balasan" style="cursor: pointer;">
                            <i class="bi bi-image text-accent"></i>
                        </label>
                        <input type="file" class="d-none" id="reply-image-{{ $comment->id }}"
                               name="image" accept="image/jpg,image/jpeg,image/png"
                               onchange="previewReplyImage(this, {{ $comment->id }})">

                        {{-- Tombol Lokasi balasan dinonaktifkan sementara --}}
                        {{-- <button type="button" class="btn btn-outline-secondary btn-sm"
                                id="btn-reply-loc-{{ $comment->id }}"
                                onclick="toggleOrResetReplyLocation({{ $comment->id }})" title="Sertakan / sesuaikan lokasi balasan">
                            <i class="bi bi-geo-alt text-danger"></i>
                        </button> --}}
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-send-fill me-1"></i> Balas
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                               onclick="toggleReplyForm({{ $comment->id }})" title="Batal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div id="reply-loc-indicator-{{ $comment->id }}" class="small text-muted ms-4 ps-2 mt-1 d-none" style="font-size: 0.72rem;">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i><span id="reply-loc-text-{{ $comment->id }}"></span>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1 border-0" onclick="clearReplyLocation({{ $comment->id }})" title="Kirim balasan ini tanpa lokasi">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Replies List --}}
        @if ($comment->replies && $comment->replies->count() > 0)
            <div class="replies-list ms-2 ms-md-3 mt-2 ps-2 border-start border-2 border-subtle">
                @foreach ($comment->replies as $reply)
                    @php
                        $isReplyOwner = $reply->user_id === Auth::id();
                        $isReplyPostOwner = isset($post) && $reply->user_id === $post->user_id;
                        $isReplyLiked = $reply->isLikedBy(Auth::user());
                        $replyLikesCount = $reply->likes->count();
                    @endphp
                    <div class="comment-item reply-item d-flex align-items-start pt-2">
                        <a href="{{ route('profile.show', $reply->user) }}" class="text-decoration-none">
                            @if ($reply->user->avatar)
                                <img src="{{ asset('storage/' . $reply->user->avatar) }}" alt="Avatar" class="avatar-xs me-2 mt-1 flex-shrink-0">
                            @else
                                <span class="avatar-placeholder avatar-xs me-2 mt-1 flex-shrink-0">
                                    <i class="bi bi-person-fill" style="font-size: 0.7rem;"></i>
                                </span>
                            @endif
                        </a>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1 gap-2">
                                <div class="d-flex align-items-baseline gap-2 flex-wrap">
                                    <a href="{{ route('profile.show', $reply->user) }}" class="text-decoration-none text-reset">
                                        <span class="fw-bold small text-truncate d-inline-block" style="max-width: 180px;">{{ $reply->user->name }}</span>
                                    </a>
                                    @if ($isReplyPostOwner)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem; padding: 1px 5px;">Penulis Post</span>
                                    @endif
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        {{ $reply->created_at->diffForHumans() }}
                                        @if ($reply->updated_at && $reply->created_at && $reply->updated_at->gt($reply->created_at))
                                            <span>· Diedit</span>
                                        @endif
                                    </small>
                                    @if ($reply->displayLocation())
                                        @if ($reply->mapsUrl())
                                            <a href="{{ $reply->mapsUrl() }}" target="_blank" rel="noopener noreferrer"
                                               class="text-decoration-none text-muted d-inline-flex align-items-center"
                                               style="font-size: 0.72rem;" title="Lihat di Google Maps (Lat: {{ $reply->latitude }}, Lng: {{ $reply->longitude }})">
                                                <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 0.68rem;"></i>
                                                <span>{{ $reply->displayLocation() }}</span>
                                            </a>
                                        @else
                                            <small class="text-muted d-inline-flex align-items-center" style="font-size: 0.72rem;">
                                                <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 0.68rem;"></i>
                                                <span>{{ $reply->displayLocation() }}</span>
                                            </small>
                                        @endif
                                    @endif
                                </div>
                                @if ($isReplyOwner)
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('comments.edit', $reply) }}"
                                           class="btn btn-sm btn-link text-secondary p-0 border-0"
                                           title="Edit balasan" style="line-height: 1;">
                                            <i class="bi bi-pencil-square" style="font-size: 0.8rem;"></i>
                                        </a>
                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus balasan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" title="Hapus balasan" style="line-height: 1;">
                                                <i class="bi bi-trash3" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            @if ($reply->comment)
                                <p class="mb-1 small" style="line-height: 1.45; white-space: pre-line;">{{ $reply->comment }}</p>
                            @endif
                            @if ($reply->image)
                                <div class="mb-1">
                                    <img src="{{ asset('storage/' . $reply->image) }}" alt="Reply image" class="comment-image">
                                </div>
                            @endif

                            {{-- Reply Actions: Suka & Balas --}}
                            <div class="d-flex align-items-center gap-3 mt-1" style="font-size: 0.75rem;">
                                <form action="{{ route('comments.like', $reply) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 border-0 text-decoration-none {{ $isReplyLiked ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.75rem; line-height: 1;">
                                        <i class="bi {{ $isReplyLiked ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                                        <span>{{ $replyLikesCount > 0 ? $replyLikesCount . ' ' : '' }}Suka</span>
                                    </button>
                                </form>

                                <button type="button" class="btn btn-sm p-0 border-0 text-muted text-decoration-none fw-semibold"
                                        style="font-size: 0.75rem; line-height: 1;"
                                        onclick="toggleReplyForm({{ $comment->id }}, '{{ '@' . $reply->user->name . ' ' }}')">
                                    <i class="bi bi-reply-fill me-1"></i> Balas
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
