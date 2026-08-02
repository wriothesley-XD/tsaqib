<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendaftaranFsiDiterima extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Registration $registration
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Baru FSI - '.$this->registration->nama_lengkap,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pendaftaran-fsi',
        );
    }
}
