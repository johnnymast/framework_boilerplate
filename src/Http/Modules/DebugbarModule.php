<?php

namespace App\Http\Modules;

use App\Framework\Application;
use App\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\Framework\Bootstrap\Kernel;
use App\Interfaces\Debugbar\DebugbarInterface;
use App\Interfaces\Debugbar\DebugbarRendererInterface;
use DebugBar\StandardDebugBar;

class DebugbarModule implements ModuleInterface
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
        $debugbar = new StandardDebugBar();
        $renderer = $debugbar->getJavascriptRenderer();

        $app->bind(DebugbarInterface::class, $debugbar);
        $app->bind(DebugbarRendererInterface::class, $renderer);
    }
}