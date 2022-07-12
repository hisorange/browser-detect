<?php

namespace hisorange\BrowserDetect\Test\Stages;

use hisorange\BrowserDetect\Payload;
use hisorange\BrowserDetect\Test\TestCase;
use hisorange\BrowserDetect\Stages\BrowserDetect;
use hisorange\BrowserDetect\Contracts\ResultInterface;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Test the UAParser stage.
 *
 * @package            hisorange\BrowserDetect\Test\Stages
 * @coversDefaultClass hisorange\BrowserDetect\Stages\BrowserDetect
 */
class BrowserDetectTest extends TestCase
{
    /**
     * @dataProvider provideScenarios
     *
     * @covers ::__invoke()
     *
     * @param array $scenario
     * @param array $expectations
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit_Framework_Exception
     */
    public function testInvoke($scenario, $expectations)
    {
        $stage  = new BrowserDetect;
        $payload = new Payload('Unknown');

        foreach ($scenario as $k => $v) {
            $payload->setValue($k, $v);
        }

        $result = $stage($payload);

        $this->assertInstanceOf(ResultInterface::class, $result);

        foreach ($expectations as $key => $expected) {
            $this->assertSame($expected, $result->$key(), sprintf('Key %s not matching when %s', $key, print_r($scenario, true)));
        }
    }

    /**
     * Check if the Prerender agents are recognized as desktop bot
     *
     * @return void
     */
    public function testPrerenderBot()
    {
        $stage  = new BrowserDetect;
        $payload = new Payload('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/W.X.Y.Z Safari/537.36 Prerender (+https://github.com/prerender/prerender)');

        $result = $stage($payload);

        $this->assertTrue($result->isBot());
        $this->assertFalse($result->isMobile());
        $this->assertFalse($result->isTablet());
        $this->assertTrue($result->isDesktop());
    }

    /**
     * Check if the Prerender agents are recognized as desktop bot
     *
     * @return void
     */
    public function testPrerenderMobileBot()
    {
        $stage  = new BrowserDetect;
        $payload = new Payload('Mozilla/5.0 (Linux; Android 11; Pixel 5) AppleWebKit/537.36 (KHTML, like Gecko)' .
            'Chrome/W.X.Y.Z Mobile Safari/537.36 Prerender (+https://github.com/prerender/prerender)');

        $result = $stage($payload);

        $this->assertTrue($result->isBot());
        $this->assertTrue($result->isMobile());
        $this->assertFalse($result->isTablet());
        $this->assertFalse($result->isDesktop());
    }

    /**
     * Check for WebView inApp browsers.
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInAppWebView()
    {
        $stage  = new BrowserDetect;
        $payload = new Payload('WebView');
        $result = $stage($payload);
        $this->assertTrue($result->isInApp());

        $stage  = new BrowserDetect;
        $payload = new Payload('Mozilla/5.0 (iPhone; CPU iPhone OS 10_0_1 like Mac OS X) ' .
            'AppleWebKit/602.1.32 (KHTML, like Gecko) Mobile/14A403 Twitter for iPhone');
        $result = $stage($payload);
        $this->assertTrue($result->isInApp());

        $stage  = new BrowserDetect;
        $payload = new Payload('Mozilla/5.0 (iPhone; CPU iPhone OS 10_1_1 like Mac OS X) ' .
            'AppleWebKit/602.2.14 (KHTML, like Gecko) Mobile/14B100 MicroMessenger/6.3.30 NetType/WIFI Language/en');
        $result = $stage($payload);
        $this->assertTrue($result->isInApp());
    }

    /**
     * Check for Apple inApp browsers.
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInAppApple()
    {
        $stage  = new BrowserDetect;
        $payload = new Payload('Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_5 like Mac OS X) ' .
            'AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13G36');
        $result = $stage($payload);
        $this->assertTrue($result->isInApp());
    }

    /**
     * Check for Andorid inApp browsers.
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInAppAndroid()
    {
        $stage  = new BrowserDetect;
        $payload = new Payload('Mozilla/5.0 (Linux; Android 5.1.1; Nexus 5 Build/LMY48B; wv) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/43.0.2357.65 Mobile Safari/537.36');
        $result = $stage($payload);
        $this->assertTrue($result->isInApp());
    }

    /**
     * Possible device scenarios.
     *
     * @return array
     */
    public function provideScenarios()
    {
        return [
            [
                [
                    'isDesktop' => true,
                    'isTablet'  => true,
                    'isMobile'  => true,
                ],
                [
                    'isDesktop' => false,
                    'isTablet'  => true,
                    'isMobile'  => false,
                ],
            ],
            [
                [
                    'isDesktop' => true,
                    'isTablet'  => true,
                    'isMobile'  => false,
                ],
                [
                    'isDesktop' => false,
                    'isTablet'  => true,
                    'isMobile'  => false,
                ],
            ],
            [
                [
                    'isDesktop' => true,
                    'isTablet'  => false,
                    'isMobile'  => false,
                ],
                [
                    'isDesktop' => true,
                    'isTablet'  => false,
                    'isMobile'  => false,
                ],
            ],
            [
                [
                    'isDesktop' => false,
                    'isTablet'  => false,
                    'isMobile'  => true,
                ],
                [
                    'isDesktop' => false,
                    'isTablet'  => false,
                    'isMobile'  => true,
                ],
            ],
            [
                [
                    'isDesktop' => true,
                    'isTablet'  => false,
                    'isMobile'  => true,
                ],
                [
                    'isDesktop' => false,
                    'isTablet'  => false,
                    'isMobile'  => true,
                ],
            ],
            [
                [
                    'isDesktop' => false,
                    'isTablet'  => true,
                    'isMobile'  => true,
                ],
                [
                    'isDesktop' => false,
                    'isTablet'  => true,
                    'isMobile'  => false,
                ],
            ],
            [
                [
                    'isDesktop' => true,
                    'isTablet'  => false,
                    'isMobile'  => false,
                ],
                [
                    'isDesktop' => true,
                    'isTablet'  => false,
                    'isMobile'  => false,
                ],
            ],
        ];
    }
}
