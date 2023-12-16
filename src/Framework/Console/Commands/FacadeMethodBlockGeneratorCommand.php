<?php

namespace App\Framework\Console\Commands;

use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'facade:methods',
    description: 'Generate a methods block for facades.',
    hidden: false,
)]
class FacadeMethodBlockGeneratorCommand extends Command
{
    protected string $lines = "";

    protected function configure(): void
    {
        $this->addArgument(
            'class',
            InputArgument::REQUIRED,
            'The namespace and class for example \'App\Client\'',
            null
        );
    }

    /**
     * @param mixed $param
     *
     * @return string
     */
    private function prefix(mixed $param): string
    {
        return (class_exists($param) ? "\\" : "") . $param;
    }

    /**
     * Build the method definition.
     *
     * @param array<string, mixed> $info
     *
     * @throws \ReflectionException
     * @return string
     */
    private function build(array $info = []): string
    {
        $params = array_map(
            function (\ReflectionParameter $param): string {
                $paramType = $param->getType();
                $type = '';

                if (!is_null($paramType)) {
                    $type = match (get_class($param->getType())) {
                        'ReflectionType' => $paramType->getName(),
                        'ReflectionUnionType' => join(
                            '|',
                            array_map(fn($t) => $t->getName(), $paramType->getTypes())
                        ),
                        default => $paramType->getName()
                    };
                }

                $type = $this->prefix($type);
                $name = $param->getName();

                if ($param->isDefaultValueAvailable()) {
                    $defaultValue = $param->getDefaultValue();

                    $name .= ' = ' . match ($type) {
                            'string' => "'" . $defaultValue . "'",
                            'array', 'Array' => "[]",
                            default => gettype($defaultValue)
                    };
                }

                return sprintf(
                    '%s $%s',
                    $type,
                    $name
                );
            },
            $info['params']
        );

        $params = '(' . join(', ', $params) . ')';
        $returnType = 'void';

        if (!is_null($info['returns'])) {
            $returnType = match (get_class($info['returns'])) {
                'ReflectionType' => $info['returns']->getName(),
                'ReflectionUnionType' => join(
                    '|',
                    array_map(fn($type) => $type->getName(), $info['returns']->getTypes())
                ),
                default => $info['returns']->getName()
            };
        }

        $returnType = $this->prefix($returnType);

        return sprintf("* @method static %s %s%s", $returnType, $info['name'], $params);
    }

    /**
     * Handle the creation of the docblock.
     *
     * @return int
     */
    public function handler(): int
    {
        try {
            $class = $this->input->getArgument('class');
            $reflection = new ReflectionClass($class);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $this->lines .= $this->build([
                        'name' => $method->getName(),
                        'returns' => $method->getReturnType(),
                        'params' => $method->getParameters()
                    ]) . "\n";
            }

            $output = "/**\n{$this->lines}*/";
            $this->output->writeln($output);
        } catch (\Exception $e) {
            $this->output->writeln("<error>{$e->getMessage()}</error>");
            return SymfonyCommand::FAILURE;
        }

        return SymfonyCommand::SUCCESS;
    }
}
