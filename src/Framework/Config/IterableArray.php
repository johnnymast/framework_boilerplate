<?php

namespace App\Framework\Config;

class IterableArray implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * The data container
     *
     * @var array<string>
     * @access private
     */
    private array $data = [];

    /**
     * Set the data.
     *
     * @param array<string> $data The data to set.
     *
     * @return void
     */
    public function setData(array $data = []): void
    {
        $this->data = $data;
    }

    /**
     * Return the Iterator
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Get a data by key
     *
     * @param string $key The key data to retrieve.
     *
     * @return mixed
     */
    public function &__get(string $key)
    {
        return $this->data[$key];
    }

    /**
     * Assigns a value to the specified data
     *
     * @param string $key   The data key to assign the value to.
     * @param mixed  $value The value to set.
     *
     * @return void
     */
    public function __set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Check if an item by key exists.
     *
     * @param string $key A data key to check for.
     *
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Unsets a data by key
     *
     * @param string $key The key to unset.
     *
     * @access public
     */
    public function __unset(string $key)
    {
        unset($this->data[$key]);
    }

    /**
     * Assigns a value to the specified offset
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Whether an offset exists
     *
     * @param string $offset An offset to check for.
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Unsets an offset
     *
     * @param string $offset The offset to unset.
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }

    /**
     * Returns the value at specified offset
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }

    /**
     * Return the number of elements.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Return the data.
     *
     * @return array<string>
     */
    public function __debugInfo(): array
    {
        return $this->data;
    }
}