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
    public function userAgent(): string;

    /**
     * Is this a mobile device.
     *
     * @return bool
     */
    public function isMobile(): bool;

    /**
     * Is this a tablet device.
     *
     * @return bool
     */
    public function isTablet(): bool;

    /**
     * Is this a desktop computer.
     *
     * @return bool
     */
    public function isDesktop(): bool;

    /**
     * Is this a crawler / bot.
     *
     * @return bool
     */
    public function isBot(): bool;

    /**
     * Is this a Chrome or Chromium browser?
     *
     * @return bool
     */
    public function isChrome(): bool;

    /**
     * Is this a Chrome or Chromium browser?
     *
     * @return bool
     */
    public function isFirefox(): bool;

    /**
     * Is this a Chrome or Chromium browser?
     *
     * @return bool
     */
    public function isOpera(): bool;

    /**
     * Is this a Chrome or Chromium browser?
     *
     * @return bool
     */
    public function isSafari(): bool;

    /**
     * Is this browser an Microsoft Edge?
     *
     * @return bool
     */
    public function isEdge(): bool;

    /**
     * Is this browser an Internet Explorer?
     *
     * @return bool
     */
    public function isIE(): bool;

    /**
     * Is this browser an android in app browser?
     *
     * @return bool
     */
    public function isInApp(): bool;

    /**
     * Is this an Internet Explorer X (or lower version).
     *
     * @param integer $version
     * @param string  $operator
     *
     * @return bool
     */
    public function isIEVersion(int $version, string $operator = '='): bool;

    /**
     * Build a human readable browser name: Internet Explorer 7, Firefox 3.6
     *
     * @return string
     */
    public function browserName(): string;

    /**
     * Browser's vendor like Chrome, Firefox, Opera.
     *
     * @return string
     */
    public function browserFamily(): string;

    /**
     * Build human readable browser version. (cuts the trailing .0 parts)
     *
     * @return string
     */
    public function browserVersion(): string;

    /**
     * Browser's semantic major version.
     *
     * @return int
     */
    public function browserVersionMajor(): int;

    /**
     * Browser's semantic minor version.
     *
     * @return int
     */
    public function browserVersionMinor(): int;

    /**
     * Browser's semantic patch version.
     *
     * @return int
     */
    public function browserVersionPatch(): int;

    /**
     * Browser's rendering engine.
     *
     * @return string
     */
    public function browserEngine(): string;

    /**
     * Operating system's human friendly name like Windows XP, MacOS 10.
     *
     * @return string
     */
    public function platformName(): string;

    /**
     * Operating system's vendor like Linux, Windows, MacOS.
     *
     * @return string
     */
    public function platformFamily(): string;

    /**
     * Build human readable os version. (cuts the trailing .0 parts)
     *
     * @return string
     */
    public function platformVersion(): string;

    /**
     * Operating system's semantic major version.
     *
     * @return int
     */
    public function platformVersionMajor(): int;

    /**
     * Operating system's semantic minor version.
     *
     * @return int
     */
    public function platformVersionMinor(): int;

    /**
     * Operating system's semantic patch version.
     *
     * @return int
     */
    public function platformVersionPatch(): int;

    /**
     * Is this a windows operating system?
     *
     * @return bool
     */
    public function isWindows(): bool;

    /**
     * Is this a linux operating system?
     *
     * @return bool
     */
    public function isLinux(): bool;

    /**
     * Is this a mac operating system?
     *
     * @return bool
     */
    public function isMac(): bool;

    /**
     * Is this an android operating system?
     *
     * @return bool
     */
    public function isAndroid(): bool;

    /**
     * Device's vendor like Samsung, Apple, Huawei.
     *
     * @return string
     */
    public function deviceFamily(): string;

    /**
     * Device's brand name like iPad, iPhone, Nexus.
     *
     * @return string
     */
    public function deviceModel(): string;

    /**
     * Device's mobile grade in scale of A,B,C for performance.
     *
     * @return string
     */
    public function mobileGrade(): string;

    /**
     * Export the result's data into an array.
     *
     * @return array
     */
    public function toArray();
}
