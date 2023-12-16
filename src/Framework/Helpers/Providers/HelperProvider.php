<?php

namespace App\Framework\Helpers\Providers;

use App\Framework\Provider;

class HelperProvider extends Provider
{

    /**
     * Register auth routes.
     *
     * @return void
     */
    public function register(): void
    {
        require_once __DIR__ . '/../Helpers.php';
    }
}

