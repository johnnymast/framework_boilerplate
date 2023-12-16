<?php

namespace App\Providers;

use App\Framework\Logger\Interfaces\LoggerInterface;
use App\Framework\Provider;
use App\Framework\Renderer\Interfaces\RendererInterface;
use App\Interfaces\Debugbar\DebugbarInterface;
use App\Interfaces\Debugbar\DebugbarRendererInterface;
use DebugBar\JavascriptRenderer;
use DebugBar\StandardDebugBar;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;

class DebugbarProvider extends Provider
{
    protected ?StandardDebugBar $debugbar = null;
    protected ?JavascriptRenderer $debugbarRenderer = null;
    protected Logger $logger;

    /**
     * Load the routes.
     *
     * @throws \DebugBar\DebugBarException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return void
     */
    public function boot(): void
    {
        if (env('APP_DEBUG') && $this->app->runningInConsole() === false) {
            $renderer = $this->app->resolve(RendererInterface::class);
            $renderer->share('phpdebugbar', $this->debugbarRenderer);

            $this->logger = $this->app->resolve(LoggerInterface::class);

            if ($this->logger) {
                $this->debugbar->addCollector(new \DebugBar\Bridge\MonologCollector($this->logger));
            }

            $entityManager = $this->app->resolve(EntityManager::class);

            if ($entityManager) {
                $debugStack = new \Doctrine\DBAL\Logging\DebugStack();
                $entityManager->getConnection()->getConfiguration()->setSQLLogger($debugStack);
                $this->debugbar->addCollector(new \DebugBar\Bridge\DoctrineCollector($debugStack));
            }
        }
    }

    /**
     * Register routine
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return void
     */
    public function register(): void
    {
        if ($this->app->runningInConsole() === false) {
            $this->debugbar = $this->app->resolve(DebugbarInterface::class);
            $this->debugbarRenderer = $this->app->resolve(DebugbarRendererInterface::class);


            app()->get('/debugbar/javascript', function (Response $response) {
                return view('web.debugbar.javascript', [
                    'renderer' => $this->debugbarRenderer
                ])->withHeader('Content-Type', 'text/javascript');
            });

            app()->get('/debugbar/css', function (Response $response) {
                return view('web.debugbar.css', [
                    'renderer' => $this->debugbarRenderer
                ])->withHeader('Content-Type', 'text/css');
            });
        }
    }
}

