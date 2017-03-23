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

class tresourceranklistController extends Controller{


	/**
	 * 列表页
	 */
	//按照发布量降序排序
	public function tresourcerankList(Request $request){
		$hidden = $request['hidden'];

		$query = DB::table('resource');

		//导出设置
		$excel = $query
			->select('userId as id','resourceAuthor as 发布人',DB::raw('count(resource.userId) as 发布量'))
			->groupBy('userId')
			->orderBy(DB::raw('count(resource.userId)'),'desc')
			->get();
		$excel = json_encode($excel);

		//前台展示
		$data = $query
			->select('resource.userId',DB::raw('count(resource.userId) as amount'),'resource.resourceAuthor')
			->groupBy('resource.userId')
			->orderBy('amount','desc')
			->paginate(10);
		$data->type = $hidden;


//		return view('admin.datacount.tresourceRank.tresourcerankList')->with('data',$data);
		return view('admin.datacount.tresourceRank.tresourcerankList',['data'=>$data,'excel'=>$excel]);

	}


}