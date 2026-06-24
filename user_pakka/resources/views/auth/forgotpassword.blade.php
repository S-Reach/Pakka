<doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/auth/forgotpassword.css') }}">
</head>
<body>

<div class="card">

    <!-- Logo -->
    <div class="logo">P</div>

    <!-- Title -->
    <h2>{{ __('ui.forgot_password') }}</h2>
    <div class="desc">
        {{ __('ui.forgot_password_desc') }}
    </div>

    <!-- Success Message -->
    @if(session('status'))
        <div class="alert">
            {{ session('status') }}
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-group">
            <label>{{ __('ui.email') }}</label>
            <input type="email" name="email" placeholder="{{ __('ui.enter_email') }}" required>
        </div>

        <button type="submit">{{ __('ui.reset_text') }}</button>
    </form>

    <!-- Back -->
    <div class="back-link">
        <a href="{{ route('login') }}">← {{ __('ui.back_to_signin') }}</a>
    </div>

    <!-- Bottom -->
    <div class="bottom">
        <span>{{ __('ui.dont_have_account') }}</span> 
        <a href="{{ route('register') }}">{{ __('ui.sign_up') }}</a>
    </div>

</div>

</body>
</html>