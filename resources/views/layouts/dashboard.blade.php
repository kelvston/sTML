<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Dashboard</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4F46E5;
            --accent: #E0E7FF;
            --background: #F9FAFB;
            --sidebar-bg: #FFFFFF;
            --nav-bg: #FFFFFF;
            --text-main: #111827;
            --text-light: #6B7280;
            --hover-bg: #EEF2FF;
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

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: var(--sidebar-bg);
            padding: 2rem 1.5rem;
            border-right: 1px solid #e5e7eb;
            box-shadow: 2px 0 5px rgba(0,0,0,0.02);
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .logo-icon {
            font-size: 1.5rem;
            background: linear-gradient(45deg, #6366f1, #4f46e5);
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

        .menu-item.active {
            background: var(--primary);
            color: white;
        }

        .menu-item .icon {
            margin-right: 1rem;
        }

        .badge {
            background: red;
            color: white;
            border-radius: 1rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            margin-left: auto;
        }

        /* Navbar */
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
            position: absolute;
            top: 60px;
            right: 20px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            display: none;
        }

        .profile-name:hover .dropdown-menu {
            display: block;
        }

        /* Content */
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
            <a href="/topics" class="menu-item">
                <span class="icon">📁</span>
                <span>Topics</span>
            </a>

            @auth
                @if(auth()->user()->isSupervisor())
                    <a href="{{ route('supervisor.dashboard') }}" class="menu-item">
                        <span class="icon">👨‍🏫</span>
                        <span>Supervisor Dashboard</span>
                        @php
                            $pendingCount = auth()->user()->supervisor->pendingTopics()->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('test-topic') }}" class="menu-item">
                        <span class="icon">📥</span>
                        <span>Import Topics</span>
                    </a>
                @endif
            @endauth
        </nav>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-title">
            <a href="#" class="logo">
                <div class="logo-icon">sTMS</div>
            </a>
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
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu">
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

    <!-- Main Content -->
    <div class="main-content">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
