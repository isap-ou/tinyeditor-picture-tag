<?php

namespace Isapp\TinyeditorPictureTag;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Isapp\TinyeditorPictureTag\Manager\TinyeditorPictureTagManager;

class TinyeditorPictureTagServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('tinyeditor-picture-tag', function (Application $app) {
            return new TinyeditorPictureTagManager($app);
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tinyeditor-picture-tag');

        AboutCommand::add('My Package', fn () => ['Version' => '1.0.0']);
    }
}
