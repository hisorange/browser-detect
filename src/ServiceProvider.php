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
        if (version_compare($this->app->version(), '5.5', '>=')) {
            foreach (['desktop', 'tablet', 'mobile'] as $key) {
                Blade::if ($key, function () use ($key) {
                    return app()->make('browser-detect')->detect()->offsetGet('is' . ucfirst($key));
                });
            }

            Blade::if ('browser', function ($key) {
                return app()->make('browser-detect')->detect()->offsetGet($key);
            });
        }
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