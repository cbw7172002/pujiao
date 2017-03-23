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
use Primecloud\Pay\Weixin\Kernel\WxPayConfig;
use Primecloud\Pay\Weixin\Kernel\WxPayApi;
use Primecloud\Pay\Weixin\Kernel\WxPayDataBase;
use Primecloud\Pay\Weixin\Kernel\WxPayUnifiedOrder;
use Primecloud\Pay\Weixin\Kernel\WxPayResults;
use App\Http\Controllers\Home\lessonComment\Gadget;
use Filter;



class createCourseController extends Controller{

    /**
     * 获取课程归属接口
     */
    public function getType(){
        $teacherId = Auth::user()->id;
        $subject = DB::table('teachersubject')->select('gradeId','subjectId','editionId','bookId')->where('tId',$teacherId)->orderBy('id','asc')->get();
        foreach ($subject as $v){
            $gradeNm = DB::table('schoolgrade')->select('gradeName')->where('id',$v->gradeId)->pluck('gradeName');
            $subjectNm = DB::table('studysubject')->select('subjectName')->where('id',$v->subjectId)->pluck('subjectName');
            $editionNm = DB::table('studyedition')->select('editionName')->where('id',$v->editionId)->pluck('editionName');
            $bookNm = DB::table('studyebook')->select('bookName')->where('id',$v->bookId)->pluck('bookName');
            $arr[] = ['id' => $v->gradeId.','.$v->subjectId.','.$v->bookId.','.$v->editionId,'text'=> $gradeNm.' - '.$subjectNm.' - '.$editionNm.' - '.$bookNm];
        }
        return response()->json($arr);
    }

    /**
     * 获取课程章节接口
     */
    public function getChapter($ids){
        $ids = explode(',',$ids);
        $data = DB::table('chapter')->select('id','chapterName as text')->where(['gradeId'=>$ids[0],'subjectId'=>$ids[1],'editionId'=>$ids[2],'bookId'=>$ids[3]])->get();
        return response()->json($data);
    }

    /**
     * 添加课程基本信息接口
     */
    public function addCourseInfo(){
        //dd($_POST);
        try{
            $_POST['data']['courseTitle'] = Filter::filter($_POST['data']['courseTitle']);
            $_POST['data']['courseIntro'] = Filter::filter($_POST['data']['courseIntro']);
            $_POST['data']['courseContent'] = Filter::filter($_POST['data']['courseContent']);
        }catch (Exception $e){
            Log::info('添加课程基本信息敏感词过滤报错：'.$e->getMessage());
        }
        if(!$_POST['courseId']){ //添加课程
            $_POST['data']['courseStatus'] = 3;
            $_POST['data']['teacherId'] = Auth::user()->id;
            if($courseId = DB::table('course')->insertGetId($_POST['data'])) return response()->json(['type'=>true,'courseId'=>$courseId]);
            else return response()->json(['type'=>false]);
        }else{ //编辑课程
            DB::table('course')->where('id',$_POST['courseId'])->update($_POST['data']);
            return response()->json(['type'=>true,'courseId'=>$_POST['courseId']]);
        }

    }

