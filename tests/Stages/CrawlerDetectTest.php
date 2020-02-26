<?php
namespace hisorange\BrowserDetect\Test\Stages;

use hisorange\BrowserDetect\Payload;
use hisorange\BrowserDetect\Test\TestCase;
use hisorange\BrowserDetect\Stages\CrawlerDetect;

/**
 * Test the CrawlerDetect stage.
 *
 * @package            hisorange\BrowserDetect\Test\Stages
 * @coversDefaultClass hisorange\BrowserDetect\Stages\CrawlerDetect
 */
class CrawlerDetectTest extends TestCase
{
    /**
     * @dataProvider provideAgents
     *
     * @covers ::__invoke()
     *
     * @param string $agent
     * @param bool   $expected
     */
    public function testInvoke($agent, $expected)
    {
        $stage  = new CrawlerDetect;
        $result = new Payload($agent);

        $stage($result);

        $this->assertSame($expected, $result->getValue('isBot'), sprintf('User agent "%s" failing the crawler test.', $agent));
    }

    /**
     * Simple agents to test the crawler stage.
     *
     * @return array
     */
    public function provideAgents()
    {
        return [
            ['NotGoingToMatch', false],
            ['GoogleBot', true],
            ['Yahoo Crawler', true],
        ];
    }
}
