<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendaftaranMasuk extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Baru: ' . $this->data['komunitas_nama'] . ' — ' . $this->data['nama_lengkap'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pendaftaran-masuk',
            with: ['data' => $this->data],
        );
    }
}
