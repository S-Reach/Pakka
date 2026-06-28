<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

<style>
/* =========================
   NAVBAR
========================= */

.pk-navbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:12px 24px;
    background:#fff;
    border-bottom:1px solid #e5e7eb;
    position:sticky;
    top:0;
    z-index:1000;
    min-height:65px;
}

.container,
.top-bar,
.back-btn,
.profile-page-wrapper{
    margin-top:50px;
}

/* =========================
   LEFT
========================= */

.pk-nav-left{
    display:flex;
    align-items:center;
    gap:18px;

    flex-shrink:0;
}

.pk-logo-link{
    text-decoration:none;
    color:inherit;
}

.pk-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    user-select: none;
}

.pk-logo-img {
    width: 50px;
    height: 50px;
    object-fit: contain;
    display: block;
}

.pk-logo span {
    font-size: 1.5rem;
    font-weight: 700;
    color: #7c3aed; /* Change to your brand color */
    font-family: 'Ariel', sans-serif;
}

/* =========================
   CENTER
========================= */

.pk-nav-links{
    display:flex;
    align-items:center;
    justify-content:center;

    gap:10px;

    flex:1;
}

.pk-nav-links a{
    text-decoration:none;

    color:#555;

    padding:8px 12px;

    border-radius:10px;

    font-size:14px;

    display:flex;
    align-items:center;
    gap:6px;

    transition:.2s;
}

.pk-nav-links a:hover{
    background:#f3f4f6;
}

.pk-nav-links a.active{
    background:#eef2ff;
    color:#4338ca;
    font-weight:600;
}

/* =========================
   RIGHT
========================= */

.pk-nav-right{
    display:flex;
    align-items:center;
    gap:14px;
    flex-shrink:0;
}

/* =========================
   NOTIFICATION
========================= */

.pk-notification-bell{
    position:relative;
    width:34px;
    height:34px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#111;
    text-decoration:none;
}

