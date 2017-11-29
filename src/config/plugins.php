<?php
/**
 * Plugin keys are case sensitive!
 * Plugins will be loaded & overwritten in the order as they appear here.
 *
 * @var array
 */
return [
	/**
	 * @package yzalis/ua-parser
	 * @link  	https://github.com/yzalis/UAParser
	 */
	'hisorange\BrowserDetect\Plugin\UAParser'	=> [
		/**
		 * Path to regexps yaml file, if null gona user the package's default.
		 *
		 * @var null|string
		 */
		'regexesPath' 	=> null,
	],

	/**
	 * @package mobiledetect/mobiledetectlib (v2.*)
	 * @link 	https://github.com/serbanghita/Mobile-Detect
	 */
	'hisorange\BrowserDetect\Plugin\MobileDetect2' => [
		/**
		 * This fake headers gona be passed to MobileDetect 2.*
		 * when parsing different than the current visitor's user-agent.
		 *
		 * @var array
		 */
		'fake_headers'	=> [
			'HTTP_FAKE_HEADER' => 'HiSoRange\Browser'
		],
	],

	/**
	 * Uses the UserAgentString.Com's api, native plugin.
	 *
	 * @link http://www.useragentstring.com/pages/api.php
	 */
	// Uncomment this value to enable the plugin. 
	//'hisorange\BrowserDetect\Plugin\UserAgentStringApi' => [],
	

];
