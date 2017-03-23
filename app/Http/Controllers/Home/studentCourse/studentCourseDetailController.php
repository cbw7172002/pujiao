<?php

namespace App\Http\Controllers\Home\studentCourse;

use Illuminate\Support\Facades\Response;
use DB;
use Log;
use Input;
use PaasResource;
use PaasUser;
use Cache;
use QrCode;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Home\lessonComment\Gadget;
use Filter;


class studentCourseDetailController extends Controller{


    /**
     * @param $courseId
     * @param $type
     * 笔记筛选
     */
    public function getWholeChapter($courseId, $type){

        if($type == 0){
            $data1 = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$courseId)->where('courseType','=',0)->get();
            $dataUp = DB::table('coursechapter')->select('id', 'title as text')->where('courseId','=',$courseId)->where('courseType','=',1)->get();
            if($dataUp){
                $data2=[];
                foreach($dataUp as $key => &$val){
                    $d = DB::table('coursechapter')->select('id', 'title as text')->where('parentId','=',$val->id)->get();
                    if($d){
                        foreach($d as $k => $v){
                            $data2[] = $v;
                        }
                    }
                }
            }
            $data3 = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$courseId)->where('courseType','=',2)->get();
            $data = array_merge_recursive($data1,$data2,$data3);
        }elseif($type == 1){
            $data = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$courseId)->where('courseType','=',0)->get();
        }elseif($type == 2){
            $dataUp = DB::table('coursechapter')->select('id', 'title as text')->where('courseId','=',$courseId)->where('courseType','=',1)->get();
            if($dataUp){
                $data=[];
                foreach($dataUp as $key => &$val){
                    $d = DB::table('coursechapter')->select('id', 'title as text')->where('parentId','=',$val->id)->get();
                    if($d){
                        foreach($d as $k => $v){
                            $data[] = $v;
                        }
                    }
                }
            }
        }elseif($type == 3){
            $data = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$courseId)->where('courseType','=',2)->get();
        }
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 获取我的笔记数据接口
     */
    public function getCourseDetailMyNotes(Request $request,$courseId){
        $chapterId = $request['chapterId'];
        $userId = \Auth::user()->id;


        $query = DB::table('users as u')
            ->leftJoin('notes as n','n.stuId','=','u.id')
            ->leftJoin('coursechapter as cc','n.chapterId','=','cc.id')
            ->select('n.*', 'u.username','u.pic','cc.title','cc.courseFormat')
            ->where('n.courseId','=',$courseId)
            ->where('n.stuId','=',$userId)
            ->orderBy('n.createtime','desc');

        if($chapterId){
            $chapterId = $chapterId + 1;
            $data = $query->where('n.chapterId','=',$chapterId)->get();
        }else{
            $data = $query->get();
        }

        $icontype = ['doc','docx','xls','xlsx','ppt','pptx','pdf'];
        if($data){
            foreach($data as $key => $val){
                if(in_array($val->courseFormat,$icontype)){
                    $courseType = true;
                    $val->courseTypes = $courseType;
                }else{
                    $courseType = false;
                    $val->courseTypes = $courseType;
                }
            }
        }
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 获取共享数据接口
     */
    public function getCourseDetailShareNote(Request $request,$courseId){
        $chapterId = $request['chapterId'];

        $query = DB::table('users as u')
            ->leftJoin('notes as n','n.stuId','=','u.id')
            ->leftJoin('coursechapter as cc','n.chapterId','=','cc.id')
            ->select('n.*', 'u.username','u.pic','cc.title','cc.courseFormat')
            ->where('n.courseId','=',$courseId)
            ->where('n.public','=','0')
            ->orderBy('n.createtime','desc');

        if($chapterId){
            $chapterId = $chapterId + 1;
            $data = $query->where('n.chapterId','=',$chapterId)->get();
        }else{
            $data = $query->get();
        }
        $icontype = ['doc','docx','xls','xlsx','ppt','pptx','pdf'];
        if($data){
            foreach($data as $key => $val){
                if(in_array($val->courseFormat,$icontype)){
                    $courseType = true;
                    $val->courseTypes = $courseType;
                }else{
                    $courseType = false;
                    $val->courseTypes = $courseType;
                }
            }
        }
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }





    /**
     * 删除笔记
     */
    public function deleteNote($noteId){
        $data = DB::table('notes')->where('id','=',$noteId)->delete();
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 修改笔记内容
     */
    public function modifyContent(Request $request){
        $input = $request->all();
        try {
            $input['notecontent'] = Filter::filter($input['notecontent']);
        } catch (\Exception $e) {
            $input['notecontent'] = $request['notecontent'];
        }
        $input['createtime'] = Carbon::now();
        if($res = DB::table('notes')->where('id','=',$input['noteId'])->update(['notecontent' => $input['notecontent'] , 'createtime' => $input['createtime'] ])){
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 笔记私密转公开
     */
    public function privateNote($noteId){
        $data = DB::table('notes')->where('id','=',$noteId)->update(['public'=>'0']);

        if($data){
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 课程问答评论提交
     */
    public function courseComment(Request $request){

        $_POST['username'] = \Auth::user()->username;

        $userId = Auth::user()->id;
        $input = $request->all();
        $input['asktime'] = Carbon::now();
        $input['stuId'] = $userId;
        $input['status'] = '1';
        try {
            $input['content'] = Filter::filter($input['content']);
        } catch (\Exception $e) {
            $input['content'] = $request['content'];
        }
        $stuName = Auth::user()->username;
        //学生xxx在课程xxx中向您发起提问
        //取课程老师的username
        if($input['courseId']){
            $courseName = DB::table('course')->where('id','=',$input['courseId'])->first()->courseTitle;
            $teacherId = DB::table('course')->where('id','=',$input['courseId'])->first()->teacherId;
            $username = DB::table('users')->where('id','=',$teacherId)->first()->username;
        }

        $a = [
            'username'=>$username,
            'content'=>'学生'.$stuName.'在课程'.$courseName.'中向您发起提问',
            'toUsername'=>\Auth::user()->username,
            'courseType'=>'1',
            'fromUsername'=>\Auth::user()->username,
            'actionId'=>$input['courseId'],
            'type'=> 11,
            'created_at'=>Carbon::now()];
        DB::table('usermessage')->insertGetId($a);
//        dd($input);
        $data = DB::table('coursecomment')->insert($input);
        if($data){
            return response()->json(['status'=>true,'data' =>$data]);
        }else{
            return response()->json(['status'=>false]);
        }
    }



    /**
     * 获取课程问答
     */
    public function getCourseCommentAsk($courseId){
        $data = DB::table('coursecomment as c')
            ->leftjoin('users as u','u.id','=','c.stuId')
            ->select('c.id','c.content','u.username','c.answer','c.asktime','c.anstime','c.courseId','u.pic','c.teaId')
            ->where('c.courseId','=',$courseId)
            ->orderBy('c.asktime','desc')
            ->get();
        if($data){
            foreach($data as $k => &$v){
                if($v->teaId){
                    $v->teaName = DB::table('users')->where('id','=',$v->teaId)->pluck('username');
                    $v->teaPic = DB::table('users')->where('id','=',$v->teaId)->pluck('pic');
                }
            }
        }
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }





}