<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FarmInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $farm;
    public $role;
    public $invitedBy;

    public function __construct($farm, $role, $invitedBy)
    {
        $this->farm = $farm;
        $this->role = $role;
        $this->invitedBy = $invitedBy;
    }

    public function build()
    {
        return $this->subject('Invitación a granja ' . $this->farm->name)
                    ->markdown('emails.farm-invitation');
    }
}
