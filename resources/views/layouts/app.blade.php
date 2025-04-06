<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
            /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fb;
            color: #333;
            padding: 0;
            overflow-x: hidden;
        }

        /* Navigation Bar */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            padding: 20px 30px;
            color: white;
        }
        header h1 {
            font-size: 24px;
        }
        header .nav-links a {
            margin-left: 20px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        header .nav-links a:hover {
            color: #ecf0f1;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        /* Welcome Section */
        .welcome-section {
            flex: 1 1 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .welcome-section h2 {
            font-size: 30px;
            margin-bottom: 10px;
            color: #34495e;
        }
        .welcome-section p {
            font-size: 16px;
            color: #7f8c8d;
        }

        /* Feature Cards */
        .card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            margin: 15px;
            flex: 1 1 calc(33% - 30px);
            text-align: center;
            padding: 25px;
            cursor: pointer;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .card-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: #3498db;
        }
        .card-title {
            font-size: 20px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .card-description {
            font-size: 14px;
            color: #7f8c8d;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }
            .card {
                flex: 1 1 100%;
                margin-bottom: 20px;
            }
        }

    </style>
</head>
<body>
    <div id="app">
        <header>
            <h1>Welcome to sTMS</h1>
            <div class="nav-links">
                <a href="#">Profile</a>
                <a href="#">Settings</a>

                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </header>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
