<?php
declare(strict_types=1);

use App\Console\ConsoleKernel;
use App\Framework\Application;
use App\Framework\Bootstrap\Bridge;
use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require 'vendor/autoload.php';

define('PROJECT_PATH', realpath(__DIR__));

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

$kernel = new ConsoleKernel();
$kernel->boot();

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [source_path("Model"), PROJECT_PATH.'/vendor/johnnymast/framework/src/Framework/Auth/Model/'],
    isDevMode: true,
);

$file = config_path('/database.php');

$settings = require $file;

$connection = DriverManager::getConnection($settings, $config);
return DependencyFactory::fromEntityManager(new PhpFile('database/migrations.php'), new ExistingEntityManager(new EntityManager($connection, $config)));
