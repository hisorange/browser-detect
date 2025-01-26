<?php
namespace hisorange\BrowserDetect\Test;

use Closure;
use hisorange\BrowserDetect\Facade;
use hisorange\BrowserDetect\Payload;
use hisorange\BrowserDetect\Result;
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

    protected function getNext(): Closure
    {
        return fn (Payload $payload) => $payload;
    }

    protected function getResultClosure(): Closure
    {
        return fn (Payload $payload) => new Result($payload->toArray());
    }
}
