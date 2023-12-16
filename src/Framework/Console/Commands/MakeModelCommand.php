<?php

namespace App\Framework\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'make:model',
    description: 'Creates a new model.',
    hidden: false,
)]
class MakeModelCommand extends Command
{
    /**
     * Configure the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the model.', null)
            ->addOption('repository', null, InputOption::VALUE_NONE, 'Also create a repository.')
            ->addOption('factory', null, InputOption::VALUE_NONE, 'Also create a factory.');
    }

    /**
     * Handle the making of a new model.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return int
     */
    public function handler(): int
    {
        $name = $this->input->getArgument('name');
        $repositoryClass = $factoryClass = '';

        $table = strtolower($name) . 's';

        if ($this->input->getOption('repository')) {
            $repositoryClass = $name . 'Repository';
        }

        $view = view('stubs.model', [
            'repositoryClass' => $repositoryClass,
            'name' => $name,
            'table' => $table,
        ]);

        file_put_contents(
            source_path('Model/' . $name . '.php'),
            $view->getBody()
        );
        $this->output->writeln("<info>Model created " . realpath(source_path('Model/' . $name . '.php')) . '</info>');


        if ($this->input->getOption('factory')) {
            $factoryClass = $name . 'Factory';

            $view = view('stubs.model_factory', [
                'factoryClass' => $factoryClass,
                'name' => $name,
                'table' => $table,
            ]);

            file_put_contents(
                source_path('Factory/' . $factoryClass . '.php'),
                $view->getBody()
            );

            $this->output->writeln(
                "<info>Factory created " . realpath(source_path('Factory/' . $factoryClass . '.php')) . '</info>'
            );
        }

        if ($this->input->getOption('repository')) {
            $view = view('stubs.model_repository', [
                'repositoryClass' => $repositoryClass,
                'name' => $name,
            ]);

            file_put_contents(
                source_path('Repository/' . $repositoryClass . '.php'),
                $view->getBody()
            );

            $this->output->writeln(
                "<info>Repository created " . realpath(
                    source_path('Repository/' . $repositoryClass . '.php')
                ) . '</info>'
            );
        }

        return Command::INVALID;
    }
}