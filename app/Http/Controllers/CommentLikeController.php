<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
{
    /**
     * Toggle like on a comment.
     */
    public function toggle(Comment $comment): RedirectResponse
    {
        $userId = Auth::id();

        $existingLike = CommentLike::where('user_id', $userId)
            ->where('comment_id', $comment->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();

            return back()->with('success', 'Like komentar dibatalkan.');
        }

        CommentLike::create([
            'user_id' => $userId,
            'comment_id' => $comment->id,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Komentar disukai!');
    }
}
