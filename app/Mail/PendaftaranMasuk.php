<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendaftaranMasuk extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Open Recruitment FSI: ' . $this->registration->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pendaftaran-masuk',
            with: ['registration' => $this->registration],
        );
    }
}
