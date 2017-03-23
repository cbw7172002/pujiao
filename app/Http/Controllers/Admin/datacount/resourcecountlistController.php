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

class resourcecountlistController extends Controller{


    /**
     * 列表页
     */
    public function resourcecountList(Request $request){
		$query = DB::table('resource');

		$hidden = $request['hidden'];
		$type = $request['type'];
		$search =  $request['search'];

		//type为1时搜索范围锁定为资源名称(resourceTitle)和发布人(resourceAuthor)
		if ($search && $type == '1') {
			$query
				->where('resourceTitle', 'like', '%'.$search.'%')
				->orwhere('resourceAuthor', 'like', '%'.$search.'%')
				->orderBy('id','asc');
		}
		//type为2时搜索范围锁定为资源名称(resourceTitle)
		if ($search && $type == '2') {
			$query
				->where('resourceTitle', 'like', '%'.$search.'%')
				->orderBy('id','asc');
		}
		//type为3时搜索范围锁定为发布人(resourceAuthor)
		if ($search && $type == '3') {
			$query
				->where('resourceAuthor','like', '%'.$search.'%')
				->orderBy('id','asc');
		}
		//导出设置
		$excel = $query
			->where('resourceIsDel',0)
			->select('id', 'resourceTitle as 资源名称', 'resourceAuthor as 发布人', 'resourceView as 游览量', 'resourceDownload as 下载量', 'resourceFav as 收藏量')
			->get();
		$excel = json_encode($excel);


		$data = $query->select('*')->orderBy('userId', 'asc')->paginate(15);
		$data->type = $hidden;


//        return view('admin.datacount.resource.resourcecountList')->with('data',$data);
		return view('admin.datacount.resource.resourcecountList',['data'=>$data,'excel'=>$excel]);
    }


}