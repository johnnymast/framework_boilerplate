<?php

namespace App\Framework\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:provider',
    description: 'Create a new Provider.',
    hidden: false,
)]
class MakeProviderCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the provider.', null);
    }

    /**
     * Handle making a new provider.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return int
     */
    public function handler(): int
    {
        $name = $this->input->getArgument('name');
        $name = $this->input->getArgument('name');

        $view = view('stubs.provider', [
            'name' => $name,
        ]);

        file_put_contents(
            source_path('Providers/' . $name . '.php'),
            $view->getBody()
        );

        $this->output->writeln(
            "<info>Model created " . realpath(source_path('Providers/' . $name . '.php')) . '</info>'
        );

        return Command::INVALID;
    }
}
