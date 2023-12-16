<?php

namespace App\Framework\Session\Interfaces;

interface SessionKeyValueInterface
{

    /**
     * Return a value from a key.
     *
     * @param string $key     The key to get the value for.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = ''): mixed;

    /**
     * Check to see if a key is present.
     *
     * @param string $key The key to check for.
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Delete an item.
     *
     * @param string $key The key to delete.
     *
     * @return void
     */
    public function delete(string $key): void;

    /**
     * Return all items.
     *
     * @return array<string>
     */
    public function all(): array;
}