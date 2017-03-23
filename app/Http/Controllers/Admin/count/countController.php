<?php

namespace App\Http\Controllers\Admin\count;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class countController extends Controller
{
    /**
     *播放统计列表
     */
    public function specialCountList(Request $request){
        $query = DB::table('course');
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('created_at','<=',$request['endTime']);
        }
        if($request['type'] == 1){
            $query = $query->where('id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('courseTitle','like','%'.trim($request['search']).'%');
        }

        $excel = $query
            ->where('courseIsDel',0)
            ->select('id','courseTitle as 课程标题','courseView as 课程详情页访问数','coursePlayView as 课程视频观看数','completecount as 课程视频观看完成数','created_at as 课程创建时间')
            ->get();
        foreach($excel as &$value){
            //点击率
            if($value->课程详情页访问数 != 0){
                $value->点击率 = intval(round($value->课程视频观看数 / $value->课程详情页访问数,2) * 100);
            }else{
                $value->点击率 = 0;
            }
            //播放完成率
            if($value->课程视频观看数 != 0){
                $value->播放完成率 = intval(round($value->课程视频观看完成数 / $value->课程视频观看数,2) * 100);
            }else{
                $value->播放完成率 = 0;
            }
        }

        $data = $query
            ->where('courseIsDel',0)
            ->select('id','courseTitle','courseView','coursePlayView','completecount','created_at')
            ->paginate(15);
        foreach($data as &$val){
            //点击率
            if($val->courseView != 0){
                $val->click = round($val->coursePlayView / $val->courseView,2) * 100;
            }else{
                $val->click = 0;
            }
            //播放完成率
            if($val->coursePlayView != 0){
                $val->complete = round($val->completecount / $val->coursePlayView,2) * 100;
            }else{
                $val->complete = 0;
            }
        }
        $excel = json_encode($excel);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        return view('admin/count/specialCountList',['data'=>$data,'excel'=>$excel]);
    }
}
