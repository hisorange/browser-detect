<?php

namespace hisorange\BrowserDetect\Contracts;

// PHP SPL.
use ArrayAccess;
use JsonSerializable;

/**
 * Interface ResultInterface
 *
 * @package hisorange\BrowserDetect
 */
interface ResultInterface extends ArrayAccess, JsonSerializable
{
    /**
     * Initialize the result with the user agent string.
     *
     * @param string $userAgent
     */
    public function __construct($userAgent);

    /**
     * Set the user agent string.
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent);

    /**
     * @return string
     */
    public function getUserAgent();

    /**
     * Extend the attributes with the given extension, overwrite existing ones.
     *
     * @param  array $extension
     * @return void
     */
    public function extend(array $extension);

    /**
     * Export the result's data into an array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Build a human readable browser name: Internet Explorer 7, Firefox 3.6
     *
     * @return string
     */
    public function browserName();

    /**
     * Build human readable browser version. (cuts the trailing .0 parts)
     *
     * @return string
     */
    public function browserVersion();

    /**
     * Build a human readable os name: Windows 7, Windows XP, Android OS 2.3.6
     *
     * @return string
     */
    public function osName();

    /**
     * Build human readable os version. (cuts the trailing .0 parts)
     *
     * @return string
     */
    public function osVersion();

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


}