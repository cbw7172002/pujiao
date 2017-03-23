<?php

namespace App\Http\Controllers\Admin\collection;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class collectionController extends Controller{

    public function collectionList(Request $request){

        $query = DB::table('collection as col')
                ->leftjoin('course as c','col.courseId','=','c.id')
                ->leftjoin('users as u','u.id','=','col.userId')
                ->select('col.id','col.type','c.courseTitle','u.username','col.created_at');


        if($request->type == 1){
            $query = $query->where('c.courseTitle','like','%'.trim($request['search']).'%')->where('col.type',0);
        }

        if($request->type == 3){
            $query = $query->where('u.username','like','%'.trim($request['search']).'%');
        }
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('col.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('col.created_at','<=',$request['endTime']);
        }

        $data = $query->where('col.type','=',0)->orderBy('id','desc')->paginate(10);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
       return view('admin.collection.collectionList')->with('collection',$data);
    }


    //删除
    public function delcollection($id){
        $courseId = DB::table('collection')->where('id',$id)->first();
//        dd($courseId->courseId);
        $res = DB::table('collection')->where('id',$id)->delete();
        if($res){
            DB::table('course')->where('id',$courseId->courseId)->decrement('courseFav',1);
            $this -> OperationLog("删除了创客课程收藏ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'collection/collectionList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



}

