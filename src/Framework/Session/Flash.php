<?php

namespace App\Framework\Session;

use App\Framework\Session\Interfaces\SessionKeyValueInterface;

final class Flash implements SessionKeyValueInterface
{

    /**
     * Flash constructor.
     *
     * @param \App\Framework\Session\Session $session
     * @param string                         $storageKey
     */
    public function __construct(protected Session $session, protected string $storageKey = '_flash')
    {
    }

    /**
     * Return all flash messages.
     *
     * @return array<string>
     */
    public function all(): array
    {
        return $this->session->get($this->storageKey);
    }

    /**
     * Check to see if a key has been set.
     *
     * @param string $key The key.
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        if ($this->session->has($this->storageKey)) {
            $session = $this->session->get($this->storageKey);

            return isset($session[$key]);
        }

        return false;
    }

    /**
     * Get a flash message by key.
     *
     * @param string $key     The key.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $result = $default;

        if ($this->session->has($this->storageKey)) {
            $session = $this->session->get($this->storageKey);

            $this->delete($key);

            $result = $session[$key] ?? $default;
        }

        return $result;
    }

    /**
     * Add a new flash message.
     *
     * @param string $key   The key.
     * @param mixed  $value The value.
     *
     * @return void
     */
    public function add(string $key, mixed $value): void
    {
        if (!$this->session->has($this->storageKey)) {
            $this->session->set($this->storageKey, []);
        }

        $session = $this->session->get($this->storageKey);

        if (isset($session[$key]) === false) {
            $session[$key] = [];
        }

        $session[$key][] = $value;

        $this->session->set($this->storageKey, $session);
    }

    /**
     * Set an array of values for a key,
     *
     * @param string        $key    The key.
     * @param array<string> $values The values.
     *
     * @return void
     */
    public function set(string $key, array $values = []): void
    {
        if (!$this->session->has($this->storageKey)) {
            $this->session->set($this->storageKey, []);
        }

        $session = $this->session->get($this->storageKey);
        $session[$key] = $values;

        $this->session->set($this->storageKey, $session);
    }

    /**
     * Delete a key from the flash storage.
     *
     * @param string $key The key.
     *
     * @return void
     */
    public function delete(string $key): void
    {
        if ($this->session->has($this->storageKey)) {
            $session = $this->session->get($this->storageKey);
            unset($session[$key]);

            $this->session->set($this->storageKey, $session);
        }
    }

    /**
     * Clear the flash data.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->session->delete($this->storageKey);
        $this->session->set($this->storageKey, []);
    }
}
