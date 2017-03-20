<?php

namespace Prototype\Resource;

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
        $this->app->singleton(\Prototype\Resource\Handlers\Index::class);
        $this->app->singleton(\Prototype\Resource\Handlers\Show::class);
        $this->app->singleton(\Prototype\Resource\Handlers\Store::class);
        $this->app->singleton(\Prototype\Resource\Handlers\Update::class);
        $this->app->singleton(\Prototype\Resource\Handlers\Delete::class);
        $this->app->singleton(\Prototype\Resource\Handlers\IndexRelated::class);
        $this->app->singleton(\Prototype\Resource\Handlers\StoreRelated::class);
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
            \Prototype\Resource\Handlers\Index::class,
            \Prototype\Resource\Handlers\Show::class,
            \Prototype\Resource\Handlers\Store::class,
            \Prototype\Resource\Handlers\Update::class,
            \Prototype\Resource\Handlers\Delete::class,
            \Prototype\Resource\Handlers\IndexRelated::class,
            \Prototype\Resource\Handlers\StoreRelated::class,
        ];
    }
    
}
