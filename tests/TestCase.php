<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Facade;
use hisorange\BrowserDetect\ServiceProvider;

/**
 * Base test case for the package tests.
 *
 * Class TestCase
 *
 * @package hisorange\BrowserDetect\Test
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Register the service.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * Register the alias.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Browser' => Facade::class,
        ];
    }
}