    /**
     * 添加课程接口
     */
    public function addCourse(){
        //dd($_POST);
        //添加课前导学
        if(isset($_POST['prelearnInfo'])){
            foreach ( $_POST['prelearnInfo'] as $a_a){
                if(isset($a_a['id'])){
                    //更新导学title
                    DB::table('coursechapter')->where('id', $a_a['id'])->update(['title' => $a_a['title'],'paperId'=>$a_a['paperId']]);
                    //循环更新每个文件
                    foreach ($a_a['dataInfo'] as $a_b){
                        if(isset($a_b['id'])){//更新该文件
                            //DB::table('coursechapter')->where('id', $a_b['id'])->update(['title'=>$a_b['title'], 'courseFormat'=>$a_b['courseFormat'], 'fileID'=>$a_b['fileID'], 'courseTime'=>$a_b['courseTime'], 'tipatime'=>$a_b['tipatime'], 'tipbtime'=>$a_b['tipbtime'], 'tipctime'=>$a_b['tipctime'], 'tipacon'=>$a_b['tipacon'], 'tipbcon'=>$a_b['tipbcon'], 'tipccon'=>$a_b['tipccon']]);
                            DB::table('coursechapter')->where('id', $a_b['id'])->update(['title'=>$a_b['title'], 'courseFormat'=>$a_b['courseFormat'], 'fileID'=>$a_b['fileID']]);
                        }else{//添加新文件
                            $a_b['parentId'] = $a_a['id'];
                            $a_b['courseId'] = $_POST['courseId'];
                            DB::table('coursechapter')->insertGetId($a_b);
                        }
                    }
                }else{ //添加导学
                    if($pid = DB::table('coursechapter')->insertGetId(['courseId'=>$a_a['courseId'],'courseType'=>$a_a['courseType'],'title'=>$a_a['title'],'paperId'=>$a_a['paperId']])){
                        foreach ($a_a['dataInfo'] as $a_b){
                            $a_b['parentId'] = $pid;
                            $a_b['courseId'] = $_POST['courseId'];
                            DB::table('coursechapter')->insertGetId($a_b);
                        }
                    }
                }
            }
        }
        //添加课堂授课
        if(isset($_POST['chaprerInfo'])){
            foreach ($_POST['chaprerInfo'] as $b_a){
                if(isset($b_a['id'])){
                    //更新章title
                    DB::table('coursechapter')->where('id',$b_a['id'])->update(['title' => $b_a['title'],'paperId'=>$b_a['paperId']]);
                    //循环更新节名称
                    foreach ($b_a['nodeInfo'] as $b_b){
                        if(isset($b_b['id'])){
                            //更新节title
                            DB::table('coursechapter')->where('id',$b_b['id'])->update(['title' => $b_b['title']]);
                            foreach ($b_b['dataInfo'] as $b_c){
                                if(isset($b_c['id'])){ //更新文件
                                    //DB::table('coursechapter')->where('id', $b_c['id'])->update(['title'=>$b_c['title'],'courseFormat'=>$b_c['courseFormat'],'fileID'=>$b_c['fileID'], 'courseTime'=>$a_b['courseTime'], 'tipatime'=>$a_b['tipatime'], 'tipbtime'=>$a_b['tipbtime'], 'tipctime'=>$a_b['tipctime'], 'tipacon'=>$a_b['tipacon'], 'tipbcon'=>$a_b['tipbcon'], 'tipccon'=>$a_b['tipccon']]);
                                    DB::table('coursechapter')->where('id', $b_c['id'])->update(['title'=>$b_c['title'],'courseFormat'=>$b_c['courseFormat'],'fileID'=>$b_c['fileID']]);
                                }else{ //添加文件
                                    $b_c['parentId'] = $b_b['id'];
                                    $b_c['courseId'] = $_POST['courseId'];
                                    DB::table('coursechapter')->insertGetId($b_c);
                                }
                            }
                        }else{
                            if($ppid = DB::table('coursechapter')->insertGetId(['title'=>$b_b['title'],'parentId'=>$b_a['id'],'courseId'=>$_POST['courseId']])){
                                foreach ($b_b['dataInfo'] as $b_c){
                                    $b_c['parentId'] = $ppid;
                                    $b_c['courseId'] = $_POST['courseId'];
                                    DB::table('coursechapter')->insertGetId($b_c);
                                }
                            }
                        }
                    }
                }else{ //添加授课
                    if($pid = DB::table('coursechapter')->insertGetId(['courseId'=>$b_a['courseId'],'courseType'=>$b_a['courseType'],'title'=>$b_a['title'],'paperId'=>$b_a['paperId']])){
                        foreach ($b_a['nodeInfo'] as $b_b){
                            if($ppid = DB::table('coursechapter')->insertGetId(['title'=>$b_b['title'],'parentId'=>$pid,'courseId'=>$_POST['courseId']])){
                                foreach ($b_b['dataInfo'] as $b_c){
                                    $b_c['parentId'] = $ppid;
                                    $b_c['courseId'] = $_POST['courseId'];
                                    DB::table('coursechapter')->insertGetId($b_c);
                                }
                            }
                        }
                    }
                }
            }
        }
        //添加课堂指导
        if(isset($_POST['guideInfo'])){
            foreach ( $_POST['guideInfo'] as $c_a){
                if(isset($c_a['id'])){
                    //更新导学title
                    DB::table('coursechapter')->where('id', $c_a['id'])->update(['title' => $c_a['title'],'paperId'=>$c_a['paperId']]);
                    //循环更新每个文件
                    foreach ($c_a['dataInfo'] as $c_b){
                        if(isset($c_b['id'])){//更新该文件
                            //DB::table('coursechapter')->where('id', $c_b['id'])->update(['title'=>$c_b['title'],'courseFormat'=>$c_b['courseFormat'],'fileID'=>$c_b['fileID'], 'courseTime'=>$a_b['courseTime'], 'tipatime'=>$a_b['tipatime'], 'tipbtime'=>$a_b['tipbtime'], 'tipctime'=>$a_b['tipctime'], 'tipacon'=>$a_b['tipacon'], 'tipbcon'=>$a_b['tipbcon'], 'tipccon'=>$a_b['tipccon']]);
                            DB::table('coursechapter')->where('id', $c_b['id'])->update(['title'=>$c_b['title'],'courseFormat'=>$c_b['courseFormat'],'fileID'=>$c_b['fileID']]);
                        }else{//添加新文件
                            $c_b['parentId'] = $c_a['id'];
                            $c_b['courseId'] = $_POST['courseId'];
                            DB::table('coursechapter')->insertGetId($c_b);
                        }
                    }
                }else{ //添加指导
                    if($pid = DB::table('coursechapter')->insertGetId(['courseId'=>$c_a['courseId'],'courseType'=>$c_a['courseType'],'title'=>$c_a['title'],'paperId'=>$c_a['paperId']])){
                        foreach ($c_a['dataInfo'] as $c_b){
                            $c_b['parentId'] = $pid;
                            $c_b['courseId'] = $_POST['courseId'];
                            DB::table('coursechapter')->insertGetId($c_b);
                        }
                    }
                }
            }
        }
        DB::table('course')->where('id', $_POST['courseId'])->update(['courseStatus' => 4]);
        return response()->json(['type'=>true]);
    }

