<?php


use App\Framework\Auth\Providers\AuthProvider;
use App\Framework\Config\Config;
use App\Framework\Console\Provider\ConsoleProvider;
use App\Framework\Csrf\Providers\CsrfBladeSupportProvider;
use App\Framework\Helpers\Providers\HelperProvider;
use App\Framework\Logger\Providers\LoggerProvider;
use App\Framework\Mail\Mailer\Mailer;
use App\Framework\Renderer\Providers\BladeProvider;
use App\Framework\Renderer\Providers\RendererProvider;
use App\Framework\Session\Session;
use App\Importer\Importer;
use App\Providers\DebugbarProvider;
use App\Providers\NewUserProvider;
use App\Providers\RouteProvider;

return [

    'uploads' => [
        'path' => realpath(__DIR__.'/../var/uploads'),
    ],
    'aliases' => [
        'config' => Config::class,
        'session' => [Session::class, require('session.php')],
        'email' => Mailer::class,
    ],
    'providers' => [
        HelperProvider::class,
        RendererProvider::class,
        RouteProvider::class,
        LoggerProvider::class,
        BladeProvider::class,
        CsrfBladeSupportProvider::class,
        DebugbarProvider::class,
        AuthProvider::class,
        ConsoleProvider::class,
    ],
];