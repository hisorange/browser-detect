<?php
namespace hisorange\BrowserDetect\Plugin;

use hisorange\Traits\ObjectConfig;

class MobileDetect2 {

	/**
	 * @since 1.0.0 ObjectConfig trait used.
	 */
	use ObjectConfig;

	/**
	 * Parse the user agent string.
	 *
	 * @param  string $agent
	 * @return \Detection\MobileDetect
	 */
	public function parse($agent) {
		$headers 	= null;

		// If the parsed user agent is not the current visitor's user agent
		// then provide fake headers, because the MobileDetect uses
		// request headers too to identify the device kind.
		if (isset($_SERVER['HTTP_USER_AGENT']) and $agent != $_SERVER['HTTP_USER_AGENT']) {
			$headers 	= $this->objectConfigGet('fake_headers');
		}

		return new \Detection\MobileDetect($headers, $agent);
	}

	/**
	 * Filter the parsed result to the schema.
	 *
	 * @param  \Detection\MobileDetect $parsed
	 * @return array
	 */
	public function filter($parsed) {
		$filtered 	= array();

		// Filtering mechanishm for MobileDetect 2.* versions.
		$filter 	= function($items) use ($parsed) {
			// Play down the $parsed->is('Android'); mechanishm.
			foreach ($items as $key => $regex) {
				if ($parsed->is($key)) {
					return $key;
				}
			}

			return null;
		};

		// Operating system's family.
		$filtered['osFamily'] 		= $filter($parsed->getOperatingSystems());

		// Browser's family.
		$filtered['browserFamily']	= $filter($parsed->getBrowsers());

		// Just 'mobile' kind devices.
		if ($parsed->isMobile() and ! $parsed->isTablet()) {
			$filtered['isMobile']		= true;
			$filtered['mobileGrade']	= $parsed->mobileGrade();
			$filtered['deviceModel']	= $filter($parsed->getPhoneDevices());
		}
		// Just 'tablet' kind devices.
		elseif ($parsed->isTablet()) {
			$filtered['isTablet']		= true;
			$filtered['mobileGrade']	= $parsed->mobileGrade();
			$filtered['deviceModel']	= $filter($parsed->getTabletDevices());
		}


		// Clear the generic and null values.
		$filtered 	= array_filter(array_filter($filtered, function($value) {
			return ! preg_match('%^generic%i', $value);
		}));

		return $filtered;
	}
}