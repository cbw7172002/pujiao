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


class teacherCourseController extends Controller{

    use Gadget;
    /**
     * 教师列表页
     */
    public function teacherCourseList(){
        $teacherId = Auth::user()->id;
        $subject = DB::table('teachersubject as ts')
            ->leftJoin('studysubject as s','ts.subjectId','=','s.id')
            ->select('s.id','s.subjectName')
            ->where('ts.tId','=',$teacherId)
            ->distinct()
            ->orderBy('ts.id','desc')
            ->get();
//        dd($subject);
        $subjectAll = DB::table('course as c')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->select('s.id','s.subjectName')
            ->distinct('s.subjectName')
            ->orderBy('s.id','asc')
            ->get();

        $default = DB::select('select default(courseStatus) as defaultStatus from course limit 1');
        $default = $default[0]->defaultStatus;

        //获取最小
        if($subject){
            $minSubjectId = min($subject)->id;
            return view('home.teacherCourse.teacherCourseList')->with('subject',$subject)->with('minSubjectId',$minSubjectId)->with('default',$default);
        }else{
            return view('home.teacherCourse.teacherCourseList')->with('subject',$subject)->with('subjectAll',$subjectAll)->with('default',$default);
        }

//        return view('home.teacherCourse.teacherCourseList')->with('subject',$subject)->with('minSubjectId',$minSubjectId);
    }


