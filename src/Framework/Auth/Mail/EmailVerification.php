<?php

declare(strict_types=1);

namespace App\Framework\Auth\Mail;

use App\Framework\Mail\Mailable\Mailable;
use App\Model\User;

class EmailVerification extends Mailable
{
    public function __construct(protected readonly User $user)
    {
    }

    /**
     * Return the subject of the email.
     *
     * @return string
     */
    public function subject(): string
    {
        return 'Confirm your email address';
    }

    /**
     * Return a array of attachments.
     *
     * @return array<string>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Return the content of the email.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return string
     */
    public function content(): string
    {
        $url = url('verify_email/' . $this->user->getVerificationToken());
        return viewAsText('auth.emails.email-verification', [
            'name' => $this->user->getName(),
            'url' => $url
        ]);
    }
}