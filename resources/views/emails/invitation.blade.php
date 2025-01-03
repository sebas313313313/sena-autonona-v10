@component('mail::message')
# Invitación para unirse a {{ $invitation->farm->name }}

Has sido invitado para unirte a la granja "{{ $invitation->farm->name }}" como {{ $invitation->role }}.

Para aceptar la invitación, haz clic en el siguiente botón:

@component('mail::button', ['url' => route('invitations.accept', ['token' => $invitation->token])])
Aceptar Invitación
@endcomponent

Esta invitación expirará en 24 horas.

Si no solicitaste esta invitación, puedes ignorar este correo.

Saludos,<br>
{{ config('app.name') }}
@endcomponent
