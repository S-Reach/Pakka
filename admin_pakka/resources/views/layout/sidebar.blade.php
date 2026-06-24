<style>

.admin-layout{
    display:flex;
    min-height:100vh;
}

/* =========================
   SIDEBAR
========================= */
.sidebar{
    width:250px;
    background:#fff;
    border-right:1px solid #e5e7eb;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    position:fixed;
    top:0;
    left:0;
    height:100vh;
}

.sidebar-top{
    padding:20px;
}

.logo{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.logo h2{
    font-size:28px;
    color:#111827;
}

.menu{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.menu a{
    color:#374151;
    padding:14px 16px;
    border-radius:12px;
    display:flex;
    align-items:center;
    gap:12px;
    transition:.3s;
    font-size:17px;
}

.menu a:hover{
    background:#f3f4f6;
}

.menu a.active{
    background:#eef2ff;
    color:#3158ff;
    font-weight:600;
}

.sidebar-bottom{
    padding:20px;
    border-top:1px solid #e5e7eb;
}

.admin-profile{
    display:flex;
    align-items:center;
    gap:12px;
}

.admin-avatar{
    width:45px;
    height:45px;
    border-radius:50%;
    background:#3158ff;
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:bold;
    font-size:18px;
}

.admin-info h4{
    font-size:16px;
    color:#111827;
}

.admin-info p{
    font-size:14px;
    color:#6b7280;
}

/* =========================
   MAIN CONTENT
========================= */
.main-wrapper{
    margin-left:250px;
    width:100%;
    padding:30px;
}

/* =========================
   PROFILE AREA
========================= */

.admin-profile{
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none;
    padding:12px;
    border-radius:12px;
    transition:.3s;
}

.admin-profile:hover{
    background:#f3f4f6;
}

.admin-avatar{
    width:45px;
    height:45px;
    border-radius:50%;
    background:#3158ff;
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:bold;
    flex-shrink:0;
}

.admin-info h4{
    margin:0;
    font-size:15px;
    color:#111827;
}

.admin-info p{
    margin:0;
    font-size:13px;
    color:#6b7280;
}

.bottom-actions{
    margin-top:15px;
    display:flex;
    flex-direction:column;
    gap:8px;
}

.logout-btn{
    width:100%;
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px 14px;
    border-radius:10px;
    text-decoration:none;
    border:none;
    background:none;
    cursor:pointer;
    color:#374151;
    font-size:15px;
    transition:.3s;
}

.settings-btn:hover{
    background:#eef2ff;
    color:#3158ff;
}

.logout-btn:hover{
    background:#fee2e2;
    color:#dc2626;
}

/* =========================
   COLLAPSED SIDEBAR
========================= */

.sidebar.collapsed {
    width: 80px;
}

.sidebar.collapsed .logo h2,
.sidebar.collapsed .menu span,
.sidebar.collapsed .admin-info {
    display: none;
}

.sidebar.collapsed .menu a {
    justify-content: center;
}

.sidebar.collapsed .admin-profile {
    justify-content: center;
}

/* Hide logout text when collapsed */
.sidebar.collapsed .logout-btn span {
    display: none;
}

/* Center logout icon when collapsed */
.sidebar.collapsed .logout-btn {
    justify-content: center;
}

.main-wrapper {
    transition: 0.3s ease;
}

.main-wrapper.expanded {
    margin-left: 80px;
}

/* =========================
   RESPONSIVE
========================= */
@media(max-width:768px){

    .sidebar{
        width:80px;
    }

    .logo h2,
    .menu span,
    .admin-info{
        display:none;
    }

    .menu a{
        justify-content:center;
    }

    .admin-profile{
        justify-content:center;
    }

    .main-wrapper{
        margin-left:80px;
    }
}
</style>

<div class="sidebar" id="sidebar">

    <div class="sidebar-top">

        <div class="logo">
            <h2>Admin Panel</h2>
            <i class="fas fa-bars toggle-btn" onclick="toggleSidebar()"></i>
        </div>

        <div class="menu">

            <a href="{{ route('admindashboard') }}"
               class="{{ request()->routeIs('admindashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-table-columns"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('contentmoderation') }}"
               class="{{ request()->routeIs('contentmoderation') ? 'active' : '' }}">
                <i class="fa-regular fa-file-lines"></i>
                <span>Content Moderation</span>
            </a>

            <a href="{{ route('reportedcontent') }}"
               class="{{ request()->routeIs('reportedcontent') ? 'active' : '' }}">
                <i class="fa-regular fa-flag"></i>
                <span>Reported Content</span>
            </a>

            <a href="{{ route('paymentverification') }}"
               class="{{ request()->routeIs('paymentverification') ? 'active' : '' }}">
                <i class="fa-regular fa-credit-card"></i>
                <span>Payment Verification</span>
            </a>

            <a href="{{ route('writerearnings') }}"
               class="{{ request()->routeIs('writerearnings') ? 'active' : '' }}">
                <i class="fa-regular fa-credit-card"></i>
                <span>Writer Earnings Payment</span>
            </a>

            <a href="{{ route('usermanagement') }}"
               class="{{ request()->routeIs('usermanagement') ? 'active' : '' }}">
                <i class="fa-regular fa-user"></i>
                <span>User Management</span>
            </a>

        </div>

    </div>

    <div class="sidebar-bottom">

        @php
            $admin = Auth::guard('admin')->user();
        @endphp

        <a href="{{ route('account.settings') }}" class="admin-profile">

            <div class="admin-avatar">
                {{ strtoupper(substr($admin->firstname ?? 'A', 0, 1)) }}
            </div>

            <div class="admin-info">
                <h4>
                    {{ $admin->firstname ?? 'Admin' }}
                    {{ $admin->lastname ?? '' }}
                </h4>

                <p>
                    {{ $admin->email ?? 'admin@email.com' }}
                </p>
            </div>

        </a>

        <div class="bottom-actions">
        
            <form action="{{ route('logout') }}"
                method="POST">

                @csrf

                <button type="submit"
                        class="logout-btn">

                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>

                </button>

            </form>

        </div>

    </div>

</div>

<script>
function toggleSidebar()
{
    const sidebar = document.getElementById('sidebar');
    const main = document.querySelector('.main-wrapper');

    sidebar.classList.toggle('collapsed');
    main.classList.toggle('expanded');
}
</script>