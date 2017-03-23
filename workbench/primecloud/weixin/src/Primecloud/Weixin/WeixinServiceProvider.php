<?php 

namespace Primecloud\Weixin;

use Illuminate\Support\ServiceProvider;
use Primecloud\Weixin\Kernel\WxPayApi;

class WeixinServiceProvider extends ServiceProvider {

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
		$this -> app -> singleton('WxPay', function() {
            return new WxPayApi();
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
