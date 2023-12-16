<?php

namespace App\Framework\Bootstrap;

use App\Framework\Application;

abstract class Kernel
{
    /**
     * @var array<string>
     */
    protected array $modules = [];

    /**
     * @var array<string>
     */
    public array $commands = [];

    /**
     * Individual kernels have to implement
     * to merge the framework modules/command
     * to load with those from the project.
     *
     * @return void
     */
    abstract protected function mergeValues(): void;

    /*
     * Boot the main kernel.
     */
    public function boot(): Application
    {
        $this->mergeValues();

        $app = app();

        foreach ($this->modules as $module) {
            $module::run($app, $this);
        }

        return $app;
    }
}