<?php

namespace kamruljpi\admintemplate\providers;

use Illuminate\Support\ServiceProvider;

class AdminTemplateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
                $this->getConfigFile(),
                'admintemplate'
            );
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $package_views = config("admintemplate.path.package_views");
        $base_views = config("admintemplate.path.base_views");
        $load_base_views = config("admintemplate.path.load_base_views");

        $assets_path = config("admintemplate.path.package_assets");
        
        $base_assets_path = config("admintemplate.path.base_assets");

        $views_path = $package_views;
        if($load_base_views){
            $views_path = $base_views;
        }
        $this->publishes([
                $package_views => $base_views,
            ]);

        $this->publishes([
                    $this->getConfigFile() => config_path('admintemplate.php'),
                ], 'config');

        $this->publishes([
                $assets_path => $base_assets_path,
                ], 'public');

        $this->loadViewsFrom($views_path, 'admintemplate');
        
    }
    protected function getConfigFile()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'admintemplate.php';
    }
}
