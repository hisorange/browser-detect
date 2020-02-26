<?php

namespace hisorange\BrowserDetect;

use Illuminate\Http\Request;
use League\Pipeline\Pipeline;
use Illuminate\Cache\CacheManager;
use hisorange\BrowserDetect\Contracts\ParserInterface;
use hisorange\BrowserDetect\Contracts\ResultInterface;
use hisorange\BrowserDetect\Exceptions\BadMethodCallException;

/**
 * Manages the parsing mechanism.
 *
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
     * Reflect calls to the result object.
     *
     * @throws \hisorange\BrowserDetect\Exceptions\BadMethodCallException
     *
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    public function __call(string $method, array $params)
    {
        $result = $this->detect();

        // Reflect a method.
        if (method_exists($result, $method)) {
            return call_user_func_array([$result, $method], $params);
        }

        throw new BadMethodCallException(
            sprintf('%s method does not exists on the %s object.', $method, ResultInterface::class)
        );
    }

    /**
     * @inheritdoc
     */
    public function detect(): ResultInterface
    {
        // Cuts the agent string at 2048 byte, anything longer will be a DoS attack.
        $userAgentStringRaw = $this->request->server('HTTP_USER_AGENT');

        if (is_string($userAgentStringRaw)) {
            $userAgentString = substr($userAgentStringRaw, 0, 2048);
        } else {
            $userAgentString = 'Unknown';
        }

        return $this->parse($userAgentString);
    }

    /**
     * @inheritdoc
     */
    public function parse(string $agent): ResultInterface
    {
        $key = $this->makeHashKey($agent);

        if (! isset($this->runtime[$key])) {
            $this->runtime[$key] = $this->cache->remember(
                $key,
                10080,
                function () use ($agent) {
                    return $this->process($agent);
                }
            );
        }

        return $this->runtime[$key];
    }

    /**
     * Create a unique cache key for the user agent.
     *
     * @param  string $agent
     * @return string
     */
    protected function makeHashKey(string $agent): string
    {
        return 'bd4_' . md5($agent);
    }

    /**
     * Pipe the payload through the stages.
     *
     * @param  string $agent
     * @return ResultInterface
     */
    protected function process(string $agent): ResultInterface
    {
        $pipeline = new Pipeline(
            [
            new Stages\UAParser(),
            new Stages\MobileDetect(),
            new Stages\CrawlerDetect(),
            new Stages\DeviceDetector(),
            new Stages\BrowserDetect(),
            ]
        );

        return $pipeline->process(new Payload($agent));
    }
}
