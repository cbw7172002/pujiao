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
use Primecloud\Pay\Weixin\Kernel\WxPayConfig;
use Primecloud\Pay\Weixin\Kernel\WxPayApi;
use Primecloud\Pay\Weixin\Kernel\WxPayDataBase;
use Primecloud\Pay\Weixin\Kernel\WxPayUnifiedOrder;
use Primecloud\Pay\Weixin\Kernel\WxPayResults;
use App\Http\Controllers\Home\lessonComment\Gadget;
use Filter;


class studentCourseController extends Controller{

    public function studentCourseList(){

        return view('home.studentCourse.studentCourseList');
    }


    /**
     * 我的课程(等待学习)
     */
    public function getListMyCourse(Request $request){
        $stuId = Auth::user()->id;
        $gradeId = Auth::user()->gradeId;
        $classId = Auth::user()->classId;
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber-1) * $pageSize;


        $cView = [];
        $cViews = DB::table('courseview')->select('courseId')->where('userId','=',$stuId)->distinct()->get();
        foreach($cViews as $key => $val){
            $cView[] = $val->courseId;
        }

        $d = DB::table('course as c')
            ->leftJoin('courseclass as cc','cc.courseId','=','c.id')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username','u.type','cc.classId')
            ->skip($skip)->take($pageSize)
            ->where('cc.gradeId','=',$gradeId)
            ->where('cc.classId','=',$classId)
            ->where('c.courseStatus','0')
            ->where('c.courseTitle','like','%' .$request['condition'] .'%')
            ->distinct('c.courseTitle')
            ->get();

        if($d){
            $data = [];
            foreach($d as $key => $val){
                if(!in_array($val->id, $cView)){
                    $data[] = $val;
                }
            }
        }

        $c = DB::table('course as c')
            ->leftJoin('courseclass as cc','cc.courseId','=','c.id')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username','u.type')
            ->where('cc.gradeId','=',$gradeId)
            ->where('cc.classId','=',$classId)
            ->where('c.courseStatus','0')
            ->distinct('c.courseTitle')
            ->get();

        if($c){
            $count = [];
            foreach($c as $key => $val){
                if(!in_array($val->id, $cView)){
                    $count[] = $val;
                }
            }
        }
        if($c){
            $count = count($c);
        }
        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false]);
        }
    }


    /**
     * 正在学习
     */
    public function getListMyCourseUnderway(Request $request){
        $stuId = Auth::user()->id;
        $gradeId = Auth::user()->gradeId;
        $classId = Auth::user()->classId;
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber-1) * $pageSize;

        $data = DB::table('course as c')
            ->leftJoin('courseclass as cc','cc.courseId','=','c.id')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->leftJoin('courseview as cv','cv.courseId','=','cc.courseId')
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username','u.type')
            ->skip($skip)->take($pageSize)
            ->where('cc.gradeId','=',$gradeId)
            ->where('cc.classId','=',$classId)
            ->where('cv.userId','=',$stuId)
            ->where('cv.type','=',1)
