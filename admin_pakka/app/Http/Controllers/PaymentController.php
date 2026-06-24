<?php

namespace App\Http\Controllers;
use App\Models\ChapterPurchase;
use Carbon\Carbon;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $transactions = ChapterPurchase::with([
            'user',
            'chapter.story.user'
        ])
        ->where('payment_status', 'paid')
        ->latest()
        ->paginate(10);

        $totalTransactions = ChapterPurchase::where('payment_status', 'paid')->count();

        $todayPurchases = ChapterPurchase::where('payment_status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $totalRevenue = ChapterPurchase::where('payment_status', 'paid')
            ->sum('amount');

        return view('paymentverification', compact(
            'transactions',
            'totalTransactions',
            'todayPurchases',
            'totalRevenue'
        ));
    }
}