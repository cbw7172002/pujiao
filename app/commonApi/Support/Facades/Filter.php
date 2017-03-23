<?php
/**
 * Created by PhpStorm.
 * User: WangCK
 * Date: 2017/3/16
 * Time: 18:22
 */

namespace App\commonApi\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Filter extends Facade
{
	protected static function getFacadeAccessor() {
		return 'filter';
	}
}