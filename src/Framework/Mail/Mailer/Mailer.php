<?php

namespace App\Framework\Mail\Mailer;

use App\Framework\Config\Config;
use App\Framework\Mail\Mailable\Mailable;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    /**
     * The email address to send the email to.
     *
     * @var array<string, mixed>
     */
    protected array $to = [];

    /**
     * The email address to send the email from.
     *
     * @var array<string, mixed>
     */
    protected array $from = [];

    /**
     * The email address to send a blind carbon copy
     * to.
     *
     * @var array<string, mixed>
     */
    protected array $bcc = [];

    /**
     * The email address to send a carbon copy.
     *
     * @var array<string, mixed>
     */
    protected array $cc = [];

    /**
     * Debug mode yes or no.
     *
     * @var bool
     */
    private bool $debug = false;

    /**
     * Add a recipient to the email.
     *
     * @param string $address The email address to send the email to.
     * @param string $name    The name of the email address.
     *
     * @return $this
     */
    public function to(string $address = '', string $name = ''): Mailer
    {
        $this->to[] = [
            'address' => $address,
            'name' => $name
        ];
        return $this;
    }

    /**
     * Add a from address for the email.
     *
     * @param string $address The email address to send the email to.
     * @param string $name    The name of the email address.
     *
     * @return $this
     */
    public function from(string $address = '', string $name = ''): Mailer
    {
        $this->from = [
            'address' => $address,
            'name' => $name
        ];
        return $this;
    }

    /**
     * Add a blind carbon copy to the email.
     *
     * @param string $address The email address to send the email to.
     * @param string $name    The name of the email address.
     *
     * @return $this
     */
    public function bcc(string $address = '', string $name = ''): Mailer
    {
        $this->bcc[] = [
            'address' => $address,
            'name' => $name
        ];
        return $this;
    }

    /**
     * Add a carbon copy to the email.
     *
     * @param string $address The email address to send the email to.
     * @param string $name    The name of the email address.
     *
     * @return $this
     */
    public function cc(string $address = '', string $name = ''): Mailer
    {
        $this->cc[] = [
            'address' => $address,
            'name' => $name
        ];
        return $this;
    }

    /**
     * Enable to use ssl.
     *
     * @param bool $value True or false.
     *
     * @return $this
     */
    public function secure(bool $value): Mailer
    {
        $this->secure = $value;
        return $this;
    }

    /**
     * Enable debug mode.
     *
     * @param bool $value True or false.
     *
     * @return $this
     */
    public function debug(bool $value): Mailer
    {
        $this->debug = $value;
        return $this;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send(Mailable $mailable): void
    {
        $mailer = new PHPMailer(true);

        $general = config('email.general');
        $settings = config('email.settings');

        foreach ($this->to as $to) {
            $mailer->addAddress($to['address'], $to['name']);
        }

        foreach ($this->cc as $cc) {
            $mailer->addCC($cc['address'], $cc['name']);
        }

        foreach ($this->bcc as $bcc) {
            $mailer->addBCC($bcc['address'], $bcc['name']);
        }

        foreach ($mailable->attachments() as $attachment) {
            $mailer->addAttachment($attachment);
        }

        if (!isset($this->from['address'])) {
            $mailer->setFrom($general['from']['address'], $general['from']['name']);
        } else {
            $mailer->setFrom($this->from['address'], $this->from['name']);
        }

        $mailer->isSMTP();
        $mailer->Host = $settings['host'];                     //Set the SMTP server to send through
        $mailer->SMTPAuth = true;                            //Enable SMTP authentication
        $mailer->Username = $settings['user'];                 //SMTP username
        $mailer->Password = $settings['password'];             //SMTP password
        $mailer->Port = $settings['port'];                     //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        if ($this->debug) {
            $mailer->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        }

        if ($settings['secure']) {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;     //Enable implicit TLS encryption
        }
        $mailer->SMTPAutoTLS = false;


        $mailer->isHTML(true);
        $mailer->Subject = $mailable->subject();
        $mailer->Body = $mailable->content();
        $mailer->AltBody = $mailable->content();
        $mailer->send();
    }
}