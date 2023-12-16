<?php

namespace App\Framework\Session\Facade;

use App\Framework\Facade;

/**
 * @method static \App\Framework\Session\Session getInstance()
 * @method static \App\Framework\Session\Flash getFlash()
 * @method static void start()
 * @method static void isStarted()
 * @method static void getId()
 * @method static void set(string $key, mixed $value)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void has(string $key)
 * @method static void delete(string $key)
 * @method static void all()
 * @method static void setValues(array $values)
 * @method static void clear()
 */
class Session extends Facade
{

    /**
     * Return the registered alias for this facade.
     *
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return 'session';
    }
}
