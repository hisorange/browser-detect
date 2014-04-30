<?php
namespace hisorange\BrowserDetect;

use hisorange\Traits\RunTimeCache;
use hisorange\Traits\ObjectConfig;
use hisorange\Traits\PluginCollection;
use Illuminate\Foundation\Application;

class Parser {

	/**
	 * @since 1.0.0 The package requirements now allows the usage of traits.
	 */
	use RunTimeCache, ObjectConfig, PluginCollection;

	/**
	 * @since 1.0.0 Store the application on the object.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Default data schema, this keys are always presents in the result even if the values are unsetted.
	 * Also this schema value's types are used in fixing the result values.
	 *
	 * @since 1.0.0 Variable renamed from $schema to $dataSchema.
	 *
	 * @var array
	 */
	public static $dataSchema	= [
		// Device's kind.
		'isMobile'				=> false, // bool
		'isTablet'				=> false, // bool
		'isDesktop'				=> false, // bool

		// Visitor's purpose.
		'isBot'					=> false, // bool

		// Browsing software.
		'browserFamily'			=> '', // string
		'browserVersionMajor' 	=> 0,  // integer
		'browserVersionMinor' 	=> 0,  // integer
		'browserVersionPatch' 	=> 0,  // integer

		// Operating software.
		'osFamily' 				=> '', // string
		'osVersionMajor'		=> 0,  // integer
		'osVersionMinor'		=> 0,  // integer
		'osVersionPatch'		=> 0,  // integer

		// Device's hardware.
		'deviceFamily' 			=> '', // string
		'deviceModel'  			=> '', // string
		'mobileGrade'  			=> '', // string

		// Browser's capability.
		'cssVersion'   			=> 0,  // integer

		// Javascript support. (The default value is true because most of the browser supporting
		// the js but only the phpbrowscap plugin can analyze it.)
		'javaScriptSupport'		=> true, // bool
	];

	/**
	 * @since 1.0.0 Initialization excepts the Application.
	 *
	 * @param  \Illuminate\Foundation\Application $app
	 * @return void
	 */
	public function __construct(Application $app)
	{
		// Store the application.
		$this->app 		= $app;

		// Import the package configuration to the parser object.
		$this->objectConfigImport($this->app['config']['browser-detect::config']);

		// Import the plugins.
		$this->pluginCollectionImport($this->app['config']['browser-detect::plugins']);
	}

	/**
	 * @since 1.0.0 Get an empty information schema array.
	 *
	 * @return array
	 */
	public static function getEmptyDataSchema()
	{
		return static::$dataSchema;
	}

	/**
	 * @since 1.0.0 Create an empty result object.
	 *
	 * @return mixed
	 */
	public function getEmptyResult()
	{
		return $this->app->make('browser-detect.result');
	}

	/**
	 * @since 1.0.0 Determine the CURRENT visitor's user agent string.
	 * CLI or browser without user agent header uses the generic agent. (from the config)
	 *
	 * @return string
	 */
	public function visitorUserAgent()
	{
		return $this->app['request']->server('HTTP_USER_AGENT', $this->objectConfig['generic']['agent']);
	}

	/**
	 * Get the reflected user agent's informations.
	 * If null is setted then the detector will use the CURRENT visitor's agent.
	 * If cache interval setted to 0 the func skip to load the cache. (in the config)
	 *
	 * @since 1.0.0
	 *
	 * @param  string|null $userAgent User agent HTTP header.
	 * @return \hisorange\BrowserDetect\Result
	 */
	public function detect($userAgent = null)
	{
		// Use fallback values for the user agent.
		$userAgent  	= $userAgent ?: $this->visitorUserAgent();

		// Generate cache key for a user-agent string.
		$key 			=  $this->hashUserAgentString($userAgent);

		// First check the runtime cache.
		if ($this->runTimeCacheExists($key)) {
			return $this->runTimeCacheGet($key);
		}

		// Only use cache if the interval is not 0.
		if ($this->objectConfig['cache']['interval']) {

			// Fetch the cached result which we store in compact string format.
			$cachedResult 	= $this->app['cache']->remember($key, $this->objectConfig['cache']['interval'], function() use ($userAgent) {
				return $this->parse($userAgent)->toString();
			});

			// Convert the result back to an object.
			$result 		= $this->getEmptyResult()->importFromString($cachedResult);
		} else {
			$result 		= $this->parse($userAgent);
		}

		// Save the result into the runtime cache.
		$this->runTimeCacheSet($key, $result);

		return $result;
	}

	/**
	 * @since 1.0.0 Hash the user-agent string. Function being seperated from the code to be more flexible.
	 *
	 * @param  string $userAgent User agent string.
	 * @return string Hashed and prefixed key.
	 */
	public function hashUserAgentString($userAgent)
	{
		return $this->objectConfig['cache']['prefix'] . '_' . md5($userAgent);
	}

	/**
	 * Parse the user agent with the plugin(s) and generate a summarized result.
	 * 
	 * @since 1.0.0 function renamed to 'parse' from '_parse' and only calling plugins from now.
	 * @since 0.9.0
	 *
	 * @param  string $userAgent
	 * @return \hisorange\BrowserDetect\Result
	 */
	public function parse($userAgent)
	{
		// Create a base schema.
		$result 		= $this->getEmptyDataSchema();

		foreach ($this->pluginCollectionExport() as $plugin => $config) {

			// Create the plugin instance.
			$pluginInstance		= new $plugin;

			// Inject the plugin config.
			$pluginInstance->objectConfigImport((array) $config);

			// Merge the result with the schema.
			$result 	= array_merge($result, $pluginInstance->filter( $pluginInstance->parse($userAgent) ));
		}

		// Fix where different packages define devices differently.
		$result['isDesktop'] 	= ! (bool) ($result['isMobile'] + $result['isTablet']);
		$result['isTablet'] 	= ! (bool) ($result['isMobile'] + $result['isDesktop']);
		$result['isMobile']		= ! (bool) ($result['isTablet'] + $result['isDesktop']);

		// Fixing empty operating system with a generic value.
		$result['osFamily']			= $result['osFamily'] ?: $this->objectConfig['generic']['operatingsystem'];

		// Fixing empty browser family with generic value.
		$result['browserFamily']	= $result['browserFamily'] ?: $this->objectConfig['generic']['browser'];

		// Common name for Internet Explorer.
		$result['browserFamily'] 	= preg_match('%^(IE|MSIE)%', $result['browserFamily']) ? 'Internet Explorer' : $result['browserFamily'];

		return $this->getEmptyResult()->importFromArray($result);
	}

	/**
	 * Reflect calls to the result object.
	 *
	 * @throws \hisorange\BrowserDetect\Exceptions\InvalidCallException if the called method
	 * do not exists in the attributes array, or not a method of the result object.
	 *
	 * @param  string $method
	 * @param  array $params
	 * @return mixed
	 */
	public function __call($method, $params)
	{
		// When calling BrowserDetect::importFromString() etc.
		// then direcly provide a new result object.
		if (substr($method, 0, 6) == 'import') {
			return call_user_func_array(array($this->getEmptyResult(), $method), $params);
		}

		$reflection 	= $this->detect(null);

		// Reflect an information.
		if ($reflection->offsetExists($method)) {
			return $reflection->offsetGet($method);
		}

		// Reflect a method.
		if (method_exists($reflection, $method)) {
			return call_user_func_array(array($reflection, $method), $params);
		}

		throw new Exceptions\InvalidCallException($method . 'does not exists on the ' . get_class($reflection) . ' object.');
	}
}