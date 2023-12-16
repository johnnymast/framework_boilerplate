<?php

namespace App\Framework\Bootstrap\Modules;

use App\Framework\Application;
use App\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\Framework\Bootstrap\Kernel;

class ProvidersModule implements ModuleInterface
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
        $file = config_path('/app.php');
        $settings = require $file;

        if (isset($settings['providers'])) {
            $providers = $settings['providers'];

            foreach ($providers as $class) {
                $provider = new $class($app, $kernel);
                $provider->register();
                $provider->boot();;
            }
        }
    }
}
