<?php

namespace App\Framework\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

;
#[AsCommand(
    name: 'make:controller',
    description: 'Creates a new controller.',
    hidden: false,
)]
class MakeControllerCommand extends Command
{
    /**
     * Configure the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the controller.', null)
            ->addOption('resource', null, InputOption::VALUE_NONE, 'Create a resource controller.');
    }

    /**
     * Handle making a new controller.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return int
     */
    public function handler(): int
    {
        $name = $this->input->getArgument('name');
        $functions = [];

        if (strpos($name, '@') > -1) {
            [$name, $function] = explode('@', $name);
            $functions[] = $function;
        }

        if ($this->input->getOption('resource')) {
            $functions = [
                'index',
                'create',
                'store',
                'show',
                'edit',
                'update',
                'destroy',
            ];
        }

        $view = view('stubs.controller', [
            'functions' => $functions,
            'name' => $name,
        ]);

        file_put_contents(
            http_path('Controller/' . $name . '.php'),
            $view->getBody()
        );

        $this->output->writeln(
            "<info>Controller created " . realpath(http_path('Controller/' . $name . '.php')) . '</info>'
        );

        return Command::INVALID;
    }
}