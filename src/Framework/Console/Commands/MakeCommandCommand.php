<?php

namespace App\Framework\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:command',
    description: 'Creates a new command.',
    hidden: false,
)]
class MakeCommandCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the command.', null);
    }

    /**
     * Handle making a new command.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return int
     */
    public function handler(): int
    {
        $name = $this->input->getArgument('name');

        $view = view('stubs.command', [
            'name' => $name,
        ]);

        file_put_contents(
            source_path('Console/Commands/' . $name . '.php'),
            $view->getBody()
        );

        $this->output->writeln("<info>Model created " . realpath(source_path('Model/' . $name . '.php')) . '</info>');

        return Command::SUCCESS;
    }
}