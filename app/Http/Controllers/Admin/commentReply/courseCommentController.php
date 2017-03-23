<?php

namespace App\Http\Controllers\Admin\commentReply;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class courseCommentController extends Controller
{
    /**
     * 列表
     */
    public function courseCommentList(Request $request){

        $query = DB::table('coursecomment as cou')
                ->leftjoin('course as c','c.id','=','cou.courseId')
                ->select('cou.id','cou.parentId','cou.commentContent','c.courseTitle','cou.username','cou.tousername','cou.checks','cou.status','cou.created_at','cou.updated_at');

        if($request->type == 1){
            $query = $query->where('cou.id','like','%'.trim($request['search']).'%');
        }
        if($request->type == 2){
            $query = $query->where('cou.username','like','%'.trim($request['search']).'%');
        }
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('cou.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('cou.created_at','<=',$request['endTime']);
        }

        $data = $query->orderBy('id','desc')->paginate(10);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];



        return view('admin.commentReply.courseCommentList')->with('data',$data);
    }



    /**
     * 编辑
     */
    public function editcourseComment($id){
        $res = DB::table('coursecomment')->where('id',$id)->first();
        return view('admin.commentReply.editcourseComment')->with('data',$res);
    }


    /**
     * 编辑方法
     */
    public function editscourseComment(){
        $input = Input::except('_token');
        $input['updated_at'] = Carbon::now();
        $res = DB::table('coursecomment')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了课程评论ID为{$input['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'commentReply/courseCommentList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }



    /**
     * 删除
     */
    public function delcourseComment($id){
        //取出所有数据数据
//        $parentId = DB::table('coursecomment')->select('parentId')->get();

        $res = DB::table('coursecomment')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了课程评论ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'commentReply/courseCommentList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     *评论状态
     */
    public function courseCommentStatus(Request $request){
        // $data = $request->except('_token');
        // dd($data);
        $data['status'] = $request['status'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('coursecomment')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了课程评论ID为{$request['id']}的评论状态", 1);
            echo 1;
        }else{
            echo 0;
        }
    }


    /**
     * 审核状态
     */
    public function courseCommentChecks(Request $request){
        $data['checks'] = $request['checks'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('coursecomment')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了课程评论ID为{$request['id']}的审核状态", 1);
            echo 1;
        }else{
            echo 0;
        }
    }



    /**
     * 查看详情
     */
    public function lookcourseComment($id){
        $res = DB::table('coursecomment as cou')
            ->leftjoin('course as c','c.id','=','cou.courseId')
            ->select('cou.id','cou.parentId','cou.commentContent','c.courseTitle','cou.username','cou.tousername','cou.checks','cou.status','cou.created_at','cou.updated_at')
            ->where('cou.id',$id)
            ->first();
        return view('admin.commentReply.lookcourseComment')->with('data',$res);
    }




}
