<?php

namespace App\Http\Controllers\Admin\datacount;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class tcourseranklistController extends Controller{


	/**
	 * 列表页
	 */
	//按照授课量降序排序
	public function tcourseRankList(Request $request){
		$hidden = $request['hidden'];

		$query = DB::table('course');

		//导出设置
		$excel = $query
			->select('teacherId as 教师id','courseTeacher as 教师名称',DB::raw('count(course.teacherId) as 授课量'))
			->groupBy('teacherId')
			->orderBy(DB::raw('count(course.teacherId)'),'desc')
			->get();
		$excel = json_encode($excel);
		//前台展示
		$data = $query
			->select('course.teacherId',DB::raw('count(course.teacherId) as amount'),'course.courseTeacher')
			->groupBy('course.teacherId')
			->orderBy('amount','desc')
			->paginate(10);
		$data->type = $hidden;

//        dd($complain);
//		return view('admin.datacount.tcourseRank.tcourserankList')->with('data',$data);
		return view('admin.datacount.tcourseRank.tcourserankList',['data'=>$data,'excel'=>$excel]);
	}


}