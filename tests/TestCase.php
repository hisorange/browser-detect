<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Facade\Browser;
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
            'Browser' => Browser::class
        ];
    }

    /**
     * Load the configs.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('hisorange.browser-detect.browser-detect-config', $this->getConfiguration('config'));
        $app['config']->set('hisorange.browser-detect.browser-detect-plugins', $this->getConfiguration('plugins'));
    }

    /**
     * Load a config file without publish.
     *
     * @param string $name Config file name.
     *
     * @return array
     */
    private function getConfiguration($name)
    {
        return require __DIR__ . '/../src/config/' . $name . '.php';
    }
}
