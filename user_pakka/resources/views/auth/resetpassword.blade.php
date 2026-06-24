<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <!-- Font Awesome (FIX for eye icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/resetpassword.css') }}">
    <script src="{{ asset('js/auth/resetpassword.js') }}" defer></script>

    
</head>

<body>

<div class="card">

    <div class="logo">P</div>

    <h2>{{ __('ui.reset_password') }}</h2>
    <div class="desc">{{ __('ui.enter_new_password') }}</div>

    <form method="POST" action="{{ route('resetpassword.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email -->
        <div class="input-group">
            <label>{{ __('ui.email') }}</label>
            <input type="email" name="email" placeholder="{{ __('ui.enter_email') }}" required>
        </div>

        <!-- Password -->
        <div class="input-group">
            <label>{{ __('ui.new_password') }}</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="{{ __('ui.enter_new_password') }}"
                    required
                >
                <i class="fa-solid fa-eye toggle-eye"
                onclick="togglePassword('password', this)">
                </i>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="input-group">
            <label>{{ __('ui.confirm_password') }}</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="{{ __('ui.confirm_new_password') }}"
                    required
                >
                <i class="fa-solid fa-eye toggle-eye"
                onclick="togglePassword('password_confirmation', this)">
                </i>
            </div>
        </div>

        <button type="submit">{{ __('ui.reset_password') }}</button>
    </form>

    <div class="footer-text">
        {{ __('ui.password_security_tips') }}
    </div>

</div>
</body>
</html>