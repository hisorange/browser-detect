<?php

namespace hisorange\BrowserDetect\Contract;

/**
 * Interface Plugin
 *
 * @package hisorange\BrowserDetect\Contract
 */
interface Plugin
{
    /**
     * @param  string $agent
     * @return mixed
     */
    public function parse($agent);

    /**
     * @param  mixed $result
     * @return mixed
     */
    public function filter($result);
}