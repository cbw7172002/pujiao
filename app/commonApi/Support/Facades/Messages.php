<?php
namespace App\commonApi\Support\Facades;
use Illuminate\Support\Facades\Facade;
class Messages extends Facade
{
    /**
     * 获取组件的注册名称。
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'message'; }
}