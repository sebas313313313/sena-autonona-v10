const nodemailer = require('nodemailer');

// Configuración del transporter de Nodemailer
const transporter = nodemailer.createTransport({
    host: 'smtp.gmail.com',
    port: 587,
    secure: false,
    auth: {
        user: 'tu_correo@gmail.com', // Reemplazar con tu correo
        pass: 'tu_contraseña_de_aplicacion' // Reemplazar con tu contraseña de aplicación de Google
    }
});

// Función para enviar el correo de recuperación
async function sendPasswordResetEmail(userEmail, resetToken, userName) {
    try {
        const resetLink = `${process.env.APP_URL}/password/reset/${resetToken}`;
        
        const mailOptions = {
            from: '"AGROVIDA" <tu_correo@gmail.com>',
            to: userEmail,
            subject: 'Recuperación de Contraseña - AGROVIDA',
            html: `
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .button {
                            background-color: #198754;
                            color: white;
                            padding: 12px 24px;
                            text-decoration: none;
                            border-radius: 5px;
                            display: inline-block;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2 style="color: #198754;">Recuperación de Contraseña - AGROVIDA</h2>
                        
                        <p>Hola ${userName},</p>
                        
                        <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                        
                        <p style="margin: 30px 0;">
                            <a href="${resetLink}" class="button">
                                Restablecer Contraseña
                            </a>
                        </p>
                        
                        <p>Este enlace expirará en 1 hora por motivos de seguridad.</p>
                        
                        <p>Si no solicitaste restablecer tu contraseña, puedes ignorar este correo.</p>
                        
                        <p>Saludos,<br>Equipo AGROVIDA</p>
                    </div>
                </body>
                </html>
            `
        };

        const info = await transporter.sendMail(mailOptions);
        console.log('Correo enviado:', info.messageId);
        return true;
    } catch (error) {
        console.error('Error al enviar el correo:', error);
        throw error;
    }
}

module.exports = {
    sendPasswordResetEmail
};
