<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Store a new post.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => ['nullable', 'string', 'max:500'],
            'caption' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'content.max' => 'Postingan tidak boleh lebih dari 500 karakter.',
            'caption.max' => 'Caption gambar tidak boleh lebih dari 500 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $hasImage = $request->hasFile('image');
        $rawContent = $hasImage ? $request->caption : $request->content;
        $postContent = $this->sanitizeText($rawContent);

        if ($postContent === null && ! $hasImage) {
            return back()->withErrors(['content' => 'Postingan harus memiliki teks atau gambar.'])->withInput();
        }

        $imagePath = $this->resolveUpdatedPublicImage($request, null, 'posts');

        Post::create([
            'user_id' => Auth::id(),
            'content' => $postContent,
            'image' => $imagePath,
        ]);

        return redirect()->route('feed')->with('success', 'Postingan berhasil dibuat!');
    }

    /**
     * Show the form for editing a post.
     */
    public function edit(Post $post): View
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit postingan ini.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update an existing post.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit postingan ini.');
        }

        $request->validate([
            'content' => ['nullable', 'string', 'max:500'],
            // 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            // 'remove_image' => ['nullable', 'boolean'],
        ], [
            'content.max' => 'Postingan tidak boleh lebih dari 500 karakter.',
        ]);
        // ], [
        //     'image.image' => 'File harus berupa gambar.',
        //     'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
        //     'image.max' => 'Ukuran gambar maksimal 2MB.',
        // ]);

        // $hasNewImage = $request->hasFile('image');
        // $removeImage = $request->boolean('remove_image');
        // $willHaveImage = $hasNewImage || ($post->image !== null && ! $removeImage);
        $willHaveImage = $post->image !== null;
        $postContent = $this->sanitizeText($request->content);

        if ($postContent === null && ! $willHaveImage) {
            return back()
                ->withErrors(['content' => 'Postingan harus memiliki teks atau gambar.'])
                ->withInput();
        }

        // $imagePath = $this->resolveUpdatedPublicImage($request, $post->image, 'posts', $removeImage);

        $post->update([
            'content' => $postContent,
            // 'image' => $imagePath,
        ]);

        return redirect()->route('feed')->with('success', 'Postingan berhasil diperbarui!');
    }

    /**
     * Delete a post.
     */
    public function destroy(Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus postingan ini.');
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        // Delete comment images
        foreach ($post->comments as $comment) {
            if ($comment->image) {
                Storage::disk('public')->delete($comment->image);
            }
        }

        $post->delete();

        return redirect()->route('feed')->with('success', 'Postingan berhasil dihapus!');
    }
}
