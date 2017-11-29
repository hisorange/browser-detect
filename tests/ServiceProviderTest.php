<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Contract\Parser;
use hisorange\BrowserDetect\Contract\Result;
use hisorange\BrowserDetect\ServiceProvider;

class ServiceProviderTest extends TestCase
{
    /**
     * Test the parser is registered.
     *
     * @covers ServiceProvider::register()
     * @covers ServiceProvider::registerParser()
     * @throws \PHPUnit_Framework_Exception
     */
    public function testRegisterParser()
    {
        $expected = Parser::class;
        $actual   = $this->app->make('browser-detect.parser');

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Test the result register.
     *
     * @covers ServiceProvider::register()
     * @covers ServiceProvider::registerResult()
     * @throws \PHPUnit_Framework_Exception
     */
    public function testRegisterResult()
    {
        $expected = Result::class;
        $actual   = $this->app->make('browser-detect.result');

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Test the provides.
     *
     * @covers ServiceProvider::provides()
     * @throws \PHPUnit_Framework_Exception
     */
    public function testProvides()
    {
        $provider = $this->getMockBuilder(ServiceProvider::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['provides'])
            ->getMock();

        $actual = $provider->provides();

        $this->assertContains('browser-detect.parser', $actual);
        $this->assertContains('browser-detect.result', $actual);
    }
}
