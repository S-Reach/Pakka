@extends('layout.master')

@section('content')

<title>Payment Verification</title>
<link rel="stylesheet" href="{{ asset('css/paymentverification.css') }}">
<script src="{{ asset('js/paymentverification.js') }}" defer></script>


<div class="page-container">

    <h2 class="page-title">User Payments</h2>

    <p class="page-subtitle">
        Chapter purchase transactions via ABA Pay
    </p>

    {{-- Statistics --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-title">
                Total Transactions
            </div>

            <div class="stat-value">
                {{ $totalTransactions }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                Today's Purchases
            </div>

            <div class="stat-value">
                {{ $todayPurchases }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                Total Revenue
            </div>

            <div class="stat-value revenue">
                ${{ number_format($totalRevenue,2) }}
            </div>
        </div>

    </div>

    {{-- Transactions Table --}}
    <div class="payment-card">

        <div class="payment-header">
            Chapter Transactions
        </div>

        <div class="table-responsive">

            <table class="table">

                <thead>
                    <tr>
                        <th>Reader</th>
                        <th>Story / Chapter</th>
                        <th>Writer</th>
                        <th>Amount</th>
                        <th>Transaction Ref</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transactions as $transaction)

                    <tr>

                        {{-- Reader --}}
                        <td>
                            <div class="reader-name">
                                {{ $transaction->user->name }}
                            </div>

                            <div class="reader-email">
                                {{ $transaction->user->email }}
                            </div>
                        </td>

                        {{-- Story --}}
                        <td>
                            <div class="story-title">
                                {{ $transaction->chapter->story->title }}
                            </div>

                            <div class="chapter-title">
                                {{ $transaction->chapter->title }}
                            </div>
                        </td>

                        {{-- Writer --}}
                        <td>
                            {{ $transaction->chapter->story->user->name }}
                        </td>

                        {{-- Amount --}}
                        <td class="amount">
                            ${{ number_format($transaction->amount,2) }}
                        </td>

                        {{-- Ref --}}
                        <td class="ref">
                            {{ $transaction->transaction_id }}
                        </td>

                        {{-- Date --}}
                        <td class="date">
                            {{ $transaction->created_at->format('Y-m-d H:i') }}
                        </td>

                        {{-- Action --}}
                        <td>
                            @php
                                $paymentData = [
                                    'transaction_id' => $transaction->transaction_id,
                                    'date' => $transaction->created_at->format('Y-m-d H:i'),
                                    'reader_name' => $transaction->user->name,
                                    'reader_email' => $transaction->user->email,
                                    'writer_name' => $transaction->chapter->story->user->name,
                                    'story_title' => $transaction->chapter->story->title,
                                    'chapter_title' => $transaction->chapter->title,
                                    'amount' => number_format($transaction->amount, 2),
                                ];
                            @endphp

                            <button
                                type="button"
                                class="btn-view"
                                onclick='openPaymentModal(@json($paymentData))'>
                                View
                            </button>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                No transactions found.
                            </div>
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        {{ $transactions->links('pagination::bootstrap-4') }}
    </div>

</div>

<div id="paymentModal" class="payment-modal">

    <div class="payment-modal-content">

        <div class="payment-title">
            Transaction Details
        </div>

        <div class="info-grid">

            <div class="info-box">
                <div class="label">Transaction Ref</div>
                <div id="m_ref"></div>
            </div>

            <div class="info-box">
                <div class="label">Date</div>
                <div id="m_date"></div>
            </div>

        </div>

        <div class="info-grid">

            <div>
                <div class="section">
                    <div class="section-title">Reader</div>
                    <div id="m_reader"></div>
                    <small id="m_email"></small>
                </div>

                <div class="section">
                    <div class="section-title">Story</div>
                    <div id="m_story"></div>
                </div>

                <div class="section">
                    <div class="section-title">Chapter Purchased</div>
                    <div id="m_chapter"></div>
                </div>
            </div>

            <div>
                <div class="section">
                    <div class="section-title">Writer</div>
                    <div id="m_writer"></div>
                </div>

                <div class="section">
                    <div class="section-title">Amount</div>
                    <div class="modal-amount">
                        $<span id="m_amount"></span>
                        <span class="currency">USD</span>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">Payment Method</div>
                    <span class="payment-badge">
                        ABA Pay
                    </span>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button class="close-btn" onclick="closePaymentModal()">
                CLOSE
            </button>
        </div>

    </div>

</div>

@endsection