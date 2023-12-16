<?php

namespace App\Framework\Renderer\Renderers;

use App\Framework\Renderer\Interfaces\RenderingEngineInterface;
use eftec\bladeone\BladeOne;

class BladeRenderer implements RenderingEngineInterface
{
    /**
     * Reference to the BladeOne instance.
     *
     * @var \eftec\bladeone\BladeOne|null
     */
    protected ?BladeOne $blade = null;

    /**
     * @var array
     */
    protected array $paths = [
        'views' => [],
        'cache' => [],
    ];

    /**
     * BladeRenderer constructor
     *
     * @param string|array<string> $viewDir  Path to the views.
     * @param string               $cacheDir Path to the view cache dir.
     */
    public function __construct(string|array $viewDir, string $cacheDir)
    {
        if (is_array($viewDir)) {
            $this->paths['views'] = $viewDir;
        } else {
            $this->paths['views'][] = $viewDir;
        }

        $this->paths['cache'] = $cacheDir;

        /**
         * MODE_DEBUG allows to pinpoint troubles.
         */
        $this->blade = new BladeOne(
            $this->paths['views'],
            $this->paths['cache'],
            BladeOne::MODE_DEBUG
        );
    }

    /**
     * Add a path to load views from.
     *
     * @param string $path The view path to add.
     *
     * @return void
     */
    public function addViewPath(string $path): void
    {
        $this->paths['views'][] = $path;
        $this->blade->setPath($this->paths['views'], $this->paths['cache']);
    }

    /**
     * Return the renderer.
     *
     * @return mixed
     */
    public function getRenderer(): mixed
    {
        return $this->blade;
    }

    /**
     * Load a blade view and return the rendered data.
     *
     * @param string               $view       The view.
     * @param array<string, mixed> $data       The data a controller can pass to a view.
     * @param array<string, mixed> $sharedData The shared data passed by the framework.
     *
     * @throws \Exception
     * @return string
     */
    public function build(string $view, array $data = [], array $sharedData = []): string
    {
        $merged = array_merge($data, $sharedData);
        return $this->blade->run($view, $merged);
    }
}
