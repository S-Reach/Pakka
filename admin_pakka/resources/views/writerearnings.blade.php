@extends('layout.master')

@section('content')

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<link rel="stylesheet" href="{{ asset('css/writerearnings.css') }}">
<script src="{{ asset('js/writerearnings.js') }}" defer></script>


<div class="page-container">

    <div class="page-header">

        <div>
            <div class="page-title">
                Writer Earnings
            </div>

            <div class="page-subtitle">
                Payouts — writers and platform
            </div>
        </div>

        <div class="earning-badges">
            <span class="writer-badge">
                Writer 80%
            </span>

            <span class="platform-badge">
                Platform 20%
            </span>
        </div>

    </div>

    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-left">
                <h4>Active Writers</h4>
                <h2>{{ count($writerData) }}</h2>
                <div class="stat-sub orange">
                    earning writers
                </div>
            </div>

            <div class="stat-icon icon-yellow">
                <i class="fa-solid fa-users"></i>
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-left">
                <h4>Total Gross Profit</h4>
                <h2>${{ number_format($totalGrossProfit,2) }}</h2>
                <div class="stat-sub green">
                    all chapter purchases
                </div>
            </div>

            <div class="stat-icon icon-green">
                <i class="fa-solid fa-chart-line"></i>
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-left">
                <h4>Total Writer Revenue</h4>
                <h2>${{ number_format($totalWriterRevenue,2) }}</h2>
                <div class="stat-sub green">
                    paid to writers
                </div>
            </div>

            <div class="stat-icon icon-green">
                <i class="fa-solid fa-wallet"></i>
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-left">
                <h4>Platform Revenue</h4>
                <h2>${{ number_format($totalPlatformRevenue,2) }}</h2>
                <div class="stat-sub purple">
                    platform earnings
                </div>
            </div>

            <div class="stat-icon icon-purple">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>

        </div>

    </div>

    <div class="table-card">

        <div class="earnings-tabs">
            <button class="tab-btn active" onclick="showTab('earnings',this)">
                Writer Earnings
            </button>

            <button class="tab-btn" onclick="showTab('withdraws',this)">
                Withdraw Requests
                <span class="tab-badge">{{ $pendingWithdraws ?? 0 }}</span>
            </button>
        </div>

        {{-- ===================== --}}
        {{-- EARNINGS TAB --}}
        {{-- ===================== --}}
        <div id="earnings-tab">

            <table>
                <thead>
                    <tr>
                        <th>Writer</th>
                        <th>Stories</th>
                        <th>Gross Revenue</th>
                        <th>Platform (20%)</th>
                        <th>Writer (80%)</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($writerData as $row)
                    <tr>
                        <td>
                            <div class="writer-name">{{ $row['writer']->name }}</div>
                            <div class="writer-email">{{ $row['writer']->email }}</div>
                        </td>

                        <td class="story-count">{{ $row['stories'] }}</td>
                        <td class="gross">${{ number_format($row['grossRevenue'],2) }}</td>
                        <td class="platform-share">${{ number_format($row['platformShare'],2) }}</td>
                        <td class="writer-share">${{ number_format($row['writerShare'],2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">No writer earnings found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===================== --}}
        {{-- WITHDRAW TAB --}}
        {{-- ===================== --}}
        <div id="withdraws-tab" style="display:none;">

            <table>
                <thead>
                    <tr>
                        <th>Writer</th>
                        <th>Amount</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($withdrawRequests as $request)
                    <tr>
                        <td>
                            <div class="writer-name">{{ $request->user->name }}</div>
                            <div class="writer-email">{{ $request->user->email }}</div>
                        </td>

                        <td class="withdraw-amount">
                            ${{ number_format($request->amount,2) }}
                        </td>

                        <td>{{ $request->created_at->format('d M Y') }}</td>

                        <td>
                            @if($request->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @else
                                <span class="badge badge-approved">Paid</span>
                            @endif
                        </td>

                        <td>
                            <button class="action-btn view-btn"
                                onclick="openWithdrawModal(
                                    '{{ $request->id }}',
                                    '{{ $request->user->name }}',
                                    '{{ number_format($request->amount,2) }}',
                                    '{{ $request->bank_name }}',
                                    '{{ $request->account_number }}',
                                    '{{ $request->account_holder_name }}',
                                    '{{ $request->created_at->format('d M Y h:i A') }}',
                                    '{{ ucfirst($request->status) }}'
                                )">
                                View
                            </button>

                            @if($request->status == 'pending')
                                <form action="{{ route('withdraw.paid', $request->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="action-btn paid-btn">
                                        Paid
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">No withdraw requests found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>

    </div>

    

    <!-- Withdraw Details Modal -->
     <div id="withdrawModal" class="custom-modal">

        <div class="modal-content">

            <div class="modal-header">
                <h3>Withdraw Request Details</h3>

                <span onclick="closeWithdrawModal()">
                    &times;
                </span>
            </div>

            <div class="modal-body">

                <div class="detail-row">
                    <strong>ID:</strong>
                    <span id="modal-id"></span>
                </div>

                <div class="detail-row">
                    <strong>Writer:</strong>
                    <span id="modal-writer"></span>
                </div>

                <div class="detail-row">
                    <strong>Amount:</strong>
                    <span id="modal-amount"></span>
                </div>

                <div class="detail-row">
                    <strong>Bank Name:</strong>
                    <span id="modal-bank-name"></span>
                </div>

                <div class="detail-row">
                    <strong>Account Number:</strong>
                    <span id="modal-account-number"></span>
                </div>

                <div class="detail-row">
                    <strong>Acoount Holder Name:</strong>
                    <span id="modal-account-holder-name"></span>
                </div>

                <div class="detail-row">
                    <strong>Request Date:</strong>
                    <span id="modal-date"></span>
                </div>

                <div class="detail-row">
                    <strong>Status:</strong>
                    <span id="modal-status"></span>
                </div>

            </div>

            <div class="modal-footer">

                <a id="modal-paid-btn"
                href="#"
                class="action-btn paid-btn">
                    Mark as Paid
                </a>

            </div>

        </div>

    </div>
</div>

@endsection