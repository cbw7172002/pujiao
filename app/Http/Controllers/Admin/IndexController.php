<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class IndexController extends Controller
{
    public function index()
    {
//        $data['feedbackCount'] = DB::table('coursefeedback')->where('courseType',0)->where('status',0)->count();//未处理的课程反馈
//        $data['complaintCount'] = DB::table('complaint')->where('status',0)->count();//未处理的意见反馈
//        $data['courseCount'] = DB::table('course')->where('courseStatus',6)->where('courseIsDel',0)->count();//有转码失败的视频
//        dd($data);
        return view('admin.index');
    }
}
