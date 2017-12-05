<?php

namespace hisorange\BrowserDetect;

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
        'userAgent'           => 'UnknownBrowser',
        // Device's kind.
        'isMobile'            => false, // bool
        'isTablet'            => false, // bool
        'isDesktop'           => false, // bool
        // Visitor's purpose.
        'isBot'               => false, // bool
        // Browsing software.
        'browserFamily'       => 'UnknownBrowserFamily', // string
        'browserVersionMajor' => 0,  // int
        'browserVersionMinor' => 0,  // int
        'browserVersionPatch' => 0,  // int
        // Operating software.
        'osFamily'            => 'UnknownOS', // string
        'osVersionMajor'      => 0,  // int
        'osVersionMinor'      => 0,  // int
        'osVersionPatch'      => 0,  // int
        // Device's hardware.
        'deviceFamily'        => 'UnknownDeviceFamily', // string
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
            $this->attributes['userAgent'] = 'UnknownBrowser';
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
