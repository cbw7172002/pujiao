<?php

namespace App\Http\Controllers\Admin\commentReply;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class applyCommentController extends Controller
{

    public function applyCommentList(Request $request){
        $query = DB::table('applycoursecomment as acc')
                ->leftjoin('commentcourse as cc','acc.courseId','=','cc.id')
                ->leftjoin('users as u','u.id','=','acc.fromUserId')
                ->select('acc.id','acc.commentContent','cc.courseTitle','acc.parentId','u.username','acc.toUserId','acc.checks','acc.created_at');

        if($request->type == 1){
            $query = $query->where('acc.id','like','%'.trim($request['search']).'%');
        }
        if($request->type == 2){
            $query = $query->where('u.username','like','%'.trim($request['search']).'%');
        }
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('acc.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('acc.created_at','<=',$request['endTime']);
        }

        $data = $query->orderBy('id','desc')->paginate(10);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        return view('admin.commentReply.applycommentList')->with('data',$data);
    }



    /**
     * 编辑
     */
    public function editapplyComment($id){
        $res = DB::table('applycoursecomment as acc')
            ->leftjoin('users as u','u.id','=','acc.fromUserId')
            ->select('acc.id','u.username','acc.commentContent')
            ->where('acc.id',$id)->first();
//        dd($res);
        return view('admin.commentReply.editapplyComment')->with('data',$res);
    }


    /**
     * 编辑方法
     */
    public function editsapplyComment(){
        $input = Input::except('_token');
//        $input['updated_at'] = Carbon::now();
        $res = DB::table('applycoursecomment')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了演奏评论ID为{$input['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'commentReply/applyCommentList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }



    /**
     * 删除
     */
    public function delapplyComment($id){

        $res = DB::table('applycoursecomment')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了演奏评论ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'commentReply/applyCommentList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }





    /**
     * 审核状态
     */
    public function applyCommentChecks(Request $request){
        $data['checks'] = $request['checks'];
//        $data['updated_at'] = Carbon::now();
        $data = DB::table('applycoursecomment')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了演奏评论ID为{$request['id']}的状态", 1);
            echo 1;
        }else{
            echo 0;
        }
    }


    /**
     * 查看详情
     */
    public function lookapplyComment($id){
        $res = DB::table('applycoursecomment as acc')
            ->leftjoin('commentcourse as cc','acc.courseId','=','cc.id')
            ->leftjoin('users as u','u.id','=','acc.fromUserId')
            ->select('acc.*','u.username','cc.courseTitle')
            ->where('acc.id',$id)
            ->first();
        return view('admin.commentReply.lookapplaycomment')->with('data',$res);
    }



}