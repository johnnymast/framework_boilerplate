<?php

namespace App\Console;

class ConsoleKernel extends \App\Framework\Bootstrap\ConsoleKernel
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
}