    /**
     * 添加下发班级接口
     */
    public function addSelclass(){
        if($courseId = DB::table('courseclass')->insert($_POST['data'])) {
            //添加试题信息
            if(isset($_POST['testTime'])) DB::table('examinfo')->insert($_POST['testTime']);
            //修改课程发布状态
            if($default = DB::select('select default(courseStatus) as defaultStatus from course limit 1')){
                $courseStatus = $default[0]->defaultStatus;
            }else{
                $courseStatus = 1;
            }
            DB::table('course')->where('id', $_POST['courseId'])->update(['courseStatus' => $courseStatus]);
            return response()->json(['type'=>true,'courseId'=>$_POST['courseId']]);
        }
        else return response()->json(['type'=>false]);
    }

    /**
     * 通知关注用户课程发布接口
     */
    public function sendMsg($courseId){
        $data = array();
        $usernames = DB::table('friends')
            ->leftJoin('users','users.id','=','friends.fromUserId')
            ->where('friends.toUserId','=',Auth::user()->id)
            ->selec('users.username')
            ->get();
        foreach ($usernames as $username){
            $data[] = ['username'=>$username,'actionId'=>$courseId,'type'=>5,'content'=>'老师'.$username.'发布了新的课程'];
        }
        if(count($data)){
            DB::table('usermessage')->insert($data);
        }
    }

