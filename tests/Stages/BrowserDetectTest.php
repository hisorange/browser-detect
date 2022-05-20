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
        $payload = new Payload('Unknown');

        $payload->setValue('userAgent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/W.X.Y.Z Safari/537.36 Prerender (+https://github.com/prerender/prerender)');
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
        $payload = new Payload('Unknown');

        $payload->setValue('userAgent', 'Mozilla/5.0 (Linux; Android 11; Pixel 5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z Mobile Safari/537.36 Prerender (+https://github.com/prerender/prerender)');
        $result = $stage($payload);

        $this->assertTrue($result->isBot());
        $this->assertTrue($result->isMobile());
        $this->assertFalse($result->isTablet());
        $this->assertFalse($result->isDesktop());
    }

    /**
     * Check for inApp browsers.
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInApp()
    {
        $stage  = new BrowserDetect;
        $payload = new Payload('Mozilla/5.0 (Linux; Android 4.4; Nexus 5 Build/_BuildID_) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36');
        $result = $stage($payload);
        $this->assertTrue($result->isInApp());


        $stage  = new BrowserDetect;
        $payload = new Payload('Mozilla/5.0 AppleWebKit/537.36');
        $result = $stage($payload);
        $this->assertFalse($result->isInApp());
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
