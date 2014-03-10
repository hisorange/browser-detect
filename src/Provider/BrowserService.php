<?php
namespace hisorange\Browser\Provider;

use Illuminate\Support\ServiceProvider;

class BrowserService extends ServiceProvider {

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
		$this->package('hisorange/browser', 'browser-detect', realpath(__DIR__ . '/../../'));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}