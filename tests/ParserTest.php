<?php
namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Parser;
use hisorange\BrowserDetect\Contracts\ResultInterface;
use hisorange\BrowserDetect\Exceptions\InvalidArgumentException;

/**
 * Class ParserTest
 * @package            hisorange\BrowserDetect\Test
 * @coversDefaultClass hisorange\BrowserDetect\Parser
 */
class ParserTest extends TestCase
{
    /**
     * @covers ::detect()
     * @covers ::<protected>key()
     * @covers ::<protected>process()
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDetect()
    {
        $parser   = $this->getParser();
        $actual   = $parser->detect();
        $expected = ResultInterface::class;

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @covers ::config()
     */
    public function testConfigMerge()
    {
        $i = new Parser(null, null, [
            'cache' => [
                'interval' => 42
            ]
        ]);

        $this->assertSame($i->config()['cache']['interval'], 42);
        $this->assertSame($i->config()['cache']['prefix'], 'bd4_');
    }

    /**
     * @covers ::__construct()
     * @covers ::parse()
     */
    public function testStandaloneConstruct()
    {
        $this->assertInstanceOf(ResultInterface::class, (new Parser())->parse('test'));
    }

    /**
     * @covers ::__callStatic()
     * @covers ::getUserAgentString()
     */
    public function testStandaloneFacade()
    {
        $this->assertSame(Parser::isMobile(), false);
    }

    /**
     * Check if the results are the same.
     */
    public function testStandaloneResult()
    {
        $this->assertSame(Parser::toArray(), $this->getParser()->parse('')->toArray());
    }

    /**
     * @covers ::parse()
     */
    public function testStandaloneRuntimeCache()
    {
        $this->assertSame(Parser::toArray(), Parser::toArray());
    }

    /**
     * @covers ::__construct()
     *
     * @return \hisorange\BrowserDetect\Contracts\ParserInterface
     */
    protected function getParser()
    {
        return $this->app->make('browser-detect');
    }

    /**
     * @dataProvider provideAgents
     * @param string $agent
     * @covers ::parse()
     * @covers ::<protected>key()
     * @covers ::<protected>process()
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit\Framework\Exception
     */
    public function testParse($agent)
    {
        $parser   = $this->getParser();
        $actual   = $parser->parse($agent);
        $expected = ResultInterface::class;

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return array
     */
    public function provideAgents()
    {
        return [
            ['Unknown'],
            ['Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1'],
            ['Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0'],
            ['Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'],
            ['Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; AS; rv:11.0) like Gecko'],
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14931'],
            ['Chrome (AppleWebKit/537.1; Chrome50.0; Windows NT 6.3) AppleWebKit/537.36 (KHTML like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14393'],
        ];
    }

    /**
     * @covers ::__call()
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testCall()
    {
        $this->assertNotEmpty($this->getParser()->userAgent());
    }

    /**
     * @covers ::__call()
     */
    public function testCallException()
    {
        $this->expectException(\hisorange\BrowserDetect\Exceptions\BadMethodCallException::class);
        $this->getParser()->BadMethod();
    }
}
