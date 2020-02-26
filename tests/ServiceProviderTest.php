<?php
namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Contracts\ParserInterface;

/**
 * Class ServiceProviderTest
 * @package            hisorange\BrowserDetect\Test
 * @coversDefaultClass hisorange\BrowserDetect\ServiceProvider
 */
class ServiceProviderTest extends TestCase
{
    /**
     * @covers ::register()
     * @covers ::registerDirectives()
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit\Framework\Exception
     */
    public function testRegister()
    {
        $expected = ParserInterface::class;
        $actual   = $this->app->make('browser-detect');

        $this->assertInstanceOf($expected, $actual);
    }
}
