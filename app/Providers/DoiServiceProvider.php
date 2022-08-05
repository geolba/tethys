<?php
/**
 * Visual Studio Code.
 * User: kaiarn
 * Date: 19.02.21
 */

namespace App\Providers;

use App\Tethys\Utils\DoiClient;
use App\Interfaces\DoiInterface;
use Illuminate\Support\ServiceProvider;

class DoiServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(\App\Interfaces\DoiInterface::class, function ($app) {
            return new DoiClient();
        });
    }

    public function provides()
    {
        return [DoiClient::class];
    }
}
