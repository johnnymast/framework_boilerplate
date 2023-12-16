<?php

namespace App\Framework;

use App\Framework\Config\Config;

abstract class Facade
{

    /**
     * Storage for resolved facade instances.
     *
     * @var array<string, mixed>
     */
    public static array $resolvedInstance = [];

    /**
     * @var \App\Framework\Application|null
     */
    public static ?Application $app = null;

    /**
     * Clear resolved Facades.
     *
     * @return void
     */
    public static function clearResolvedInstances(): void
    {
        static::$resolvedInstance = [];
    }

    /**
     * Return the real facade instance.
     *
     * @throws \Exception
     * @return mixed
     */
    public static function getFacadeRoot(): mixed
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Return an instance of the real facade class.
     *
     * @param string $alias The alias the facade is registered as.
     *
     * @throws \Exception
     * @return mixed
     */
    public static function resolveFacadeInstance(string $alias): mixed
    {
        if (isset(static::$resolvedInstance[$alias]) === true) {
            return static::$resolvedInstance[$alias];
        }

        if (isset(static::$resolvedInstance[$alias]) === false) {
            self::$resolvedInstance[$alias] = static::$app->resolve($alias);
        }

        return self::$resolvedInstance[$alias];
    }

    /**
     * Set the Main application.
     *
     * @param \App\Framework\Application $app The Application instance.
     *
     * @return void
     */
    public static function setFacadeApplication(Application $app): void
    {
        static::$app = $app;

        $aliases = (new Config())
            ->load('app.aliases');


        foreach ($aliases as $name => $info) {
            if (is_array($info)) {
                $args = $info[1];
                $class = $info[0];

                static::$app->bind($name, new $class($args));
            } else {
                static::$app->bind($name, new $info);
            }
        }
    }

    /**
     * Handle static calls.
     *
     * @param string               $method The method to call.
     * @param array<string, mixed> $args   The arguments for the method.
     *
     * @throws \Exception
     * @return mixed
     */
    public static function __callStatic(string $method, array $args = []): mixed
    {
        $instance = static::getFacadeRoot();

        if (!$instance) {
            throw new \RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }

    /**
     * The function will be called if the Facade file does
     * implement this method. This will cause a RuntimeException
     * informing the developer that he did not implement this method.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        throw new \RuntimeException('Facade does not implement getFacadeAccessor method.');
    }
}
