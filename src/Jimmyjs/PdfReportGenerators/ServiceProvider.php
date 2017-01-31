<?php

namespace Jimmyjs\PdfReportGenerators;

use Illuminate\Support\ServiceProvider;

class ServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton('pdf-report-generator', function ($app) {
            return new PdfReportGenerator($app);
        });
	}

	public function boot()
	{
		$this->loadViewsFrom(__DIR__ . '/views', 'pdf-report-generators');
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
