<?php

namespace Pat\JsonUpload;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class JsonUploadServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(UploadJson::class, function ($app) {
            return new UploadJson;
        });     
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
     
    }
}
