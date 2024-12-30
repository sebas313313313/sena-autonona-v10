@extends('layouts.main')

@section('title', 'AGROVIDA - Login')

@section('styles')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.login-page {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.decorative-image {
    position: fixed;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
    top: 0;
    left: 0;
}

.login-box {
    width: 100%;
    max-width: 400px;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    position: relative;
    z-index: 2;
    backdrop-filter: blur(5px);
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
    color: #22c55e;
    margin: 0;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
}

.login-title {
    font-size: 1.5rem;
    color: #374151;
    margin: 0;
    font-family: 'Poppins', sans-serif;
}

.spiral-sun-logo {
    width: 60px;
    height: 60px;
    position: relative;
}

.spiral-svg {
    width: 100%;
    height: 100%;
}

.spiral-path {
    animation: draw-spiral 3s linear forwards, rotate-spiral 20s linear infinite;
    stroke-dasharray: 400;
    stroke-dashoffset: 400;
    transform-origin: 30px 30px;
}

.rays {
    animation: rays-rotate 20s linear infinite;
    transform-origin: 30px 30px;
}

@keyframes draw-spiral {
    to {
        stroke-dashoffset: 0;
    }
}

@keyframes rotate-spiral {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes rays-rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 25px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #3B82F6;
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

.forgot-password-link {
    color: #666;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    font-weight: 600;
}

.forgot-password-link:hover {
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

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: block;
}

/* Responsive Design */
@media (max-width: 640px) {
    .login-box {
        margin: 1rem;
        padding: 1.5rem;
    }

    .logo-text {
        font-size: 1.75rem;
    }

    .login-title {
        font-size: 1.25rem;
    }
}

.modal-content {
    background: white;
    border-radius: 15px;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    background-color: #f8f9fa;
    border-radius: 15px 15px 0 0;
}

.modal-title {
    color: #198754;
    font-weight: 600;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1rem;
}

.btn-primary {
    background-color: #198754;
    border-color: #198754;
}

.btn-primary:hover {
    background-color: #157347;
    border-color: #157347;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #ced4da;
    padding: 0.5rem;
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

.form-label {
    color: #495057;
    font-weight: 500;
}
</style>
@endsection

@section('content')
<div class="login-page">
    <img src="{{ asset('images/arbol.png') }}" alt="Árbol decorativo" class="decorative-image">
    <div class="login-box">
        {{-- Logo personalizado de AGROVIDA con animación SVG --}}
        <div class="logo-container">
            <div class="logo-title-wrapper">
                <div class="spiral-sun-logo">
                    <svg viewBox="0 0 60 60" class="spiral-svg">
                        <!-- Espiral central -->
                        <path class="spiral-path" d="
                            M 30 15
                            A 15 15 0 0 1 45 30
                            A 15 15 0 0 1 30 45
                            A 15 15 0 0 1 15 30
                            A 15 15 0 0 1 30 15
                            
                            A 12 12 0 0 0 42 30
                            A 12 12 0 0 0 30 42
                            A 12 12 0 0 0 18 30
                            A 12 12 0 0 0 30 18
                            
                            A 9 9 0 0 1 39 30
                            A 9 9 0 0 1 30 39
                            A 9 9 0 0 1 21 30
                            A 9 9 0 0 1 30 21
                            
                            A 6 6 0 0 0 36 30
                            A 6 6 0 0 0 30 36
                            A 6 6 0 0 0 24 30
                            A 6 6 0 0 0 30 24
                            
                            A 3 3 0 0 1 33 30
                            A 3 3 0 0 1 30 33
                            A 3 3 0 0 1 27 30
                            A 3 3 0 0 1 30 27"
                            fill="none"
                            stroke="#22c55e"
                            stroke-width="1.5"
                        />
                        <!-- Rayos -->
                        <g class="rays">
                            <g class="ray" transform="rotate(0 30 30)">
                                <circle cx="30" cy="15" r="2" fill="red"/>
                            </g>
                            <g class="ray" transform="rotate(72 30 30)">
                                <circle cx="30" cy="15" r="2" fill="#red"/>
                            </g>
                            <g class="ray" transform="rotate(144 30 30)">
                                <circle cx="30" cy="15" r="2" fill="#red"/>
                            </g>
                            <g class="ray" transform="rotate(216 30 30)">
                                <circle cx="30" cy="15" r="2" fill="#red"/>
                            </g>
                            <g class="ray" transform="rotate(288 30 30)">
                                <circle cx="30" cy="15" r="2" fill="#red"/>
                            </g>
                        </g>
                    </svg>
                </div>
                <h1 class="logo-text">AGROVIDA</h1>
            </div>
            <h2 class="login-title">Iniciar Sesión</h2>
        </div>

        {{-- Sistema de autenticación con validación y mensajes de estado --}}
        <form method="POST" action="{{ route('login.submit') }}" class="login-form">
            @csrf
            <meta name="csrf-token" content="{{ csrf_token() }}">
            
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

            {{-- Campo de correo electrónico --}}
            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo de contraseña --}}
            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Enlace de recuperación de contraseña --}}
            <div class="form-group text-center">
                <a href="#" class="forgot-password-link" data-bs-toggle="modal" data-bs-target="#recoveryModal">¿Olvidaste tu contraseña?</a>
            </div>

            {{-- Botón de inicio de sesión --}}
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>

            {{-- Enlace para registrarse --}}
            <div class="form-group text-center">
                <span class="register-text">¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate</a></span>
            </div>
            <!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Icono de administrador -->
