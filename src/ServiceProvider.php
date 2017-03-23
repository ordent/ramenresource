<?php

namespace Ordent\RamenResource;

use Illuminate\Support\ServiceProvider as BaseProvider;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends BaseProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //register local class
        $this->app->singleton(\Ordent\RamenResource\Handlers\Index::class);
        $this->app->singleton(\Ordent\RamenResource\Handlers\Show::class);
        $this->app->singleton(\Ordent\RamenResource\Handlers\Store::class);
        $this->app->singleton(\Ordent\RamenResource\Handlers\Update::class);
        $this->app->singleton(\Ordent\RamenResource\Handlers\Delete::class);
        $this->app->singleton(\Ordent\RamenResource\Handlers\IndexRelated::class);
        $this->app->singleton(\Ordent\RamenResource\Handlers\StoreRelated::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [

            // defer handlers
            \Ordent\RamenResource\Handlers\Index::class,
            \Ordent\RamenResource\Handlers\Show::class,
            \Ordent\RamenResource\Handlers\Store::class,
            \Ordent\RamenResource\Handlers\Update::class,
            \Ordent\RamenResource\Handlers\Delete::class,
            \Ordent\RamenResource\Handlers\IndexRelated::class,
            \Ordent\RamenResource\Handlers\StoreRelated::class,
        ];
    }
    
}
