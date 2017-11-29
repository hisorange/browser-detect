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
     * @return mixed
     */
    public function parse();

    /**
     * @return mixed
     */
    public function filter();
}