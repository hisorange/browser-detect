<?php

namespace hisorange\BrowserDetect\Test;

use Browser;
use hisorange\BrowserDetect\ParserInterface;

/**
 * Class FacadeTest
 * @package            hisorange\BrowserDetect\Test
 */
class FacadeTest extends TestCase
{
    /**
     * @covers \hisorange\BrowserDetect\Facade
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit_Framework_Exception
     */
    public function testResolve()
    {
        $this->assertTrue(class_exists('Browser'));

        $expected = ParserInterface::class;
        $actual   = Browser::getFacadeRoot();

        $this->assertInstanceOf($expected, $actual);
    }
}
