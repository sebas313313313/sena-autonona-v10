{{-- 
    Vista principal de autenticación de AGROVIDA
    Maneja el proceso de inicio de sesión y muestra mensajes de retroalimentación al usuario
--}}
@extends('layouts.main')

@section('title', 'AGROVIDA - Login')

@section('content')
<div class="login-page">
    <img src="{{ asset('images/arbol.png') }}" alt="Árbol decorativo" class="decorative-image">
    <div class="login-box">
        {{-- Logo personalizado de AGROVIDA con animación SVG --}}
        <div class="logo-container">
            <div class="logo-title-wrapper">
                <svg class="sun-logo" width="60" height="60" viewBox="0 0 60 60">
                    <circle cx="30" cy="30" r="15" fill="#22c55e" />
                    {{-- Genera 8 rayos del sol usando un bucle --}}
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
                <h1 class="logo-text">AGROVIDA</h1>
            </div>
            <h2 class="login-title">Iniciar Sesión</h2>
        </div>

        {{-- Sistema de autenticación con validación y mensajes de estado --}}
        <form method="POST" action="{{ route('login.submit') }}" class="login-form">
            @csrf
            
            {{-- Mensajes de éxito --}}
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- Mensajes de error --}}
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            {{-- Errores de validación --}}
            @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Campo de correo electrónico --}}
            <div class="form-group">
                <input type="email" 
                       class="form-input @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Correo electrónico"
                       required 
                       autofocus>
            </div>

            {{-- Campo de contraseña --}}
            <div class="form-group">
                <input type="password" 
                       class="form-input @error('password') is-invalid @enderror" 
                       name="password" 
                       placeholder="Contraseña"
                       required>
            </div>

            {{-- Checkbox de recordar contraseña --}}
            <div class="remember-check">
                <label class="remember-label">
                    <input type="checkbox" name="remember"> Recordar contraseña
                </label>
            </div>

            {{-- Botón de inicio de sesión --}}
            <div class="form-group">
                <button type="submit" class="login-button">Iniciar Sesión</button>
            </div>

            {{-- Enlace para registrarse --}}
            <div class="form-group text-center">
                <span class="register-text">¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate</a></span>
            </div>
        </form>
    </div>
</div>

{{-- Estilos responsivos y modernos para la interfaz de login --}}
<style>
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.login-box {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

.logo-container {
    text-align: center;
    margin-bottom: 2rem;
}

.logo-title-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.logo-text {
    font-size: 2rem;
    font-weight: bold;
    color: #000000;
    margin: 0;
}

.login-title {
    font-size: 1.5rem;
    color: #000000;
    margin: 0;
}

.sun-logo {
    width: 60px;
    height: 60px;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    font-size: 1rem;
}

.form-input:focus {
    outline: none;
    border-color: #3B82F6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.remember-check {
    width: 100%;
    text-align: center;
    margin-bottom: 1.5rem;
}

.remember-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #4B5563;
    cursor: pointer;
}

.remember-label input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    cursor: pointer;
}

.login-button {
    width: 100%;
    padding: 12px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: background 0.3s ease;
}

.login-button:hover {
    background: #45a049;
}

.register-text {
    color: #666;
    font-family: 'Poppins', sans-serif;
}

.register-text a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: 600;
}

.register-text a:hover {
    color: #45a049;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
    text-align: center;
}

.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert-success {
    background-color: #dcfce7;
    color: #22c55e;
    border: 1px solid #c6f6d5;
}

.is-invalid {
    border-color: #dc2626;
}

.decorative-image {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: -1;
    opacity: 1;
}
</style>
@endsection
