<?php
namespace hisorange\BrowserDetect;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * Facading class to mask the service behind the "Browser" class.
 *
 * @example Browser::isMobile();
 * @package hisorange\BrowserDetect
 */
class Facade extends BaseFacade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return 'browser-detect';
    }
}
