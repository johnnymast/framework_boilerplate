<?php

namespace App\Framework\Bootstrap\Modules;

use App\Framework\Application;
use App\Framework\Bootstrap\Kernel;
use App\Framework\RouteEntityBindingStrategy;
use Doctrine\ORM\EntityManager;

class ModelRouteBinding
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
        $app->getRouteCollector()->setDefaultInvocationStrategy(
            new RouteEntityBindingStrategy(
                $app->resolve(EntityManager::class),
                $app->getResponseFactory()
            )
        );
    }
}