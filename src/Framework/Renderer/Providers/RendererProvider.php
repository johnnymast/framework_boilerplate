<?php

namespace App\Framework\Renderer\Providers;


use App\Framework\Provider;

class RendererProvider extends Provider
{
    public function register(): void
    {
        require __DIR__ . '/../Helpers.php';
    }
}