{{-- 
    Vista principal de autenticación de AGROVIDA
    Maneja el proceso de inicio de sesión y muestra mensajes de retroalimentación al usuario
--}}
@extends('layouts.main')

@section('title', 'AGROVIDA - Login')

@section('content')
<div class="login-page">
    <div class="login-box">
        {{-- Logo personalizado de AGROVIDA con animación SVG --}}
        <div class="logo-container">
            <svg class="sun-logo" width="60" height="60" viewBox="0 0 60 60">
                <circle cx="30" cy="30" r="15" fill="#3B82F6" />
                {{-- Genera 8 rayos del sol usando un bucle --}}
                @for ($i = 0; $i < 8; $i++)
                    <line 
                        x1="{{ 30 + cos($i * pi() / 4) * 15 }}"
                        y1="{{ 30 + sin($i * pi() / 4) * 15 }}"
                        x2="{{ 30 + cos($i * pi() / 4) * 25 }}"
                        y2="{{ 30 + sin($i * pi() / 4) * 25 }}"
                        stroke="#3B82F6"
                        stroke-width="4"
                        stroke-linecap="round"
                    />
                @endfor
            </svg>
            <h1 class="logo-text">AGROVIDA</h1>
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

            {{-- Opción de recordar contraseña --}}
            <div class="form-group remember-me">
                <label>
                    <input type="checkbox" name="remember"> Recordar contraseña
                </label>
            </div>

            {{-- Botón de inicio de sesión --}}
            <div class="form-group">
                <button type="submit" class="login-button">Iniciar Sesión</button>
            </div>

            {{-- Enlace para registro --}}
            <div class="register-link">
                ¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
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
    background-color: #f3f4f6;
}

.login-box {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

.logo-container {
    text-align: center;
    margin-bottom: 2rem;
}

.logo-text {
    color: #3B82F6;
    margin-top: 1rem;
    font-size: 1.5rem;
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

.remember-me {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.login-button {
    width: 100%;
    padding: 0.75rem;
    background-color: #3B82F6;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.login-button:hover {
    background-color: #2563eb;
}

.register-link {
    text-align: center;
    font-size: 0.875rem;
    color: #6b7280;
}

.register-link a {
    color: #3B82F6;
    text-decoration: none;
}

.register-link a:hover {
    text-decoration: underline;
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
</style>
@endsection
