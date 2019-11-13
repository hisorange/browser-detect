<?php
namespace hisorange\BrowserDetect;

use hisorange\BrowserDetect\Contracts\PayloadInterface;

/**
 * This class is passed down in the pipeline
 * and each stage makes the changes on this
 * state carrier object.
 *
 * @package hisorange\BrowserDetect
 */
class Payload implements PayloadInterface
{
    /**
     * @var string
     */
    protected $agent;

    /**
     * @var array
     */
    protected $store = [];

    /**
     * @inheritdoc
     */
    public function __construct($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @inheritdoc
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @inheritdoc
     */
    public function getValue($key)
    {
        if (array_key_exists($key, $this->store)) {
            return $this->store[$key];
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function setValue($key, $value)
    {
        if ($value !== null) {
            $this->store[$key] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return array_merge($this->store, [
            'userAgent' => $this->agent,
        ]);
    }
}
