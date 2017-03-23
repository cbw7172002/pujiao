<?php 

namespace Primecloud\Pass;

use Illuminate\Support\ServiceProvider;
use Primecloud\Pass\Kernel\User;
use Primecloud\Pass\Kernel\Resourse;

class PassServiceProvider extends ServiceProvider 
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
		$this->app->singleton('user', function() {
            return new User();
        });

        $this->app->singleton('recourse', function() {
            return new Resourse();
        });
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
