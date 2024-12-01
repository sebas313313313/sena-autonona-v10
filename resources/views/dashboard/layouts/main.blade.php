<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Dashboard Assets -->
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/style.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-wrapper">
        <!-- Botón de Cerrar Sesión -->
        @include('dashboard.components.logout-button')
        
        <!-- Sidebar -->
        @include('dashboard.components.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            @include('dashboard.components.navbar')

            <!-- Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('dashboard-assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>