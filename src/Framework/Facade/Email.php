<?php

namespace App\Framework\Facade;

use App\Framework\Facade;
use App\Framework\Mail\Mailable\Mailable;
use App\Framework\Mail\Mailer\Mailer;

/**
 * @method static Mailer to(string $address = '', string $name = '')
 * @method static Mailer from(string $address = '', string $name = '')
 * @method static Mailer bcc(string $address = '', string $name = '')
 * @method static Mailer cc(string $address = '', string $name = '')
 * @method static Mailer secure(bool $value)
 * @method static Mailer debug(bool $value)
 * @method static void send(Mailable $mailable)
 */
class Email extends Facade
{

    /**
     * Return the registered alias for this facade.
     *
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return 'email';
    }
}