//            ->where('cc.type','!=',0)
            ->distinct('cv.courseId')
            ->get();
        //取总条数和学习课程的条数
        if($data){
            foreach($data as $key => $val){
                $val->learnNumber = DB::table('coursechapterview')->where('courseId','=',$val->id)->where('userId','=',$stuId)->count();  //已学习数
                $data1 = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$val->id)->where('courseType','=',0)->get();
                $dataUp = DB::table('coursechapter')->select('id', 'title as text')->where('courseId','=',$val->id)->where('courseType','=',1)->get();
                if($dataUp){
                    $data2=[];
                    foreach($dataUp as $keys => &$vals){
                        $d = DB::table('coursechapter')->select('id', 'title as text')->where('parentId','=',$vals->id)->get();
                        if($d){
                            foreach($d as $k => $v){
                                $data2[] = $v;
                            }
                        }
                    }
                }
                $data3 = DB::table('coursechapter')->select('id','title as text')->where('courseId','=',$val->id)->where('courseType','=',2)->get();
                $dataSum = array_merge_recursive($data1,$data2,$data3);
                $val->sumNumber = count($dataSum);
//                $val->sumNumber = DB::table('coursechapter')->where(['courseId'=>$val->id, 'courseType' => 0,'courseFormat' => null])->orWhere(['courseId'=>$val->id, 'courseType' => null,'courseFormat' => null])->count();
            }
        }


        $count = count($data);

        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false]);
        }

    }


    /**
     * 学习完成
     */
    public function getListMyCourseFinish(Request $request){
        $stuId = Auth::user()->id;
        $gradeId = Auth::user()->gradeId;
        $classId = Auth::user()->classId;
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber-1) * $pageSize;

        $data = DB::table('course as c')
            ->leftJoin('courseclass as cc','cc.courseId','=','c.id')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->leftJoin('courseview as cv','cv.courseId','=','cc.courseId')
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username','u.type')
            ->skip($skip)->take($pageSize)
            ->where('cc.gradeId','=',$gradeId)
            ->where('cc.classId','=',$classId)
            ->where('cv.userId','=',$stuId)
            ->where('cv.type','=','2')
            ->distinct('cv.courseId')
            ->get();

        $count = DB::table('course as c')
            ->leftJoin('courseclass as cc','cc.courseId','=','c.id')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->leftJoin('courseview as cv','cv.courseId','=','cc.courseId')
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username','u.type')
            ->where('cc.gradeId','=',$gradeId)
            ->where('cc.classId','=',$classId)
            ->where('cv.type','=','2')
            ->distinct('cv.courseId')
            ->get();

        $count = count($count);

