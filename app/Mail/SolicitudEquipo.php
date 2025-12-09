<?php

namespace App\Mail;

use App\Models\SolicitudEquipo as SolicitudEquipoModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudEquipo extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;

    public $nombreParticipante;

    public $nombreEquipo;

    /**
     * Create a new message instance.
     */
    public function __construct(SolicitudEquipoModel $solicitud)
    {
        $this->solicitud = $solicitud;
        $this->nombreParticipante = $solicitud->participante->user->name;
        $this->nombreEquipo = $solicitud->equipo->nombre;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Solicitud para Unirse a tu Equipo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitud-equipo',
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
