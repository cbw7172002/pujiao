<?php

namespace App\Http\Controllers\Admin\specialCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class notesController extends Controller
{
    /**
     *课程笔记列表
     */
    public function notesList($id,Request $request){
        $query = DB::table('notes as n');

        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('n.createtime','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('n.createtime','<=',$request['endTime']);
        }

        if($request['type'] == 1){
            $query = $query->where('n.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('n.parchaptertitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('u.username','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('users as u','u.id','=','n.stuId')
            ->where('courseId',$id)
            ->orderBy('id','desc')
            ->select('n.*','u.username')
            ->paginate(15);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
//        dd($data);
        return view('admin.specialCourse.notes.notesList',['data'=>$data]);
    }

    /**
     *笔记详情
     */
    public function detailNotes($id){
        $data = DB::table('notes')->where('id',$id)->first();
        return view('admin.specialCourse.notes.detailNotes',['data'=>$data]);
    }

    /**
     *删除笔记
     */
    public function delNotes($id){
        if(DB::table('notes')->where('id',$id)->delete()){
            return redirect()->back()->with(['status'=>'删除成功']);
        }else{
            return redirect()->back()->withErrors('删除失败');
        }
    }
}
