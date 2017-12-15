<?php

namespace hisorange\BrowserDetect\Contracts;

interface PayloadInterface
{
    /**
     * Initialize the payload for process.
     *
     * @param string $agent
     */
    public function __construct($agent);

    /**
     * Each stage can access the user agent through this interface.
     *
     * @return string
     */
    public function getAgent();

    /**
     * Set a value in the temporary data schema before the next stage.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setValue($key, $value);

    /**
     * Get a value if exists in the data schema, returns null if the key does not exists.
     *
     * @param  sting $key
     * @return mixed
     */
    public function getValue($key);

    /**
     * Immutable accessor to the internal data collection.
     *
     * @return array
     */
    public function toArray();
}