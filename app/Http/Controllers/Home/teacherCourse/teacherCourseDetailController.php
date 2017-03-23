<?php

namespace App\Http\Controllers\Home\teacherCourse;

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


class teacherCourseDetailController extends Controller{


    /**
     * @param $courseId
     * @param $type
     * 获取共享数据接口
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
            $data = DB::table('coursechapter')->select('id', 'title as text')->where('courseId','=',$courseId)->where('courseType','=',1)->get();
            if($data){
                foreach($data as $key => $val){
                    $val->chapterId = DB::table('coursechapter')->select('id', 'title as text')->where('parentId','=',$val->id)->get();
                }
            }
        }elseif($type == 3){
            $data = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$courseId)->where('courseType','=',2)->get();
        }

//        dd($data);
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }

    /**
     * 获取共享数据接口
     */
    public function getCourseDetailShareNote($courseId){
        $data = DB::table('users as u')
            ->leftJoin('notes as n','n.stuId','=','u.id')
            ->leftJoin('coursechapter as cc','n.chapterId','=','cc.id')
            ->select('n.*', 'u.username','u.pic','cc.title')
            ->where('n.courseId','=',$courseId)
            ->where('n.public','=','0')
            ->orderBy('n.createtime','desc')
            ->get();

//        if($data){
//            foreach($data as $key => $val){
//                $value = array(
//                    "minutes" => 0, "seconds" => 0,
//                );
//                if($val->notetime >= 60){
//                    $value["minutes"] = floor($val->notetime/60);
//                    $val->notetime = ($val->notetime%60);
//                }
//                $value["seconds"] = floor($val->notetime);
//                $val->notetime=$value["minutes"] .":".$value["seconds"]."";
//            }
//        }
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 获取我的笔记数据接口
     */
    public function getCourseDetailMyNotes($courseId){
        $userId = \Auth::user()->id;
        $data = DB::table('users as u')
            ->leftJoin('notes as n','n.stuId','=','u.id')
            ->leftJoin('coursechapter as cc','n.chapterId','=','cc.id')
            ->select('n.*', 'u.username','u.pic','cc.title')
            ->where('n.courseId','=',$courseId)
            ->where('n.stuId','=',$userId)
            ->orderBy('n.createtime','desc')
            ->get();
//        if($data){
//            foreach($data as $key => $val){
//                $value = array(
//                    "minutes" => 0, "seconds" => 0,
//                );
//                if($val->notetime >= 60){
//                    $value["minutes"] = floor($val->notetime/60);
//                    $val->notetime = ($val->notetime%60);
//                }
//                $value["seconds"] = floor($val->notetime);
//                $val->notetime=$value["minutes"] .":".$value["seconds"]."";
//            }
//        }
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
        $userId = Auth::user()->id;
        $commentId = $request['parentId'];

        try {
            $content = Filter::filter($request['answer']);
        } catch (\Exception $e) {
            $content['content'] = $request['answer'];;
        }
        $stuId = DB::table('coursecomment')->where('id','=',$commentId)->first()->stuId;
        $courseId = DB::table('coursecomment')->where('id','=',$commentId)->first()->courseId;
        $username = DB::table('users')->where('id','=',$stuId)->first()->username;
        $courseName = DB::table('course')->where('id','=',$courseId)->first()->courseTitle;

//        dd($username);
        //您的关于xxx课程的提问已被老师回答
        $a = [
            'username'=>$username,
            'content'=>'您的关于'.$courseName.'课程的提问已被老师回答',
            'toUsername'=>\Auth::user()->username,
            'courseType'=>'1',
            'fromUsername'=>\Auth::user()->username,
            'actionId'=>$courseId,
            'type'=> 4,
            'created_at'=>Carbon::now()];
            DB::table('usermessage')->insertGetId($a);

//        $data = DB::table('usermessage')->insertGetId(['actionId'=>$courseId,'username'=>$username,'tempId'=>0,'content'=>$content,
//            'fromUsername'=>\Auth::user()->username,'type'=>4,'created_at'=>Carbon::now()]);


        if($res = DB::table('coursecomment')->where('id','=',$commentId)->update(['answer'=>$content,'teaId'=>$userId, 'anstime'=>Carbon::now(),'status' => '2' ])){
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false,]);
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




    /**
     * 尚未学习
     */
    public function getCourseStudyNo(Request $request){

        $condition = $request['condition'];
        $courseId = $request['courseId'];
        $where['cc.courseId'] = $courseId;

        //取出所有对应课程的学生
        $courseView = DB::table('courseview')->select('userId')->where('courseId','=',$courseId)->distinct()->get();
        if($courseView){
            $userId = [];
            foreach($courseView as $key => $val){
                foreach($val as $k=>$v){
                    $userId[] = $v;
                }
            }
        }

        //未学习的课程
        if($condition){
            $grade = [];
            $class = [];
            foreach($condition as $val) {
                $collection = explode('-',$val);
                $grade[] = $collection[0];
                $class[] = $collection[1];
            }
            $query = DB::table('course as c')
                ->leftJoin('courseclass as cc','c.id','=','cc.courseId')
                ->select('c.id','cc.gradeId','cc.classId')
                -> whereIn('cc.gradeId', $grade)
                -> whereIn('cc.classId', $class)
                ->where($where);
        } else {
            $grade = [];
            $class = [];
            $query = DB::table('course as c')
                ->leftJoin('courseclass as cc','c.id','=','cc.courseId')
                ->select('c.id','cc.gradeId','cc.classId')
                ->where($where);
        }


        $data = $query->get();
        $username = [];
        if($data && $courseView){
            foreach($data as $key => &$val){
                $result = DB::table('users')->select('id','username')->where('gradeId','=',$val->gradeId)->where('classId','=',$val->classId)->whereNotIn('id',$userId)->get();
                if($result){
                    $username[] = $result ;
                }
            }
        }

        if($username) {
            $user = [];
            foreach($username as $key => $value) {
                foreach($value as $k=> $v) {
                    $user[] = $v;
                }
            }
            $count = count($user);
        }else{
            $user = [];
        }

        if($user){
            return response()->json(['status'=>true,'data' => $user, 'count' => $count]);
        }else{
            return response()->json(['status'=>false,]);
        }

    }



    /**
     * 学习完成
     */
    public function getCourseStudyFinish(Request $request){

        $condition = $request['condition'];
        $courseId = $request['courseId'];
        $where['cc.courseId'] = $courseId;


        //取出所有对应课程的学生
        $courseView = DB::table('courseview')->select('userId')->where('courseId','=',$courseId)->where('type','=',2)->distinct()->get();
        if($courseView){
            $userId = [];
            foreach($courseView as $key => $val){
                foreach($val as $k=>$v){
                    $userId[] = $v;
                }
            }
        }
        //学习完成的课程
        if($condition){
            $grade = [];
            $class = [];
            foreach($condition as $val) {
                $collection = explode('-',$val);
                $grade[] = $collection[0];
                $class[] = $collection[1];
            }
            $query = DB::table('course as c')
                ->leftJoin('courseclass as cc','c.id','=','cc.courseId')
                ->select('c.id','cc.gradeId','cc.classId')
                -> whereIn('cc.gradeId', $grade)
                -> whereIn('cc.classId', $class)
                ->where($where);
        } else {
            $grade = [];
            $class = [];
            $query = DB::table('course as c')
                ->leftJoin('courseclass as cc','c.id','=','cc.courseId')
                ->select('c.id','cc.gradeId','cc.classId')
                ->where($where);
        }


        $data = $query->get();


        $username = [];
        if($data && $courseView){
            foreach($data as $key => &$val){
                $result = DB::table('users')->select('id','username')->where('gradeId','=',$val->gradeId)->where('classId','=',$val->classId)->whereIn('id',$userId)->get();
                if($result){
                    $username[] = $result ;
                }
            }
        }

        if($username) {
            $user = [];
            foreach($username as $key => $value) {
                foreach($value as $k=> $v) {
                    $user[] = $v;
                }
            }
            $count = count($user);
        }else{
            $user = [];
        }

        if($user){
            return response()->json(['status'=>true,'data' => $user,'count' => $count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    
    /**
     * 正在学习
     */
    public function getCourseStudySchedule(Request $request){
        $condition = $request['condition'];
        $courseId = $request['courseId'];
        $where['cc.courseId'] = $courseId;

        //取出所有对应课程的学生
        $courseView = DB::table('courseview')->select('userId')->where('courseId','=',$courseId)->where('type','=',1)->distinct()->get();
        if($courseView){
            $userId = [];
            foreach($courseView as $key => $val){
                foreach($val as $k=>$v){
                    $userId[] = $v;
                }
            }
        }


        //学习完成的课程
        if($condition){
            $grade = [];
            $class = [];
            foreach($condition as $val) {
                $collection = explode('-',$val);
                $grade[] = $collection[0];
                $class[] = $collection[1];
            }
            $query = DB::table('course as c')
                ->leftJoin('courseclass as cc','c.id','=','cc.courseId')
                ->select('c.id','cc.gradeId','cc.classId')
                -> whereIn('cc.gradeId', $grade)
                -> whereIn('cc.classId', $class)
                ->where($where);
        } else {
            $grade = [];
            $class = [];
            $query = DB::table('course as c')
                ->leftJoin('courseclass as cc','c.id','=','cc.courseId')
                ->select('c.id','cc.gradeId','cc.classId')
                ->where($where);
        }
        $data = $query->get();

        $username = [];
        if($data && $courseView){
            foreach($data as $key => &$val){
                $result = DB::table('users')->select('id','username')->where('gradeId','=',$val->gradeId)->where('classId','=',$val->classId)->whereIn('id',$userId)->get();
                if($result){
                    $username[] = $result ;
                }
            }
        }
        if($username) {
            $user = [];
            foreach($username as $key => $value) {
                foreach($value as $k=> $v) {
                    $v->learnNumber = DB::table('coursechapterview')->where('courseId','=',$courseId)->where('userId','=',$v->id)->count();  //已学习数
                    $data1 = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$courseId)->where('courseType','=',0)->get();
                    $dataUp = DB::table('coursechapter')->select('id', 'title as text')->where('courseId','=',$courseId)->where('courseType','=',1)->get();
                    if($dataUp){
                        $data2=[];
                        foreach($dataUp as $keys => &$vals){
                            $d = DB::table('coursechapter')->select('id', 'title as text')->where('parentId','=',$vals->id)->get();
                            if($d){
                                foreach($d as $key => $val){
                                    $data2[] = $val;
                                }
                            }
                        }
                    }
                    $data3 = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$courseId)->where('courseType','=',2)->get();
                    $dataSum = array_merge_recursive($data1,$data2,$data3);
                    $v->sumNumber = count($dataSum);
                    $user[] = $v;
                }
            }
        }else{
            $user = [];
        }
        if($user){
            return response()->json(['status'=>true,'data' => $user ]);
        }else{
            return response()->json(['status'=>false,]);
        }

    }



    /**
     *  选择下拉框数据
     */
    public function getCourseGradeClass(Request $request){
        $courseId = $request['courseId'];

        $data = DB::table('courseclass as cc')
            ->join('schoolgrade as sg','sg.id','=','cc.gradeId')
            ->join('schoolclass as sc','sc.id','=','cc.classId')
            ->select('gradeId','classId','gradeName','classname as className')->where('courseId','=',$courseId)->get();
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }




}
