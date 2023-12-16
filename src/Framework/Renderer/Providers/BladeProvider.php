<?php

namespace App\Framework\Renderer\Providers;

use App\Framework\Provider;
use App\Framework\Renderer\Interfaces\RendererInterface;
use eftec\bladeone\BladeOne;

class BladeProvider extends Provider
{
    public function boot(): void
    {
        $renderer = $this->app->resolve(RendererInterface::class);
        $engine = $renderer->getRenderingEngine();


        $blade = $engine->getRenderer();

        $renderer->share('app', $this->app);

        if ($blade instanceof BladeOne) {
            $engine->getRenderer()->directive("debug", function ($expression) {
                return "<?php if (env('APP_DEBUG')): ?>";
            });

            $engine->getRenderer()->directive("enddebug", function ($expression) {
                return '<?php endif ?>';
            });
        }
    }
}