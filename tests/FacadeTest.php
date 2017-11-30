<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Facade;

/**
 * Class FacadeTest
 * @package hisorange\BrowserDetect\Test
 */
class FacadeTest extends TestCase
{
    /**
     * @covers Facade::getFacadeAccessor()
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testAccessibility()
    {
        $this->assertTrue(class_exists('Browser'));
    }
}
