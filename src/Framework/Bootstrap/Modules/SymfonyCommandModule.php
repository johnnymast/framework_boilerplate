<?php

namespace App\Framework\Bootstrap\Modules;

use App\Framework\Application;
use App\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\Framework\Bootstrap\Kernel;

class SymfonyCommandModule implements ModuleInterface
{

    /**
     * Run the module.
     *
     * @param \App\Framework\Application      $app    Reference to the Application instance.
     * @param \App\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void
    {
        $console = $app->resolve(\Symfony\Component\Console\Application::class);

        foreach ($kernel->commands as $command) {
            $console->add(new $command);
        }
    }
}
