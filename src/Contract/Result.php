<?php

namespace hisorange\BrowserDetect\Contract;

// PHP SPL.
use ArrayAccess;

/**
 * Interface for parser results.
 *
 * @package hisorange\BrowserDetect\Contract
 */
interface Result extends ArrayAccess
{
    /**
     * Import a result from an array to the object.
     * Sniff out if the array has named keys or need to merge with the schema.
     *
     * @param  array $array
     *
     * @return self
     */
    public function importFromArray(array $array);
}