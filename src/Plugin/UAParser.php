<?php
namespace hisorange\BrowserDetect\Plugin;

use hisorange\Traits\ObjectConfig;

class UAParser {

	/**
	 * @since 1.0.0 ObjectConfig trait used.
	 */
	use ObjectConfig;

	/**
	 * Parse the user agent string.
	 *
	 * @param  string $agent
	 * @return \UAParser\Result\Result
	 */
	public function parse($agent) {
		// Create the new instance.
		$uaparser 	= new \UAParser\UAParser($this->objectConfigGet('regexesPath'));

		return $uaparser->parse($agent);
	}

	/**
	 * Filter the parsed result to the schema.
	 *
	 * @param  \UAParser\Result\Result $parsed
	 * @return array
	 */
	public function filter($parsed) {
		$browser 	= $parsed->getBrowser();
		$os 	 	= $parsed->getOperatingSystem();
		$device	 	= $parsed->getDevice();
		$filtered 	= array();

		// Browser informations.
		if ($browser->getFamily() != 'Other') {
			$filtered['browserFamily']			= $browser->getFamily();
			$filtered['browserVersionMajor']	= $browser->getMajor() ?: 0;
			$filtered['browserVersionMinor']	= $browser->getMinor() ?: 0;
			$filtered['browserVersionPatch']	= $browser->getPatch() ?: 0;
		}

		// Operating system informations.
		if ($os->getFamily() != 'Other') {
			$filtered['osFamily']			= $os->getFamily();
			$filtered['osVersionMajor']		= $os->getMajor() ?: 0;
			$filtered['osVersionMinor']		= $os->getMinor() ?: 0;
			$filtered['osVersionPatch']		= $os->getPatch() ?: 0;
		}

		// Device informations.
		if ($device->getConstructor() != 'Other') {
			$filtered['isMobile']			= $device->isMobile();
			$filtered['isTablet']			= $device->isTablet();
			$filtered['deviceFamily'] 		= $device->getConstructor();
			$filtered['deviceModel']		= $device->getModel() ?: null;
		}

		return $filtered;
	}
}