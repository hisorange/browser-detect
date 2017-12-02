<?php

namespace hisorange\BrowserDetect\Test;

/**
 * Class FacadeTest
 * @package hisorange\BrowserDetect\Test
 * @coversDefaultClass hisorange\BrowserDetect\Facade
 */
class FacadeTest extends TestCase
{
    /**
     * @covers ::getFacadeAccessor()
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testAccessibility()
    {
        $this->assertTrue(class_exists('Browser'));
    }
}
