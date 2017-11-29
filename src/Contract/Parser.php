<?php

namespace hisorange\BrowserDetect\Contract;

/**
 * Interface for parsing.
 *
 * @package hisorange\BrowserDetect\Contract
 */
interface Parser
{
    /**
     * @return Result
     */
    public function getEmptyResult();

    /**
     * @param string $agent
     *
     * @return string
     */
    public function hashUserAgentString($agent);

    /**
     * @param string $agent
     *
     * @return Result
     */
    public function parse($agent);

    /**
     * @param null|string $agent
     *
     * @return Result
     */
    public function detect($agent = null);

    /**
     * @return array
     */
    public function getEmptyDataSchema();

    /**
     * @return string
     */
    public function visitorUserAgent();
}