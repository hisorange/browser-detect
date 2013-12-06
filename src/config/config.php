<?php
return array(
	/**
	 * The package only caches the most necessary datas in a keyless array to minimalize the cache usage.
	 * Will use the Laravel Cache::put() function for it if turned on, and operates with the application's cache config.
	 */
	'cache'	=> true,

	/**
	 * Caching for X day, in case if you don't wana overfill your memory with old results.
	 * You can increase the interval but the Google releaseing a new Chrome everytime when some1 blinks so...
	 */
	'cache_interval' => 7,

	/**
	 * Prefix used for the result cache keys.
	 */
	'cache_prefix'	=> 'hbd_',

	/**
	 * The garetjax/phpbrowscap package's useing a file cache for the browsecap.ini and it's cached format.
	 * if setted to NULL it will use the package's cache directory, or you can set your own path.
	 */
	'browscap_cache' => null,
);