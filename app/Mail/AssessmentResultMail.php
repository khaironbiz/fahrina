<?php

namespace App\Mail;

use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssessmentResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Assessment $assessment,
        public string $pdfAbsolutePath
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[STAFF-ERGO] Hasil Skrining Ergonomi Tempat Kerja - {$this->assessment->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.assessment_result',
        );
    }

    public function attachments(): array
    {
        if (file_exists($this->pdfAbsolutePath)) {
            return [
                Attachment::fromPath($this->pdfAbsolutePath)
                    ->as("Hasil-Skrining-Ergonomi-{$this->assessment->name}.pdf")
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
