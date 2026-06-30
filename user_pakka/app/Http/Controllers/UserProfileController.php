<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Follow;
use App\Models\Story;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // 🔥 FOLLOW STATS (IMPORTANT FIX)
        $followers = Follow::where('following_id', $user->id)->count();
        $following = Follow::where('follower_id', $user->id)->count();

        return view('usereditprofile', compact('user', 'followers', 'following'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'bio' => 'nullable|max:500',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'spaces');
            $user->avatar = $path;
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'bio' => $request->bio,
        ]);

        return redirect()->back()->with('success', 'Profile updated!');
    }

    public function writerprofile($id)
    {
        $user = User::findOrFail($id);

        // STORIES
        $stories = Story::where('user_id', $id)
            ->latest()
            ->get();

        // FOLLOWERS LIST
        $followersList = Follow::with('follower')
            ->where('following_id', $id)
            ->get();

        // FOLLOWING LIST
        $followingList = Follow::with('following')
            ->where('follower_id', $id)
            ->get();

        // COUNTS (from collections → no extra queries)
        $followersCount = $followersList->count();
        $followingCount = $followingList->count();

        // CHECK IF AUTH USER FOLLOWS THIS WRITER
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Follow::where('follower_id', Auth::id())
                ->where('following_id', $id)
                ->exists();
        }

        return view('writerprofile', compact(
            'user',
            'stories',
            'followersList',
            'followingList',
            'followersCount',
            'followingCount',
            'isFollowing'
        ));
    }
}