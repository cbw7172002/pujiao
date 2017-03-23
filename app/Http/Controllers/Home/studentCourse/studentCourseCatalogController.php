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


class studentCourseCatalogController extends Controller{

    use Gadget;

    /**
     * 获取课程章节目录接口
     */
    public function getCourseChapter($courseId){
        $stuId = Auth::user()->id;

        $data = DB::table('course as c')
            ->leftJoin('coursechapter as cc','c.id','=','cc.courseId')
            ->select('cc.*')
            ->where('cc.courseId','=',$courseId)
            ->whereIn('cc.courseType',[0,1,2])
            ->get();

        $cView = [];
        $cViews = DB::table('courseview')->select('chapterId')->where('courseId','=',$courseId)->where('userId','=',$stuId)->get();
        foreach($cViews as $key => $val){
            $cView[] = $val->chapterId;
        }

        $icontype = ['doc','docx','xls','xlsx','ppt','pptx','pdf','swf'];
        if($data){
            $imageUrl = '/home/image/teacherCourse/detail/circle';
            //章
            foreach($data as $key => &$val){
                if($val->courseType == 1){
                    $val->chapter = DB::table('coursechapter as cc')->where('cc.parentId','=',$val->id)->get();
                }else{
                    $val->chapter = DB::table('coursechapter as cc')->where('cc.parentId','=',$val->id)->orderBy('cc.id','desc')->get();
                    $val->sumNumber = count($val->chapter);             //总视数
                    $val->haveLearned = 0;                              //学习数
                    $val->selectImage ='';
                    foreach($val->chapter as $k => $v){
                        if(in_array($v->id, $cView)){
                            $val->haveLearned++ ;
                        }
                    }
                    if($val->sumNumber == 1 and $val->haveLearned == 0){
                        $val->selectImage = $imageUrl.'0.png';     //空圈
                    }elseif($val->sumNumber == 1 and $val->haveLearned == 1){
                        $val->selectImage = $imageUrl.'4.png';;     //实体圈
                    }elseif($val->sumNumber == 2 and $val->haveLearned == 0){
                        $val->selectImage = $imageUrl.'0.png';;     //空圈
                    }elseif($val->sumNumber == 2 and $val->haveLearned == 1){
                        $val->selectImage = $imageUrl.'2.png';;     //半空圈
                    }elseif($val->sumNumber == 2 and $val->haveLearned == 2){
                        $val->selectImage = $imageUrl.'4.png';;     //实体圈
                    }elseif($val->sumNumber == 3 and $val->haveLearned == 0){
                        $val->selectImage = $imageUrl.'0.png';;     //空圈
                    }elseif($val->sumNumber == 3 and $val->haveLearned == 1){
                        $val->selectImage = $imageUrl.'1.png';;     //三分之一
                    }elseif($val->sumNumber == 3 and $val->haveLearned == 2){
                        $val->selectImage = $imageUrl.'3.png';;     //三分之二
                    }elseif($val->sumNumber == 3 and $val->haveLearned == 3){
                        $val->selectImage = $imageUrl.'4.png';;     //实体圈
                    }
                }

                //节
                if($val->chapter){
                    foreach($val->chapter as $key => &$val){
                        if(in_array(strtolower($val->courseFormat),$icontype)){
                            $val->icontype = 1;
                        }else{
                            $val->icontype = 0;
                        }
                        $val->node = DB::table('coursechapter as cc')->where('cc.parentId','=',$val->id)->orderBy('cc.id','desc')->get();
                        $val->sumNumberChapter = count($val->node);             //总视数
                        $val->haveLearnedChapter = 0;                           //学习数
                        $val->selectImageChapter ='';
                        foreach($val->node as $k => $v){
                            if(in_array($v->id, $cView)){
                                $val->haveLearnedChapter++ ;
                            }
                        }
                        if($val->sumNumberChapter == 1 and $val->haveLearnedChapter == 0){
                            $val->selectImageChapter = $imageUrl.'0.png';;     //空圈
                        }elseif($val->sumNumberChapter == 1 and $val->haveLearnedChapter == 1){
                            $val->selectImageChapter = $imageUrl.'4.png';;     //实体圈
                        }elseif($val->sumNumberChapter == 2 and $val->haveLearnedChapter == 0){
                            $val->selectImageChapter = $imageUrl.'0.png';;     //空圈
                        }elseif($val->sumNumberChapter == 2 and $val->haveLearnedChapter == 1){
                            $val->selectImageChapter = $imageUrl.'2.png';;     //半空圈
                        }elseif($val->sumNumberChapter == 2 and $val->haveLearnedChapter == 2){
                            $val->selectImageChapter = '/home/image/teacherCourse/detail/circle4.png';     //实体圈
                        }elseif($val->sumNumberChapter == 3 and $val->haveLearnedChapter == 0){
                            $val->selectImageChapter = '/home/image/teacherCourse/detail/circle0.png';     //空圈
                        }elseif($val->sumNumberChapter == 3 and $val->haveLearnedChapter == 1){
                            $val->selectImageChapter = '/home/image/teacherCourse/detail/circle1.png';     //三分之一
                        }elseif($val->sumNumberChapter == 3 and $val->haveLearnedChapter == 2){
                            $val->selectImageChapter = '/home/image/teacherCourse/detail/circle3.png';     //三分之二
                        }elseif($val->sumNumberChapter == 3 and $val->haveLearnedChapter == 3){
                            $val->selectImageChapter = '/home/image/teacherCourse/detail/circle3.png';     //实体圈
                        }
                        if($val->node){
                            foreach($val->node as &$val){
                                if(in_array(strtolower($val->courseFormat),$icontype)){
                                    $val->icontype = 1;
                                }else{
                                    $val->icontype = 0;
                                }
                            }
                        }
                    }
                }
            }

            $info = [
                'duidance'=>[],
                'teaching'=>[],
                'guidance'=>[]
            ];
            foreach ($data as &$item) {
                if($item->courseType == 0){
                    $info['duidance'][] = $item;
                }
                if($item->courseType == 1){
                    $info['teaching'][] = $item;
                }
                if($item->courseType == 2){
                    $info['guidance'][] = $item;
                }
            }

            if($info){
                return response()->json(['status'=>true,'data' => $info]);
            }else{
                return response()->json(['status'=>false,]);
            }

        }
    }


