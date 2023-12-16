<?php

declare(strict_types=1);

namespace App\Mail;

use App\Framework\Mail\Mailable\Mailable;

class TestEmail extends Mailable
{

    public function __construct()
    {
    }

    /**
     * Return the subject of the email.
     *
     * @return string
     */
    public function subject(): string
    {
        return 'Test Mail';
    }

    /**
     * Return a array of attachments.
     *
     * @return array<int, mixed>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Return the content of the email.
     *
     * @return string
     */
    public function content(): string
    {
        return viewAsText('emails.testemail', ['name' => 'Test Name']);
    }
}