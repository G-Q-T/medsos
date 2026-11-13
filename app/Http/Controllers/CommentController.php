<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'parent_id' => ['nullable', 'integer'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'caption' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_name' => ['nullable', 'string', 'max:255'],
        ]);

        $hasImage = $request->hasFile('image');
        $rawText = $request->filled('caption') ? $request->caption : $request->comment;
        $commentText = $this->sanitizeText($rawText);

        if ($commentText === null && ! $hasImage) {
            return back()->withErrors(['comment' => 'Komentar harus memiliki teks atau gambar.'])->withInput();
        }

        $resolvedParentId = null;
        if ($request->filled('parent_id')) {
            $parentComment = Comment::where('post_id', $post->id)->find($request->parent_id);
            if ($parentComment) {
                $resolvedParentId = $parentComment->parent_id ?? $parentComment->id;
            }
        }

        $imagePath = $this->resolveUpdatedPublicImage($request, null, 'comments');
        $latitude = $request->filled('latitude') ? (float) $request->latitude : null;
        $longitude = $request->filled('longitude') ? (float) $request->longitude : null;
        $locationName = $request->filled('location_name') ? strip_tags(trim($request->location_name)) : null;

        Comment::create([
            'post_id' => $post->id,
            'parent_id' => $resolvedParentId,
            'user_id' => Auth::id(),
            'comment' => $commentText,
            'image' => $imagePath,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location_name' => $locationName,
        ]);

        $message = $resolvedParentId ? 'Balasan berhasil ditambahkan!' : 'Komentar berhasil ditambahkan!';

        return back()->with('success', $message);
    }

    /**
     * Show the form for editing a comment.
     */
    public function edit(Comment $comment): View
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit komentar ini.');
        }

        $comment->load('post.user');

        return view('comments.edit', compact('comment'));
    }

    /**
     * Update an existing comment.
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit komentar ini.');
        }

        $request->validate([
            'comment' => ['nullable', 'string', 'max:2000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_name' => ['nullable', 'string', 'max:255'],
            // 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            // 'remove_image' => ['nullable', 'boolean'],
        ]);
        // ], [
        //     'image.image' => 'File harus berupa gambar.',
        //     'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
        //     'image.max' => 'Ukuran gambar maksimal 2MB.',
        // ]);

        // $hasNewImage = $request->hasFile('image');
        // $removeImage = $request->boolean('remove_image');
        // $willHaveImage = $hasNewImage || ($comment->image !== null && ! $removeImage);
        $willHaveImage = $comment->image !== null;
        $commentText = $this->sanitizeText($request->comment);

        if ($commentText === null && ! $willHaveImage) {
            return back()
                ->withErrors(['comment' => 'Komentar harus memiliki teks atau gambar.'])
                ->withInput();
        }

        // $imagePath = $this->resolveUpdatedPublicImage($request, $comment->image, 'comments', $removeImage);

        $latitude = $request->filled('latitude') ? (float) $request->latitude : null;
        $longitude = $request->filled('longitude') ? (float) $request->longitude : null;
        $locationName = $request->filled('location_name') ? strip_tags(trim($request->location_name)) : null;

        $comment->update([
            'comment' => $commentText,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location_name' => $locationName,
            // 'image' => $imagePath,
        ]);

        return redirect()->route('feed')->with('success', 'Komentar berhasil diperbarui!');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        if ($comment->image) {
            Storage::disk('public')->delete($comment->image);
        }

        foreach ($comment->replies as $reply) {
            if ($reply->image) {
                Storage::disk('public')->delete($reply->image);
            }
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus!');
    }
}
