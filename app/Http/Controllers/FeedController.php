<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Display the home feed.
     */
    public function index(Request $request)
    {
        $posts = Post::with([
            'user',
            'likes',
            'comments.user',
            'comments.likes',
            'comments.replies.user',
            'comments.replies.likes',
        ])
            ->withCount(['likes', 'allComments as comments_count'])
            ->latest()
            ->paginate(10);

        return view('feed.index', compact('posts'));
    }
}
