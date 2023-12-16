<?php

namespace App\Framework\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:mailable',
    description: 'Creates a new mailable.',
    hidden: false,
)]
class MakeMailableCommand extends Command
{
    /**
     * Configure the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the mailable.', null);
    }

    /**
     * Handle the making of a new Mailable.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return int
     */
    public function handler(): int
    {
        $name = $this->input->getArgument('name');
        $viewName = strtolower($name);
        $viewFile = $viewName . '.blade.php';

        if (!is_dir(view_path() . DIRECTORY_SEPARATOR . 'emails')) {
            if (mkdir(view_path() . DIRECTORY_SEPARATOR . 'emails')) {
                $this->output->writeln(
                    "<info>Created directory " . view_path() . DIRECTORY_SEPARATOR . 'emails' . "</info>"
                );
            }
        }


        $view = view('stubs.email_template', []);

        file_put_contents(
            view_path('emails' . DIRECTORY_SEPARATOR . $viewFile),
            $view->getBody()
        );
        $this->output->writeln("<info>Created view " . $viewFile . '</info>');


        $view = view('stubs.mailable', [
            'name' => $name,
            'view' => $viewName,
        ]);

        file_put_contents(
            source_path('Mail' . DIRECTORY_SEPARATOR . $name . '.php'),
            $view->getBody()
        );

        $this->output->writeln("<info>Created mailable " . $name . '</info>');

        return Command::INVALID;
    }
}
