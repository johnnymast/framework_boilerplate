<?php

namespace App\Framework\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\Table;

#[AsCommand(
    name: 'route:list',
    description: 'Show all configured routes',
    hidden: false,
)]
class RouteListCommand extends Command
{
    protected function configure(): void
    {
        //	$this->addArgument('name', InputArgument::REQUIRED, 'The name of the command.', null);
    }

    /**
     * Handle listing the routes.
     *
     * @return int
     */
    public function handler(): int
    {
        $routes = app()->getRouteCollector()->getRoutes();

        $data= [];

        /**
         * @var \Slim\Routing\Route $route
         */
        foreach ($routes as $route) {
            $pattern = $route->getPattern();
            $methods = implode('|', $route->getMethods());
            $name = $route->getName();
            $data[] = [$pattern, $methods,  $name];
        }


        $table = new Table($this->output);

        $table
            ->setHeaders(['Pattern', 'Methods',  'Name'])
            ->setRows($data)
            ->setColumnWidths([10, 0, 30])
            ->render();

        return Command::SUCCESS;
    }
}
