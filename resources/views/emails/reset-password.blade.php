<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña - AGROVIDA</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #198754;">Recuperación de Contraseña - AGROVIDA</h2>
        
        <p>Hola {{ $name }},</p>
        
        <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
        
        <p style="margin: 30px 0;">
            <a href="{{ route('password.reset', ['token' => $token]) }}" 
               style="background-color: #198754; 
                      color: white; 
                      padding: 12px 24px; 
                      text-decoration: none; 
                      border-radius: 5px;
                      display: inline-block;">
                Restablecer Contraseña
            </a>
        </p>
        
        <p>Este enlace expirará en 1 hora por motivos de seguridad.</p>
        
        <p>Si no solicitaste restablecer tu contraseña, puedes ignorar este correo.</p>
        
        <p>Saludos,<br>Equipo AGROVIDA</p>
    </div>
</body>
</html>
