<?php
/**
 * Plugin keys are case sensitive!
 * Plugins will be loaded & overwrited in the order as they apper here.
 *
 * @var array
 */
return [
	
	/**
	 * @package garetjax/phpbrowscap
	 * @link 	https://github.com/GaretJax/phpbrowscap
	 */
	'hisorange\BrowserDetect\Plugin\Browscap'	=> [
		/**
		 * Location of the browscap.ini file.
		 * If setted to 'null' it will use the BrowserDetect package's cache directory.
		 *
		 * @see https://github.com/GaretJax/phpbrowscap
		 * @var string|null
		 */
		'cacheDir'		=> null,

		/**
		 * Where to store the downloaded ini file.
		 *
		 * @var string
		 */
		'iniFilename' 	=> 'browscap.ini',

	    /**
	     * Where to store the cached PHP arrays.
	     *
	     * @var string
	     */
	    'cacheFilename' => 'browscap_cache.php',

		/**
		 * Flag to disable the automatic interval based update.
		 *
		 * @var boolean
		 */
		'doAutoUpdate' 	=> true,

		/**
		 * The update interval in seconds.
		 *
		 * @var integer
		 */
		'updateInterval'=> 432000, // 5 days

		/**
		 * The next update interval in seconds in case of an error.
		 *
		 * @var integer
		 */
		'errorInterval'	=> 7200, // 2 hours

		/**
		 * The method to use to update the file, has to be a value of an UPDATE_* constant, null or false.
		 *
		 * @var mixed
		 */
		'updateMethod' 	=> null,

		/**
		 * The timeout for the requests, when downloading th browscap.ini.
		 *
		 * @var integer
		 */
		'timeout'		=> 5,

	],

	/**
	 * @package yzalis/ua-parser
	 * @link  	https://github.com/yzalis/UAParser
	 */
	//'hisorange\BrowserDetect\Plugin\UAParser'	=> [
		/**
		 * Path to regexps yaml file, if null gona user the package's default.
		 *
		 * @var null|string
		 */
	//	'regexesPath' 	=> null,
	//],

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
