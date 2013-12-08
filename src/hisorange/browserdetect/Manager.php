<?php
namespace hisorange\browserdetect;

// Laravel app.
use Cache;
use Config;

// Exceptions.
use InvalidArgumentException;

// Browscap package.
use phpbrowscap\Browscap;

// UAParser package.
use UAParser\UAParser;

// Mobile Detect package.
use Detection\MobileDetect;

class Manager {

	/**
	 * The currently processed user agent.
	 *
	 * @type string
	 */
	protected $ua;

	/**
	 * Runtime cache for results.
	 *
	 * @type array
	 */
	protected $results = array();

	/**
	 * Key translations for Browscap.
	 *
	 * @type array
	 */
	static $transBrowscap = array(
		'Platform'			=> 'osFamily',
		'Browser'			=> 'browserFamily',
		'Major'				=> 'browserVersionMajor',
		'Minor'				=> 'browserVersionMinor',
		'MajorVer'			=> 'browserVersionMajor',
		'MinorVer'			=> 'browserVersionMinor',
		'CssVersion'		=> 'cssVersion',
		'Device_Name'		=> 'deviceFamily',
		'isMobileDevice'	=> 'isMobile',
		'Crawler'			=> 'isBot',
	);

	/**
	 * Default data schema, those key value pairs always presents in the result even if unsetted.
	 * Please do not change the order or if you do empty your cache, since when caching the results only saving a numeric indexed array 
	 * and when fetching back the result from cache the script combines this keys and the cached data.
	 *
	 * @type array
	 */
	static $schema 	= array(
		'userAgentString'	=> null,

		'isMobile'			=> false,
		'isTablet'			=> false,
		'isDesktop'			=> false,
		'isBot'				=> false,

		'browserFamily'		=> null,
		'browserVersionMajor' => 0,
		'browserVersionMinor' => 0,
		'browserVersionPatch' => 0,

		'osFamily' => null,
		'osVersionMajor' => 0,
		'osVersionMinor' => 0,
		'osVersionPatch' => 0,

		'deviceFamily' => null,
		'deviceModel'  => null,

		'cssVersion'   => null,

		'mobileGrade'  => null,
	);

	/**
	 * Change the processed user agent.
	 *
	 * @return $this
	 */
	public function setAgent($ua)
	{
		$this->ua = $ua;

		return $this;
	}

	/**
	 * Get the currently processed user agent string.
	 *
	 * @return string
	 */
	public function getAgent()
	{
		return $this->ua;
	}

	/**
	 * Get the analization's result for the currently processed user agent.
	 *
	 * @return hisorange\browserdetect\Info
	 */
	public function getInfo()
	{
		// If no user agent setted for processing use the current.
		if (empty($this->ua)) {
			$this->ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown Browser'; // Small strict protection.
		}

		// If the user agent not analized yet.
		if ( ! array_key_exists($this->ua, $this->results)) {
			// Try to load it from the cache if allowed.
			if (Config::get('browser-detect::cache', false)) {
				// Generate cache key by prefix_md5_hashed_user_agent_string
				$key 		= Config::get('browser-detect::cache_prefix', 'hbd_').md5($this->ua);
				// Multiply the config value to convert days to minutes, I see no reason to cache only for minutes or hours.
				$interval 	= round(Config::get('browser-detect::cache_interval') * 1440);

				// Compability for PHP 5.3 @ http://www.php.net/manual/en/functions.anonymous.php#98384
				$self 	= $this;

				// Load the datas from cache, the result now is useing numeric keys to save memory/cache.
				$cacheData = Cache::remember($key, $interval, function() use ($self) {
					return array_values($self->_parse()->data);
				});
				
				// Recreate the result from schema keys and cache datas.
				$this->results[$this->ua] = new Info($cacheData);
			} else {
				$this->results[$this->ua] = $this->_parse();	
			}
		}

		return $this->results[$this->ua];
	}

	/**
	 * Analize the user agent and create an Info object.
	 * It's only public function because of a PHP 5.3 bug, DO NOT CALL DIRECTLY !
	 * Bug description @ http://www.php.net/manual/en/functions.anonymous.php#98384
	 *
	 * @return hisorange\browserdetect\Info
	 */
	public function _parse()
	{
		// Create result schema.
		$schema 					= self::$schema;
		$schema['userAgentString'] 	= $this->ua;

		// Fetch result from Browscap.
		$browsercap = $this->fetchBrowscap();

		// Fetch result from UAParser.
		$uaparser 	= $this->fetchUAParser();

		// Fetch result from MobileDetect.
		$mobiledetect = $this->fetchMobileDetect();

		// First dump all the datas from Browscap, this is the most reliable source.
		foreach ($browsercap as $key => $value) {
			$schema[$key]	= $value;
		}

		// Fill in the missing values by the UAParser result.
		// Also allows to overwrite the *Version*, device* and os* values because the Browscap barely have version informations and only able to identify the device as PC.
		foreach ($uaparser as $key => $value) {
			if (empty($schema[$key]) or preg_match('%(^(device|os)|version)%i', $key)) {
				$schema[$key]	= $value;
			}
		}

		// Overwrite with the MobileDetect infos, because it's identifies the "device kind" even better then the others.
		foreach ($mobiledetect as $key => $value) {
			$schema[$key] = $value;
		}

		// Dance around to find the most reliable device info.
		// If any of the result said it's mobile then we belive that.
		if ($schema['isMobile']) {
			$schema['isDesktop'] = $schema['isTablet'] = false;
		} 
		// Some of the result said it's a tablet, still beliveable since the MobileDetect didn't overwrite the isMobile.
		elseif($schema['isTablet']) {
			$schema['isDesktop'] = $schema['isMobile'] = false;
		} 
		// Neither of the results said it's mobile or tablet so accept the device as a desktop.
		else {
			$schema['isMobile'] = $schema['isTablet'] = false;
			$schema['isDesktop'] = true;
		}

		// OS not found.
		if (empty($schema['osFamily'])) {
			$schema['osFamily'] = 'Default OS';
		}

		// Internet Explorer is more humanic then IE for an average user.
		if ($schema['browserFamily'] == 'IE') {
			$schema['browserFamily'] = 'Internet Explorer';
		}

		return new Info($schema);
	}

