<?php

namespace App\Framework\Bootstrap\Interfaces;

use App\Framework\Application;
use App\Framework\Bootstrap\Kernel;

interface ModuleInterface
{
    /**
     * Run a bootstrapping module.
     *
     * @param \App\Framework\Application      $app    Reference to the Application instance.
     * @param \App\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void;
}