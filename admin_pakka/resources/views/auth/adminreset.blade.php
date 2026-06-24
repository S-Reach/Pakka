<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* =========================
   BASE
========================= */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f5f6fa;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

/* =========================
   LAYOUT
========================= */
.wrapper{
    width:100%;
    max-width:420px;
    padding:20px;
}

.card{
    background:#fff;
    border-radius:24px;
    padding:40px;
    border:1px solid #ececec;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
}

/* =========================
   HEADER
========================= */
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
    margin-bottom:8px;
    color:#111827;
}

.header p{
    color:#6b7280;
}

/* =========================
   FORM
========================= */
.form-group{
    margin-bottom:20px;
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

/* =========================
   BUTTON
========================= */
.btn{
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

.btn:hover{
    background:#2648db;
}

/* =========================
   ERROR
========================= */
.error{
    margin-top:6px;
    font-size:13px;
    color:#dc2626;
}

/* =========================
   RESPONSIVE
========================= */
@media(max-width:500px){
    .card{
        padding:30px 22px;
    }
}
</style>
</head>

<body>

<div class="wrapper">
<div class="card">

    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <i class="fa-solid fa-unlock-keyhole"></i>
        </div>
        <h2>Reset Password</h2>
        <p>Create your new password</p>
    </div>

    <!-- FORM -->
    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <!-- EMAIL -->
        <div class="form-group">
            <label>Email</label>
            <div class="input-wrapper">
                <input type="email" name="email" value="{{ $email }}" required>
                <i class="fa-regular fa-envelope icon"></i>
            </div>
        </div>

        <!-- PASSWORD -->
        <div class="form-group">
            <label>New Password</label>

            <div class="input-wrapper">
                <input type="password" id="password" name="password" placeholder="Enter new password" required>

                <i class="fa-solid fa-eye icon toggle-password"
                data-target="password"></i>
            </div>

            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="form-group">
            <label>Confirm Password</label>

            <div class="input-wrapper">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>

                <i class="fa-solid fa-eye icon toggle-password"
                data-target="password_confirmation"></i>
            </div>
        </div>

        <!-- BUTTON -->
        <button type="submit" class="btn">
            Reset Password
        </button>
    </form>

</div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function () {

        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);

        const isPassword = input.type === 'password';

        // Toggle input type
        input.type = isPassword ? 'text' : 'password';

        // Toggle icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});
</script>

</body>
</html>
