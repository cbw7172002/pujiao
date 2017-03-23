<?php

namespace App\Http\Controllers\Admin\question;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Cache;
use Messages;
use Illuminate\Support\Facades\Input;

class questionController extends Controller
{
    public function questionList(Request $request){

        $query = DB::table('question as q');

        if($request->type == 1){
            $query = $query->where('q.id','like','%'.trim($request['search']).'%');

        }else if($request->type == 2){
            $query = $query->where('q.qestitle','like','%'.trim($request['search']).'%');

        }else if($request->type == 3){
            $query = $query->where('u.realname','like','%'.trim($request['search']).'%');

        }else if($request->type == 4){
            $query = $query->where('s.subjectName','like','%'.trim($request['search']).'%');

        }else if($request['beginTime']){ //上传的起止时间
            $query = $query->where('q.asktime','>=',$request['beginTime']);

        }else if($request['endTime']){ //上传的起止时间
            $query = $query->where('q.asktime','<=',$request['endTime']);

        }

        $question = $query
            ->leftjoin('users as u','u.id','=','q.uId')
            ->leftjoin('studysubject as s','s.id','=','q.type')
            ->select('q.*','u.username','u.realname','s.subjectName','u.type')
            ->where('u.checks',0)
            ->orderBy('id','desc')
            ->where('q.delete','=','0')
            ->paginate(15);
        $question->type = $request['type'];
        $question->beginTime = $request['beginTime'];
        $question->endTime = $request['endTime'];
//        dd($question);

        return view('admin.question.questionList')->with('data',$question);
    }




    /**
     * 编辑
     */
    public function editquestion($id){
        $data = DB::table('question as q')
            ->leftjoin('users as u','u.id','=','q.stuId')
            ->select('q.id','q.uId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer')
            ->where('u.checks',0)
            ->where('q.id',$id)
            ->first();
        return view('admin.question.editquestion')->with('data',$data);
    }


    /**
     * 编辑方法
     */
    public function editsquestion(){
        $input = Input::except('_token');

        $res = DB::table('question')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了问答列表ID为{$input['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'question/questionList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }



    /**
     * 删除
     */
    public function delquestion($id){

        $res = DB::table('question')->where('id',$id)->update(['delete' => 1]);
        if($res){
            $this -> OperationLog("删除了问答列表ID为{$id}的信息(回收站)", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'question/questionList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }


    /**
     * 问答详情
     */
    public function viewquestion($id){
        $data = DB::table('question as q')
            ->leftjoin('users as u','u.id','=','q.uId')
            ->select('q.*','u.username','u.realname')
            ->where('u.checks',0)
            ->where('q.id',$id)
            ->first();
        return view('admin.question.viewquestion')->with('data',$data);

    }


    /**
     * 回答
     */
    public function replyquestion($id){
        $data = DB::table('question as q')
            ->leftjoin('users as u','u.id','=','q.stuId')
            ->select('q.id','q.stuId','q.teaId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer')
            ->where('u.checks',0)
            ->where('q.id',$id)
            ->first();
        return view('admin.question.replyquestion')->with('data',$data);

    }

    /**
     *查看问答下的所有的回复内容列表
     */
    public function replyList($qesId,Request $request){
        $query = DB::table('questioncomment as c');
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('c.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('c.created_at','<=',$request['endTime']);
        }
        if($request['type'] == 1){
            $query = $query->where('c.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('q.qestitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('c.username','like','%'.trim($request['search']).'%');
        }
        $data = $query
            ->leftJoin('question as q','q.id','=','c.qesId')
            ->where('c.qesId',$qesId)
//            ->where('parentId','0')
            ->select('c.*','q.qestitle')
            ->paginate(10);
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        $data->type = $request['type'];
//        dd($data);
        return view('admin.question.replyList',['data'=>$data]);
    }

    /**
     *查看详情
     */
    public function deltailReply($id){
        $data = DB::table('questioncomment')->where('id',$id)->first();
        return view('admin.question.detailReply',['data'=>$data]);
    }

    /**
     *删除问答下的回复
     */
    public function delReply($id){
        $data = DB::table('questioncomment')->where('id',$id)->orWhere('parentId',$id)->delete();
        if($data){
            return back()->withInput()->with(['status'=>'删除成功！']);
        }else{
            return back()->withInput()->withErrors('删除失败！');
        }
    }




}
