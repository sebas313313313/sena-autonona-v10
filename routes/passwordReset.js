const express = require('express');
const router = express.Router();
const { sendPasswordResetEmail } = require('../public/js/emailService');
const User = require('../models/User'); // Ajusta la ruta según tu estructura
const crypto = require('crypto');

// Ruta para solicitar recuperación de contraseña
router.post('/request-reset', async (req, res) => {
    try {
        const { email } = req.body;
        
        // Buscar usuario por email
        const user = await User.findOne({ where: { email } });
        if (!user) {
            return res.status(404).json({
                success: false,
                message: 'No se encontró un usuario con ese correo electrónico'
            });
        }

        // Generar token único
        const resetToken = crypto.randomBytes(32).toString('hex');
        const tokenExpiry = new Date(Date.now() + 3600000); // 1 hora de validez

        // Actualizar usuario con el token
        await user.update({
            password_reset_token: resetToken,
            password_reset_expires_at: tokenExpiry
        });

        // Enviar correo con Nodemailer
        await sendPasswordResetEmail(email, resetToken, user.name);

        res.json({
            success: true,
            message: 'Se ha enviado un enlace de recuperación a tu correo electrónico'
        });
    } catch (error) {
        console.error('Error en recuperación de contraseña:', error);
        res.status(500).json({
            success: false,
            message: 'Error al procesar la solicitud de recuperación'
        });
    }
});

// Ruta para verificar token y actualizar contraseña
router.post('/reset-password', async (req, res) => {
    try {
        const { token, password } = req.body;

        // Buscar usuario con token válido
        const user = await User.findOne({
            where: {
                password_reset_token: token,
                password_reset_expires_at: {
                    [Op.gt]: new Date() // Token no expirado
                }
            }
        });

        if (!user) {
            return res.status(400).json({
                success: false,
                message: 'El enlace de recuperación es inválido o ha expirado'
            });
        }

        // Actualizar contraseña y limpiar token
        await user.update({
            password: bcrypt.hashSync(password, 10),
            password_reset_token: null,
            password_reset_expires_at: null
        });

        res.json({
            success: true,
            message: 'Contraseña actualizada correctamente'
        });
    } catch (error) {
        console.error('Error al restablecer contraseña:', error);
        res.status(500).json({
            success: false,
            message: 'Error al restablecer la contraseña'
        });
    }
});

module.exports = router;
