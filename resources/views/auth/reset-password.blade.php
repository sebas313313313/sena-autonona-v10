@extends('layouts.main')

@section('title', 'AGROVIDA - Restablecer Contraseña')

@section('content')
<div class="login-page">
    <div class="login-box">
        <div class="logo-container">
            <h2 class="login-title">Restablecer Contraseña</h2>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="login-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            <div class="form-group">
                <input type="password" 
                       class="form-input @error('password') is-invalid @enderror" 
                       name="password" 
                       placeholder="Nueva Contraseña"
                       required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" 
                       class="form-input" 
                       name="password_confirmation" 
                       placeholder="Confirmar Nueva Contraseña"
                       required>
            </div>

            <div class="form-group">
                <button type="submit" class="login-button">Restablecer Contraseña</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Reutilizamos los estilos del login */
.login-page {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background-image: url('../images/arbol.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.login-box {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.login-title {
    text-align: center;
    color: #198754;
    font-size: 24px;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.form-input:focus {
    border-color: #198754;
    outline: none;
}

.error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
    display: block;
}

.login-button {
    width: 100%;
    padding: 12px;
    background-color: #198754;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.login-button:hover {
    background-color: #146c43;
}

.alert {
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c2c7;
    color: #842029;
}
</style>
@endsection
