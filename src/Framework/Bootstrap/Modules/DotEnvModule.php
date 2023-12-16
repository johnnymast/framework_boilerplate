<?php

namespace App\Framework\Bootstrap\Modules;

use App\Framework\Application;
use App\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\Framework\Bootstrap\Kernel;
use Dotenv\Dotenv;

class DotEnvModule implements ModuleInterface
{

    /**
     * Run the module.
     *
     * @param \App\Framework\Application      $app    Reference to the Application instance.
     * @param \App\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void
    {
        $isDocker = fn() => is_file("/.dockerenv");
        $name = $isDocker() ? ".env-docker" : ".env-local";

        $dotenv = Dotenv::createImmutable(PROJECT_PATH, $name);
        $dotenv->load();
    }
}