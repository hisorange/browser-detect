<?php

namespace hisorange\BrowserDetect\Test\Stages;

use hisorange\BrowserDetect\Payload;
use hisorange\BrowserDetect\Stages\DeviceDetector;
use hisorange\BrowserDetect\Test\TestCase;
use function var_export;

/**
 * Test the DeviceDetector stage.
 *
 * @package            hisorange\BrowserDetect\Test\Stages
 * @coversDefaultClass hisorange\BrowserDetect\Stages\DeviceDetector
 */
class DeviceDetectorTest extends TestCase
{
    /**
     * @dataProvider provideAgents
     *
     * @covers ::__invoke()
     *
     * @param string $agent
     * @param array  $changes
     */
    public function testInvoke($agent, $changes)
    {
        $stage  = new DeviceDetector;
        $result = new Payload($agent);

        $stage($result);

        foreach ($changes as $key => $expected) {
            $this->assertSame($expected, $result->getValue($key), 'Changes are not matched ' . print_r($changes, true) . ' with ' . $key . ' being ' . var_export($result->getValue($key), true));
        }
    }

    /**
     * Simple agents to test the crawler stage.
     *
     * @return array
     */
    public function provideAgents()
    {
        return [
            [
                'Mozilla/5.0 (Linux; U; Android 2.2.1; en-ca; LG-P505R Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
                [
                    'browserEngine' => 'WebKit',
                ],
            ],
            [
                'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.360',
                [
                    'browserEngine' => 'Blink',
                ],
            ],
            [
                'NotGonaMatchMe',
                [
                    'isMobile'      => null,
                    'isTablet'      => null,
                    'isDesktop'     => null,
                    'browserEngine' => null,
                ],
            ],
        ];;
    }
}