	/**
	 * Fetch informations from Browscap database.
	 *
	 * @return array
	 */
	protected function fetchBrowscap()
	{
		// Use the package's cache directory.
		if (Config::get('browser-detect::browscap_cache', null) === null) {
			$cachePath 	= dirname(dirname(__DIR__)).'/cache';
		} 
		// Use the user configured cache path.
		else {
			$cachePath 	= Config::get('browser-detect::browscap_cache');
		}

		// Initialize the Browscap with the cache directory.
		$browsercap 	= new Browscap($cachePath);

		// Fetch the informations for this user agent.
		$datas 			= $browsercap->getBrowser($this->ua, true);
		$result 		= array();
		
		// Translate data keys.
		foreach ($datas as $browscapKey => $value) {
			// Fetch only the keys which are used by our protocol.
			if (isset(self::$transBrowscap[$browscapKey]) and $value != 'unknown' and $value != 'Default Browser') {
				$result[self::$transBrowscap[$browscapKey]]	= $value;
			}
		}

		return $result;
	}

	/**
	 * Fetch informations from UAParser.
	 *
	 * @return array
	 */
	protected function fetchUAParser()
	{
		// Parse the user-agent string for versions.
		$uaparser 		= new UAParser();
		$datas 			= $uaparser->parse($this->ua);
		$result 		= array();

		// Get subs.
		$browser 		= $datas->getBrowser();
		$os 	 		= $datas->getOperatingSystem();
		$device	 		= $datas->getDevice();
		
		// Fetch browser informations, if the result is not 'Other'.
		if ($browser->getFamily() != 'Other') {
			$result['browserFamily']			= $browser->getFamily();
			$result['browserVersionMajor']		= $browser->getMajor() ?: 0;
			$result['browserVersionMinor']		= $browser->getMinor() ?: 0;
			$result['browserVersionPatch']		= $browser->getPatch() ?: 0;
		}

		// Fetch os informations, if the result is not 'Other'.
		if ($os->getFamily() != 'Other') {
			$result['osFamily']			= $os->getFamily();
			$result['osVersionMajor']		= $os->getMajor() ?: 0;
			$result['osVersionMinor']		= $os->getMinor() ?: 0;
			$result['osVersionPatch']		= $os->getPatch() ?: 0;
		}

		// Fetch device informations, if the result is not 'Other'.
		if ($device->getConstructor() != 'Other') {
			$result['deviceFamily'] = $device->getConstructor();
			$result['deviceModel']	= $device->getModel() ?: null;
		}

		return $result;
	}

	/**
	 * Fetch informations from MobileDetect.
	 *
	 * @return array
	 */
	protected function fetchMobileDetect()
	{
		// If the analized ua is the current then allow header usage, but if not then display fake headers.
		$headers  = ($this->ua != $_SERVER['HTTP_USER_AGENT']) ? array('HTTP_FAKE' => 1) : null;

		// Fetch informations.
		$md 	= new MobileDetect($headers, $this->ua);
		$result = array();

		// Fetch OS' family.
		$result['osFamily'] = $this->_mdFetcher($md, $md->getOperatingSystems());

		// Fetch browser's family.
		$result['browserFamily'] = $this->_mdFetcher($md, $md->getBrowsers());

		// Check out if it's a tablet since the MobileDetect passes isTablet and isMobile together sometimes.
		if ($md->isMobile() and ! $md->isTablet()) {
			// Fetch phone's family.
			$result['deviceModel'] = $this->_mdFetcher($md, $md->getPhoneDevices());
			$result['isMobile'] 	= true;
			$result['mobileGrade']  = $md->mobileGrade();
		} elseif ($md->isTablet()) {
			// Fetch tablet's family.
			$result['deviceModel'] = $this->_mdFetcher($md, $md->getTabletDevices());
			$result['isTablet'] 	= true;
			$result['mobileGrade']  = $md->mobileGrade();
		}

		// Filter the generic ***t...
		$result = array_filter($result, function($value) {
			return ($value !== null and $value !== 'GenericPhone' and $value !== 'GenericBrowser');
		});

		return $result;
	}

	/**
	 * MobileDetect fetcher till the 3.0 comes out.
	 *
	 * @return array
	 */
	protected function _mdFetcher($md, $list)
	{
		foreach ($list as $name => $pattern) {
			if ($md->is($name)) {
				return $name;
			}
		}

		return null;
	}

	/**
	 * Mirror the request to the Info object.
	 *
	 * @return mixed
	 */
	public function __call($method, $params)
	{
		// Importing the info.
		if (substr($method, 0, 6) == 'import') {
			return new Info($params[0]);
		}

		$info = $this->getInfo();

		// Check for browser data.
		if (array_key_exists($method, $info->data)) {
			return $info->data[$method];
		} 
		// Check for Info functions.
		elseif (method_exists($info, $method)) {
			return sizeof($params) ? call_user_func_array(array($info, $method), $params) : $info->$method();
		}

		throw new InvalidArgumentException($method.' call does not supported.');
	}
}