<?php

namespace App\Framework\Renderer;

use App\Framework\Renderer\Interfaces\RendererInterface;
use App\Framework\Renderer\Interfaces\RenderingEngineInterface;
use App\Framework\Session\Facade\Session;
use Psr\Http\Message\ResponseInterface;

final class TemplateRenderer implements RendererInterface
{

    /**
     * Storage for the shared data from
     * the framework.
     *
     * @var array<string, mixed>
     */
    protected array $shared = [];

    public function __construct(protected readonly RenderingEngineInterface $engine)
    {
        $this->setup();
    }

    /**
     * Return the configured rendering Engine.
     *
     * @return \App\Framework\Renderer\Interfaces\RenderingEngineInterface
     */
    public function getRenderingEngine(): RenderingEngineInterface
    {
        return $this->engine;
    }

    /**
     * Empty for now.
     *
     * @return void
     */
    private function setup(): void
    {
       // Empty for now
    }

    /**
     * Share template variables among all templates.
     *
     * @param string $name  The name of the variable.
     * @param mixed  $value The value of the variable.
     *
     * @return void
     */
    public function share(string $name, mixed $value): void
    {
        $this->shared[$name] = $value;
    }

    /**
     * Tell the renderer to render a view Object.
     *
     * @param \App\Framework\Renderer\View $view
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function render(View $view): ResponseInterface
    {
        /**
         * This is plashed here because in the methods of the controllers
         * the flash messages may be added. Therefore, if i would put it in the
         * blade provider it would share() empty flash messages (since it runs before the routes).
         */
        $this->share('session', Session::getInstance());
        $this->share('flash', Session::getFlash());

        $content = $this->engine->build($view->getView(), $view->getData(), $this->shared);
        return $view->render($content);
    }
}
