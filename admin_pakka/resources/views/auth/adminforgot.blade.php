<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Forgot Password</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

/* ===================== BASE ===================== */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f5f6fa;
}

/* ===================== WRAPPER ===================== */

.wrapper{
    width:100%;
    max-width:420px;
    padding:20px;
}

/* ===================== CARD ===================== */

.card{
    background:#fff;
    border-radius:24px;
    padding:40px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
    border:1px solid #ececec;
}

/* ===================== HEADER ===================== */

.header{
    text-align:center;
    margin-bottom:30px;
}

.logo{
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

.header h2{
    color:#111827;
    margin-bottom:8px;
}

.header p{
    color:#6b7280;
}

/* ===================== FORM ===================== */

.form-group{
    margin-bottom:22px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#374151;
}

.input-wrapper{
    position:relative;
}

input{
    width:100%;
    height:52px;
    border:1px solid #d1d5db;
    border-radius:14px;
    padding:0 45px 0 16px;
    font-size:15px;
    outline:none;
    transition:.3s;
}

input:focus{
    border-color:#3158ff;
    box-shadow:0 0 0 4px rgba(49,88,255,.1);
}

.icon{
    position:absolute;
    right:16px;
    top:50%;
    transform:translateY(-50%);
    color:#9ca3af;
}

/* ===================== BUTTON ===================== */

button{
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

button:hover{
    background:#2648db;
}

/* ===================== ALERTS ===================== */

.message{
    margin-bottom:18px;
    padding:12px;
    border-radius:12px;
    font-size:14px;
}

.success{
    background:#ecfdf5;
    color:#16a34a;
    border:1px solid #bbf7d0;
}

.error{
    background:#fef2f2;
    color:#dc2626;
    border:1px solid #fecaca;
}

/* ===================== BACK LINK ===================== */

.back{
    display:block;
    margin-top:20px;
    text-align:center;
    color:#3158ff;
    text-decoration:none;
    font-size:14px;
}

.back:hover{
    text-decoration:underline;
}

/* ===================== RESPONSIVE ===================== */

@media (max-width:500px){
    .card{
        padding:30px 22px;
    }
}

</style>

</head>

<body>

<div class="wrapper">

    <div class="card">

        {{-- ================= HEADER ================= --}}
        <div class="header">

            <div class="logo">
                <i class="fa-solid fa-key"></i>
            </div>

            <h2>Forgot Password</h2>

            <p>Enter your email to receive a reset link</p>

        </div>

        {{-- ================= SUCCESS MESSAGE ================= --}}
        @if(session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= ERROR MESSAGE ================= --}}
        @error('email')
            <div class="message error">
                {{ $message }}
            </div>
        @enderror

        {{-- ================= FORM ================= --}}
        <form method="POST" action="{{ route('admin.password.email') }}">

            @csrf

            <div class="form-group">

                <label>Email Address</label>

                <div class="input-wrapper">

                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter your email"
                           required>

                    <i class="fa-regular fa-envelope icon"></i>

                </div>

            </div>

            <button type="submit">
                Send Reset Link
            </button>

        </form>

        {{-- ================= BACK LINK ================= --}}
        <a href="{{ route('login') }}" class="back">
            ← Back to Login
        </a>

    </div>

</div>

</body>
</html>