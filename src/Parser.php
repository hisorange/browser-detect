<?php

namespace hisorange\BrowserDetect;

use Illuminate\Cache\CacheManager;
use Illuminate\Http\Request;
use League\Pipeline\Pipeline;

/**
 * Class Parser
 * @package hisorange\BrowserDetect
 */
class Parser implements ParserInterface
{
    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Runtime cache to reduce the parse calls.
     *
     * @var array
     */
    protected $runtime;

    /**
     * Parser constructor.
     *
     * @param CacheManager $cache
     * @param Request      $request
     */
    public function __construct(CacheManager $cache, Request $request)
    {
        $this->cache   = $cache;
        $this->request = $request;
        $this->runtime = [];
    }

    /**
     * Get a unique cache key for the user agent.
     *
     * @param  string $agent
     * @return string
     */
    protected function key($agent)
    {
        return 'bd3_' . md5($agent);
    }

    /**
     * Pipe the result through the stages.
     *
     * @param  ResultInterface $result
     * @return ResultInterface
     */
    protected function process($result)
    {
        $pipeline = new Pipeline([
            new Stages\UAParser,
            new Stages\MobileDetect,
            new Stages\Correction,
        ]);

        return $pipeline->process($result);
    }

    /**
     * @inheritDoc
     */
    public function parse($agent)
    {
        $key = $this->key($agent);

        if ( ! isset($this->runtime[$key])) {
            $this->runtime[$key] = $this->cache->remember($key, 10080, function () use ($agent) {
                return $this->process(new Result($agent));
            });
        }

        return $this->runtime[$key];
    }

    /**
     * @inheritDoc
     */
    public function detect()
    {
        return $this->parse($this->request->userAgent());
    }

    /**
     * Reflect calls to the result object.
     *
     * @throws \InvalidArgumentException
     *
     * @param  string $method
     * @param  array  $params
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        $result = $this->detect();

        // Reflect a parsed value.
        if ($result->offsetExists($method)) {
            return $result->offsetGet($method);
        }

        // Reflect a method.
        if (method_exists($result, $method)) {
            return call_user_func_array([$result, $method], $params);
        }

        throw new \InvalidArgumentException(sprintf('%s method does not exists on the %s object.', $method, Result::class));
    }
}
