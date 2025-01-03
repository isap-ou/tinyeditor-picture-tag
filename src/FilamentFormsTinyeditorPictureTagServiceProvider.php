<?php

namespace Isapp\FilamentFormsTinyeditorPictureTag;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Isapp\FilamentFormsTinyeditorPictureTag\Manager\FilamentFormsTinyeditorPictureTagManager;

class FilamentFormsTinyeditorPictureTagServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'filament-tinyeditor-picture-tag');

        $this->app->bind('filament-tinyeditor-picture-tag', function (Application $app) {
            return new FilamentFormsTinyeditorPictureTagManager($app);
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('filament-tinyeditor-picture-tag.php'),
        ], 'config');


        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-tinyeditor-picture-tag');

        AboutCommand::add('My Package', fn () => ['Version' => '1.0.0']);
    }
}