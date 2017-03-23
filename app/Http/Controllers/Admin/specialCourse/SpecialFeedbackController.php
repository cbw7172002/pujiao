<?php

namespace App\Http\Controllers\Admin\specialCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class SpecialFeedbackController extends Controller
{
    /**
     * 意见反馈列表
     */
    public function specialFeedbackList(Request $request,$courseType){
        $query = DB::table('coursefeedback as f');
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('f.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('f.created_at','<=',$request['endTime']);
        }
        //审核状态
        if($request['status'] === '0' || $request['status'] === '1'){
            $query = $query->where('f.status',$request['status']);
        }
        if($request['type'] == 1){
            $query = $query->where('f.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('c.courseTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('f.backType','like','%'.trim($request['search']).'%');
        }
        if($courseType == 1){  //专家课程
            $table = 'commentcourse as c';
        }else{
            $table = 'course as c';
        }
        $data = $query
            ->leftJoin($table,'f.courseId','=','c.id')
            ->where('f.courseType',$courseType)
            ->select('f.*','c.courseTitle')
            ->orderBy('f.id','desc')
            ->paginate(15);
        $data->type = $request['type'];
        $data->status = $request['status'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        $data->courseType = $courseType;
//        dd($data);
        return view('admin.specialCourse.specialFeedbackList',['data'=>$data]);

    }

    /**
     *意见反馈状态
     */
    public function specialFeedbackState(Request $request){
        $data['status'] = $request['status'];
        $data = DB::table('coursefeedback')->where('id',$request['id'])->update($data);
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *删除
     */
    public function delSpecialFeedback($id,$type){
        $data = DB::table('coursefeedback')->where('id',$id)->delete();
        if($data){
            if($type == 0){
                $this -> OperationLog('删除了id为'.$id.'的创课课程意见反馈');
            }
            if($type == 1){
                $this -> OperationLog('删除了id为'.$id.'的辅导课程意见反馈');
            }
            return redirect('admin/message')->with(['status'=>'意见反馈删除成功','redirect'=>'specialCourse/specialFeedbackList/'.$type]);
        }else{
            return redirect('admin/message')->with(['status'=>'意见反馈删除失败','redirect'=>'specialCourse/specialFeedbackList/'.$type]);
        }
    }
}
