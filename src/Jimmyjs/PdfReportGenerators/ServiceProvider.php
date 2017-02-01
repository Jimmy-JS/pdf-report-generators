<?php

namespace Jimmyjs\PdfReportGenerators;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
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

        $this->registerAliases();
	}

	public function boot()
	{
		$this->loadViewsFrom(__DIR__ . '/views', 'pdf-report-generators');
	}

	protected function registerAliases()
	{
	    if (class_exists('Illuminate\Foundation\AliasLoader')) {
	        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
	        $loader->alias('PdfReportGenerator', \Jimmyjs\PdfReportGenerators\Facade::class);
	    }
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
