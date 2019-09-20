<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Solarium\Client;

class SolariumServiceProvider extends ServiceProvider
{
    protected $defer = true;
    

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function ($app) {
            // $config = config('solarium');
            $config = array(
                'endpoint' => array(
                    'localhost' => array(
                        'host' => 'repository.geologie.ac.at',
                        'port' => '8983',
                        'path' => '/solr/',
                        'core' => 'rdr_data'
                    )
                )
            );
                //return new Client($config);
            return new Client($config);
                //return new Client($app['config']['solarium']);
        });
    }
   
    public function provides()
    {
        return [Client::class];
    }
}
