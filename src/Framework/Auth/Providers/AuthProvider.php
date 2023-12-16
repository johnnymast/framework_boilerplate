<?php

namespace App\Framework\Auth\Providers;

use App\Framework\Provider;
use App\Framework\Renderer\Interfaces\RendererInterface;
use App\Framework\Session\Facade\Session;
use eftec\bladeone\BladeOne;

class AuthProvider extends Provider
{

    /**
     * Load the routes.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return void
     */
    public function boot(): void
    {
        $renderer = $this->app->resolve(RendererInterface::class);

        /**
         * @var  \App\Framework\Renderer\Interfaces\RenderingEngineInterface $engine;
         */
        $engine = $renderer->getRenderingEngine();
        $engine->addViewPath(realpath(__DIR__ . '/../views'));

        /**
         * @var \eftec\bladeone\BladeOne $blade
         */
        $blade = $engine->getRenderer();

        $renderer->share('user', Session::get('user'));

        if ($blade instanceof BladeOne) {
            $blade->directive("old", function (string $expression) {
                $data = app()->request()->getParsedBody();

                $parser = app()->resolve('bladeExpressionParser');
                $parsed = array_pad($parser($expression), 3, "");

                [$field, $default] = $parsed;

                $value = $default;

                if (isset($data[$field]) and strlen($data[$field]) > 0) {
                    $value = $data[$field];
                }

                return $value;
            });

            $blade->if('auth', function () {
                return Session::has('user');
            });

            $blade->if('guest', function () {
                return !Session::has('user');
            });
        }
    }

    /**
     * Register auth routes.
     *
     * @return void
     */
    public function register(): void
    {
        require __DIR__ . '/../Routes.php';
    }
}

