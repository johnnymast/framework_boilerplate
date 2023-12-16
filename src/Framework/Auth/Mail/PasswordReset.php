<?php

declare(strict_types=1);

namespace App\Framework\Auth\Mail;

use App\Framework\Mail\Mailable\Mailable;
use App\Model\User;

class PasswordReset extends Mailable
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
        return 'Reset your password';
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
        $url = url('password/reset/'.$this->user->getPasswordToken());
        return viewAsText('auth.emails.email-password-reset', [
            'name' => $this->user->getName(),
            'url' => $url
        ]);
    }
}