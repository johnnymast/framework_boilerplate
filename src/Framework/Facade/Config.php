<?php

namespace App\Framework\Facade;

use App\Framework\Facade;

/**
 * @method static mixed load(string $key = '')
 * @method static void setData(array $data = [])
 */
class Config extends Facade
{

    /**
     * Return the registered alias for this facade.
     *
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return 'config';
    }
}
