<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoryReport;

class StoryReportController extends Controller
{
     public function store(Request $request, $storyId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        // prevent duplicate report from same user
        $exists = StoryReport::where('story_id', $storyId)
            ->where('user_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already reported this story.');
        }

        StoryReport::create([
            'story_id' => $storyId,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'details' => $request->details,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Report submitted successfully.');
    }
}
