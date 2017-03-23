<?php

namespace App\Http\Controllers\Admin\companyUser;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class systemController extends Controller
{
    /**
     *系统设置列表
     */
    public function systemList(){
        $data = DB::table('systemsttings')->paginate(10);
//        dd($data);
        return view('admin.companyUser.system.systemList',['data'=>$data]);
    }

    /**
     *修改是否状态
     */
    public function status($id,$isTrue){
        $data = DB::table('systemsttings')->where('id',$id)->update(['isTrue'=>$isTrue]);
        return back();
    }
}
