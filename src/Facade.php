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
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return 'browser-detect';
    }
}