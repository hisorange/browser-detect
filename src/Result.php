<?php

namespace hisorange\BrowserDetect;

use function array_merge;
use const JSON_NUMERIC_CHECK;
use JsonSerializable;

/**
 * Class Result
 * @package hisorange\BrowserDetect
 */
class Result implements ResultInterface, JsonSerializable
{
    /**
     * @var array
     */
    protected $attributes = [
        // Original user agent string.
        'agent'               => 'UnknownBrowser',
        // Device's kind.
        'isMobile'            => false, // bool
        'isTablet'            => false, // bool
        'isDesktop'           => false, // bool
        // Visitor's purpose.
        'isBot'               => false, // bool
        // Browsing software.
        'browserFamily'       => '', // string
        'browserVersionMajor' => 0,  // int
        'browserVersionMinor' => 0,  // int
        'browserVersionPatch' => 0,  // int
        // Operating software.
        'osFamily'            => '', // string
        'osVersionMajor'      => 0,  // int
        'osVersionMinor'      => 0,  // int
        'osVersionPatch'      => 0,  // int
        // Device's hardware.
        'deviceFamily'        => '', // string
        'deviceModel'         => '', // string
        'mobileGrade'         => '', // string
        // Browser's capability.
        'cssVersion'          => 0,  // int
        // Javascript support.
        'javaScriptSupport'   => true, // bool
    ];

    /**
     * @inheritDoc
     */
    public function __construct($agent)
    {
        if ( ! empty($agent)) {
            $this->attributes['agent'] = $agent;
        }
    }

    /**
     * @inheritDoc
     */
    public function extend(array $extension)
    {
        foreach ($extension as $key => $value) {
            if ($value !== null) {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function __sleep()
    {
        return ['attributes'];
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return json_encode($this->attributes, JSON_NUMERIC_CHECK);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return array_key_exists($this->attributes, $offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }
}
