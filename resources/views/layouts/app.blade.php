<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vellora - Katalog Film & Series</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #ffffff; }
        .navbar { background-color: #0a0a0a !important; }

        .dropdown-menu.bg-dark .dropdown-item:hover {
        background-color: #343a40 !important;
        color: #ffc107 !important; /* Berubah jadi kuning saat di-hover */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="{{ route('home') }}">VELLORA</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm mt-1 ms-2 fw-bold" href="{{ route('register') }}">Register</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                Halo, {{ Auth::user()->username }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary shadow" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item text-light" href="#">Profile</a>
                                </li>
                                
                                <li>
                                    <a class="dropdown-item text-light" href="{{ route('watchlist.index') }}">My Watchlist</a>
                                </li>
                                
                                <li><hr class="dropdown-divider border-secondary"></li>
                                
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>