//        $count = DB::table('course as c')
//            ->leftJoin('courseclass as cc','cc.courseId','=','c.id')
//            ->leftJoin('courseview as cv','cv.courseId','=','cc.courseId')
//            ->where('cc.gradeId','=',$gradeId)
//            ->where('cc.classId','=',$classId)
//            ->where('cv.type','=','2')
//            ->count();

        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false]);
        }
    }


    /**
     * 全部课程数据接口
     */
    public function getListAllCourse(Request $request){
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $type = $request['type'];
        $gradeId = $request['gradeId'];
        $subjectId = $request['subjectId'];
        $bookId = $request['bookId'];
        $editionId = $request['editionId'];
        $skip = ($pageNumber-1) * $pageSize;

        $query = DB::table('course as c')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->where('c.courseStatus','=',0)
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username','u.type')->where('c.courseTitle','like','%' .$request['condition'] .'%');

        if ($gradeId == 'all' && $subjectId == 'all' && $bookId == 'all' && $editionId == 'all') {
            $query = $query->skip($skip)->take($pageSize);
            $count = $query->count();
        }else if($gradeId !== 'all' && $subjectId == 'all' && $bookId == 'all' && $editionId == 'all'){
            $query = $query->whereIn('c.gradeId', $gradeId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.gradeId', $gradeId)->count();
        }else if($gradeId == 'all' && $subjectId !== 'all' && $bookId == 'all' && $editionId == 'all'){
            $query = $query->whereIn('c.subjectId', $subjectId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.subjectId', $subjectId)->count();
        }else if($gradeId == 'all' && $subjectId == 'all' && $bookId !== 'all' && $editionId == 'all'){
            $query = $query->whereIn('c.book', $bookId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.book', $bookId)->count();
        }else if($gradeId == 'all' && $subjectId == 'all' && $bookId == 'all' && $editionId !== 'all'){
            $query = $query->whereIn('c.editionId', $editionId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.editionId', $editionId)->count();
        }else if($gradeId !== 'all' && $subjectId !== 'all' && $bookId == 'all' && $editionId == 'all'){
            $query = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.subjectId', $subjectId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.subjectId', $subjectId)->count();
        }else if($gradeId !== 'all' && $subjectId == 'all' && $bookId !== 'all' && $editionId == 'all'){
            $query = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.bookId', $bookId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.bookId', $bookId)->count();
        }else if($gradeId !== 'all' && $subjectId == 'all' && $bookId == 'all' && $editionId !== 'all'){
            $query = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.editionId', $editionId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.editionId', $editionId)->count();
        }else if($gradeId == 'all' && $subjectId !== 'all' && $bookId !== 'all' && $editionId == 'all'){
            $query = $query->whereIn('c.subjectId', $subjectId)->whereIn('c.bookId', $bookId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.subjectId', $subjectId)->whereIn('c.bookId', $bookId)->count();
        }else if($gradeId == 'all' && $subjectId !== 'all' && $bookId == 'all' && $editionId !== 'all'){
            $query = $query->whereIn('c.subjectId', $subjectId)->whereIn('c.editionId', $editionId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.subjectId', $subjectId)->whereIn('c.editionId', $editionId)->count();
        }else if($gradeId == 'all' && $subjectId == 'all' && $bookId !== 'all' && $editionId !== 'all'){
            $query = $query->whereIn('c.bookId', $bookId)->whereIn('c.editionId', $editionId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.bookId', $bookId)->whereIn('c.editionId', $editionId)->count();
        }else if($gradeId !== 'all' && $subjectId !== 'all' && $bookId !== 'all' && $editionId == 'all'){
            $query = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.subjectId', $subjectId)->whereIn('c.bookId', $bookId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.subjectId', $subjectId)->whereIn('c.bookId', $bookId)->count();
        }else if($gradeId !== 'all' && $subjectId !== 'all' && $bookId == 'all' && $editionId !== 'all'){
            $query = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.subjectId', $subjectId)->whereIn('c.editionId', $editionId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.subjectId', $subjectId)->whereIn('c.editionId', $editionId)->count();
        }else if($gradeId !== 'all' && $subjectId == 'all' && $bookId !== 'all' && $editionId !== 'all'){
            $query = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.bookId', $bookId)->whereIn('c.editionId', $editionId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.bookId', $bookId)->whereIn('c.editionId', $editionId)->count();
        }else if($gradeId !== 'all' && $subjectId !== 'all' && $bookId !== 'all' && $editionId !== 'all'){
            $query = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.subjectId', $subjectId)->whereIn('c.bookId', $bookId)->whereIn('c.editionId', $editionId)->skip($skip)->take($pageSize);
            $count = $query->whereIn('c.gradeId', $gradeId)->whereIn('c.subjectId', $subjectId)->whereIn('c.bookId', $bookId)->whereIn('c.editionId', $editionId)->count();
        }


        if($type == 1){
            $data = $query->orderBy('c.courseStudyNum','desc')->get();
        }else if($type == 2){
            $data = $query->orderBy('c.created_at','desc')->get();
        }else{
            $data = $query->orderBy('c.courseStudyNum','desc')->get();
        }
        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false]);
        }
    }



    /**
     * 学生详情页
     */
    public function studentCourseDetail($id){
        $userId = Auth::user()->id;

        $data = DB::table('course as c')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username','u.type')
            ->where('c.id','=',$id)
            ->orderBy('c.created_at','desc')
            ->first();
        $isset_courseFav = DB::table('resourcestore')->where('resourceId','=',$id)->where('userId','=',$userId)->first();
        if($isset_courseFav){
            return view('home.studentCourse.studentCourseDetail')->with('data',$data)->with('isset_courseFav',$isset_courseFav)->with('userId', $userId);
        }else{
            return view('home.studentCourse.studentCourseDetail')->with('data',$data)->with('userId', $userId);
        }
    }


    /**
     * 学生课程目录详情页
     */
    public function studentCourseCatalog($id,$chapterId){
        $data = DB::table('course as c')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username','u.type')
            ->where('c.id','=',$id)
            ->orderBy('c.created_at','desc')
            ->first();
        //获取课程对应的老师

        if($data){
            return view('home.studentCourse.studentCourseCatalog')->with('data',$data)->with('chapterId',$chapterId);
        }
    }


}