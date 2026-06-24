<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayWayController extends Controller
{
    // =========================
    // SHOW PAYMENT PAGE
    // =========================
    public function payChapter($chapterId)
    {
        $chapter = Chapter::findOrFail($chapterId);

        // FREE CHAPTER
        if ($chapter->is_premium) {

            return redirect()->route(
                'chapter.read',
                $chapter->id
            );
        }

        // ALREADY PURCHASED
        $alreadyPurchased = ChapterPurchase::where([
            'user_id' => auth()->id(),
            'chapter_id' => $chapter->id,
            'payment_status' => 'paid'
        ])->exists();

        if ($alreadyPurchased) {

            return redirect()->route(
                'reader.readchapters',
                $chapter->id
            );
        }

        // CREATE TRANSACTION
        $transactionId = 'CHAPTER_' . uniqid();

        ChapterPurchase::create([

            'user_id' => auth()->id(),

            'chapter_id' => $chapter->id,

            'transaction_id' => $transactionId,

            'amount' => $chapter->price,

            'payment_status' => 'pending'

        ]);

        $reqTime = now()->format('YmdHis');

        $merchantId = config('payway.merchant_id');

        $apiKey = config('payway.api_key');

        // HASH
        $hash = hash_hmac(
            'sha512',
            $reqTime .
            $merchantId .
            $transactionId .
            $chapter->price .
            'USD',
            $apiKey
        );

        // PAYMENT DATA
        $data = [

            'req_time' => $reqTime,

            'merchant_id' => $merchantId,

            'tran_id' => $transactionId,

            'amount' => $chapter->price,

            'currency' => 'USD',

            'return_url' => route('payway.success'),

            'cancel_url' => route('payway.cancel'),

            'hash' => $hash

        ];

        return view('payment.payway', compact(
            'data',
            'chapter'
        ));
    }

    // =========================
    // PAYMENT SUCCESS
    // =========================
    public function success(Request $request)
    {
        $transactionId = $request->tran_id;

        $purchase = ChapterPurchase::where(
            'transaction_id',
            $transactionId
        )->first();

        if (!$purchase) {

            abort(404);
        }

        // VERIFY TRANSACTION
        $reqTime = now()->format('YmdHis');

        $merchantId = config('payway.merchant_id');

        $apiKey = config('payway.api_key');

        $verifyHash = hash_hmac(
            'sha512',
            $reqTime .
            $merchantId .
            $transactionId,
            $apiKey
        );

        $response = Http::post(
            'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction',
            [

                'req_time' => $reqTime,

                'merchant_id' => $merchantId,

                'tran_id' => $transactionId,

                'hash' => $verifyHash

            ]
        );

        $result = $response->json();

        // SUCCESS
        if (
            isset($result['status']) &&
            $result['status'] == 0
        ) {

            $purchase->update([
                'payment_status' => 'paid'
            ]);

            return redirect()->route(
                'reader.readchapter',
                $purchase->chapter_id
            )->with(
                'success',
                'Chapter unlocked successfully.'
            );
        }

        // FAILED
        $purchase->update([
            'payment_status' => 'failed'
        ]);

        return redirect()->back()->with(
            'error',
            'Payment verification failed.'
        );
    }

    // =========================
    // PAYMENT CANCEL
    // =========================
    public function cancel()
    {
        return redirect()->back()->with(
            'error',
            'Payment cancelled.'
        );
    }
    
    public function processPayment($id)
    {
        // STEP 1: verify payment success
        // STEP 2: save purchase

        ChapterPurchase::create([
            'user_id' => auth()->id(),
            'chapter_id' => $id,
            'transaction_id' => 'SIMULATED_' . uniqid(),
            'amount' => Chapter::findOrFail($id)->price,
            'payment_status' => 'paid'
        ]);

        // STEP 3: redirect to chapter
        $chapter = Chapter::findOrFail($id);

        return redirect() ->route('reader.readchapters', [
            'storyId' => $chapter->story_id,
            'chapterId' => $chapter->id,
        ])
        ->with('success', 'Payment successful!');
    }
}