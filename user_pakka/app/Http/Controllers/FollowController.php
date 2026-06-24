<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggleFollow($id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Login required'], 401);
        }

        $userId = Auth::id();

        if ($userId == $id) {
            return response()->json(['message' => 'Cannot follow yourself'], 400);
        }

        $follow = Follow::where('follower_id', $userId)
            ->where('following_id', $id)
            ->first();

        if ($follow) {
            $follow->delete();

            return response()->json([
                'following' => false,
                'followers' => Follow::where('following_id', $id)->count()
            ]);
        }

        Follow::create([
            'follower_id' => $userId,
            'following_id' => $id
        ]);

        return response()->json([
            'following' => true,
            'followers' => Follow::where('following_id', $id)->count()
        ]);
    }
}