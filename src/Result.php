<?php

namespace hisorange\BrowserDetect;
use hisorange\BrowserDetect\Contracts\ResultInterface;
use function stripos;
use function version_compare;

/**
 * Class Result
 * @package hisorange\BrowserDetect
 */
class Result implements ResultInterface
{
    /**
     * @var array
     */
    protected $attributes = [
        // Original user agent string.
        'userAgent'           => 'Unknown',
        // Device's kind.
        'isMobile'            => false, // bool
        'isTablet'            => false, // bool
        'isDesktop'           => false, // bool
        // Visitor's purpose.
        'isBot'               => false, // bool
        // Browsing software.
        'browserFamily'       => 'Unknown', // string
        'browserVersionMajor' => 0,  // int
        'browserVersionMinor' => 0,  // int
        'browserVersionPatch' => 0,  // int
        // Operating software.
        'osFamily'            => 'Unknown', // string
        'osVersionMajor'      => 0,  // int
        'osVersionMinor'      => 0,  // int
        'osVersionPatch'      => 0,  // int
        // Device's hardware.
        'deviceFamily'        => 'Unknown', // string
        'deviceModel'         => '', // string
        'mobileGrade'         => '', // string
    ];

    /**
     * @inheritdoc
     */
    public function __construct($userAgent = null)
    {
        $this->setUserAgent($userAgent);
    }

    /**
     * @inheritdoc
     */
    public function setUserAgent($userAgent)
    {
        if (isset($userAgent)) {
            $this->attributes['userAgent'] = $userAgent;
        } else {
            $this->attributes['userAgent'] = 'Unknown';
        }
    }

    /**
     * @inheritdoc
     */
    public function getUserAgent()
    {
        return $this->attributes['userAgent'];
    }

    /**
     * @inheritdoc
     */
    public function extend(array $extension)
    {
        foreach ($extension as $key => $value) {
            if ($value !== null) {
                $this->offsetSet($key, $value);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $this->attributes[$offset] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->attributes);
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
                $this->attributes['browserVersionPatch']
            ])
        );
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
    public function isIEVersion($version, $operator = '=')
    {
        return $this->isIE() && version_compare($this->attributes['browserVersionMajor'], $version, $operator);
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
     * Export attributes for serialization.
     */
    public function __sleep()
    {
        return ['attributes'];
    }

    /**
     * Export data as json string.
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->attributes;
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }
}
