<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Result;
use hisorange\BrowserDetect\ResultInterface;

/**
 * Class ResultTest
 * @package            hisorange\BrowserDetect\Test
 * @coversDefaultClass hisorange\BrowserDetect\Result
 */
class ResultTest extends TestCase
{
    /**
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit\Framework\Exception
     */
    public function testInterfaceImplementation()
    {
        $this->assertInstanceOf(ResultInterface::class, new Result(null));
    }

    /**
     * @dataProvider agentProvider
     * @covers ::__construct
     * @param $actual
     * @param $expected
     */
    public function testConstruct($actual, $expected)
    {
        $result = new Result($actual);

        $this->assertSame($expected, $result->offsetGet('userAgent'));
    }

    /**
     * @covers ::toArray()
     */
    public function testToArray()
    {
        $actual   = $this->getEmptyResult()->toArray();
        $expected = $this->getSchema();

        $this->assertSame($expected, $actual);
    }

    /**
     * @return ResultInterface
     */
    protected function getEmptyResult()
    {
        return new Result(null);
    }

    /**
     * Provide an empty schema for testing.
     *
     * @return array
     */
    protected function getSchema()
    {
        return [
            'userAgent'           => 'UnknownBrowser',
            'isMobile'            => false,
            'isTablet'            => false,
            'isDesktop'           => false,
            'isBot'               => false,
            'browserFamily'       => 'UnknownBrowserFamily',
            'browserVersionMajor' => 0,
            'browserVersionMinor' => 0,
            'browserVersionPatch' => 0,
            'osFamily'            => 'UnknownOS',
            'osVersionMajor'      => 0,
            'osVersionMinor'      => 0,
            'osVersionPatch'      => 0,
            'deviceFamily'        => 'UnknownDeviceFamily',
            'deviceModel'         => '',
            'mobileGrade'         => '',
        ];
    }

    /**
     * @return array
     */
    public function agentProvider()
    {
        return [
            [null, 'UnknownBrowser'],
            ['', ''],
            [0, 0],

            ['Test 1', 'Test 1'],
        ];
    }

    /**
     * @covers ::extend()
     * @dataProvider extendProvider
     * @param array $extension
     * @param array $expected
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit\Framework\Exception
     */
    public function testExtend($extension, $expected)
    {
        $result = $this->getEmptyResult();
        $result->extend($extension);
        $result = $result->toArray();

        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $result);
            $this->assertContains($value, $result);
        }
    }

    /**
     * @return array
     */
    public function extendProvider()
    {
        return [
            [
                [
                    'isMobile'  => true,
                    'isDesktop' => true,
                ],
                [
                    'isMobile'  => true,
                    'isDesktop' => true,
                ],
            ],
            [
                [
                    'userAgent' => null,
                ],
                [
                    'userAgent' => 'UnknownBrowser',
                ],
            ],
        ];
    }

    /**
     * @covers ::jsonSerialize
     * @covers ::__toString
     */
    public function testJsonification()
    {
        $result = new Result('TestAgentValue');
        $json   = json_encode($result);
        $string = (string) $result;

        $this->assertSame($json, $string);

        $wakened = $this->getEmptyResult();
        $wakened->extend(json_decode($json, true));

        $this->assertSame($result->toArray(), $wakened->toArray());

    }

    /**
     * @covers ::__sleep
     */
    public function testSerialization()
    {
        $result       = new Result('TestSerialized');
        $serialized   = serialize($result);
        $unserialized = unserialize($serialized);

        $this->assertSame($result->toArray(), $unserialized->toArray());
    }

    /**
     * @covers ::offsetExists()
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testOffsetExists()
    {
        $result = $this->getEmptyResult();

        $this->assertTrue($result->offsetExists('userAgent'));
        $this->assertTrue($result->offsetExists('isDesktop'));
        $this->assertFalse($result->offsetExists('isDesktop2'));
        $this->assertFalse($result->offsetExists(''));
    }

    /**
     * @covers ::offsetGet()
     */
    public function testOffsetGet()
    {
        $result   = $this->getEmptyResult();
        $expected = $this->getSchema();

        $this->assertSame($result->offsetGet('userAgent'), $expected['userAgent']);
        $this->assertSame($result->offsetGet('isDesktop'), $expected['isDesktop']);
    }

    /**
     * @covers ::offsetSet()
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testOffsetSet()
    {
        $result = $this->getEmptyResult();
        $result->offsetSet('userAgent', 'a');

        $this->assertSame($result->offsetGet('userAgent'), 'a');

        $result->offsetSet('b', 'c');

        $this->assertFalse($result->offsetExists('b'));
    }

    /**
     * @covers ::offsetUnset()
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testOffsetUnset()
    {
        $result = $this->getEmptyResult();
        $result->offsetUnset('userAgent');

        $this->assertFalse($result->offsetExists('userAgent'));
    }

    /**
     * @covers ::extend()
     * @throws \PHPUnit\Framework\Exception
     */
    public function testExtendFakeKey()
    {
        $result = new Result(null);
        $result->extend(['fake' => true]);

        $this->assertArrayNotHasKey('fake', $result->toArray());
    }
}
