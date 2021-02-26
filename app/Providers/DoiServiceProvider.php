<?php
/**
 * Visual Studio Code.
 * User: kaiarn
 * Date: 19.02.21
 */

namespace App\Providers;

use App\Tethys\Utils\DoiService;
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
        $this->app->singleton('App\Interfaces\DoiInterface', function ($app) {
            return new DoiService();
        });
    }

    public function provides()
    {
        return [DoiService::class];
    }
}
