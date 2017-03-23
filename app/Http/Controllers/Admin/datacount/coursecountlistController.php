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

class coursecountlistController extends Controller{


	/**
	 * 列表页
	 */
	public function coursecountList(Request $request){
		$query = DB::table('course');

		$hidden = $request['hidden'];
		$type = $request['type'];
		$search =  $request['search'];

		//type为1时搜索范围锁定为课程名称(courseTitle)和授课教师(courseTeacher)
		if ($search && $type == '1') {
			$query
				->where('courseTitle', 'like', '%'.$search.'%')
				->orwhere('courseTeacher', 'like', '%'.$search.'%')
				->orderBy('id','asc');
		}
		//type为2时搜索范围锁定为课程名称(courseTitle)
		if ($search && $type == '2') {
			$query
				->where('courseTitle', 'like', '%'.$search.'%')
				->orderBy('id','asc');
		}
		//type为3时搜索范围锁定为授课教师(courseTeacher)
		if ($search && $type == '3') {
			$query
				->where('courseTeacher','like', '%'.$search.'%')
				->orderBy('id','asc');
		}
		//导出设置
		$excel = $query
			->where('courseIsDel',0)
			->select('id', 'courseTitle as 课程名称', 'courseTeacher as 授课教师', 'courseView as 游览量', 'courseStudyNum as 完成度', 'courseFav as 收藏量')
			->get();
		$excel = json_encode($excel);

		$data = $query->select('*')->orderBy('id','asc')->paginate(15);
		$data->type = $hidden;

//		return view('admin.datacount.course.coursecountList')->with('data',$data);
		return view('admin.datacount.course.coursecountList',['data'=>$data,'excel'=>$excel]);
	}


}