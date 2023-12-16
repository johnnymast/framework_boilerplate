<?php

namespace App\Framework\Console\Commands;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand
{
    /**
     * Storage for the InputInterface.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected InputInterface $input;

    /**
     * Storage for the OutputInterface.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * Overwrite for the commands.
     *
     * @return int
     */
    public function handler(): int
    {
        return SymfonyCommand::SUCCESS;
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input  The Symfony Input instance.
     * @param \Symfony\Component\Console\Output\OutputInterface $output The Symfony Output instance.
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        return $this->handler();
    }
}