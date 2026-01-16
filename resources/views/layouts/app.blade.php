<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IlmHub - Islamic Event Manager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    :root {
        --ilm-bg: #eef2ec;
        --ilm-bg-deep: #102814;
        --ilm-surface: #ffffff;
        --ilm-surface-soft: #f7faf7;
        --ilm-primary: #3d7b4a;
        --ilm-primary-soft: #e3f3e7;
        --ilm-primary-deep: #21512c;
        --ilm-accent: #9c7cf4;
        --ilm-accent-soft: #f0eaff;
        --ilm-border-subtle: #d5e3d6;
        --ilm-text-main: #123125;
        --ilm-text-muted: #6b7b6c;
        --ilm-radius-lg: 18px;
        --ilm-radius-md: 14px;
        --ilm-radius-pill: 999px;
        --ilm-shadow-soft: 0 18px 45px rgba(0, 0, 0, 0.08);
        --ilm-shadow-hover: 0 24px 60px rgba(0, 0, 0, 0.12);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        background: linear-gradient(135deg, #eef2ec 0%, #f0f7f0 50%, #e8f5e9 100%);
        color: var(--ilm-text-main);
        position: relative;
        overflow-x: hidden;
    }

    /* Decorative background elements */
    body::before {
        content: '';
        position: fixed;
        top: -20%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(61, 123, 74, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
        pointer-events: none;
        animation: float 20s ease-in-out infinite;
    }

    body::after {
        content: '';
        position: fixed;
        bottom: -20%;
        left: -10%;
        width: 450px;
        height: 450px;
        background: radial-gradient(circle, rgba(156, 124, 244, 0.06) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
        pointer-events: none;
        animation: float 25s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.05); }
        66% { transform: translate(-20px, 20px) scale(0.95); }
    }

    .ilm-shell {
        padding-bottom: 4rem;
        position: relative;
        z-index: 1;
    }

    /* Enhanced Navbar */
    .navbar {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
        border-bottom: 1px solid rgba(213, 227, 214, 0.5);
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .navbar.scrolled {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        background: rgba(255, 255, 255, 1);
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        font-weight: 800;
        font-size: 1.35rem;
        letter-spacing: 0.02em;
        color: var(--ilm-primary-deep) !important;
        transition: transform 0.3s ease;
    }

    .navbar-brand:hover {
        transform: translateY(-2px);
    }

    .navbar-brand::before {
        content: "🌙";
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: linear-gradient(135deg, #fef9c3 0%, #facc15 50%, #a3e635 100%);
        box-shadow: 0 4px 16px rgba(250, 204, 21, 0.35), 0 0 0 4px rgba(226, 242, 228, 0.8);
        transition: all 0.3s ease;
    }

    .navbar-brand:hover::before {
        transform: rotate(20deg) scale(1.1);
        box-shadow: 0 6px 24px rgba(250, 204, 21, 0.5), 0 0 0 5px rgba(226, 242, 228, 1);
    }

    .navbar-nav {
        align-items: center;
        gap: 0.35rem;
    }

    .nav-link {
        position: relative;
        padding: 0.5rem 1.1rem !important;
        border-radius: var(--ilm-radius-pill);
        font-weight: 500;
        font-size: 0.95rem;
        color: var(--ilm-text-muted) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: var(--ilm-primary);
        transition: all 0.3s ease;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .nav-link.fw-bold,
    .nav-link.active {
        color: var(--ilm-primary-deep) !important;
        background-color: rgba(61, 123, 74, 0.1);
        font-weight: 600;
    }

    .nav-link.fw-bold::after,
    .nav-link.active::after {
        width: 24px;
    }

    .nav-link:hover {
        color: var(--ilm-primary-deep) !important;
        background-color: rgba(61, 123, 74, 0.15);
        transform: translateY(-1px);
    }

    /* Enhanced Buttons */
    .ilm-cta-primary {
        border-radius: var(--ilm-radius-pill);
        padding: 0.65rem 1.8rem;
        font-weight: 600;
        font-size: 0.95rem;
        background: linear-gradient(135deg, var(--ilm-primary) 0%, var(--ilm-primary-deep) 100%);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 8px 24px rgba(35, 76, 44, 0.35);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .ilm-cta-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .ilm-cta-primary:hover::before {
        left: 100%;
    }

    .ilm-cta-primary:hover {
        background: linear-gradient(135deg, var(--ilm-primary-deep) 0%, #14321c 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 36px rgba(35, 76, 44, 0.45);
    }

    .ilm-cta-primary:active {
        transform: translateY(0);
    }

    .ilm-cta-outline {
        border-radius: var(--ilm-radius-pill);
        padding: 0.55rem 1.5rem;
        font-weight: 500;
        font-size: 0.95rem;
        border: 2px solid rgba(148, 163, 153, 0.6);
        color: var(--ilm-text-main);
        background-color: rgba(255, 255, 255, 0.7);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ilm-cta-outline:hover {
        border-color: var(--ilm-primary);
        background-color: rgba(255, 255, 255, 0.98);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        color: var(--ilm-primary-deep);
    }

    /* Main Container */
    .ilm-main-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Enhanced Cards */
    .ilm-page-card {
        background: linear-gradient(135deg, var(--ilm-surface) 0%, var(--ilm-surface-soft) 100%);
        border-radius: var(--ilm-radius-lg);
        padding: 2rem 1.75rem;
        box-shadow: 0 8px 32px rgba(15, 23, 15, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(213, 227, 214, 0.5);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .ilm-page-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(61, 123, 74, 0.04) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .ilm-page-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(15, 23, 15, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    /* Enhanced Alerts */
    .alert {
        border-radius: var(--ilm-radius-md);
        border: none;
        padding: 1.1rem 1.35rem;
        font-weight: 500;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        animation: slideInDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #ecfdf3 0%, #dcfce7 100%);
        border-left: 4px solid #22c55e;
        color: #166534;
    }

    .alert-success::before {
        content: '✓';
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: #22c55e;
        color: white;
        border-radius: 50%;
        margin-right: 0.85rem;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-left: 4px solid #f59e0b;
        color: #92400e;
    }

    .alert-info {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid #3b82f6;
        color: #1e40af;
    }

    /* Form Enhancements */
    .form-control:focus,
    .form-select:focus {
        border-color: var(--ilm-primary);
        box-shadow: 0 0 0 0.25rem rgba(61, 123, 74, 0.15);
        outline: none;
    }

    .form-control,
    .form-select {
        border-radius: var(--ilm-radius-md);
        border: 2px solid rgba(213, 227, 214, 0.6);
        transition: all 0.2s ease;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: rgba(61, 123, 74, 0.3);
    }

    /* Button in form (logout) */
    .btn.nav-link {
        border: none;
        background: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn.nav-link:hover {
        color: var(--ilm-primary-deep) !important;
    }

    /* Dropdown menu styling */
    .dropdown-menu {
        border-radius: var(--ilm-radius-md);
        border: 1px solid var(--ilm-border-subtle);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        padding: 0.5rem;
        animation: fadeInScale 0.2s ease;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: rgba(61, 123, 74, 0.1);
        color: var(--ilm-primary-deep);
    }

    /* Responsive Improvements */
    @media (max-width: 768px) {
        .navbar-brand::before {
            width: 36px;
            height: 36px;
            font-size: 1.3rem;
        }

        .navbar-brand {
            font-size: 1.2rem;
        }

        .ilm-page-card {
            padding: 1.5rem 1.25rem;
        }

        body::before,
        body::after {
            width: 300px;
            height: 300px;
        }
    }

    /* Loading animation for transitions */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Smooth scroll */
    html {
        scroll-behavior: smooth;
    }

    /* Table improvements */
    .table {
        background: white;
        border-radius: var(--ilm-radius-md);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .table thead {
        background: linear-gradient(135deg, var(--ilm-primary-soft) 0%, rgba(227, 243, 231, 0.6) 100%);
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(61, 123, 74, 0.04);
    }

    /* Badge styling */
    .badge {
        padding: 0.45rem 0.85rem;
        border-radius: var(--ilm-radius-pill);
        font-weight: 500;
        font-size: 0.85rem;
    }

    /* Card improvements */
    .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(213, 227, 214, 0.5);
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }
</style>
</head>
<body>

    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container ilm-main-container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">IlmHub</a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#ilmNavbar" aria-controls="ilmNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="ilmNavbar">
                <ul class="navbar-nav ms-auto">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('home') ? 'fw-bold' : '' }}" href="{{ route('home') }}">Home</a>
    </li>
    
    @guest
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('login') ? 'fw-bold' : '' }}" href="{{ route('login') }}">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('register') ? 'fw-bold' : '' }}" href="{{ route('register') }}">Sign Up</a>
        </li>
    @endguest

    @auth
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('events.create') ? 'fw-bold' : '' }}" href="{{ route('events.create') }}">Create Event</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('events.mine') || request()->routeIs('events.edit') ? 'fw-bold' : '' }}" href="{{ route('events.mine') }}">My Events</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('bookings.index') ? 'fw-bold' : '' }}" href="{{ route('bookings.index') }}">My Bookings</a>
        </li>
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn nav-link" style="border: none; background: none;">Logout</button>
            </form>
        </li>
    @endauth
</ul>
            </div>
        </div>
    </nav>

    <div class="container ilm-main-container ilm-shell">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>