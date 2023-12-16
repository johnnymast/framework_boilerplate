<?php

namespace App\Framework\Csrf;

use App\Framework\Exceptions\Csrf\ExpiredCSRFToken;
use App\Framework\Exceptions\Csrf\InvalidCSRFToken;
use App\Framework\Session\Facade\Session;

class CsrfProtection
{

    /**
     * Store csrf information in the session with this
     * key.
     *
     * @var string
     */
    protected string $sessionKey = '_csf';

    /**
     * Check to see if the CSRF information is allowed to bne set.
     * This is the case if the session has started.
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return Session::isStarted();
    }

    /**
     * Check to see if a token already exists.
     *
     * @return bool
     */
    public function hasToken(): bool
    {
        if ($this->isReady()) {
            return Session::has($this->sessionKey);
        }
        return false;
    }

    /**
     * Get the token string.
     *
     * @return string
     */
    public function getTokenValue(): string
    {
        $token = '';

        if ($this->hasToken()) {
            $info = Session::get($this->sessionKey);

            if (isset($info['token'])) {
                $token = $info['token'];
            }
        }

        return $token;
    }

    /**
     * Validate the csrf token.
     *
     * @param string $token the token strong.
     *
     * @throws \App\Framework\Exceptions\Csrf\ExpiredCSRFToken
     * @throws \App\Framework\Exceptions\Csrf\InvalidCSRFToken
     * @return bool
     */
    public function validateToken(string $token = ''): bool
    {
        if ($this->hasToken() && !empty($token)) {
            $info = Session::get($this->sessionKey);

            if (isset($info['token']) && isset($info['token_expires'])) {
                if (time() > $info['token_expires']) {
                 //   throw new ExpiredCSRFToken('CSRF Token has expired.');
                }

                if ($token !== $info['token']) {
                    throw new InvalidCSRFToken('Token mismatch.');
                }

              //  $this->forgetToken();

                return true;
            }
        }

        return false;
    }

    /**
     * Generate a new csrf token.
     *
     * @throws \Exception
     */
    public function generateToken(): void
    {
        if ($this->hasToken()) {
            $this->forgetToken();
        }

        $info = [
            'token' => bin2hex(random_bytes(32)),
            'token_expires' => time() + (60 * 30), /* Token will last 30 minutes */
        ];

        Session::set($this->sessionKey, $info);
    }

    /**
     * Forget the token.
     *
     * @return void
     */
    public function forgetToken(): void
    {
        Session::delete($this->sessionKey);
    }
}
