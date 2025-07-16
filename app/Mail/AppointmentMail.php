<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $appointment;
    public $doctor;
    public $type;

    public function __construct($patient, $appointment, $doctor, $type = 'cancelled')
    {
        $this->patient = $patient;
        $this->appointment = $appointment;
        $this->doctor = $doctor;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->type === 'confirmed'
                ? 'Appointment Confirmed'
                : 'Appointment Cancelled'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
