<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Contracts\ParserInterface;
use hisorange\BrowserDetect\Facade;

/**
 * Class FacadeTest
 * @package            hisorange\BrowserDetect\Test
 */
class FacadeTest extends TestCase
{
    /**
     * @covers \hisorange\BrowserDetect\Facade
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit\Framework\Exception
     */
    public function testResolve()
    {
        $this->assertTrue(class_exists('Browser'));

        $expected = ParserInterface::class;
        $actual   = Facade::getFacadeRoot();

        $this->assertInstanceOf($expected, $actual);
    }
}
