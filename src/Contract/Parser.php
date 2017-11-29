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
}