<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <style>

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:'Segoe UI', sans-serif;
    }

    body{
        background:#f5f6fa;
        display:flex;
        justify-content:center;
        align-items:center;
        min-height:100vh;
    }

    .login-wrapper{
        width:100%;
        max-width:420px;
        padding:20px;
    }

    .login-card{
        background:#fff;
        border-radius:24px;
        padding:40px;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
        border:1px solid #ececec;
    }

    .login-header{
        text-align:center;
        margin-bottom:35px;
    }

    .login-logo{
        width:70px;
        height:70px;
        background:#3158ff;
        color:#fff;
        border-radius:18px;
        display:flex;
        justify-content:center;
        align-items:center;
        font-size:28px;
        margin:0 auto 18px;
    }

    .login-header h1{
        font-size:32px;
        color:#111827;
        margin-bottom:8px;
    }

    .login-header p{
        color:#6b7280;
        font-size:16px;
    }

    /* =========================
       FORM
    ========================= */

    .form-group{
        margin-bottom:22px;
    }

    .form-label{
        display:block;
        margin-bottom:8px;
        color:#374151;
        font-weight:600;
        font-size:15px;
    }

    .input-wrapper{
        position:relative;
    }

    .form-input{
        width:100%;
        height:52px;
        border:1px solid #d1d5db;
        border-radius:14px;
        padding:0 45px 0 16px;
        font-size:15px;
        outline:none;
        transition:.3s;
    }

    .form-input:focus{
        border-color:#3158ff;
        box-shadow:0 0 0 4px rgba(49,88,255,.1);
    }

    .input-icon{
        position:absolute;
        right:16px;
        top:50%;
        transform:translateY(-50%);
        color:#9ca3af;
        cursor:pointer;
    }

    .password-toggle{
        cursor:pointer;
    }

    .password-toggle:hover{
        color:#3158ff;
    }

    /* =========================
       ERROR
    ========================= */

    .error-message{
        background:#fef2f2;
        color:#dc2626;
        padding:12px 15px;
        border-radius:12px;
        margin-bottom:20px;
        font-size:14px;
        border:1px solid #fecaca;
    }

    .validation-error{
        color:#dc2626;
        font-size:13px;
        margin-top:6px;
        display:block;
    }

    /* =========================
       OPTIONS
    ========================= */

    .form-options{
        display:flex;
        justify-content:flex-end;
        align-items:center;
        margin-bottom:25px;
    }

    .forgot-password{
        margin-left:auto;
        color:#3158ff;
        font-size:14px;
        text-decoration:none;
        transition:.3s;
    }

    .forgot-password:hover{
        text-decoration:underline;
    }

    /* =========================
       BUTTON
    ========================= */

    .login-btn{
        width:100%;
        height:52px;
        border:none;
        border-radius:14px;
        background:#3158ff;
        color:#fff;
        font-size:16px;
        font-weight:600;
        cursor:pointer;
        transition:.3s;
    }

    .login-btn:hover{
        background:#2648db;
    }

    /* =========================
       FOOTER
    ========================= */

    .login-footer{
        text-align:center;
        margin-top:25px;
        color:#6b7280;
        font-size:14px;
    }

    /* =========================
       MOBILE
    ========================= */

    @media(max-width:500px){

        .login-card{
            padding:30px 22px;
        }

        .login-header h1{
            font-size:26px;
        }
    }

    </style>

</head>
<body>

<div class="login-wrapper">

    <div class="login-card">

        <!-- HEADER -->
        <div class="login-header">

            <div class="login-logo">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <h1>Admin Login</h1>

            <p>
                Sign in to access the admin dashboard
            </p>

        </div>

        <!-- SESSION ERROR -->
        @if(session('error'))

            <div class="error-message">
                {{ session('error') }}
            </div>

        @endif

        <!-- LOGIN FORM -->
        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <input
                        type="email"
                        name="email"
                        class="form-input"
                        placeholder="Enter your email"
                        value="{{ old('email') }}"
                        required
                    >
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>

                @error('email')
                    <div class="validation-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Enter your password"
                        required
                    >
                    <i class="fa-solid fa-eye input-icon password-toggle"
                    onclick="togglePassword('password', this)"></i>
                </div>

                @error('password')
                    <div class="validation-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-options">
                <a href="{{ route('admin.password.request') }}" class="forgot-password">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="login-btn">
                Login
            </button>
        </form>

        <!-- FOOTER -->
        <div class="login-footer">

            © {{ date('Y') }} Admin Panel

        </div>

    </div>

</div>

<script>
function togglePassword(id, icon){

    let input = document.getElementById(id);

    if(!input){
        return;
    }

    if(input.type === 'password'){

        input.type = 'text';

        icon.classList.remove('fa-eye');

        icon.classList.add('fa-eye-slash');

    }else{

        input.type = 'password';

        icon.classList.remove('fa-eye-slash');

        icon.classList.add('fa-eye');

    }

}
</script>
</body>
</html>
