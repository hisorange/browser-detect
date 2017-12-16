<?php

namespace hisorange\BrowserDetect\Test\Stages;

use hisorange\BrowserDetect\Contracts\ResultInterface;
use hisorange\BrowserDetect\Payload;
use hisorange\BrowserDetect\Stages\BrowserDetect;
use hisorange\BrowserDetect\Test\TestCase;
use function json_encode;

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
