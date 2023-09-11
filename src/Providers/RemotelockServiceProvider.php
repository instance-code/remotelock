<?php

namespace InstanceCode\Remotelock\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;

class RemotelockServiceProvider extends ServiceProvider {
    public $commands = [
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register facades
        $this->app->bind('instance-code-remotelock', function() {
            return new \InstanceCode\Remotelock\Client;
        });

        // merge config
        $this->mergeConfigFrom(
        __DIR__ . '/../Config/remotelock.php', 'remotelock'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // public config
        $this->publishes([
            __DIR__ . '/../Config' => config_path(),
        ], 'remotelock');
    }
}
