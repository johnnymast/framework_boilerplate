<?php

namespace App\Framework;

use App\{Framework\Bootstrap\Kernel,};

class Provider
{
    /**
     * @param \App\Framework\Application      $app    Reference to the Application instance.
     * @param \App\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     */
    public function __construct(protected Application $app, protected Kernel $kernel)
    {
    }

    /**
     * Overrideable function for Providers
     * so it always exists.
     *
     * @return void
     */
    public function boot(): void
    {
        // Overwrite me
    }

    /**
     * Overrideable function for Providers
     * so it always exists.
     *
     * @return void
     */
    public function register(): void
    {
        // Overwrite me
    }
}