<?php

namespace App\Mail;

use App\Models\SolicitudEquipo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitacionEquipoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitacion;

    public $nombreLider;

    public $nombreEquipo;

    /**
     * Create a new message instance.
     */
    public function __construct(SolicitudEquipo $invitacion)
    {
        $this->invitacion = $invitacion;
        $this->nombreEquipo = $invitacion->equipo->nombre;

        // Obtener el líder del equipo
        $lider = $invitacion->equipo->participantes()->wherePivot('es_lider', true)->first();
        $this->nombreLider = $lider ? $lider->user->name : 'El líder';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitación para Unirte a un Equipo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invitacion-equipo',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