.pk-notification-count{
    position:absolute;
    top:-3px;
    right:-3px;
    min-width:16px;
    height:16px;
    padding:0 4px;
    border-radius:999px;
    background:red;
    color:white;
    font-size:10px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* =========================
   PROFILE
========================= */

.pk-profile-wrapper{
    position:relative;
}

.pk-profile-trigger{
    display:flex;
    align-items:center;
    gap:8px;
    cursor:pointer;
}

.pk-profile{
    width:36px;
    height:36px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #e5e7eb;
}

.pk-username{
    max-width:120px;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
    font-size:14px;
}

/* =========================
   DROPDOWN
========================= */

.pk-dropdown{
    position:absolute;
    top:52px;
    right:0;

    width:220px;

    background:#fff;

    border:1px solid #e5e7eb;
    border-radius:14px;

    display:none;
    flex-direction:column;

    overflow:hidden;

    z-index:9999;

    box-shadow:
        0 12px 28px rgba(0,0,0,0.12);
}

/* all items */

.pk-dropdown a,
.pk-logout-btn{

    width:100%;
    height:48px;
    padding:0 18px;
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none;
    font-size:14px;
    font-weight:500;
    color:#374151;
    background:none;
    border:none;
    cursor:pointer;
    transition:0.2s;
    font-family: 'Battambang', sans-serif;
}

/* icon alignment */

.pk-dropdown i,
.pk-logout-btn i{

    width:18px;

    text-align:center;

    font-size:14px;
}

/* hover */

.pk-dropdown a:hover{
    background:#f9fafb;
}

/* separator line */

.pk-dropdown-divider{
    height:1px;

    background:#e5e7eb;

    margin:4px 0;
}

/* logout */

.pk-logout-btn{
    color:#ef4444;
}

.pk-logout-btn:hover{
    background:#fef2f2;
    color:#dc2626;
}

/* AUTH BUTTONS */
.pk-auth-buttons{
    display:flex;
    align-items:center;
    gap:10px;
}

.pk-login-btn,
.pk-register-btn{
    text-decoration:none;
    padding:8px 18px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    transition:0.2s;
}

/* LOGIN */
.pk-login-btn{
    background:#6366f1;
    color:white;
    border:1px solid #ddd;
}

.pk-login-btn:hover{
    background:#4f46e5;
}

/* REGISTER */
.pk-register-btn{
    background:#6366f1;
    color:white;
}

.pk-register-btn:hover{
    background:#4f46e5;
}
/* =========================
   HAMBURGER
========================= */

.pk-menu-toggle{
    display:none;

    cursor:pointer;

    font-size:22px;
}

/* language switcher */
.lang-wrapper { position: relative; display: inline-block; }

.lang-trigger {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 12px; border-radius: 8px; cursor: pointer;
    border: 1px solid #e5e7eb; background: #fff;
    font-size: 14px; font-weight: 600; color: #374151;
    user-select: none; transition: background .15s;
}
.lang-trigger:hover { background: #f1f5f9; }

.lang-dropdown {
    display: none; position: absolute; top: calc(100% + 6px); right: 0;
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 12px; padding: 6px; min-width: 180px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08); z-index: 999;
}
.lang-wrapper.open .lang-dropdown { display: block; }
.lang-wrapper.open #langChevron { transform: rotate(180deg); }

.lang-option {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 8px; text-decoration: none;
    font-size: 14px; font-weight: 600; color: #374151;
    transition: background .12s;
}
.lang-option:hover { background: #f1f5f9; }

/* =========================
   TABLET
========================= */

@media(max-width:992px){

    .pk-username{
        display:none;
    }

    .pk-nav-links{
        gap:6px;
    }

    .pk-nav-links a{
        font-size:13px;
        padding:6px 8px;
    }
}

/* =========================
   MOBILE
========================= */

@media(max-width:768px){

    .pk-menu-toggle{
        display:block;
    }

    .pk-nav-links{

        display:none;

        position:absolute;

        top:100%;
        left:0;
        right:0;

        background:white;

        flex-direction:column;

        align-items:flex-start;

        border-top:1px solid #eee;

        box-shadow:
            0 10px 25px
            rgba(0,0,0,.08);
    }

    .pk-nav-links.show{
        display:flex;
    }

    .pk-nav-links a{

        width:100%;

        padding:14px 20px;

        border-radius:0;
    }

    .pk-profile-trigger i,
    .pk-username{
        display:none;
    }
}

/* =========================
   SMALL MOBILE
========================= */

@media(max-width:480px){

    .pk-navbar{
        padding:10px 14px;
    }

    .pk-logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pk-logo-img {
        width: 45px;
        height: 45px;
        object-fit: contain;
    }

    .pk-profile{
        width:32px;
        height:32px;
    }
}
</style>

@php
    $user = Auth::user();
@endphp

<div class="pk-navbar">

    <div class="pk-nav-left">

        <a href="{{ route('home') }}" class="pk-logo-link">
            <div class="pk-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Pakka Logo" class="pk-logo-img">
                <span>Pakka</span>
            </div>
        </a>

        <div class="pk-menu-toggle" onclick="pkToggleMenu()">
            <i class="fa-solid fa-bars"></i>
        </div>

    </div>

    <div class="pk-nav-links" id="pkNavLinks">

        <a href="/">
            <i class="fa-solid fa-house"></i>
            {{ __('ui.home') }}
        </a>

        <a href="/browse">
            <i class="fa-solid fa-magnifying-glass"></i>
            {{ __('ui.browse') }}
        </a>

        <a href="/writer/dashboard">
            <i class="fa-solid fa-pen"></i>
            {{ __('ui.write') }}
        </a>

        <a href="/mylibrary">
            <i class="fa-solid fa-book"></i>
            {{ __('ui.library') }}
        </a>

    </div>

    <div class="pk-nav-right">

        <!-- Language Switcher -->
    <div class="lang-wrapper" id="langWrapper">
        <div class="lang-trigger"
            onclick="toggleLangDropdown()"
            aria-haspopup="true"
            aria-expanded="false"
            id="langTrigger">

            @if(app()->getLocale() === 'km')
                <!-- Cambodia flag: blue / red / blue + Angkor Wat -->
                <svg width="24" height="16" viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
                <rect width="24" height="16" fill="#032EA1"/>
                <rect y="3.5" width="24" height="9" fill="#E00025"/>
                <g fill="#fff">
                    <rect x="6" y="11" width="12" height="1.2"/>
                    <rect x="6.5" y="9.5" width="2" height="1.5"/>
                    <polygon points="7.5,7.5 6.5,9.5 8.5,9.5"/>
                    <rect x="10.5" y="8" width="3" height="3"/>
                    <polygon points="12,5 10.5,8 13.5,8"/>
                    <rect x="15" y="9.5" width="2" height="1.5"/>
                    <polygon points="16,7.5 15,9.5 17,9.5"/>
                    <rect x="9" y="10.5" width="1.5" height="0.6"/>
                    <rect x="13.5" y="10.5" width="1.5" height="0.6"/>
                </g>
                </svg>
                ភាសាខ្មែរ
            @else
                <svg width="22" height="16" viewBox="0 0 22 16" xmlns="http://www.w3.org/2000/svg" aria-label="UK flag">
                    <rect width="22" height="16" fill="#012169"/>
                    <path d="M0 0L22 16M22 0L0 16" stroke="#fff" stroke-width="3"/>
                    <path d="M0 0L22 16M22 0L0 16" stroke="#C8102E" stroke-width="1.8"/>
                    <path d="M11 0V16M0 8H22" stroke="#fff" stroke-width="5"/>
                    <path d="M11 0V16M0 8H22" stroke="#C8102E" stroke-width="3"/>
                </svg>
                English
            @endif

            <i class="ti ti-chevron-down" id="langChevron" aria-hidden="true"></i>
        </div>

        <div class="lang-dropdown" id="langDropdown" role="listbox">

            <a href="{{ route('lang.switch', 'en') }}"
            class="lang-option {{ app()->getLocale() === 'en' ? 'active' : '' }}"
            role="option"
            aria-selected="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}">
                <svg width="22" height="16" viewBox="0 0 22 16" aria-hidden="true">
                    <rect width="22" height="16" fill="#012169"/>
                    <path d="M0 0L22 16M22 0L0 16" stroke="#fff" stroke-width="3"/>
                    <path d="M0 0L22 16M22 0L0 16" stroke="#C8102E" stroke-width="1.8"/>
                    <path d="M11 0V16M0 8H22" stroke="#fff" stroke-width="5"/>
                    <path d="M11 0V16M0 8H22" stroke="#C8102E" stroke-width="3"/>
                </svg>
                English
                @if(app()->getLocale() === 'en')
                    <i class="ti ti-check" style="margin-left:auto;color:#22c55e" aria-hidden="true"></i>
                @endif
            </a>

            <a href="{{ route('lang.switch', 'km') }}"
            class="lang-option {{ app()->getLocale() === 'km' ? 'active' : '' }}"
            role="option"
            aria-selected="{{ app()->getLocale() === 'km' ? 'true' : 'false' }}">
            <svg width="24" height="16" viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="16" fill="#032EA1"/>
            <rect y="3.5" width="24" height="9" fill="#E00025"/>
            <g fill="#fff">
                <rect x="6" y="11" width="12" height="1.2"/>
                <rect x="6.5" y="9.5" width="2" height="1.5"/>
                <polygon points="7.5,7.5 6.5,9.5 8.5,9.5"/>
                <rect x="10.5" y="8" width="3" height="3"/>
                <polygon points="12,5 10.5,8 13.5,8"/>
                <rect x="15" y="9.5" width="2" height="1.5"/>
                <polygon points="16,7.5 15,9.5 17,9.5"/>
                <rect x="9" y="10.5" width="1.5" height="0.6"/>
                <rect x="13.5" y="10.5" width="1.5" height="0.6"/>
            </g>
            </svg>
                ភាសាខ្មែរ
                @if(app()->getLocale() === 'km')
                    <i class="ti ti-check" style="margin-left:auto;color:#22c55e" aria-hidden="true"></i>
                @endif
            </a>

        </div>
    </div>

        @auth

        @php
            $unreadCount = auth()->user()->unreadNotifications->count();
        @endphp

        <a href="{{ route('notifications') }}"
           class="pk-notification-bell">

            <i class="fa-solid fa-bell"></i>

            @if($unreadCount)
                <span class="pk-notification-count">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif

        </a>

        <div class="pk-profile-wrapper">

            <div class="pk-profile-trigger"
                 onclick="pkToggleDropdown()">

                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}"
                         class="pk-profile">
                @else
                    <div class="pk-profile pk-profile-letter">
                        {{ strtoupper(substr($user->username,0,1)) }}
                    </div>
                @endif

                <span class="pk-username">
                    {{ $user->username }}
                </span>

                <i class="fa-solid fa-chevron-down"></i>

            </div>

            <div id="pkDropdown" class="pk-dropdown">

                <a href="/usereditprofile">
                    <i class="fa-solid fa-user"></i>
                    {{ __('ui.profile') }}
                </a>

                <a href="/userpassword">
                    <i class="fa-solid fa-lock"></i>
                    {{ __('ui.change_password') }}
                </a>

                <div class="pk-dropdown-divider"></div>
                
                <form method="POST"
                      action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="pk-logout-btn">

                        <i class="fa-solid fa-right-from-bracket"></i>
                        {{ __('ui.logout') }}

                    </button>

                </form>

            </div>

        </div>

        @else

        <div class="pk-auth-buttons">

            <a href="/login"
               class="pk-login-btn">
                {{ __('ui.login') }}
            </a>

            <a href="/register"
               class="pk-register-btn">
                {{ __('ui.register') }}
            </a>

        </div>

        @endauth

    </div>

</div>

<script>
function pkToggleMenu() {
    document
        .getElementById('pkNavLinks')
        .classList.toggle('show');
}

function pkToggleDropdown() {

    const menu =
        document.getElementById('pkDropdown');

    menu.style.display =
        menu.style.display === 'flex'
        ? 'none'
        : 'flex';
}

document.addEventListener('click', function(e){

    const wrapper =
        document.querySelector('.pk-profile-wrapper');

    const menu =
        document.getElementById('pkDropdown');

    if(wrapper &&
       !wrapper.contains(e.target)) {

        menu.style.display = 'none';
    }
});
</script>

<script>
function toggleLangDropdown() {
    const wrapper = document.getElementById('langWrapper');
    wrapper.classList.toggle('open');
}

// Close when clicking outside
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('langWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        wrapper.classList.remove('open');
    }
});
</script>

<!--Responsive-->
<script>
function toggleDropdown() {
    const menu = document.getElementById("dropdownMenu");
    menu.style.display = menu.style.display === "flex" ? "none" : "flex";
}

// mobile nav toggle
function toggleMenu() {
    const nav = document.querySelector(".nav-links");
    nav.classList.toggle("show");
}

// close dropdown when clicking outside
document.addEventListener("click", function(e) {
    const wrapper = document.querySelector(".profile-wrapper");
    const menu = document.getElementById("dropdownMenu");

    if (!wrapper.contains(e.target)) {
        menu.style.display = "none";
    }
});
</script>

