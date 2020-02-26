<?php
namespace hisorange\BrowserDetect\Test\Stages;

use hisorange\BrowserDetect\Payload;
use hisorange\BrowserDetect\Test\TestCase;
use hisorange\BrowserDetect\Stages\UAParser;

/**
 * Test the UAParser stage.
 *
 * @package            hisorange\BrowserDetect\Test\Stages
 * @coversDefaultClass hisorange\BrowserDetect\Stages\UAParser
 */
class UAParserTest extends TestCase
{
    /**
     * @dataProvider provideAgent
     *
     * @covers ::__invoke()
     *
     * @param string $agent
     * @param array  $changes
     *
     * @throws \UAParser\Exception\FileNotFoundException
     */
    public function testInvoke($agent, $changes)
    {
        $stage  = new UAParser;
        $result = new Payload($agent);

        $stage($result);

        foreach ($changes as $key => $expected) {
            $this->assertSame($expected, $result->getValue($key), 'Changes are not matched ' . print_r($changes, true) . ' with ' . $key . ' being ' . var_export($result->getValue($key), true));
        }
    }

    /**
     * Common agents to test the crawler stage.
     *
     * @return array
     */
    public function provideAgent()
    {
        return [
            [
                'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.',
                [
                    'deviceFamily' => 'iPad',
                ],
            ],
            [
                'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2224.3 Safari/537.36',
                [
                    'browserFamily'       => 'Chrome',
                    'browserVersionMajor' => 41,
                    'browserVersionMinor' => 0,
                    'browserVersionPatch' => 2224,
                ],
            ],
            [
                'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1',
                [
                    'browserFamily'       => 'Firefox',
                    'browserVersionMajor' => 40,
                    'browserVersionMinor' => 1,
                    'browserVersionPatch' => 0,
                ],
            ],
            [
                'TestUserAgentString',
                [
                    'browserFamily'       => null,
                    'browserVersionMajor' => null,
                    'browserVersionMinor' => null,
                    'browserVersionPatch' => null,
                ],
            ],
        ];
    }
}
