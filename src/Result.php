<?php

namespace hisorange\BrowserDetect;

use hisorange\BrowserDetect\Contracts\ResultInterface;

/**
 * Class Result
 *
 * @package hisorange\BrowserDetect
 */
class Result implements ResultInterface
{
    /**
     * @var string
     */
    protected $userAgent = 'Unknown';

    /**
     * @var bool
     */
    protected $isMobile = false;

    /**
     * @var bool
     */
    protected $isTablet = false;

    /**
     * @var bool
     */
    protected $isDesktop = false;

    /**
     * @var bool
     */
    protected $isBot = false;

    /**
     * @var bool
     */
    protected $isChrome = false;

    /**
     * @var bool
     */
    protected $isFirefox = false;

    /**
     * @var bool
     */
    protected $isOpera = false;

    /**
     * @var bool
     */
    protected $isSafari = false;

    /**
     * @var bool
     */
    protected $isIE = false;

    /**
     * @var string
     */
    protected $browserFamily = 'Unknown';

    /**
     * @var int
     */
    protected $browserVersionMajor = 0;

    /**
     * @var int
     */
    protected $browserVersionMinor = 0;

    /**
     * @var int
     */
    protected $browserVersionPatch = 0;

    /**
     * @var string
     */
    protected $osFamily = 'Unknown';

    /**
     * @var int
     */
    protected $osVersionMajor = 0;

    /**
     * @var int
     */
    protected $osVersionMinor = 0;

    /**
     * @var int
     */
    protected $osVersionPatch = 0;

    /**
     * @var string
     */
    protected $deviceFamily = 'Unknown';

    /**
     * @var string
     */
    protected $deviceModel = '';

    /**
     * @var string
     */
    protected $mobileGrade = '';

    /**
     * @inheritdoc
     */
    public function __construct(array $result)
    {
        foreach ($result as $property => $value) {
            $this->$property = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function userAgent()
    {
        return $this->userAgent;
    }

    /**
     * @inheritdoc
     */
    public function isMobile()
    {
        return $this->isMobile;
    }

    /**
     * @inheritdoc
     */
    public function isTablet()
    {
        return $this->isTablet;
    }

    /**
     * @inheritdoc
     */
    public function isDesktop()
    {
        return $this->isDesktop;
    }

    /**
     * @inheritdoc
     */
    public function isBot()
    {
        return $this->isBot;
    }

    /**
     * @inheritdoc
     */
    public function isChrome()
    {
        return $this->isChrome;
    }

    /**
     * @inheritdoc
     */
    public function isFirefox()
    {
        return $this->isFirefox;
    }

    /**
     * @inheritdoc
     */
    public function isOpera()
    {
        return $this->isOpera;
    }

    /**
     * @inheritdoc
     */
    public function isSafari()
    {
        return $this->isSafari;
    }

    /**
     * @inheritdoc
     */
    public function isIE()
    {
        return $this->isIE;
    }

    /**
     * @inheritdoc
     */
    public function isIEVersion($version, $operator = '=')
    {
        return ($this->isIE && version_compare($version, $this->browserVersion(), $operator));
    }

    /**
     * @inheritdoc
     */
    public function browserName()
    {
        return $this->browserName;
    }

    /**
     * @inheritdoc
     */
    public function browserFamily()
    {
        return $this->browserFamily;
    }

    /**
     * @inheritdoc
     */
    public function browserVersion()
    {
        return $this->browserVersion;
    }

    /**
     * @inheritdoc
     */
    public function browserVersionMajor()
    {
        return $this->browserVersionMajor;
    }

    /**
     * @inheritdoc
     */
    public function browserVersionMinor()
    {
        return $this->browserVersionMinor;
    }

    /**
     * @inheritdoc
     */
    public function browserVersionPatch()
    {
        return $this->browserVersionPatch;
    }

    /**
     * @inheritdoc
     */
    public function osName()
    {
        return $this->osName;
    }

    /**
     * @inheritdoc
     */
    public function osFamily()
    {
        return $this->osFamily;
    }

    /**
     * @inheritdoc
     */
    public function osVersion()
    {
        return $this->osVersion;
    }

    /**
     * @inheritdoc
     */
    public function osVersionMajor()
    {
        return $this->osVersionMajor;
    }

    /**
     * @inheritdoc
     */
    public function osVersionMinor()
    {
        return $this->osVersionMinor;
    }

    /**
     * @inheritdoc
     */
    public function osVersionPatch()
    {
        return $this->osVersionPatch;
    }

    /**
     * @inheritdoc
     */
    public function deviceFamily()
    {
        return $this->deviceFamily;
    }

    /**
     * @inheritdoc
     */
    public function deviceModel()
    {
        return $this->deviceModel;
    }

    /**
     * @inheritdoc
     */
    public function mobileGrade()
    {
        return $this->mobileGrade;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return (array) $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
