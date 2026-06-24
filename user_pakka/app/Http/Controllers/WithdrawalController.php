<?php

namespace App\Http\Controllers;

use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5',
            'bank_name' => 'required',
            'account_number' => 'required',
            'account_holder_name' => 'required',
        ]);

        PayoutRequest::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder_name' => $request->account_holder_name,
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Withdrawal request submitted successfully.'
        );
    }
}