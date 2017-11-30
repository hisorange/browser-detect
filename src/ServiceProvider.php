<?php

namespace hisorange\BrowserDetect;

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
     * @inheritdoc
     */
    public function register()
    {
        $this->app->singleton('browser-detect.parser', Parser::class);
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['browser-detect.parser'];
    }
}