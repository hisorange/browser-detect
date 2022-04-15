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
final class Parser implements ParserInterface
{
    /**
     * @var CacheManager|null
     */
    protected $cache;

    /**
     * @var Request|null
     */
    protected $request;

    /**
     * Runtime cache to reduce the parse calls.
     *
     * @var array
     */
    protected $runtime;

    /**
     * Parsing configurations.
     *
     * @var array
     */
    protected $config;

    /**
     * Singleton used in standalone mode.
     *
     * @var self|null
     */
    protected static $instance;

    /**
     * Parser constructor.
     *
     * @param CacheManager $cache
     * @param Request      $request
     * @param array        $config
     */
    public function __construct($cache = null, $request = null, array $config = [])
    {
        if ($cache !== null) {
            $this->cache   = $cache;
        }

        if ($request !== null) {
            $this->request = $request;
        }

        $this->config = array_replace_recursive(
            require(__DIR__ . '/../config/browser-detect.php'),
            $config
        );

        $this->runtime = [];
    }

    /**
     * Read the applied final config.
     *
     * @return array
     */
    public function config(): array
    {
        return $this->config;
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
            /* @phpstan-ignore-next-line */
            return call_user_func_array([$result, $method], $params);
        }

        throw new BadMethodCallException(
            sprintf('%s method does not exists on the %s object.', $method, ResultInterface::class)
        );
    }

    /**
     * Acts as a facade, but proxies all the call to a singleton.
     *
     * @param string $method
     * @param array $params
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $params)
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        /* @phpstan-ignore-next-line */
        return call_user_func_array([static::$instance, $method], $params);
    }

    /**
     * @inheritdoc
     */
    public function detect(): ResultInterface
    {
        // Cuts the agent string at 2048 byte, anything longer will be a DoS attack.
        $userAgentString = substr(
            $this->getUserAgentString(),
            0,
            $this->config['security']['max-header-length']
        );

        return $this->parse($userAgentString);
    }

    /**
     * Wrapper around the request accessor, in standalone mode
     * the fn will use the $_SERVER global.
     *
     * @return string
     */
    protected function getUserAgentString(): string
    {
        if ($this->request !== null) {
            return $this->request->userAgent() ?? '';
        } else {
            return isset($_SERVER['HTTP_USER_AGENT']) ? ((string) $_SERVER['HTTP_USER_AGENT']) : '';
        }
    }

    /**
     * @inheritdoc
     */
    public function parse(string $agent): ResultInterface
    {
        $key = $this->makeHashKey($agent);

        if (!isset($this->runtime[$key])) {
            // In standalone mode You can run the parser without cache.
            if ($this->cache !== null) {
                $result = $this->cache->remember(
                    $key,
                    $this->config['cache']['interval'],
                    function () use ($agent) {
                        return $this->process($agent);
                    }
                );
            } else {
                $result = $this->process($agent);
            }

            $this->runtime[$key] = $result;
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
        return $this->config['cache']['prefix'] . md5($agent);
    }

    /**
     * Pipe the payload through the stages.
     *
     * @param  string $agent
     * @return ResultInterface
     */
    protected function process(string $agent): ResultInterface
    {
        return (new Pipeline())
            ->pipe(new Stages\UAParser())
            ->pipe(new Stages\MobileDetect())
            ->pipe(new Stages\CrawlerDetect())
            ->pipe(new Stages\DeviceDetector())
            ->pipe(new Stages\BrowserDetect())
            ->process(new Payload($agent));
    }
}
