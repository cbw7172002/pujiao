<?php

namespace App\Http\Controllers\Admin\collection;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class questionfavController extends Controller{

    public function questionfavList(Request $request){


        $query = DB::table('questionfav as qf')
            ->leftjoin('question as q','q.id','=','qf.qesId')
            ->select('q.qestitle','qf.id','qf.username','qf.created_at','qf.created_at');

        if($request->type == 1){
            $query = $query->where('q.qestitle','like','%'.trim($request['search']).'%');
        }

        if($request->type == 3){
            $query = $query->where('qf.username','like','%'.trim($request['search']).'%');
        }
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('qf.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('qf.created_at','<=',$request['endTime']);
        }

        $data = $query->orderBy('qf.id','desc')->paginate(10);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
       return view('admin.collection.questionfavList')->with('data',$data);
    }


    //删除
    public function delquestionfav($id){
        $res = DB::table('questionfav')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了问答收藏ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'collection/questionfavList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



}

