<?php

namespace hisorange\BrowserDetect\Test;

use hisorange\BrowserDetect\Contract\Result;
use hisorange\BrowserDetect\Parser\Parser;

/**
 * Class ParserTest
 *
 * @package hisorange\BrowserDetect\Test
 */
class ParserTest extends TestCase
{
    /**
     * @var \hisorange\BrowserDetect\Contract\Parser
     */
    protected $parser;

    /**
     * Create the parser.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->parser = $this->app->make('browser-detect.parser');
    }

    /**
     * @covers Parser::getEmptyDataSchema()
     * @throws \PHPUnit_Framework_Exception
     */
    public function testGetEmptyDataSchema()
    {
        $actual = $this->parser->getEmptyDataSchema();

        $this->assertInternalType('array', $actual);
    }

    /**
     * @covers Parser::getEmptyResult()
     * @throws \PHPUnit_Framework_Exception
     */
    public function testGetEmptyResult()
    {
        $actual = $this->parser->getEmptyResult();

        $this->assertInstanceOf(Result::class, $actual);
    }

    /**
     * @covers Parser::visitorUserAgent()
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testVisitorUserAgent()
    {
        $actual = $this->parser->visitorUserAgent();

        $this->assertNotEmpty($actual);
    }

    /**
     * @param $agent
     * @param $expected
     *
     * @dataProvider providerForHashUserAgentString
     * @covers Parser::hashUserAgentString()
     */
    public function testHashUserAgentString($agent, $expected)
    {
        $actual = $this->parser->hashUserAgentString($agent);

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers Parser::parse()
     * @throws \PHPUnit_Framework_Exception
     */
    public function testParse()
    {
        $actual = $this->parser->parse('-');

        $this->assertInstanceOf(Result::class, $actual);
    }

    /**
     * @covers Parser::detect()
     * @throws \PHPUnit_Framework_Exception
     */
    public function testDetect()
    {
        $actual = $this->parser->detect();

        $this->assertInstanceOf(Result::class, $actual);
    }

    /**
     * @return array
     */
    public function providerForHashUserAgentString()
    {
        $prefix = 'hbd1';
        $agents = [
            'Chrome',
            'Firefox',
            'Opera',
            'Internet Explorer',
        ];
        $hashes = [];

        foreach ($agents as $agent) {
            $hashes[] = [$agent, $prefix . '_' . md5($agent)];
        }

        return $hashes;
    }
}
