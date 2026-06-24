<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Story;
use App\Models\ChapterPurchase;
use App\Models\PayoutRequest;

class WriterDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // BASE QUERY
        $query = Story::where('user_id', $user->id);

        // FILTER: story_progress (all / published / draft)
        if ($request->filled('story_progress') && $request->story_progress !== 'all') {
            $query->where('story_progress', $request->story_progress);
        }

        // SORT: latest / oldest
        if ($request->sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest(); // default = latest
        }

        // PAGINATION
        $stories = $query->paginate(10)->appends($request->query());

        // Revenue calculation (unchanged)
        $grossRevenue = ChapterPurchase::where('payment_status', 'paid')
            ->whereHas('chapter.story', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->sum('amount');

        $totalEarned = $grossRevenue * 0.80;

        $totalPaid = PayoutRequest::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');

        $writerShare = max(0, $totalEarned - $totalPaid);

        $stats = [
            'stories' => Story::where('user_id', $user->id)->count(),
            'published' => Story::where('user_id', $user->id)
                ->where('story_progress', 'published')
                ->count(),
            'drafts' => Story::where('user_id', $user->id)
                ->where('story_progress', 'draft')
                ->count(),
            'writerShare' => $writerShare,
            'totalEarned' => $totalEarned,
            'totalPaid' => $totalPaid,
        ];

        $latestWithdrawal = PayoutRequest::where('user_id', auth()->id())
            ->latest()
            ->first();

        return view('writer.dashboard', compact('stories', 'stats', 'latestWithdrawal'));
    }
}