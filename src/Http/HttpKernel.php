<?php

namespace App\Http;

use App\Http\Modules\DebugbarModule;

class HttpKernel extends \App\Framework\Bootstrap\HttpKernel
{

    /**
     * The boostrap modules to load.
     *
     * @var array|string[]
     */
    protected array $modules = [
        DebugbarModule::class,
    ];

    /**
     * The middleware to load.
     *
     * @var array|string[]
     */
    protected array $middleware = [];

    /**
     * The error middleware to load.
     *
     * @var array|string[]
     */
    protected array $errorMiddleware = [];
}