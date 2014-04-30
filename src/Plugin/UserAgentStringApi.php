<?php
namespace hisorange\BrowserDetect\Plugin;

use hisorange\Traits\ObjectConfig;

class UserAgentStringApi {

	/**
	 * @since 1.0.0 ObjectConfig trait used.
	 */
	use ObjectConfig;

	/**
	 * API request's base url.
	 *
	 * @var string
	 */
	const BASE_URL  = 'http://www.useragentstring.com/';

	/**
	 * Parse the user agent string.
	 *
	 * @param  string $agent
	 * @return array
	 */
	public function parse($agent) {
		return json_decode(file_get_contents($this->buildURL($agent)), true);
	}

	/**
	 * Build request url.
	 *
	 * @return boolean
	 */
	static function buildURL($agent)
	{
		return self::BASE_URL.'?'.http_build_query(array('uas' => $agent, 'getJSON' => 'all'));
	}

	/**
	 * Filter the parsed result to the schema.
	 *
	 * @param  array $parsed
	 * @return array
	 */
	public function filter($parsed) {
		// Key conversions to our schema.
		$conversion = array(
			'os_name'			=> 'osFamily',
			'agent_name'		=> 'browserFamily'
		);

		$parsed		= array_filter($parsed);
		$filtered 	= array();

		foreach ($parsed as $key => $value) {
			// Only keys in the conversion are used.
			if (array_key_exists($key, $conversion)) {
				$filtered[$conversion[$key]]	= $value;
			}
		}

		// Browser version.
		if (isset($parsed['agent_version'])) {
			// Parse the version into valid semver.	
			$semver = $this->convertSemver($parsed['agent_version'], 'browser');

			// If the parsing fail then will return with 0.0.0
			if (array_sum($semver) > 0) {
				$filtered 	= $filtered + $semver;
			}
		}

		// OS version.
		if (isset($parsed['os_versionNumber'])) {
			// Parse the version into valid semver.	
			$semver = $this->convertSemver($parsed['agent_version'], 'os');

			// If the parsing fail then will return with 0.0.0
			if (array_sum($semver) > 0) {
				$filtered 	= $filtered + $semver;
			}
		}

		// Linux dist.
		if (isset($parsed['linux_distibution']) and strtolower($parsed['linux_distibution']) != 'null') {
			$filtered['osFamily'] = trim($parsed['linux_distibution'].' '.$filtered['osFamily']);
		}

		return $filtered;
	}

	/**
	 * Convert version into semantic version.
	 *
	 * @param  $version string
	 * @return array
	 */
	public function convertSemver($version, $append)
	{
		// Match the version numbers.
		preg_match('%^(?<major>\d+)(\.(?<minor>\d+)(\.(?<patch>\d+))?)?%', $version, $semver);
		
		// Merge with an empty array.
		$semver 	= array_merge(array('major' => 0, 'minor' => 0, 'patch' => 0), $semver);
		
		return array(
			$append.'VersionMajor' => (int) $semver['major'], 
			$append.'VersionMinor' => (int) $semver['minor'], 
			$append.'VersionPatch' => (int) $semver['patch']
		);
	}
}