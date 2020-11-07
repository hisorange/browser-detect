<?php
namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Result;
use hisorange\BrowserDetect\Contracts\ResultInterface;

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
            'isInApp'              => false,
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
            'isWindows'            => false,
            'isLinux'              => false,
            'isMac'                => false,
            'isAndroid'            => false,
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
     * @covers ::isInApp()
     * @covers ::isEdge()
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
     * @covers ::isWindows()
     * @covers ::isLinux()
     * @covers ::isMac()
     * @covers ::isAndroid()
     * @covers ::deviceFamily()
     * @covers ::deviceModel()
     * @covers ::mobileGrade()
     */
    public function testUserAgent()
    {
        $keys   = $this->getKeys();
        $value  = 'valueType';
        $result = new Result(array_fill_keys($keys, $value));

        $this->assertSame($value, $result->userAgent());
        $this->assertSame(!!$value, $result->isMobile());
        $this->assertSame(!!$value, $result->isTablet());
        $this->assertSame(!!$value, $result->isDesktop());
        $this->assertSame(!!$value, $result->isBot());
        $this->assertSame(!!$value, $result->isChrome());
        $this->assertSame(!!$value, $result->isFirefox());
        $this->assertSame(!!$value, $result->isOpera());
        $this->assertSame(!!$value, $result->isSafari());
        $this->assertSame(!!$value, $result->isIE());
        $this->assertSame(!!$value, $result->isInApp());
        $this->assertSame($value, $result->browserName());
        $this->assertSame($value, $result->browserFamily());
        $this->assertSame($value, $result->browserVersion());
        $this->assertSame((int) $value, $result->browserVersionMajor());
        $this->assertSame((int) $value, $result->browserVersionMinor());
        $this->assertSame((int) $value, $result->browserVersionPatch());
        $this->assertSame($value, $result->browserEngine());
        $this->assertSame($value, $result->platformName());
        $this->assertSame($value, $result->platformFamily());
        $this->assertSame($value, $result->platformVersion());
        $this->assertSame((int) $value, $result->platformVersionMajor());
        $this->assertSame((int) $value, $result->platformVersionMinor());
        $this->assertSame((int) $value, $result->platformVersionPatch());
        $this->assertSame(!!$value, $result->isWindows());
        $this->assertSame(!!$value, $result->isLinux());
        $this->assertSame(!!$value, $result->isMac());
        $this->assertSame(!!$value, $result->isAndroid());
        $this->assertSame($value, $result->deviceFamily());
        $this->assertSame($value, $result->deviceModel());
        $this->assertSame($value, $result->mobileGrade());
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
            'isInApp',
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
            'isWindows',
            'isLinux',
            'isMac',
            'isAndroid',
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

    public function testChromeFamily()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36';
        $result = $parser->parse($agent);

        $this->assertSame($result->isChrome(), true);
        $this->assertSame($result->isFirefox(), false);
        $this->assertSame($result->isOpera(), false);
        $this->assertSame($result->isSafari(), false);
        $this->assertSame($result->isIE(), false);
        $this->assertSame($result->isEdge(), false);
    }

    public function testFirefoxFamily()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (X11; Linux i686; rv:64.0) Gecko/20100101 Firefox/64.0';
        $result = $parser->parse($agent);

        $this->assertSame($result->isChrome(), false);
        $this->assertSame($result->isFirefox(), true);
        $this->assertSame($result->isOpera(), false);
        $this->assertSame($result->isSafari(), false);
        $this->assertSame($result->isIE(), false);
        $this->assertSame($result->isEdge(), false);
    }

    public function testOperaFamily()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Opera/9.80 (Macintosh; Intel Mac OS X 10.14.1) Presto/2.12.388 Version/12.16';
        $result = $parser->parse($agent);

        $this->assertSame($result->isChrome(), false);
        $this->assertSame($result->isFirefox(), false);
        $this->assertSame($result->isOpera(), true);
        $this->assertSame($result->isSafari(), false);
        $this->assertSame($result->isIE(), false);
        $this->assertSame($result->isEdge(), false);
    }

    public function testSafariFamily()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A';
        $result = $parser->parse($agent);

        $this->assertSame($result->isChrome(), false);
        $this->assertSame($result->isFirefox(), false);
        $this->assertSame($result->isOpera(), false);
        $this->assertSame($result->isSafari(), true);
        $this->assertSame($result->isIE(), false);
        $this->assertSame($result->isEdge(), false);
    }

    public function testIEFamily()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (compatible, MSIE 11, Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko';
        $result = $parser->parse($agent);

        $this->assertSame($result->isChrome(), false);
        $this->assertSame($result->isFirefox(), false);
        $this->assertSame($result->isOpera(), false);
        $this->assertSame($result->isSafari(), false);
        $this->assertSame($result->isIE(), true);
        $this->assertSame($result->isEdge(), false);
    }

    public function testEdgeFamily()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14931';
        $result = $parser->parse($agent);

        $this->assertSame($result->isChrome(), false);
        $this->assertSame($result->isFirefox(), false);
        $this->assertSame($result->isOpera(), false);
        $this->assertSame($result->isSafari(), false);
        $this->assertSame($result->isEdge(), true);
    }

    public function testSamsungBrowser()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-G960U) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/10.2 Chrome/71.0.3578.99 Mobile Safari/537.36';
        $result = $parser->parse($agent);

        $this->assertSame($result->isMobile(), true);
    }

    public function testWindows()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (Windows NT 5.1; rv:11.0) Gecko Firefox/11.0 (via ggpht.com GoogleImageProxy)';
        $result = $parser->parse($agent);


        $this->assertSame($result->platformFamily(), 'Windows');
        $this->assertSame($result->isWindows(), true);
    }

    public function testIOS()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';
        $result = $parser->parse($agent);


        $this->assertSame($result->platformFamily(), 'iOS');
        $this->assertSame($result->isMac(), true);
    }

    public function testMac()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-en) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4';
        $result = $parser->parse($agent);


        $this->assertSame($result->platformFamily(), 'Mac');
        $this->assertSame($result->isMac(), true);
    }

    public function testAndroid()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (Linux; U; Android 2.2) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';
        $result = $parser->parse($agent);


        $this->assertSame($result->platformFamily(), 'Android');
        $this->assertSame($result->isAndroid(), true);
    }

    public function testLinux()
    {
        $parser = $this->app->make('browser-detect');
        $agent  = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36';
        $result = $parser->parse($agent);


        $this->assertSame($result->platformFamily(), 'GNU/Linux');
        $this->assertSame($result->isLinux(), true);
    }
}
