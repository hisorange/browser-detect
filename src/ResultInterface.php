<?php

namespace hisorange\BrowserDetect;

// PHP SPL.
use ArrayAccess;

/**
 * Interface ResultInterface
 *
 * @package hisorange\BrowserDetect
 */
interface ResultInterface extends ArrayAccess
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
}