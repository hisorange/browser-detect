<?php

namespace hisorange\BrowserDetect;

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
     * @param string $agent
     */
    public function __construct($agent);

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
}