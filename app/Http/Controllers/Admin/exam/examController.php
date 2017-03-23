<?php

namespace App\Http\Controllers\Admin\exam;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class examController extends Controller
{
    /**
     *试卷列表
     */
    public function examList(Request $request){
        $query = DB::table('exampaper as p');

        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('p.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('p.created_at','<=',$request['endTime']);
        }

        if($request['resourceGrade']){
            $query = $query->where('p.gradeId',$request['resourceGrade']);
        }
        if($request['resourceSubject']){
            $query = $query->where('p.subjectId',$request['resourceSubject']);
        }
        if($request['resourceEdition']){
            $query = $query->where('p.editionId',$request['resourceEdition']);
        }
        if($request['resourceBook']){
            $query = $query->where('p.bookId',$request['resourceBook']);
        }

        if($request['type'] == 1){
            $query = $query->where('p.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('p.title','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('u.username','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('users as u','u.id','=','p.userId')
            ->leftJoin('studysection as x','x.id','=','p.sectionId')
            ->leftJoin('schoolgrade as g','g.id','=','p.gradeId')
            ->leftJoin('studysubject as s','s.id','=','p.subjectId')
            ->leftJoin('studyedition as e','e.id','=','p.editionId')
            ->leftJoin('studyebook as b','b.id','=','p.bookId')
            ->select('p.*','u.username','g.gradeName','s.subjectName','e.editionName','b.bookName')
            ->orderBy('id','desc')
            ->paginate(10);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        $data->resourceGrade = $request['resourceGrade'] ? $request['resourceGrade'] : 0;
        $data->resourceSubject = $request['resourceSubject'] ? $request['resourceSubject'] : 0;
        $data->resourceEdition = $request['resourceEdition'] ? $request['resourceEdition'] : 0;
        $data->resourceBook = $request['resourceBook'] ? $request['resourceBook'] : 0;
        $data->resourceType = $request['resourceType'] ? $request['resourceType'] : 0;
//        dd($data);
        return view('admin.exam.examList',['data'=>$data]);
    }

    /**
     *修改状态
     */
    public function status($id,$status){
        $data = DB::table('exampaper')->where('id',$id)->update(['status'=>$status]);
        return back();
    }

    /**
     *删除试卷
     */
    public function delExam($id){
        $data = DB::table('exampaper')->where('id',$id)->delete();
        if($data){
            return redirect()->back()->with(['status'=>'删除成功']);
        }else{
            return redirect()->back()->withErrors('删除失败');
        }
    }
}