    /**
     * 我的课程数据接口
     */
    public function getListMyCourse(Request $request, $type = '',$pageNumber,$pageSize){
        $skip = ($pageNumber-1) * $pageSize;

        $teacherId = Auth::user()->id;
        if(is_numeric($type)){
            $where = $type;
        }else{
            $where = '0';
        }


        $query = DB::table('course as c')
            ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
            ->leftJoin('studysubject as s','c.subjectId','=','s.id')
            ->leftJoin('studyedition as e','c.editionId','=','e.id')
            ->leftJoin('studyebook as b','c.bookId','=','b.id')
            ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname')
            ->skip($skip)->take($pageSize)
            ->where('c.teacherId',$teacherId)
            ->where('c.courseTitle','like','%' .$request['condition'] .'%');

        $default = DB::select('select default(courseStatus) as defaultStatus from course limit 1');
        $default = $default[0]->defaultStatus;
        if($default == 1){
            if($where == 0){         //已发布
                $data = $query->where('c.courseStatus','=','0')->get();
                $count = DB::table('course')->where('teacherId',$teacherId)->where('courseStatus','=','0')->count();
            }else if($where == 1){  //审核中
//                $data = $query->where('c.courseStatus',1)->get();
                $data = $query->whereIn('c.courseStatus',[1,7])->get();
                $count = DB::table('course')->where('teacherId',$teacherId)->whereIn('courseStatus',[1,7])->count();
//                $count = DB::table('course')->where('teacherId',$teacherId)->where('courseStatus',1)->count();
            }else if($where == 2){  //未通过
                $data = $query->where('c.courseStatus','=','2')->get();
                $count = DB::table('course')->where('teacherId',$teacherId)->where('courseStatus','=','2')->count();
            }else if($where == 3){  //待发布
                $data = $query->whereIn('c.courseStatus',[3,4])->get();
                $count = DB::table('course')->where('teacherId',$teacherId)->whereIn('courseStatus',[3,4])->count();
            }
        }elseif($default == 0){
            if($where == 0){
                $data = $query->whereIn('courseStatus',[0,7])->get();
                $count = DB::table('course')->where('teacherId',$teacherId)->whereIn('courseStatus',[0,7])->count();
            }else if($where == 3){
                $data = $query->whereIn('c.courseStatus',[3,4,6])->get();
                $count = DB::table('course')->where('teacherId',$teacherId)->whereIn('courseStatus',[3,4,6])->count();
            }
        }


        if($data){
            //查看表字段(courseStatus)的默认值
            $default = DB::select('select default(courseStatus) as defaultStatus from course limit 1');

            $type = ['png','jpg','jpeg','pdf','swf'];
            $document = ['doc','docx','xls','xlsx','ppt','pptx'];

            foreach($data as &$val){
                $transcod = true;
                $chapter = DB::table('coursechapter')->where('courseId',$val->id)->orderBy('id','desc')->get();
                if($chapter){
                    foreach($chapter as &$valChapter){

                        if(!in_array(strtolower($valChapter->courseFormat),$type)){
                            if(!$valChapter->courseLowPath || !$valChapter->courseMediumPath || !$valChapter->courseHighPath){
                                if($valChapter->fileID && $valChapter->parentId != 0){

                                    if(strtolower($valChapter->courseFormat) == 'mp3'){
                                        $convertype = 1; //音频
                                    }elseif(in_array(strtolower($valChapter->courseFormat),$document)){
                                        $convertype = 2; //文档
                                    }else{
                                        $convertype = 0; //视频
                                    }

                                    $FileList = $this->transformations($valChapter->fileID,$convertype);
//                                dd($FileList);

                                    //转换失败
                                    if($FileList['code'] == 503){
                                        DB::table('course')->where('id',$valChapter->courseId)->update(['courseStatus'=>6]);
                                    }
                                    //正在转码
                                    if($FileList['code'] == 200 && $FileList['data']['Waiting'] == 0){
                                        $transcod = false;
                                    }

                                    if($FileList['code'] == 200 && $FileList['data']['Waiting'] < 0){
                                        $filelists = $FileList['data']['FileList']; //取出转好的码
                                        $lists = [];
                                        foreach($filelists as $value){
                                            switch($value['Level']){
                                                case 0:
                                                    $lists['courseLowPath'] = $value['FileID'];
                                                    break;
                                                case 1:
                                                    $lists['courseLowPath'] = $value['FileID'];
                                                    break;
                                                case 2:
                                                    $lists['courseMediumPath'] = $value['FileID'];
                                                    break;
                                                case 3:
                                                    $lists['courseHighPath'] = $value['FileID'];
                                                    break;
                                            }
                                        }
                                        if($lists){
                                            DB::table('coursechapter')->where('id',$valChapter->id)->update($lists);
                                            $courseStatus = DB::table('course')->where('id',$val->id)->pluck('courseStatus');
                                            if($courseStatus == 7){
                                                DB::table('course')->where('id',$val->id)->update(['courseStatus'=>$default[0]->defaultStatus]);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
                //将正在转码的字段记录
                if($transcod == false){
                    DB::table('course')->where('id',$val->id)->update(['courseStatus'=>7]);
                }

            }
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false]);
        }
    }

    /**
     * 删除课程
     */
    public function deleteCourse($courseId){
        $data = DB::table('course')->where('id','=',$courseId)->delete();
        if($data){
            DB::table('coursechapter')->where('courseId','=',$courseId)->delete();
            return response()->json(['status'=>true,'data'=>$data]);
        }else{
            return response()->json(['status'=>false]);
        }
    }


    /**
     * 推荐课程数据接口
     */
    public function getListRecCourse(Request $request,$subjectId,$pageNumber,$pageSize,$minId){
        $skip = ($pageNumber-1) * $pageSize;

        $teacherId = Auth::user()->id;
        if(is_numeric($subjectId)){
            $where = $subjectId;
        }else{
            $where = $minId;
        }


        $data = DB::table('teachersubject')->where('tId','=',$teacherId)->where('subjectId','=',$where)->get();
//        dd($data);
        if($data){
            $rec = [];
            foreach($data as $key => &$val){
                $result = DB::table('course as c')
                    ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
                    ->leftJoin('studysubject as s','c.subjectId','=','s.id')
                    ->leftJoin('studyedition as e','c.editionId','=','e.id')
                    ->leftJoin('studyebook as b','c.bookId','=','b.id')
                    ->leftJoin('users as u','u.id','=','c.teacherId')
                    ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username')
                    ->where('c.gradeId','=',$val->gradeId)
                    ->where('c.subjectId','=',$val->subjectId)
                    ->where('c.editionId','=',$val->editionId)
                    ->where('c.bookId','=',$val->bookId)
//                    ->where('c.teacherId','=',$teacherId)
                    ->where('c.courseStatus','=',0)
                    ->get();
//                $data[$key] -> rec = $result ? $result : '';
                if($result){
                    $rec[] = $result ;
                }
            }
        }
//        dd($rec);

//        $count = DB::table('teachersubject')->where('tId','=',$teacherId)->count();
        $count = count($rec);
        if($rec){
            return response()->json(['status'=>true,'data'=>$rec,'count'=>$count]);
        }else{
            return response()->json(['status'=>false]);
        }

    }


    //取年级
    public function getGradeCourse(){
        $data = DB::table('schoolgrade')->select('id','gradeName')->where('status','=',1)->get();
        if($data){
            return response()->json(['status'=>true,'data'=>$data]);
        }else{
            return response()->json(['status'=>false]);
        }
    }
    //取学科
    public function getSubjectCourse(){
        $data = DB::table('studysubject')->select('id','subjectName')->get();
        if($data){
            return response()->json(['status'=>true,'data'=>$data]);
        }else{
            return response()->json(['status'=>false]);
        }
    }
    //取册别
    public function getBookCourse(){
        $data = DB::table('studyebook')->select('id','bookName')->get();
        if($data){
            return response()->json(['status'=>true,'data'=>$data]);
        }else{
            return response()->json(['status'=>false]);
        }
    }
    //取版本
    public function getEditionCourse(){
        $data = DB::table('studyedition')->select('id','editionName')->get();
        if($data){
            return response()->json(['status'=>true,'data'=>$data]);
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
     * 教师详情页
     */
    public function teacherCourseDetail($id){
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
        if (!$data) {
            return view('home.teacherCourse.courseFalse');
        }
        $isset_courseFav = DB::table('resourcestore')->where('resourceId','=',$id)->where('userId','=',$userId)->first();
        //取课程所属于哪个老师
        $teacherId = DB::table('course')->select('teacherId')->where('id','=',$id)->pluck('teacherId');
        if($isset_courseFav){
            return view('home.teacherCourse.teacherCourseDetail')->with('data',$data)->with('isset_courseFav',$isset_courseFav)->with('teacherId',$teacherId);
        }else{
            return view('home.teacherCourse.teacherCourseDetail')->with('data',$data)->with('teacherId',$teacherId);
        }
    }


    /**
     * 收藏接口
     */
    public function collectionCourse($courseId){

        $userId = Auth::user()->id;
        $created_at = Carbon::now();
        $resourcetitle = DB::table('course')->select('courseTitle')->where('id',$courseId)->first()->courseTitle;
        $data = DB::table('resourcestore')->insert(['resourceId'=>$courseId,'userId'=>$userId,'type'=>'1','created_at'=>$created_at, 'resourcetitle'=>$resourcetitle]);
        if($data){
            DB::table('course')->where('id','=',$courseId)->increment('courseFav', 1);
            return response() -> json(['data' => $data, 'status' => true]);
        }else{
            return response() -> json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 取消收藏接口
     */
    public function collectionCourseDel($courseId){
        $userId = Auth::user()->id;
        $data = DB::table('resourcestore')->where('resourceId','=',$courseId)->where('userId','=',$userId)->delete();
        if($data){
            DB::table('course')->where('id','=',$courseId)->decrement('courseFav', 1);
            return response() -> json(['data' => $data, 'status' => true]);
        }else{
            return response() -> json(['data' => false, 'status' => false]);
        }
    }






    /**
     * 教师课程目录详情页
     */
    public function teacherCourseCatalog($id,$chapterId){
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

        $teacherId = DB::table('course')->select('teacherId')->where('id','=',$id)->first()->teacherId;


        if($data){
            return view('home.teacherCourse.teacherCourseCatalog')->with('data',$data)->with('chapterId',$chapterId)->with('teacherId',$teacherId);
        }else{
            return view('home.teacherCourse.teacherCourseDetail')->with('data',$data)->with('teacherId',$teacherId);
        }

    }

}