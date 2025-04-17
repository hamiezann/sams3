<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'University Portal') }}</title>

    <!-- Fonts & Icons -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Roboto:400,500,700|Merriweather:400,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #1a3a5f;
            --secondary-color: #102a43;
            --accent-color: #c8102e;
            --light-gray: #f6f9fc;
            --medium-gray: #e6edf5;
            --text-light: #fff;
            --text-muted: #d0d6e2;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--light-gray);
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            padding: 0;
        }

        .navbar-brand-container {
            background-color: var(--secondary-color);
            padding: 0.8rem 1.5rem;
        }

        .navbar .navbar-brand {
            color: var(--text-light);
            font-weight: 700;
            font-size: 1.25rem;
            font-family: 'Merriweather', serif;
            letter-spacing: 0.5px;
            margin: 0;
            white-space: nowrap;
        }

        .navbar .navbar-nav {
            padding: 0 0.5rem;
        }

        .navbar .nav-link {
            color: var(--text-light) !important;
            margin: 0 0.25rem;
            padding: 1.25rem 1rem;
            font-weight: 500;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--text-light) !important;
            border-bottom: 3px solid var(--accent-color);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .navbar .nav-icon {
            margin-right: 6px;
            font-size: 0.85rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            padding: 0 1rem;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            background-color: rgba(255, 255, 255, 0.2);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .btn-logout {
            background-color: var(--accent-color);
            border: none;
            padding: 7px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            color: white;
            border-radius: 4px;
            letter-spacing: 0.3px;
            transition: background-color 0.2s ease;
            margin-left: 1rem;
        }

        .btn-logout:hover {
            background-color: #a30d24;
        }

        @media (max-width: 991.98px) {
            .navbar-nav {
                padding: 0.5rem 0;
            }

            .navbar .nav-link {
                padding: 0.75rem 1rem;
                border-left: 3px solid transparent;
                border-bottom: none;
            }

            .navbar .nav-link:hover,
            .navbar .nav-link.active {
                border-left: 3px solid var(--accent-color);
                border-bottom: none;
            }

            .user-info {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding: 1rem;
                margin-bottom: 0.5rem;
            }

            .btn-logout {
                margin: 0.5rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid px-0">
                <div class="navbar-brand-container">
                    <a class="navbar-brand" href="">
                        <i class="fas fa-university me-2"></i> {{ config('app.name', 'UNIVERSITY PORTAL') }}
                    </a>
                </div>

                <button class="navbar-toggler text-white me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side -->
                    <ul class="navbar-nav me-auto">
                        @auth
                        @if(Auth::user()->type === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}" href="{{ route('admin.home') }}">
                                <i class="fas fa-tachometer-alt nav-icon"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('activities.index') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                                <i class="fas fa-calendar-alt nav-icon"></i> Activity Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.students') ? 'active' : '' }}" href="{{ route('admin.students') }}">
                                <i class="fas fa-user-graduate nav-icon"></i> Student Registry
                            </a>
                        </li>
                        @elseif(Auth::user()->type === 'user')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fas fa-home nav-icon"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('student.activities') ? 'active' : '' }}" href="{{ route('student.activities') }}">
                                <i class="fas fa-calendar-alt nav-icon"></i> Activities
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('student.my-activity') ? 'active' : '' }}" href="{{ route('student.my-activity') }}">
                                <i class="fas fa-clipboard-check nav-icon"></i> My Enrollments
                            </a>
                        </li>
                        @endif
                        @endauth
                    </ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt nav-icon"></i> {{ __('Login') }}
                            </a>
                        </li>
                        @endif
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus nav-icon"></i> {{ __('Register') }}
                            </a>
                        </li>
                        @endif
                        @else
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div>{{ Auth::user()->name }}</div>
                                <small class="text-muted">{{ Auth::user()->type === 'admin' ? 'Administrator' : 'Student' }}</small>
                            </div>
                        </div>
                        <li class="nav-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-logout">
                                    <i class="fas fa-sign-out-alt me-1"></i> Sign Out
                                </button>
                            </form>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    @stack('styles')
</body>

</html>