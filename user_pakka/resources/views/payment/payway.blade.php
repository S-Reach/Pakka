@extends('layout.master')

@section('content')

<title>Payway</title>
<link rel="stylesheet" href="{{ asset('css/payment/payway.css') }}">

<div class="container-box">

    <div class="unlock-icon">
        🔓
    </div>

    <div class="title">
        {{ __('ui.unlock_chapter') }}
    </div>

    <p class="sub-text">
        {{ __('ui.payway_description') }}
    </p>

    <div class="chapter-box">

        <div class="chapter-title">
             {{ __('ui.chapter') }} {{ $chapter->chapter_number }}: {{ $chapter->title }}
        </div>

        <div class="chapter-meta">
            {{ __('ui.story') }}: {{ $chapter->story->title }}
        </div>

        <div class="price">
            ${{ number_format($chapter->price, 2) }}
        </div>

    </div>

    {{-- =========================
        PAYWAY FORM (IMPORTANT)
        ========================= --}}
    <form method="POST" action="{{ config('payway.url') }}">

        @csrf

        @foreach($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <button type="submit" class="pay-btn">
            {{ __('ui.pay_with_aba') }}
        </button>

    </form>

    <a href="{{ url()->previous() }}" class="back-link">
        ← {{ __('ui.cancel_and_go_back') }}
    </a>

    <div class="secure">
        🔒 {{ __('ui.secure_payment') }}
    </div>

</div>

@endsection