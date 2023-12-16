<?php

namespace App\Framework\Bootstrap;

use App\Framework\Bootstrap\Modules\ConsoleModule;
use App\Framework\Bootstrap\Modules\DatabaseModule;
use App\Framework\Bootstrap\Modules\DotEnvModule;
use App\Framework\Bootstrap\Modules\FacadeModule;
use App\Framework\Bootstrap\Modules\HelpersModule;
use App\Framework\Bootstrap\Modules\MonologModule;
use App\Framework\Bootstrap\Modules\ProvidersModule;
use App\Framework\Bootstrap\Modules\RendererModule;
use App\Framework\Bootstrap\Modules\SymfonyCommandModule;
use App\Framework\Console\Commands\FacadeMethodBlockGeneratorCommand;
use App\Framework\Console\Commands\MakeCommandCommand;
use App\Framework\Console\Commands\MakeControllerCommand;
use App\Framework\Console\Commands\MakeMailableCommand;
use App\Framework\Console\Commands\MakeMiddlewareCommand;
use App\Framework\Console\Commands\MakeModelCommand;
use App\Framework\Console\Commands\MakeProviderCommand;
use App\Framework\Console\Commands\RouteListCommand;

class ConsoleKernel extends Kernel
{

    /**
     * The boostrap modules to load.
     *
     * @var array|string[]
     */
    protected array $modules = [];

    /**
     * The commands to load.
     *
     * @var array|string[]
     */
    public array $commands = [];

    /**
     * Default values with modules,
     * middleware and error_middleware to
     * load from the bootstrapper internally.
     *
     * @var array|array[]
     */
    protected array $defaults = [
        'modules' => [
            HelpersModule::class,
            DotEnvModule::class,
            FacadeModule::class,
            MonologModule::class,
            ConsoleModule::class,
            SymfonyCommandModule::class,
            RendererModule::class,
            DatabaseModule::class,
            ProvidersModule::class,
        ],
        'commands' => [
            FacadeMethodBlockGeneratorCommand::class,
            MakeControllerCommand::class,
            MakeCommandCommand::class,
            MakeModelCommand::class,
            MakeMailableCommand::class,
            MakeMiddlewareCommand::class,
            MakeProviderCommand::class,
            RouteListCommand::class,
        ],
    ];


    /**
     * Merge code modules, middleware and error_middleware with those
     * loaded by default during bootstrap.
     *
     * @return void
     */
    protected function mergeValues(): void
    {
        $this->modules = array_merge($this->modules, $this->defaults['modules']);
        $this->commands = array_merge($this->commands, $this->defaults['commands']);
    }
}