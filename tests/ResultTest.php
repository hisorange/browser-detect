<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Contracts\ResultInterface;
use hisorange\BrowserDetect\Result;
use function json_decode;

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
        $this->assertInstanceOf(ResultInterface::class, new Result([]));
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $result = new Result(['userAgent' => 'test']);

        $this->assertSame('test', $result->userAgent());
    }

    /**
     * @covers ::toArray()
     */
    public function testToArray()
    {
        $actual   = $this->getEmptyResult()->toArray();
        $expected = [
            'userAgent'            => 'Unknown',
            'isMobile'             => false,
            'isTablet'             => false,
            'isDesktop'            => false,
            'isBot'                => false,
            'isChrome'             => false,
            'isFirefox'            => false,
            'isOpera'              => false,
            'isSafari'             => false,
            'isEdge'               => false,
            'isIE'                 => false,
            'browserName'          => 'Unknown',
            'browserFamily'        => 'Unknown',
            'browserVersion'       => '',
            'browserVersionMajor'  => 0,
            'browserVersionMinor'  => 0,
            'browserVersionPatch'  => 0,
            'browserEngine'        => 'Unknown',
            'platformName'         => 'Unknown',
            'platformFamily'       => 'Unknown',
            'platformVersion'      => '',
            'platformVersionMajor' => 0,
            'platformVersionMinor' => 0,
            'platformVersionPatch' => 0,
            'deviceFamily'         => 'Unknown',
            'deviceModel'          => '',
            'mobileGrade'          => '',
        ];

        $this->assertSame($expected, $actual);
    }

    /**
     * @return ResultInterface
     */
    protected function getEmptyResult()
    {
        return new Result([]);
    }

    /**
     * @covers ::__construct()
     * @covers ::userAgent()
     * @covers ::isMobile()
     * @covers ::isTablet()
     * @covers ::isDesktop()
     * @covers ::isBot()
     * @covers ::isChrome()
     * @covers ::isFirefox()
     * @covers ::isOpera()
     * @covers ::isSafari()
     * @covers ::isIE()
     * @covers ::browserName()
     * @covers ::browserFamily()
     * @covers ::browserVersion()
     * @covers ::browserVersionMajor()
     * @covers ::browserVersionMinor()
     * @covers ::browserVersionPatch()
     * @covers ::browserEngine()
     * @covers ::platformName()
     * @covers ::platformFamily()
     * @covers ::platformVersion()
     * @covers ::platformVersionMajor()
     * @covers ::platformVersionMinor()
     * @covers ::platformVersionPatch()
     * @covers ::deviceFamily()
     * @covers ::deviceModel()
     * @covers ::mobileGrade()
     */
    public function testUserAgent()
    {
        $keys   = $this->getKeys();
        $value  = 'testable';
        $result = new Result(array_fill_keys($keys, $value));

        foreach ($keys as $key) {
            $this->assertSame($value, $result->$key());
        }
    }

    /**
     * @return array
     */
    protected function getKeys()
    {
        return [
            'userAgent',
            'isMobile',
            'isTablet',
            'isDesktop',
            'isBot',
            'isChrome',
            'isFirefox',
            'isOpera',
            'isSafari',
            'isEdge',
            'isIE',
            'browserName',
            'browserFamily',
            'browserVersion',
            'browserVersionMajor',
            'browserVersionMinor',
            'browserVersionPatch',
            'browserEngine',
            'platformName',
            'platformFamily',
            'platformVersion',
            'platformVersionMajor',
            'platformVersionMinor',
            'platformVersionPatch',
            'deviceFamily',
            'deviceModel',
            'mobileGrade',
        ];
    }

    /**
     * @covers ::isIEVersion()
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testIEVersion()
    {
        $result = new Result([
            'isIE'           => true,
            'browserVersion' => 6,
        ]);

        $this->assertTrue($result->isIEVersion(6, '='));
        $this->assertTrue($result->isIEVersion(6, '<='));
        $this->assertFalse($result->isIEVersion(6, '>'));
        $this->assertFalse($result->isIEVersion(7, '>'));
    }

    public function testJsonOutput()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.';
        $result = $parser->parse($agent);
        // Encode and decode to get the keys.
        $keys   = array_keys(json_decode(json_encode($result), true));

        $this->assertSame($keys, $this->getKeys());
    }
}
