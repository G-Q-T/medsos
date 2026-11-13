<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->show($user);
    }

    /**
     * Show any user's profile (self or other users).
     */
    public function show(User $user): View
    {
        $posts = $user->posts()
            ->with([
                'user',
                'likes',
                'comments.user',
                'comments.likes',
                'comments.replies.user',
                'comments.replies.likes',
            ])
            ->withCount(['likes', 'allComments as comments_count'])
            ->latest()
            ->get();

        $isOwnProfile = Auth::id() === $user->id;

        return view('profile.index', compact('user', 'posts', 'isOwnProfile'));
    }

    /**
     * Show the edit profile form.
     */
    public function edit()
    {

        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\pL\s\.\'\,\\-]+$/u',
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'name.regex' => 'Nama tidak valid. Nama hanya boleh berupa huruf, spasi, titik, koma, tanda petik, atau tanda hubung.',
            'name.min' => 'Nama harus memiliki minimal 2 karakter.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
            'avatar.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        $user = Auth::user();
        $user->name = strip_tags(trim($request->name));
        $user->bio = $request->bio ? strip_tags(trim($request->bio)) : null;

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
