<?php

namespace hisorange\BrowserDetect\Facade;

use Illuminate\Support\Facades\Facade;

class Parser extends Facade
{
    /**
     * {@inheritdocs}
     */
    protected static function getFacadeAccessor()
    {
        return 'browser-detect.parser';
    }
}