<?php

namespace Ordent\RamenResource;

use Illuminate\Support\ServiceProvider as BaseProvider;
use Illuminate\Support\Facades\Response;

class ServiceProvider extends BaseProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        //add response factory to response facade using macro
        $responseFactory = $this->app[\Ordent\RamenResource\Response\ResponseFactory::class];
        foreach (get_class_methods($responseFactory) as $method){
            Response::macro($method, [$responseFactory, $method]);
        }

        //register middleware
        $this->app['router']
            ->aliasMiddleware('validate', \Ordent\RamenResource\Validator\ValidationMiddleware::class)
            ->aliasMiddleware('storeFiles', \Ordent\RamenResource\FileUpload\StoreFilesMiddleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //register local class
        $this->app->singleton(\Ordent\RamenResource\Response\ResponseFactory::class);
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
