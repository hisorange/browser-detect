<?php

namespace hisorange\BrowserDetect\Test\Stages;

use hisorange\BrowserDetect\Result;
use hisorange\BrowserDetect\Stages\Correction;
use hisorange\BrowserDetect\Test\TestCase;

/**
 * Test the UAParser stage.
 *
 * @package            hisorange\BrowserDetect\Test\Stages
 * @coversDefaultClass hisorange\BrowserDetect\Stages\Correction
 */
class CorrectionTest extends TestCase
{
    /**
     * @dataProvider provideScenarios
     *
     * @covers ::__invoke()
     *
     * @param array $scenario
     * @param array $expectations
     */
    public function testInvoke($scenario, $expectations)
    {
        $stage  = new Correction;
        $result = new Result(null);
        $result->extend($scenario);

        $stage($result);

        foreach ($expectations as $key => $expected) {
            $this->assertSame($expected, $result->offsetGet($key));
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
