<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Contracts\ParserInterface;
use hisorange\BrowserDetect\ServiceProvider;

/**
 * Class ServiceProviderTest
 * @package            hisorange\BrowserDetect\Test
 * @coversDefaultClass hisorange\BrowserDetect\ServiceProvider
 */
class ServiceProviderTest extends TestCase
{
    /**
     * @covers ::isDeferred()
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIsDeferred()
    {
        $provider = $this->getMockBuilder(ServiceProvider::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['isDeferred'])
            ->getMock();

        $actual = $provider->isDeferred();

        $this->assertTrue($actual);
    }

    /**
     * @covers ::register()
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit\Framework\Exception
     */
    public function testRegister()
    {
        $expected = ParserInterface::class;
        $actual   = $this->app->make('browser-detect');

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @covers ::provides()
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit\Framework\Exception
     */
    public function testProvides()
    {
        $provider = $this->getMockBuilder(ServiceProvider::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['provides'])
            ->getMock();

        $actual = $provider->provides();

        $this->assertContains('browser-detect', $actual);
    }
}
