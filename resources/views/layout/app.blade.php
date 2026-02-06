<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Blood Donation System')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('dashboard') }}" class="nav-brand">
                <i class="fas fa-droplet"></i>
                <span>BloodConnect</span>
            </a>

            <div class="nav-menu">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.donors') }}" class="nav-link {{ request()->routeIs('admin.donors') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Donors
                        </a>
                        <a href="{{ route('admin.requests') }}" class="nav-link {{ request()->routeIs('admin.requests') ? 'active' : '' }}">
                            <i class="fas fa-hand-holding-medical"></i> Requests
                        </a>
                        <a href="{{ route('admin.map') }}" class="nav-link {{ request()->routeIs('admin.map') ? 'active' : '' }}">
                            <i class="fas fa-map-marked-alt"></i> Map
                        </a>
                        <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i> Reports
                        </a>
                    @elseif(auth()->user()->isHospital())
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                        <a href="{{ route('hospital.requests.index') }}" class="nav-link {{ request()->routeIs('hospital.requests.*') ? 'active' : '' }}">
                            <i class="fas fa-hand-holding-medical"></i> My Requests
                        </a>
                        <a href="{{ route('hospital.requests.create') }}" class="nav-link">
                            <i class="fas fa-plus-circle"></i> New Request
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                        <a href="{{ route('donor.profile') }}" class="nav-link {{ request()->routeIs('donor.profile') ? 'active' : '' }}">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <a href="{{ route('donor.requests') }}" class="nav-link {{ request()->routeIs('donor.requests') ? 'active' : '' }}">
                            <i class="fas fa-bell"></i> Requests
                        </a>
                        <a href="{{ route('donor.history') }}" class="nav-link {{ request()->routeIs('donor.history') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> History
                        </a>
                    @endif

                    <div class="nav-user">
                        <button class="user-btn" onclick="toggleUserMenu()">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('notifications.index') }}" class="dropdown-item">
                                <i class="fas fa-bell"></i> Notifications
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                {{ session('info') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('styles')
    @stack('scripts')
    @stack('scripts')
</body>
</html>