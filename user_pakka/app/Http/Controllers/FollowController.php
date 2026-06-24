<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle($id)
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Login required'
            ], 401);
        }

        $follow = Follow::where('follower_id', auth()->id())
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
            'follower_id' => auth()->id(),
            'following_id' => $id
        ]);

        return response()->json([
            'following' => true,
            'followers' => Follow::where('following_id', $id)->count()
        ]);
    }
}