<?php
namespace hisorange\browserdetect\Providers;

// Laravel.
use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;

// Package.
use hisorange\browserdetect\Manager;

class BrowserDetectServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Register the package.
		$this->package('hisorange/browser-detect');

		// Register the package's config.
		$this->app['config']->package('hisorange/browser-detect', dirname(dirname(dirname(__DIR__))).'/config');

		// Register the manager.
		$this->app['browserdetect'] = $this->app->share(function($app) {
			return new Manager;
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('browserdetect');
	}

}