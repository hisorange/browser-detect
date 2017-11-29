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

    /**
     * Import a result from a compact string format to the object.
     * Split and merge with the schema. Also convert the is* values back to boolean.
     *
     * @param  string $string
     *
     * @return self
     */
    public function importFromString($string);
}