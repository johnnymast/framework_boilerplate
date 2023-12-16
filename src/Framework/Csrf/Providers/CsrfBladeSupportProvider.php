<?php

namespace App\Framework\Csrf\Providers;

use App\Framework\Provider;

class CsrfBladeSupportProvider extends Provider
{
    public function register(): void
    {
        require __DIR__ . '/../Helpers.php';
    }
}