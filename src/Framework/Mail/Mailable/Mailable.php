<?php

namespace App\Framework\Mail\Mailable;

abstract class Mailable
{
    /**
     * Return the subject of the email.
     *
     * @return string
     */
    abstract public function subject(): string;

    /**
     * Return a array of attachments.
     *
     * @return array<int, mixed>
     */
    abstract public function attachments(): array;

    /**
     * Return the content of the email.
     *
     * @return string
     */
    abstract public function content(): string;
}