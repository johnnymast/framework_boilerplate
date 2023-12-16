{!! '<' !!}?php

namespace App\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use App\Framework\Console\Commands\Command;

#[AsCommand(
name: 'hello',
description: 'echo a name to the terminal.',
hidden: false,
)]
class {{$name}} extends Command
{
{{"\t"}}protected function configure(): void
{{"\t"}}{
{{"\t"}}{{"\t"}}$this->addArgument('name', InputArgument::REQUIRED, 'The name of the command.', null);
{{"\t"}}}{{"\n"}}
{{"\t"}}public function handler(): int {
{{"\t"}}{{"\t"}}$name = $this->input->getArgument('name');

{{"\t"}}{{"\t"}}$this->output->writeln("<info>Hello " . $name.'</info>');
{{"\t"}}{{"\t"}}return Command::INVALID;
{{"\t"}}}
}
