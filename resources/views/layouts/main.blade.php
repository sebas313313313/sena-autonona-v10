<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AGROVIDA')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
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
