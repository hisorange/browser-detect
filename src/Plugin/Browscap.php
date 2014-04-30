<?php
namespace hisorange\BrowserDetect\Plugin;

use hisorange\Traits\ObjectConfig;

class Browscap {

	/**
	 * @since 1.0.0 ObjectConfig trait used.
	 */
	use ObjectConfig;

	/**
	 * Parse the user agent string.
	 *
	 * @param  string $agent
	 * @return mixed
	 */
	public function parse($agent)
	{
		// Location of the browscap cache dir.
		$cacheDir 	=  $this->objectConfigGet('cacheDir') ?: realpath(__DIR__.'/../../cache/Browscap');
		
		// Create the instance with the ini's location.
		$browscap 	= new \phpbrowscap\Browscap($cacheDir);

		// Overwrite the class defaults, with custom configs.
		$browscap->iniFilename 		= $this->objectConfigGet('iniFilename');
		$browscap->cacheFilename 	= $this->objectConfigGet('cacheFilename');
		$browscap->doAutoUpdate 	= $this->objectConfigGet('doAutoUpdate');
		$browscap->updateInterval 	= $this->objectConfigGet('updateInterval');
		$browscap->errorInterval 	= $this->objectConfigGet('errorInterval');
		$browscap->updateMethod 	= $this->objectConfigGet('updateMethod');
		$browscap->timeout 			= $this->objectConfigGet('timeout');

		return $browscap->getBrowser($agent, true);
	}

	/**
	 * Filter the parsed result to the schema.
	 *
	 * @param  mixed $parsed
	 * @return array
	 */
	public function filter($parsed) {
		// Key conversions to our schema.
		$conversion = array(
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
			'JavaScript'		=> 'javaScriptSupport'
		);

		$filtered 	= array();

		foreach ($parsed as $key => $value) {
			// Only keys in the conversion are used.
			if (array_key_exists($key, $conversion)) {
				$filtered[$conversion[$key]]	= $value;
			}
		}

		// Filter unknown and default values.
		$filtered 	= array_filter(array_filter($filtered, function($value) {
			return ! preg_match('%^(unknown|default)%i', $value);
		}));

		return $filtered;
	}
}