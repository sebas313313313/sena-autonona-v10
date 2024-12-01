<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AGROVIDA')</title>
    
    <!-- Estilos CSS -->
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: none;
            min-height: 100vh;
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @yield('styles')
</head>
<body>
    @yield('content')

    @yield('scripts')
</body>
</html>
