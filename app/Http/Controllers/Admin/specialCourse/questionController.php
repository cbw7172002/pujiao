<?php

namespace App\Http\Controllers\Admin\specialCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class questionController extends Controller
{
    /**
     * 课程问答列表
     */
    public function questionList($id,Request $request){
        $query = DB::table('coursecomment as q');

        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('q.asktime','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('q.asktime','<=',$request['endTime']);
        }

        if($request['type'] == 1){
            $query = $query->where('q.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('q.content','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('u.username','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('users as u','u.id','=','q.stuId')
            ->leftJoin('users as t','t.id','=','q.teaId')
            ->where('q.courseId',$id)
            ->orderBy('q.id','desc')
            ->select('q.*','u.username','t.username as teachername')
            ->paginate(15);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
//        dd($data);
        return view('admin.specialCourse.question.questionList',['data'=>$data]);
    }

    /**
     *问答详情
     */
    public function detailQuestion($id){
        $data = DB::table('coursecomment as q')
            ->leftJoin('users as t','t.id','=','q.teaId')
            ->where('q.id',$id)
            ->select('q.*','t.username as teachername')
            ->first();
//        dd($data);
        return view('admin.specialCourse.question.detailquestion',['data'=>$data]);

    }

    /**
     *删除问答
     */
    public function delQuestion($id){
        if(DB::table('coursecomment')->where('id',$id)->delete()){
            return redirect()->back()->with(['status'=>'删除成功']);
        }else{
            return redirect()->back()->withErrors('删除失败');
        }
    }
}
