<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Story;
use App\Models\ChapterPurchase;
use App\Models\PayoutRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WriterEarningsController extends Controller
{
    public function writerEarnings()
    {
        // Get all writers who have stories
        $writers = User::whereHas('stories')
            ->with('stories')
            ->get();

        $writerData = [];

        $totalWriterRevenue = 0;
        $totalPlatformRevenue = 0;
        $awaitingPayout = 0;

        foreach ($writers as $writer) {

            // Revenue from purchased chapters belonging to this writer
            $grossRevenue = ChapterPurchase::where('payment_status', 'paid')
                ->whereHas('chapter.story', function ($query) use ($writer) {
                    $query->where('user_id', $writer->id);
                })
                ->sum('amount');

            // Calculate awaiting payout amount
            $paidOut = PayoutRequest::where('user_id', $writer->id)
                ->where('status', 'paid')
                ->sum('amount');

            $totalGrossProfit = ChapterPurchase::where('payment_status', 'paid')
                ->sum('amount');
            $platformShare = $grossRevenue * 0.20;
            $writerShare = ($grossRevenue * 0.80) - $paidOut;

            if ($writerShare < 0) {
                $writerShare = 0;
            }

            $writerData[] = [
                'writer' => $writer,
                'stories' => $writer->stories->count(),
                'grossRevenue' => $grossRevenue,
                'platformShare' => $platformShare,
                'writerShare' => $writerShare,
                'paidAmount' => 0,
                'remaining' => $writerShare,
                'status' => $writerShare > 0 ? 'Pending' : 'Paid',
            ];

            $totalWriterRevenue += $writerShare;
            $totalPlatformRevenue += $platformShare;
        }

        // Withdraw Requests
        $withdrawRequests = PayoutRequest::with('user')
            ->latest()
            ->get();

        $pendingWithdraws = PayoutRequest::where('status', 'pending')
            ->count();

        return view('writerearnings', [
            'writerData' => $writerData,
            'totalWriterRevenue' => $totalWriterRevenue,
            'totalPlatformRevenue' => $totalPlatformRevenue,
            'totalGrossProfit' => $totalGrossProfit,
            'awaitingPayout' => $awaitingPayout,

            // Withdraw Requests Data
            'withdrawRequests' => $withdrawRequests,
            'pendingWithdraws' => $pendingWithdraws,
        ]);
    }

    public function markAsPaid($id)
    {
        $withdraw = PayoutRequest::findOrFail($id);

        if ($withdraw->status == 'paid') {
            return back()->with('error', 'Already paid.');
        }

        $withdraw->status = 'paid';
        $withdraw->paid_amount = $withdraw->amount;
        $withdraw->save();

        //create notification
        DB::table('notifications')->insert([
            'id' => Str::uuid()->toString(),
            'type' => 'PayoutPaidNotification',

            'notifiable_type' => User::class,
            'notifiable_id' => $withdraw->user_id,

            'data' => json_encode([
                'title' => 'Payout Paid',
                'message' => 'Your withdrawal request of $' .
                            number_format($withdraw->amount, 2) .
                            ' has been paid successfully.',
                'status' => 'paid',
                'amount' => $withdraw->amount,
            ]),

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Payment completed.');
    }
}