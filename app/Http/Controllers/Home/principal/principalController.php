<?php
/**
 * Created by PhpStorm.
 * User: Mr.H
 * Date: 2017/3/22
 * Time: 10:45
 */

namespace App\Http\Controllers\Home\principal;

use App\Http\Controllers\Controller;

class principalController extends Controller
{
    /**
     * 校长模块首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function index()
    {
        return view('home.principal.index');
    }
}