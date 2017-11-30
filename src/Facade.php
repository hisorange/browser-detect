<?php

namespace hisorange\BrowserDetect;

/**
 * Class Facade
 * @example Browser::isMobile();
 * @package hisorange\BrowserDetect
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * @inheritDocs
     */
    protected static function getFacadeAccessor()
    {
        return 'browser-detect.parser';
    }
}