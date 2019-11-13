<?php

namespace hisorange\BrowserDetect\Contracts;

use JsonSerializable;

/**
 * Interface ResultInterface
 *
 * @package hisorange\BrowserDetect
 */
interface ResultInterface extends JsonSerializable
{
    /**
     * Initialize the result object with a processed payload.
     *
     * @param array $result
     */
    public function __construct(array $result);

    /**
     * Get the original user agent string.
     *
     * @return string
     */
    public function userAgent();

    /**
     * Is this a mobile device.
     *
     * @return bool
     */
    public function isMobile();

    /**
     * Is this a tablet device.
     *
     * @return bool
     */
    public function isTablet();

    /**
     * Is this a desktop computer.
     *
     * @return bool
     */
    public function isDesktop();

    /**
     * Is this a crawler / bot.
     *
     * @return bool
     */
    public function isBot();

    /**
     * Is this a Chrome or Chromium browser?
     *
     * @return bool
     */
    public function isChrome();

    /**
     * Is this a Chrome or Chromium browser?
     *
     * @return bool
     */
    public function isFirefox();

    /**
     * Is this a Chrome or Chromium browser?
     *
     * @return bool
     */
    public function isOpera();

    /**
     * Is this a Chrome or Chromium browser?
     *
     * @return bool
     */
    public function isSafari();

    /**
     * Is this browser an Microsoft Edge?
     *
     * @return bool
     */
    public function isEdge();

    /**
     * Is this browser an Internet Explorer?
     *
     * @return bool
     */
    public function isIE();

    /**
     * Is this an Internet Explorer X (or lower version).
     *
     * @param  integer $version
     * @param  string  $operator
     *
     * @return mixed
     */
    public function isIEVersion($version, $operator = '=');

    /**
     * Build a human readable browser name: Internet Explorer 7, Firefox 3.6
     *
     * @return string
     */
    public function browserName();

    /**
     * Browser's vendor like Chrome, Firefox, Opera.
     *
     * @return string
     */
    public function browserFamily();

    /**
     * Build human readable browser version. (cuts the trailing .0 parts)
     *
     * @return string
     */
    public function browserVersion();

    /**
     * Browser's semantic major version.
     *
     * @return int
     */
    public function browserVersionMajor();

    /**
     * Browser's semantic minor version.
     *
     * @return int
     */
    public function browserVersionMinor();

    /**
     * Browser's semantic patch version.
     *
     * @return int
     */
    public function browserVersionPatch();

    /**
     * Browser's rendering engine.
     *
     * @return string
     */
    public function browserEngine();

    /**
     * Operating system's human friendly name like Windows XP, MacOS 10.
     *
     * @return string
     */
    public function platformName();

    /**
     * Operating system's vendor like Linux, Windows, MacOS.
     *
     * @return string
     */
    public function platformFamily();

    /**
     * Build human readable os version. (cuts the trailing .0 parts)
     *
     * @return string
     */
    public function platformVersion();

    /**
     * Operating system's semantic major version.
     *
     * @return int
     */
    public function platformVersionMajor();

    /**
     * Operating system's semantic minor version.
     *
     * @return int
     */
    public function platformVersionMinor();

    /**
     * Operating system's semantic patch version.
     *
     * @return int
     */
    public function platformVersionPatch();

    /**
     * Device's vendor like Samsung, Apple, Huawei.
     *
     * @return string
     */
    public function deviceFamily();

    /**
     * Device's brand name like iPad, iPhone, Nexus.
     *
     * @return string
     */
    public function deviceModel();

    /**
     * Device's mobile grade in scale of A,B,C for performance.
     *
     * @return string
     */
    public function mobileGrade();

    /**
     * Export the result's data into an array.
     *
     * @return array
     */
    public function toArray();
}
