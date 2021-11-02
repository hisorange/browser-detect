<?php

namespace hisorange\BrowserDetect;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * Facading class to mask the service behind the "Browser" class.
 *
 * @method static bool isMobile()
 * @method static bool isTablet()
 * @method static bool isDesktop()
 * @method static bool isChrome()
 * @method static bool isFirefox()
 * @method static bool isOpera()
 * @method static bool isSafari()
 * @method static bool isIE()
 * @method static bool isEdge()
 * @method static bool isWindows()
 * @method static bool isAndroid()
 * @method static bool isMac()
 * @method static bool isLinux()
 * @method static bool isInApp()
 * @method static bool isBot()
 * @method static string mobileGrade()
 * @method static string deviceModel()
 * @method static string deviceFamily()
 * @method static string|null platformFamily()
 * @method static string|null browserFamily()
 * @method static string browserEngine()
 * @method static int browserVersionMajor()
 * @method static int browserVersionMinor()
 * @method static int browserVersionPatch()
 * @method static int platformVersionMajor()
 * @method static int platformVersionMinor()
 * @method static int platformVersionPatch()
 *
 * @example Browser::isMobile();
 * @package hisorange\BrowserDetect
 */
class Facade extends BaseFacade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'browser-detect';
    }
}
