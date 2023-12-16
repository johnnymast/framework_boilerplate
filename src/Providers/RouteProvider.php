<?php

namespace App\Providers;

use App\Framework\Provider;

class RouteProvider extends Provider
{

    /**
     * Load the routes.
     *
     * @return void
     */
    public function register(): void
    {
        require_once route_path('web.php');
    }
}