    /**
     * 获取我的笔记数据接口
     */
    public function getCourseMyNotes($courseId){
        $userId = \Auth::user()->id;
        $data = DB::table('users as u')
            ->leftJoin('notes as n','n.stuId','=','u.id')
            ->leftJoin('coursechapter as cc','n.chapterId','=','cc.id')
            ->select('n.*', 'u.username','u.pic','cc.courseFormat')
            ->where('n.courseId','=',$courseId)
            ->where('n.stuId','=',$userId)
//            ->where('n.public','=','0')
            ->orderBy('n.createtime','desc')
            ->limit(5)
            ->get();

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
     * 验证类型
     */
    public function courseJudgeType($chapterId){
        $courseFormat = DB::table('coursechapter')->select('courseFormat')->where('id','=',$chapterId)->first()->courseFormat;
        $icontype = ['doc','docx','xls','xlsx','ppt','pptx','pdf','swf'];
        if($courseFormat){
            $courseType = in_array($courseFormat,$icontype);
        }
        dd($chapterId);
        dd($courseType);
        if($courseType){
            return response()->json(['status'=>true,'courseType' => $courseType]);
        }else{
            return response()->json(['status'=>false,'courseType' => $courseType]);
        }
    }


    /**
     * 提交笔记内容
     */
    public function courseSubmitNote(Request $request){
        $userId = Auth::user()->id;
        $input = $request->all();
        try {
            $input['notecontent'] = Filter::filter($input['notecontent']);
        } catch (\Exception $e) {
            $input['notecontent'] = $request['notecontent'];
        }
        $input['createtime'] = Carbon::now();
        $input['stuId'] = $userId;
        $input['coursetype'] = DB::table('coursechapter')->where('id','=',$input['chapterId'])->first()->courseFormat;
        $courseType =$input['coursetype'];
        $chapterId = $input['chapterId'];
        $parChapterId = $input['parChapterId'];
        $parChapterId = $parChapterId - 1;
        if($parChapterId){
            $parChapterTitle = DB::table('coursechapter')->where('id','=',$parChapterId)->first()->title;
        }

        if($input['time']){
            if($courseType == 'mp4'){
                $notetime = floor($input['time']);
            }else{
                $notetime = 0;
            }
        }else{
            $notetime = 0;
        }
//        dd($input);
        $data = DB::table('notes')->insert(['courseId' => $input['courseId'],'public'=>$input['public'],'parchaptertitle' => $parChapterTitle ,'notetime'=>$notetime,'notecontent'=>$input['notecontent'],'stuId' => $userId , 'coursetype'=>$courseType,'chapterId' =>$chapterId,'createtime'=>Carbon::now()]);
        if($data){
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 获取共享数据接口
     */
    public function getCourseShareNote($courseId){
        $data = DB::table('users as u')
            ->leftJoin('notes as n','n.stuId','=','u.id')
            ->leftJoin('coursechapter as cc','n.chapterId','=','cc.id')
            ->select('n.*', 'u.username','u.pic','cc.courseFormat')
            ->where('n.courseId','=',$courseId)
            ->where('n.public','=','0')
            ->orderBy('n.createtime','desc')
            ->limit(5)
            ->get();
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
     * 获取课程问答
     */
    public function getCourseCommentCatalogAsk($courseId){
        $data = DB::table('coursecomment as c')
            ->leftjoin('users as u','u.id','=','c.stuId')
            ->select('c.id','c.content','u.username','c.answer','c.asktime','c.anstime','c.courseId','u.pic','c.teaId')
            ->where('c.courseId','=',$courseId)
            ->orderBy('c.asktime','desc')
            ->limit(5)
            ->get();
//        dd($data);
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
     * 获取视频资料接口getCourseVideo
     */
    public function getCourseVideo($chapterId){

        $data = DB::table('coursechapter')->where('id','=',$chapterId)->get();

        $icontype = ['doc','docx','xls','xlsx','ppt','pptx','pdf','swf'];
        if($data){
            foreach($data as $key => $val){
                $val->chapter = DB::table('coursechapter')->where('parentId','=',$val->id)->get();
                $val->countChapter = count($val->chapter);
                if($val->chapter){
                    foreach($val->chapter as $k => $v){
                        if(in_array(strtolower($v->courseFormat),$icontype)){
                            $v->icontype = 1;
                        }else{
                            $v->icontype = 0;
                        }
                    }
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
     * 默认取视频接口
     */
    public function getDefaultInfo($chapterId){

//        $data = DB::table('coursechapter')->where('parentId','=',$chapterId)->first();

        $data = DB::table('coursechapter')->where('id','=',$chapterId)->first();

        $data->courseFormat = strtolower($data->courseFormat);
        if($data->courseLowPath){
            $data->courseLowPath = $this->getPlayUrl($data->courseLowPath);
        }
        if($data->courseMediumPath){
            $data->courseMediumPath = $this->getPlayUrl($data->courseMediumPath);
        }
        if($data->courseHighPath){
            $data->courseHighPath = $this->getPlayUrl($data->courseHighPath);
        }
        //下载
        $download = $this->getdownload($data->fileID);
        if($download['code'] == '200'){
            $data->download = $download['data'];
        }else{
            $data->download = null;
        }
        //取出系统中文档是否需要转码
        $isTranscode = DB::table('systemsttings')->where('type',0)->pluck('isTrue');
        $data->isTranscode = $isTranscode;
//        dd($data);
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    /**
     * 取视频数据接口
     */
    public function getShowCourseVideo($chapterId){
        $data = DB::table('coursechapter')->where('id','=',$chapterId)->first();

        $data->courseFormat = strtolower($data->courseFormat);
        if($data->courseLowPath){
            $data->courseLowPath = $this->getPlayUrl($data->courseLowPath);
        }
        if($data->courseMediumPath){
            $data->courseMediumPath = $this->getPlayUrl($data->courseMediumPath);
        }
        if($data->courseHighPath){
            $data->courseHighPath = $this->getPlayUrl($data->courseHighPath);
        }

        //下载
        $download = $this->getdownload($data->fileID);
        if($download['code'] == '200'){
            $data->download = $download['data'];
        }else{
            $data->download = null;
        }

        //取出系统中文档是否需要转码
        $isTranscode = DB::table('systemsttings')->where('type',0)->pluck('isTrue');
        $data->isTranscode = $isTranscode;

        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    //视频播放
    public function courseOnPlay($chapterId,$courseId){
        $gradeId = Auth::user()->gradeId;
        $classId = Auth::user()->classId;
        $created_at = Carbon::now();
        $userId = Auth::user()->id;
        //取大章节id
        $chapId = DB::table('coursechapter')->where('id','=',$chapterId)->first()->parentId;
        $chapData = DB::table('coursechapter')->where('parentId','=',$chapId)->get();
        if($chapData){
            $chapDataId = [];
            foreach($chapData as $key => $val){
                $chapDataId[] = $val->id;
            }
        }
        $chapDataCount = count($chapDataId);

        $flag = DB::table('courseview')->where('courseId','=',$courseId)->where('userId','=',$userId)->where('chapterId','=',$chapterId)->first();
        //学习数 + 1
        DB::table('course')->where('id',$courseId)->increment('courseStudyNum');
        if(!$flag){
            $data = DB::table('courseview')->insert(['courseId' => $courseId , 'userId' => $userId , 'chapterId' =>$chapterId , 'created_at'=>$created_at,'type'=>'1']);
            if($data){
                //取出courseview中数据
                $n = null;
                $courseViewData = DB::table('courseview')->where('userId','=',$userId)->where('courseId','=',$courseId)->where('type','=',1)->get();
                if($courseViewData){
                    foreach($courseViewData as $key => $val){
                        if(in_array($val->chapterId,$chapDataId)){
                            $n++;
                        }
                    }
                    if($n == $chapDataCount){
                        DB::table('coursechapterview')->insert(['courseId'=>$courseId,'userId' => $userId, 'chapterId'=>$chapId,'created_at'=>$created_at]);
                    }
                }
                $chapterCount = DB::table('coursechapter')->where('courseId','=',$courseId)->where('courseFormat','!=','')->count();
                $learnCount = DB::table('courseview')->where('courseId','=',$courseId)->where('userId','=',$userId)->where('type','=','1')->count();
                if($chapterCount == $learnCount){
                    DB::table('courseview')->where('courseId','=',$courseId)->where('userId','=',$userId)->where('type','=','1')->update(['type'=>2]);
                }
            }else{
                $data = '';
            }
        }
        $upData = DB::table('courseclass')
            ->where('courseId','=',$courseId)
            ->where('gradeId','=',$gradeId)
            ->where('classId','=',$classId)
            ->update(['type' => 1]);
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    //打开文档
    public function courseDocument($chapterId,$courseId){
        $gradeId = Auth::user()->gradeId;
        $classId = Auth::user()->classId;
        $created_at = Carbon::now();
        $userId = Auth::user()->id;
        //取大章节id
        $chapId = DB::table('coursechapter')->where('id','=',$chapterId)->first()->parentId;
        $chapData = DB::table('coursechapter')->where('parentId','=',$chapId)->get();
        if($chapData){
            $chapDataId = [];
            foreach($chapData as $key => $val){
                $chapDataId[] = $val->id;
            }
        }
        $chapDataCount = count($chapDataId);


        $icontype = ['doc','docx','xls','xlsx','ppt','pptx','pdf','swf'];
        $format = DB::table('coursechapter')->where('id','=',$chapterId)->first()->courseFormat;
        if(in_array($format,$icontype)){
            $flag = DB::table('courseview')->where('courseId','=',$courseId)->where('userId','=',$userId)->where('chapterId','=',$chapterId)->get();
            //学习数 + 1
            DB::table('course')->where('id',$courseId)->increment('courseStudyNum');
            if(!$flag){
                $data = DB::table('courseview')->insertGetId(['courseId' => $courseId , 'userId' => $userId , 'chapterId' =>$chapterId , 'created_at'=>$created_at,'type'=>'1']);
                if($data){
                    //取出courseview中数据
                    $n = null;
                    $courseViewData = DB::table('courseview')->where('userId','=',$userId)->where('courseId','=',$courseId)->where('userId','=',$userId)->where('type','=',1)->get();
                    if($courseViewData){
                        foreach($courseViewData as $key => $val){
                            if(in_array($val->chapterId,$chapDataId)){
                                $n++;
                            }
                        }
                        if($n == $chapDataCount){
                            DB::table('coursechapterview')->insert(['courseId'=>$courseId,'userId' => $userId, 'chapterId'=>$chapId,'created_at'=>$created_at]);
                        }
                    }
                    $chapterCount = DB::table('coursechapter')->where('courseId','=',$courseId)->where('courseFormat','!=','')->count();
                    $learnCount = DB::table('courseview')->where('courseId','=',$courseId)->where('userId','=',$userId)->where('type','=','1')->count();
                    if($chapterCount == $learnCount){
                        DB::table('courseview')->where('courseId','=',$courseId)->where('userId','=',$userId)->where('type','=','1')->update(['type'=>2]);
                    }
                }
            }else{
                $data = '';
            }
        }
        $upData = DB::table('courseclass')
            ->where('courseId','=',$courseId)
            ->where('gradeId','=',$gradeId)
            ->where('classId','=',$classId)
            ->update(['type' => 1]);
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    //获取贴士绑定时间
    public function courseTipsTime($chapterId,$courseId){
        $teacherId = DB::table('course')->where('id','=',$courseId)->first()->teacherId;

        $data = DB::table('tips')->select('id','tipstime','tipscontent')->where('teaId','=',$teacherId)->where('courseId','=',$courseId)->where('chapterId','=',$chapterId)->get();
//        dd($data);
        if($data){
            $d = [];
            foreach($data as $key => $val){
                $d[$val->tipstime] = $val->tipscontent;
                $d[$val->tipstime.'isShow'] = false;
                $d[$val->tipstime.'id'] = $val->id;
            }
        }else{
            $d = [];
        }
//        dd($d);
        if($d){
            return response()->json(['status'=>true,'data' => $d]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    //获取对应贴士个数
    public function getCountTip($chapterId){
        $tipsCount = DB::table('tips')->where('chapterId','=',$chapterId)->get();
        $data = count($tipsCount);

        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


}