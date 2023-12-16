<?php

use App\Framework\Application;
use App\Framework\Bootstrap\Bridge;
use App\Http\HttpKernel;
use DI\Container;


require '../vendor/autoload.php';

define('PROJECT_PATH', realpath(__DIR__.'/../'));

error_reporting(E_ALL);
ini_set('memory_limit', '5G');

$app = Bridge::createFromContainer(new Container());

if (!isset($_SERVER['app'])) {
    $_SERVER['app'] = $app;
}

function app(): ?Application
{
    if (isset($_SERVER['app'])) {
        $app = &$_SERVER['app'];
        return $app;
    }

    return null;
}

$kernel = new HttpKernel();

$kernel->boot();
$kernel->registerMiddleware();
$kernel->registerErrorMiddleware();
$app->run();

session_write_close();