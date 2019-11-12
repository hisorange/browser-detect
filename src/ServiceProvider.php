<?php

namespace hisorange\BrowserDetect;

use Illuminate\Support\Facades\Blade;

/**
 * Class ServiceProvider
 * @package hisorange\BrowserDetect
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function isDeferred()
    {
        return true;
    }

    /**
     * Register the custom blade directives.
     */
    public function boot()
    {
        $this->registerDirectives();
    }

    /**
     * Register the blade directives.
     */
    protected function registerDirectives()
    {
        Blade::if('desktop', function () {
            return app()->make('browser-detect')->detect()->isDesktop();
        });

        Blade::if('tablet', function () {
            return app()->make('browser-detect')->detect()->isTablet();
        });

        Blade::if('mobile', function () {
            return app()->make('browser-detect')->detect()->isMobile();
        });

        Blade::if('browser', function ($fn) {
            return app()->make('browser-detect')->detect()->$fn();
        });
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->app->singleton('browser-detect', Parser::class);
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['browser-detect'];
    }
}
