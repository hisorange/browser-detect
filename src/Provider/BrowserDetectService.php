<?php
namespace hisorange\BrowserDetect\Provider;

use hisorange\BrowserDetect\Parser;
use Illuminate\Support\ServiceProvider;

class BrowserDetectService extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
		$this->package('hisorange/browser-detect', 'browser-detect', realpath(__DIR__ . '/../'));
	}

	/**
	 * Register the service provider.
	 * @since 1.0.0 the function splitted into parser & result registration to be more extendable.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerParser();
		$this->registerResult();
	}

	/**
	 * @since 1.0.0 Register the parser.
	 *
	 * @return void
	 */
	public function registerParser()
	{
		$this->app['browser-detect.parser']	= $this->app->share(function($app) {
			return new Parser($app);
		});
	}

	/**
	 * @since 1.0.0 Register the result.
	 *
	 * @return void
	 */
	public function registerResult()
	{
		$this->app->bind('browser-detect.result', 'hisorange\BrowserDetect\Result');
	}

	/**
	 * Get the services provided by the provider.
	 * @since 1.0.0 Component names changed to avoid conflict with older versions.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['browser-detect.parser', 'browser-detect.result'];
	}

}