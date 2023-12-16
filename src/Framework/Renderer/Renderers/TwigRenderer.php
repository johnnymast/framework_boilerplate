<?php

namespace App\Framework\Renderer\Renderers;

use App\Framework\Renderer\Interfaces\RenderingEngineInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigRenderer implements RenderingEngineInterface
{
    /**
     * Reference to the BladeOne instance.
     *
     * @var \Twig\Environment|null
     */
    protected ?Environment $twig = null;

    /**
     * @var \Twig\Loader\FilesystemLoader|null
     */
    protected ?FilesystemLoader $loader = null;

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

        $this->loader = new FilesystemLoader($viewDir);
        $this->twig = new Environment($this->loader, [
            'cache' => $cacheDir,
        ]);

        $tsf = new TwigFunction('fooTwig', function ($str) {
            return eval($str);
        });
        $this->twig->addFunction($tsf);

        $tsf = new TwigFilter('fooTwig', function ($str, $fn) {
            $args = array_merge(array($str), array_slice(func_get_args(), 2));
            return call_user_func_array($fn, $args);
        });
        $this->twig->addFilter($tsf);
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
        $this->loader->setPaths($this->paths['views']);
        $this->twig->setCache($this->paths['cache']);
    }

    /**
     * Return the renderer.
     *
     * @return mixed
     */
    public function getRenderer(): mixed
    {
        return $this->twig;
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
        /**
         * Twig does not use the dot notation
         * as blade does so we need to help it a little.
         */
        if (str_contains($view, '.')) {
            $matches = array_filter($this->paths['views'],fn($path) => file_exists($path.DIRECTORY_SEPARATOR.$view));

            if (!count($matches)) {
                $view = join(DIRECTORY_SEPARATOR, explode('.', $view));
            }
        }

        $merged = array_merge($data, $sharedData);
        return $this->twig->render($view, $merged);
    }
}