    /**
     * 引用资源列表接口
     */
    public function getresource($type,$chapterId){
        if($type == 1){
            $data = DB::table('resource')->leftJoin('users','users.id','=','resource.userId')->select('resource.resourceTitle','users.username','resource.created_at','resource.fileID','resource.resourceFormat')->where('resource.resourceStatus',0)->where('resource.resourceIsDel',0)->where('resource.passCode',2)->where('resource.resourceChapter',$chapterId)->get();
        }else{
            $data = DB::table('resourcestore')->leftJoin('resource','resource.id','=','resourcestore.resourceId')->leftJoin('users','users.id','=','resourcestore.userId')->select('resource.resourceTitle','users.username','resource.created_at','resource.fileID','resource.resourceFormat')->where('resourcestore.userId',Auth::user()->id)->where('resourcestore.type',0)->where('resource.resourceChapter',$chapterId)->get();
        }
        return response()->json(['data'=>$data]);
    }

    /**
     * 编辑课程信息获取接口
     */
    public function geteditCourseInfo($type,$courseId){
        if($type == 1){
            //dd($type.'|'.$courseId);
            if($data = DB::table('course')
                ->leftJoin('schoolgrade','schoolgrade.id','=','course.gradeId')
                ->leftJoin('studysubject','studysubject.id','=','course.subjectId')
                ->leftJoin('studyedition','studyedition.id','=','course.editionId')
                ->leftJoin('studyebook','studyebook.id','=','course.bookId')
                ->leftJoin('chapter','chapter.id','=','course.chapterId')
                ->select('course.gradeId','course.subjectId','course.editionId','course.bookId','course.chapterId','course.courseTitle','course.coursePic','course.courseIntro','course.courseContent','schoolgrade.gradeName','studysubject.subjectName','studyedition.editionName','studyebook.bookName','chapter.chapterName')
                ->where('course.id',$courseId)
                ->first())
                return response()->json(['status'=>true,'data'=>$data]);
            else
                return response()->json(['status'=>false]);

        }else{
            //获取课前导学
            $prelearnInfos = DB::table('coursechapter')->select('id','courseType','title','courseId','paperId')->where('courseType',0)->where('courseId',$courseId)->get();
            foreach ($prelearnInfos as &$prelearnInfo){
                //$prelearnInfo->dataInfo = DB::table('coursechapter')->select('id','title','courseFormat','fileID','courseTime','tipatime','tipbtime','tipctime','tipacon','tipbcon','tipccon')->where('parentId',$prelearnInfo->id)->get();
                $prelearnInfo->dataInfo = DB::table('coursechapter')->select('id','title','courseFormat','fileID')->where('parentId',$prelearnInfo->id)->get();
                foreach ($prelearnInfo->dataInfo as &$nodea){
                    $nodea->showjdbar = false;
                    $nodea->stopupload = false;
                    $nodea->jdmsg = '';
                    $nodea->progressBara = '0';
                    $nodea->progressBarb = '0';
                }
            }
            if(!$prelearnInfos) $prelearnInfos = [];
            //获取课堂授课
            $chaprerInfos = DB::table('coursechapter')->select('id','courseType','title','courseId','paperId')->where('courseType',1)->where('courseId',$courseId)->get();
            foreach ($chaprerInfos as &$chaprerInfo){
                $chaprerInfo->nodeInfo = DB::table('coursechapter')->select('id','title')->where('parentId',$chaprerInfo->id)->get();
                foreach ($chaprerInfo->nodeInfo as &$nodea){
                    //$nodea->dataInfo = DB::table('coursechapter')->select('id','title','courseFormat','fileID','courseTime','tipatime','tipbtime','tipctime','tipacon','tipbcon','tipccon')->where('parentId',$nodea->id)->get();
                    $nodea->dataInfo = DB::table('coursechapter')->select('id','title','courseFormat','fileID')->where('parentId',$nodea->id)->get();
                    foreach ($nodea->dataInfo as &$nodeb){
                        $nodeb->showjdbar = false;
                        $nodeb->stopupload = false;
                        $nodeb->jdmsg = '';
                        $nodeb->progressBara = '0';
                        $nodeb->progressBarb = '0';
                    }
                }
            }
            if(!$chaprerInfos) $chaprerInfos = [];
            //获取课堂指导
            $guideInfos = DB::table('coursechapter')->select('id','courseType','title','courseId','paperId')->where('courseType',2)->where('courseId',$courseId)->get();
            foreach ($guideInfos as &$guideInfo){
                //$guideInfo->dataInfo = DB::table('coursechapter')->select('id','title','courseFormat','fileID','courseTime','tipatime','tipbtime','tipctime','tipacon','tipbcon','tipccon')->where('parentId',$guideInfo->id)->get();
                $guideInfo->dataInfo = DB::table('coursechapter')->select('id','title','courseFormat','fileID')->where('parentId',$guideInfo->id)->get();
                foreach ($guideInfo->dataInfo as &$nodea){
                    $nodea->showjdbar = false;
                    $nodea->stopupload = false;
                    $nodea->jdmsg = '';
                    $nodea->progressBara = '0';
                    $nodea->progressBarb = '0';
                }
            }
            if(!$guideInfos) $guideInfos = [];

            //dd($chaprerInfos);

            return response()->json(['status'=>true,'prelearnInfo'=>$prelearnInfos,'chaprerInfo'=>$chaprerInfos,'guideInfo'=>$guideInfos]);
        }
    }

