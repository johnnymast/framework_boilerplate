<?php
declare(strict_types=1);

use App\Console\ConsoleKernel;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require 'vendor/autoload.php';
$app = require __DIR__ . '/src/Bootstrap/bootstrapper.php';

$kernel = new ConsoleKernel();
$kernel->boot();

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [source_path("Model")],
    isDevMode: true,
);

$file = config_path('/database.php');

$settings = require $file;

$connection = DriverManager::getConnection($settings, $config);
return DependencyFactory::fromEntityManager(new PhpFile('database/migrations.php'), new ExistingEntityManager(new EntityManager($connection, $config)));
