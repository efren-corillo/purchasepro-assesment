<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the vite_asset function
        app()->singleton('vite_asset', function () {
            return function ($file) {
                $manifestPath = public_path('build/.vite/manifest.json');

                if (! File::exists($manifestPath)) {
                    throw new \Exception('Vite manifest not found. Please run "npm run build"');
                }

                $manifest = json_decode(File::get($manifestPath), true);

                return isset($manifest[$file]) ? '/build/'.$manifest[$file]['file'] : '';
            };
        });
    }
}
