<?php

namespace App\Framework\Bootstrap\Modules;

use App\Framework\Application;
use App\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\Framework\Bootstrap\Kernel;
use App\Framework\Renderer\Interfaces\RendererInterface;
use App\Framework\Renderer\TemplateRenderer;

use function DI\value;

class RendererModule implements ModuleInterface
{

    /**
     * Run the module.
     *
     * @param \App\Framework\Application      $app    Reference to the Application instance.
     * @param \App\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void
    {
        $className = config('template.renderer');
        $settings = config('template.view');

        /**
         * @var \App\Framework\Renderer\Interfaces\RenderingEngineInterface $engine
         */
        $engine = new $className($settings['path'], $settings['cache']);

        $app->bind(
            'bladeExpressionParser',
            value(function (string $expression = '', string $separator = ',') {
                $parts = explode($separator, $expression);

                if (count($parts)) {
                    return array_map(fn($part) => trim(trim($part), "'"), $parts);
                }
                return [];
            })
        );

        $app->bind(RendererInterface::class, new TemplateRenderer($engine));
    }
}