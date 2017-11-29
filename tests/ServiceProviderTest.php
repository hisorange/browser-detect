<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Contract\Parser;
use hisorange\BrowserDetect\Contract\Result;
use hisorange\BrowserDetect\Provider\BrowserDetectServiceProvider;

class ServiceProviderTest extends TestCase
{
    /**
     * Test the parser is registered.
     *
     * @covers BrowserDetectServiceProvider::register()
     * @covers BrowserDetectServiceProvider::registerParser()
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
     * @covers BrowserDetectServiceProvider::register()
     * @covers BrowserDetectServiceProvider::registerResult()
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
     * @covers BrowserDetectServiceProvider::provides()
     * @throws \PHPUnit_Framework_Exception
     */
    public function testProvides()
    {
        $provider = $this->getMockBuilder(BrowserDetectServiceProvider::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['provides'])
            ->getMock();

        $actual = $provider->provides();

        $this->assertContains('browser-detect.parser', $actual);
        $this->assertContains('browser-detect.result', $actual);
    }
}
