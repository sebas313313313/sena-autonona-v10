@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Top Bar -->
<div class="top-bar">
    <div class="logo">
        <img src="{{ asset('images/sun-logo.svg') }}" alt="Logo">
        <span class="logo-text">Granjas</span>
    </div>
    <div class="user-menu">
        <button>
            {{ Auth::user()->name }}
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header-section">
        <h1>Granjas</h1>
        <p>Aquí están registradas todas las granjas que tienes hasta el momento.</p>
    </div>

    <div class="action-bar">
        <button class="create-button">
            <i class="fas fa-plus"></i>
            Crear Granja
        </button>
    </div>

    <div class="farms-grid">
        <!-- Granja 1 -->
        <div class="farm-card">
            <h3>Granja 1</h3>
            <ul class="farm-info">
                <li>Extensión: 250m²</li>
                <li>Ubicación: Sitio Cacao</li>
                <li>Coordenadas de latitud: xxxxx.xx</li>
                <li>Coordenadas de longitud: xxxxx.xx</li>
            </ul>
        </div>

        <!-- Granja 2 (ejemplo adicional) -->
        <div class="farm-card">
            <h3>Granja 2</h3>
            <ul class="farm-info">
                <li>Extensión: 180m²</li>
                <li>Ubicación: Sitio Palmeras</li>
                <li>Coordenadas de latitud: xxxxx.xx</li>
                <li>Coordenadas de longitud: xxxxx.xx</li>
            </ul>
        </div>

        <!-- Granja 3 (ejemplo adicional) -->
        <div class="farm-card">
            <h3>Granja 3</h3>
            <ul class="farm-info">
                <li>Extensión: 300m²</li>
                <li>Ubicación: Sitio Mangos</li>
                <li>Coordenadas de latitud: xxxxx.xx</li>
                <li>Coordenadas de longitud: xxxxx.xx</li>
            </ul>
        </div>
    </div>
</div>
@endsection
