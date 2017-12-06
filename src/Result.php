<?php

namespace hisorange\BrowserDetect;

use hisorange\BrowserDetect\Contracts\ResultInterface;

/**
 * Class Result
 * @package hisorange\BrowserDetect
 */
class Result implements ResultInterface
{
    protected $userAgent = 'Unknown';
    protected $isMobile = false;
    protected $isTablet = false;
    protected $isDesktop = false;
    protected $isBot = false;
    protected $browserFamily = 'Unknown';
    protected $browserVersionMajor = 0;
    protected $browserVersionMinor = 0;
    protected $browserVersionPatch = 0;
    protected $osFamily = 'Unknown';
    protected $osVersionMajor = 0;
    protected $osVersionMinor = 0;
    protected $osVersionPatch = 0;
    protected $deviceFamily = 'Unknown';
    protected $deviceModel = '';
    protected $mobileGrade = '';
    protected $isChrome;
    protected $isFirefox;
    protected $isOpera;
    protected $isSafari;
    protected $isIE;

    /**
     * @inheritdoc
     */
    public function __construct(array $result)
    {
        foreach ($result as $property => $value) {
            if ($value !== null) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * @inheritdoc
     */
    public function browserName()
    {
        return trim($this->attributes['browserFamily'] . ' ' . $this->browserVersion());
    }

    /**
     * @inheritdoc
     */
    public function browserVersion()
    {
        return $this->trimVersion(
            implode('.', [
                $this->attributes['browserVersionMajor'],
                $this->attributes['browserVersionMinor'],
                $this->attributes['browserVersionPatch'],
            ])
        );
    }

    /**
     * Trim the trailing .0 versions from a semantic version string.
     * It makes it more readable for an end user.
     *
     * @param  string $version
     *
     * @return string
     */
    protected function trimVersion($version)
    {
        return preg_replace('%(^0.0.0$|\.0\.0$|\.0$)%', '', $version);
    }

    /**
     * @inheritdoc
     */
    public function osName()
    {
        return trim($this->attributes['osFamily'] . ' ' . $this->osVersion());
    }

    /**
     * @inheritdoc
     */
    public function osVersion()
    {
        return $this->trimVersion(
            sprintf(
                '%d.%d.%d',
                $this->attributes['osVersionMajor'],
                $this->attributes['osVersionMinor'],
                $this->attributes['osVersionPatch']
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function isChrome()
    {
        return false !== stripos($this->attributes['browserFamily'], 'chrom');
    }

    /**
     * @inheritdoc
     */
    public function isFirefox()
    {
        return false !== stripos($this->attributes['browserFamily'], 'firefox');
    }

    /**
     * @inheritdoc
     */
    public function isOpera()
    {
        return false !== stripos($this->attributes['browserFamily'], 'opera');
    }

    /**
     * @inheritdoc
     */
    public function isSafari()
    {
        return false !== stripos($this->attributes['browserFamily'], 'safari');
    }

    /**
     * @inheritdoc
     */
    public function isIEVersion($version, $operator = '=')
    {
        return $this->isIE() && version_compare($this->attributes['browserVersionMajor'], $version, $operator);
    }

    /**
     * @inheritdoc
     */
    public function isIE()
    {
        return (
            false !== stripos($this->attributes['browserFamily'], 'explorer') ||
            false !== stripos($this->attributes['browserFamily'], 'ie') ||
            false !== stripos($this->attributes['browserFamily'], 'trident')
        );
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->attributes;
    }

    /**
     * Export data as json string.
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
