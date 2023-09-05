<?php

namespace kamruljpi\admintemplate\providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminTemplateRouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'kamruljpi\admintemplate\controllers';
    public function register()
    {

    }
    public function boot()
    {
        parent::boot();
    }
    public function map()
    {
        Route::prefix('kamruljpi')
             ->namespace($this->namespace)
             ->group(__DIR__.'/../routes/web.php');
    }
}
