<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NodemailerService
{
    protected $nodeServer;

    public function __construct()
    {
        // URL donde estará corriendo nuestro servidor de Nodemailer
        $this->nodeServer = env('NODEMAILER_SERVER_URL', 'http://localhost:3000');
    }

    public function sendPasswordResetEmail($email, $token, $name)
    {
        try {
            $response = Http::post($this->nodeServer . '/send-reset-email', [
                'email' => $email,
                'token' => $token,
                'name' => $name,
                'resetUrl' => url("/password/reset/{$token}")
            ]);

            if ($response->successful()) {
                Log::info('Correo de recuperación enviado exitosamente', ['email' => $email]);
                return true;
            }

            Log::error('Error al enviar correo de recuperación', [
                'email' => $email,
                'response' => $response->json()
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Exception al enviar correo de recuperación', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
