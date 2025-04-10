<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Dashboard</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .sidebar.collapsed .logo {
            justify-content: center;
        }

        .sidebar.collapsed .logo-icon {
            font-size: 1.25rem;
        }

        :root {
            --primary: #2563eb;
            --accent: #eff6ff;
            --background: #f3f4f6;
            --sidebar-bg: #ffffff;
            --nav-bg: #1e40af;
            --text-main: #1f2937;
            --text-light: #6b7280;
            --hover-bg: #e0f2fe;
        }
        .navbar.collapsed {
            left: 72px;
        }

        .main-content.collapsed {
            margin-left: 72px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
        }

        a {
            text-decoration: none;
        }

        .menu-item.active {
            background: linear-gradient(to right, var(--primary), #3b82f6);
            color: white;
            font-weight: 600;
        }

        .profile-name .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            min-width: 150px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            z-index: 999;
            background: white;
            border-radius: 0.5rem;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: var(--text-main);
            transition: background 0.3s;
        }

        .dropdown-item:hover {
            background: var(--hover-bg);
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            /*background: var(--sidebar-bg);*/
            padding: 2rem 1.5rem;
            border-right: 1px solid #e5e7eb;
            /*box-shadow: 2px 0 5px rgba(0,0,0,0.02);*/
            z-index: 100;
        }
        .sidebar {
            background: linear-gradient(to bottom, #ffffff, #f9fafb);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .logo-icon {
            font-size: 1.5rem;
            background: linear-gradient(45deg, #60a5fa, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-text {
            margin-left: 0.5rem;
            color: var(--primary);
            text-transform: uppercase;
            font-size: 1rem;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: var(--text-main);
            transition: 0.2s;
        }

        .menu-item:hover {
            background: var(--hover-bg);
        }

        .menu-item .icon {
            margin-right: 1rem;
        }

        .badge {
            background: #ef4444;
            color: white;
            border-radius: 1rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            margin-left: auto;
        }

        .navbar {
            position: fixed;
            left: 250px;
            right: 0;
            top: 0;
            height: 64px;
            background: var(--nav-bg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            border-bottom: 1px solid #e5e7eb;
            z-index: 90;
        }

        .nav-title {
            font-weight: bold;
            color: white;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--primary);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        .dropdown-menu {
            display: none;
        }

        .profile-name:hover .dropdown-menu {
            display: block;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 64px;
            padding: 2rem;
            min-height: calc(100vh - 64px);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 72px;
                padding: 1rem 0.5rem;
            }

            .menu-item span:not(.icon) {
                display: none;
            }

            .navbar {
                left: 72px;
                padding: 0 1rem;
            }

            .main-content {
                margin-left: 72px;
                padding: 1rem;
            }

            .nav-title {
                display: none;
            }
        }
        .sidebar.collapsed {
            width: 72px;
            overflow: hidden;
        }

        .sidebar.collapsed .menu-item span:not(.icon),
        .sidebar.collapsed .logo-text {
            display: none;
        }

    </style>
</head>
<body>
<div id="app">
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <div class="logo-icon">sTMS</div>
            <div class="logo-text">Dashboard</div>
        </a>
        <nav class="menu">
            <a href="/topics" class="menu-item {{ request()->is('topics') ? 'active' : '' }}">
                <span class="icon">📁</span>
                <span>Topics</span>
            </a>

            @auth
                @if(auth()->user()->isSupervisor())
                    <a href="{{ route('supervisor.dashboard') }}" class="menu-item {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
                        <span class="icon">👨‍🏫</span>
                        <span>Supervisor</span>
                        @php
                            $pendingCount = auth()->user()->supervisor->pendingTopics()->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('test-topic') }}" class="menu-item {{ request()->routeIs('test-topic') ? 'active' : '' }}">
                        <span class="icon">📥</span>
                        <span>Import Topics</span>
                    </a>
                @endif
            @endauth
        </nav>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <button id="sidebarToggle" class="text-white md:hidden">
            ☰
        </button>

        <div class="nav-title">
            <span style="color: white; font-size: 1.25rem;">Welcome, {{ Auth::user()->name }}</span>
        </div>
        <div class="profile-section">
            <div class="profile-pic">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset('storage/profile-pictures/' . Auth::user()->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                @endif
            </div>
            <div class="profile-name position-relative">
                <button id="profileDropdownBtn" style="background: none; border: none; color: white; font-weight: 500;">
                    {{ Auth::user()->name }} ▾
                </button>
                <div class="dropdown-menu" id="profileDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <div class="main-content">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</div>
<script>
    // Simple toggle for dropdown
    document.getElementById('profileDropdownBtn')?.addEventListener('click', function () {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const btn = document.getElementById('profileDropdownBtn');
        if (!dropdown.contains(event.target) && !btn.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        document.querySelector('.navbar').classList.toggle('collapsed');
        document.querySelector('.main-content').classList.toggle('collapsed');
    });
</script>
</body>
</html>
