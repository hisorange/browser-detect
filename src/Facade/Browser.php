<?php

namespace hisorange\BrowserDetect\Facade;

use Illuminate\Support\Facades\Facade;

class Browser extends Facade
{
    /**
     * {@inheritdocs}
     */
    protected static function getFacadeAccessor()
    {
        return 'browser-detect.parser';
    }
}