    /**
     * 同步测验数据(课前导学)
     * @return mixed
     */
    public function getTestInfoa($id)
    {
//        $id = 1;
        $result = DB::table('coursechapter as c')
            ->join('exampaper as e', 'c.paperId', '=', 'e.id')
            ->select('e.id as paperId', 'e.title')
            ->where(['c.courseId' => $id, 'c.status' => 0, 'c.courseType' => 0])->first();
        if ($result) {
            return response() -> json(['data' => $result, 'status' => true]);
        } else {
            return response() -> json(['data' => false, 'status' => false]);
        }
    }


    /**
     * 同步测验数据(课堂授课)
     * @return mixed
     */
    public function getTestInfob($id)
    {
//        $id = 1;
        $result = DB::table('coursechapter as c')
            ->join('exampaper as e', 'c.paperId', '=', 'e.id')
            ->select('e.id as paperId', 'e.title')
            ->where(['c.courseId' => $id, 'c.status' => 0, 'c.courseType' => 1])->first();
        if ($result) {
            return response() -> json(['data' => $result, 'status' => true]);
        } else {
            return response() -> json(['data' => false, 'status' => false]);
        }
    }


    /**
     * 同步测验数据(课后指导)
     * @return mixed
     */
    public function getTestInfoc($id)
    {
//        $id = 1;
        $result = DB::table('coursechapter as c')
            ->join('exampaper as e', 'c.paperId', '=', 'e.id')
            ->select('e.id as paperId', 'e.title')
            ->where(['c.courseId' => $id, 'c.status' => 0, 'c.courseType' => 2])->first();
        if ($result) {
            return response() -> json(['data' => $result, 'status' => true]);
        } else {
            return response() -> json(['data' => false, 'status' => false]);
        }
    }


    /**
     * 删除库中数据接口
     */
    public function deletedatabase($type,$id){
        if($type == 1){
            DB::table('coursechapter')->where('id', $id)->delete();
        }else{
            DB::table('coursechapter')->where('id', $id)->delete();
            DB::table('coursechapter')->where('parentId', $id)->delete();
        }
    }

}