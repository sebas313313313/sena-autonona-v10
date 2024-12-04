<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AGROVIDA')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    
    <!-- Estilos CSS -->
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            min-height: 100vh;
            width: 100%;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            padding: 0.5rem 0;
        }
        .container {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .sun-logo {
            margin-right: 0.5rem;
            width: 32px;
            height: 32px;
        }
        .sun-logo circle,
        .sun-logo line {
            transition: all 0.3s ease;
        }
        .navbar-brand:hover .sun-logo circle {
            fill: #15803d;
        }
        .navbar-brand:hover .sun-logo line {
            stroke: #15803d;
        }
        .user-name {
            font-weight: 500;
            font-size: 0.9rem;
        }
        .user-name i {
            font-size: 1.1rem;
            vertical-align: middle;
        }
        .btn-outline-danger {
            padding: 0.25rem 0.75rem;
            font-size: 0.9rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <svg class="sun-logo" width="32" height="32" viewBox="0 0 60 60">
                    <circle cx="30" cy="30" r="15" fill="#22c55e" />
                    @for ($i = 0; $i < 8; $i++)
                        <line 
                            x1="{{ 30 + cos($i * pi() / 4) * 15 }}"
                            y1="{{ 30 + sin($i * pi() / 4) * 15 }}"
                            x2="{{ 30 + cos($i * pi() / 4) * 25 }}"
                            y2="{{ 30 + sin($i * pi() / 4) * 25 }}"
                            stroke="#22c55e"
                            stroke-width="4"
                            stroke-linecap="round"
                        />
                    @endfor
                </svg>
                AGROVIDA
            </a>
            @auth
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 text-dark user-name">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ auth()->user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    @yield('content')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