<div class="text-center mt-3">
    <a href="{{ route('superD.blank') }}" class="text-decoration-none">
        <i class="fas fa-user-shield" style="font-size: 24px; color: #22c55e;"></i>
    </a>
</div>
        </form>
    </div>
</div>

{{-- Modal de Recuperación de Contraseña --}}
<div class="modal fade" id="recoveryModal" tabindex="-1" aria-labelledby="recoveryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recoveryModalLabel">Recuperar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Paso 1: Formulario de correo electrónico --}}
                <form id="emailForm" class="recovery-step" data-step="1">
                    @csrf
                    <div class="mb-3">
                        <label for="recovery_email" class="form-label">Ingresa tu correo electrónico</label>
                        <input type="email" class="form-control" id="recovery_email" name="email" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>

                {{-- Paso 2: Formulario de pregunta de recuperación --}}
                <form id="questionForm" class="recovery-step" data-step="2" style="display: none;">
                    @csrf
                    <div class="mb-3">
                        <p class="recovery-question-text"></p>
                        <label for="recovery_answer" class="form-label">Tu respuesta</label>
                        <input type="text" class="form-control" id="recovery_answer" name="answer" required>
                        <input type="hidden" id="recovery_email_confirm" name="email">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="showRecoveryStep(1)">Atrás</button>
                        <button type="submit" class="btn btn-primary">Verificar</button>
                    </div>
                </form>

                {{-- Paso 3: Mensaje de confirmación --}}
                <div id="confirmationStep" class="recovery-step" data-step="3" style="display: none;">
                    <div class="alert alert-success">
                        <p>Se ha enviado un enlace de recuperación a tu correo electrónico.</p>
                        <p>Por favor, revisa tu bandeja de entrada y sigue las instrucciones para restablecer tu contraseña.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<!-- jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Luego Bootstrap Bundle con Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showRecoveryStep(step) {
    $('.recovery-step').hide();
    $(`[data-step="${step}"]`).show();
}

$(document).ready(function() {
    // Manejar el formulario de correo electrónico
    $('#emailForm').on('submit', function(e) {
        e.preventDefault();
        const email = $('#recovery_email').val();
        
        $.ajax({
            url: '{{ route("password.check-email") }}',
            type: 'POST',
            data: {
                email: email,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#recovery_email_confirm').val(email);
                    $('.recovery-question-text').text(response.question);
                    showRecoveryStep(2);
                } else {
                    alert(response.message || 'Correo electrónico no encontrado');
                }
            },
            error: function() {
                alert('Error al verificar el correo electrónico');
            }
        });
    });

    // Manejar el formulario de pregunta de recuperación
    $('#questionForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("password.check-answer") }}',
            type: 'POST',
            data: {
                email: $('#recovery_email_confirm').val(),
                answer: $('#recovery_answer').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showRecoveryStep(3);
                } else {
                    alert(response.message || 'Respuesta incorrecta');
                }
            },
            error: function() {
                alert('Error al verificar la respuesta');
            }
        });
    });
});
</script>
@endsection

@endsection
