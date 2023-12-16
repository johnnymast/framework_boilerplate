<?php

namespace App\Framework\Bootstrap\Modules;

use App\Framework\Application;
use App\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\Framework\Bootstrap\Kernel;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class DatabaseModule implements ModuleInterface
{
    /**
     * Run the module.
     *
     * @param \App\Framework\Application      $app    Reference to the Application instance.
     * @param \App\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [source_path("Model")],
            isDevMode: true,
        );

        $file = config_path('/database.php');

        $settings = require $file;

        $connection = DriverManager::getConnection($settings, $config);
        $app->bind(EntityManager::class, new EntityManager($connection, $config));
    }
}