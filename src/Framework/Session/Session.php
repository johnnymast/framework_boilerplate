<?php

namespace App\Framework\Session;

use App\Framework\Session\Exception\SessionException;
use App\Framework\Session\Interfaces\SessionInterface;

class Session implements SessionInterface
{
    /**
     * Storage for the keys and
     * values.
     *
     * @var array
     */
    protected array $storage = [];

    protected Flash $flash;

    /**
     * @var array<string, mixed>
     */
    protected array $options = [
        'id' => null,
        'name' => 'app',
        'lifetime' => 7200,
        'path' => null,
        'domain' => null,
        'secure' => false,
        'httponly' => true,
        'cache_limiter' => 'nocache',
        'testing' => false,
    ];


    /**
     * Construct a new session.
     *
     * @param array<string> $options The options for the session.
     *
     * @throws \App\Framework\Session\Exception\SessionException
     */
    public function __construct(array $options = [])
    {
        $this->flash = new Flash($this);

        $keys = array_keys($this->options);
        foreach ($keys as $key) {
            if (array_key_exists($key, $options)) {
                $this->options[$key] = $options[$key];
                unset($options[$key]);
            }
        }

        foreach ($options as $key => $value) {
            ini_set('session.' . $key, $value);
        }

        $this->start();
    }

    /**
     * The instance of the session facade.
     *
     * @return $this
     */
    public function getInstance(): Session
    {
        return $this;
    }

    /**
     * Start the session.
     *
     * @throws \App\Framework\Session\Exception\SessionException
     * @return void
     */
    public function start(): void
    {
        if ($this->options['testing'] === false) {
            if (headers_sent($file, $line) && filter_var(ini_get('session.use_cookies'), FILTER_VALIDATE_BOOLEAN)) {
                throw new SessionException(
                    sprintf(
                        'Failed to start the session because headers have already been sent by "%s" at line %d.',
                        $file,
                        $line
                    )
                );
            }

            $current = session_get_cookie_params();

            $lifetime = (int)($this->options['lifetime'] ?: $current['lifetime']);
            $path = $this->options['path'] ?: $current['path'];
            $domain = $this->options['domain'] ?: $current['domain'];
            $secure = (bool)$this->options['secure'];
            $httponly = (bool)$this->options['httponly'];

            session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
            session_name($this->options['name']);
            session_cache_limiter($this->options['cache_limiter']);

            $sessionId = $this->options['id'] ?: null;
            if ($sessionId) {
                session_id($sessionId);
            }


            if ($this->isStarted() === false) {
                if (!session_start()) {
                    throw new SessionException('Failed to start the session.');
                }
            }

            $this->storage = &$_SESSION;
        }
    }

    /**
     * Check to see if the session has started.
     *
     * @return bool
     */
    public function isStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Return the session id.
     *
     * @return string
     */
    public function getId(): string
    {
        return session_id();
    }

    /**
     * Set a value bound to a key.
     *
     * @param string $key    The key.
     * @param mixed  $value The value.
     *
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->storage[$key] = $value;
    }

    /**
     * Return a value from a key.
     *
     * @param string $key     The key to get the value for.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->storage[$key] ?? $default;
    }

    /**
     * Check to see if a key is present.
     *
     * @param string $key The key to check for.
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->storage[$key]);
    }

    /**
     * Delete an item.
     *
     * @param string $key The key to delete.
     *
     * @return void
     */
    public function delete(string $key): void
    {
        if ($this->has($key)) {
            unset($this->storage[$key]);
        }
    }

    /**
     * Return all items.
     *
     * @return array<string>
     */
    public function all(): array
    {
        return $this->storage;
    }

    /**
     * Set the values from an array.
     *
     * @param array<string> $values The values to set assoc array.
     *
     * @return void
     */
    public function setValues(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Clear the session values.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->storage = [];
    }

    /**
     * Return the flash messages.
     *
     * @return \App\Framework\Session\Flash
     */
    public function getFlash(): Flash
    {
        return $this->flash;
    }
}
