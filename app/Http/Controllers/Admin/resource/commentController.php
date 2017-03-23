<?php

namespace App\Http\Controllers\Admin\resource;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class commentController extends Controller
{
    /**
     * 资源评论列表
     */
    public function getCommentList($id,Request $request){
        $query = DB::table('resourcecomment as c');

        if($request['type'] == 1){
            $query = $query->where('c.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('c.username','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('resource as r','r.id','=','c.resourceId')
            ->where('c.resourceId',$id)
            ->orderBy('id','desc')
            ->select('c.*','r.resourceTitle')
            ->paginate(10);
        $data->type = $request['type'];
//        dd($data);
        return view('admin.resource.comment.commentList',['data'=>$data]);
    }

    /**
     *评论详情
     */
    public function detailComment($id){
        $data = DB::table('resourcecomment as c')
            ->leftJoin('resource as r','r.id','=','c.resourceId')
            ->where('c.id',$id)
            ->select('c.*','r.resourceTitle')
            ->first();
//        dd($data);
        return view('admin.resource.comment.detailComment',['data'=>$data]);
    }

    /**
     *删除评论
     */
    public function delComment($id){
        $data = DB::table('resourcecomment')->where('id',$id)->delete();
        if($data){
            $this -> OperationLog('删除了id为'.$id.'资源评论');
            return redirect()->back()->with(['status'=>'删除成功']);
        }else{
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }
}
