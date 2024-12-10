@extends('layouts.main')

@section('title', 'AGROVIDA - Registro')

@section('content')
<div class="register-page">
    <img src="{{ asset('images/pez.png') }}" alt="Pez decorativo" class="decorative-image">
    <div class="register-box">
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
                                <circle cx="30" cy="15" r="2" fill="#22c55e"/>
                            </g>
                            <g class="ray" transform="rotate(72 30 30)">
                                <circle cx="30" cy="15" r="2" fill="#22c55e"/>
                            </g>
                            <g class="ray" transform="rotate(144 30 30)">
                                <circle cx="30" cy="15" r="2" fill="#22c55e"/>
                            </g>
                            <g class="ray" transform="rotate(216 30 30)">
                                <circle cx="30" cy="15" r="2" fill="#22c55e"/>
                            </g>
                            <g class="ray" transform="rotate(288 30 30)">
                                <circle cx="30" cy="15" r="2" fill="#22c55e"/>
                            </g>
                        </g>
                    </svg>
                </div>
                <h1 class="logo-text">AGROVIDA</h1>
            </div>
            <h2 class="register-title">Registro</h2>
        </div>

        <form method="POST" action="{{ route('register.submit') }}" class="register-form">
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

            <!-- Información básica del usuario -->
            <div class="form-group">
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Correo electrónico"
                       required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       placeholder="Contraseña"
                       required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" 
                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                       name="password_confirmation" 
                       placeholder="Confirmar contraseña"
                       required>
                @error('password_confirmation')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pregunta y respuesta de seguridad -->
            <div class="form-group">
                <select class="form-control @error('security_question_id') is-invalid @enderror" 
                        name="security_question_id" 
                        required>
                    <option value="">Selecciona una pregunta de seguridad</option>
                    @foreach($securityQuestions as $question)
                        <option value="{{ $question->id }}" {{ old('security_question_id') == $question->id ? 'selected' : '' }}>
                            {{ $question->question }}
                        </option>
                    @endforeach
                </select>
                @error('security_question_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-control @error('security_answer') is-invalid @enderror" 
                       name="security_answer" 
                       value="{{ old('security_answer') }}" 
                       placeholder="Respuesta de seguridad"
                       required>
                @error('security_answer')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Información detallada del usuario -->
            <div class="form-group">
                <select class="form-control @error('identification_type_id') is-invalid @enderror" 
                        name="identification_type_id" 
                        required>
                    <option value="">Tipo de identificación</option>
                    @foreach($identificationTypes as $type)
                        <option value="{{ $type->id }}" {{ old('identification_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->description }}
                        </option>
                    @endforeach
                </select>
                @error('identification_type_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-control @error('identification') is-invalid @enderror" 
                       name="identification" 
                       value="{{ old('identification') }}" 
                       placeholder="Número de identificación"
                       maxlength="15"
                       required>
                @error('identification')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="Nombres"
                       maxlength="50"
                       required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-control @error('last_name') is-invalid @enderror" 
                       name="last_name" 
                       value="{{ old('last_name') }}" 
                       placeholder="Apellidos"
                       maxlength="50"
                       required>
                @error('last_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="date" 
                       class="form-control @error('birth_date') is-invalid @enderror" 
                       name="birth_date" 
                       value="{{ old('birth_date') }}" 
                       required>
                @error('birth_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <select class="form-control @error('municipality_id') is-invalid @enderror" 
                        name="municipality_id" 
                        required>
                    <option value="">Municipio</option>
                    @foreach($municipalities as $municipality)
                        <option value="{{ $municipality->id }}" {{ old('municipality_id') == $municipality->id ? 'selected' : '' }}>
                            {{ $municipality->name }}
                        </option>
                    @endforeach
                </select>
                @error('municipality_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-control @error('direction') is-invalid @enderror" 
                       name="direction" 
                       value="{{ old('direction') }}" 
                       placeholder="Dirección"
                       maxlength="100"
                       required>
                @error('direction')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="tel" 
                       class="form-control @error('contact_number') is-invalid @enderror" 
                       name="contact_number" 
                       value="{{ old('contact_number') }}" 
                       placeholder="Teléfono de contacto"
                       maxlength="15"
                       pattern="[0-9]{10}"
                       required>
                @error('contact_number')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
            </div>

            <div class="form-group text-center">
                <span class="login-text">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia Sesión</a></span>
            </div>
        </form>
    </div>
</div>

<style>
.register-page {
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

.register-box {
    width: 100%;
    max-width: 400px;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    position: relative;
    z-index: 2;
    margin: 1rem;
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

.register-title {
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

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 25px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #3B82F6;
}

.btn-primary {
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

.btn-primary:hover {
    background: #45a049;
}

.login-text {
    color: #666;
    font-family: 'Poppins', sans-serif;
}

.login-text a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: 600;
}

.login-text a:hover {
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

.invalid-feedback {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: block;
}

/* Responsive Design */
@media (max-width: 640px) {
    .register-box {
        margin: 1rem;
        padding: 1.5rem;
    }

    .logo-text {
        font-size: 1.75rem;
    }

    .register-title {
        font-size: 1.25rem;
    }
}
</style>
@endsection
