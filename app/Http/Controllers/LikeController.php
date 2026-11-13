<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Toggle like on a post.
     */
    public function toggle(Post $post)
    {
        $userId = Auth::id();

        $existingLike = Like::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();

            return back()->with('success', 'Like dihapus.');
        }

        Like::create([
            'user_id' => $userId,
            'post_id' => $post->id,
        ]);

        return back()->with('success', 'Postingan disukai!');
    }
}
