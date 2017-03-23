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


class teacherCourseCatalogController extends Controller{

    use Gadget;


    /**
     * 获取课程章节目录接口
     */
    public function getCourseChapter($courseId){

        $data = DB::table('course as c')
            ->leftJoin('coursechapter as cc','c.id','=','cc.courseId')
            ->select('cc.*')
            ->where('cc.courseId','=',$courseId)
            ->whereIn('cc.courseType',[0,1,2])
            ->get();
//        dd($data);
        $icontype = ['doc','docx','xls','xlsx','ppt','pptx','pdf','swf'];
        if($data){
            //章
            foreach($data as $key => &$val){
//              $val->chapter = DB::table('coursechapter as cc')->where('cc.parentId','=',$val->id)->orderBy('cc.id','desc')->get();
                if($val->courseType == 1){
                    $val->chapter = DB::table('coursechapter as cc')->where('cc.parentId','=',$val->id)->get();
                }else{
                    $val->chapter = DB::table('coursechapter as cc')->where('cc.parentId','=',$val->id)->orderBy('cc.id','desc')->get();
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

            $info = [];
            foreach ($data as $item) {
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
            ->select('n.*', 'u.username','u.pic')
            ->where('n.courseId','=',$courseId)
            ->where('n.stuId','=',$userId)
//            ->where('n.public','=','0')
            ->orderBy('n.createtime','desc')
            ->limit(2)
            ->get();

        if($data){
            return response()->json(['status'=>true,'data' => $data]);
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
            ->select('n.*', 'u.username','u.pic')
            ->where('n.courseId','=',$courseId)
            ->where('n.public','=','0')
            ->orderBy('n.createtime','desc')
            ->limit(2)
            ->get();

        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 获取贴士数据接口
     */
    public function getCourseTips($courseId,$chapterId){

        $userId = \Auth::user()->id;
        $data = DB::table('users as u')
            ->leftJoin('tips as t','t.teaId','=','u.id')
            ->select('t.id','t.tipstime','t.tipscontent')
            ->where('t.courseId','=',$courseId)
            ->where('t.teaId','=',$userId)
            ->where('t.chapterId','=',$chapterId)
            ->orderBy('t.id','asc')
            ->get();

        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }




    /**
     * 删除贴士
     */
    public function deleteTips($tipsId){
        //$datas = DB::table('tips')->where('id','=',$tipsId)->first()->chapterId;
        $sumId = DB::table('tips')->select('id')->get();
        if($sumId){
            $tipId = [];
            foreach($sumId as $kye => $val){
                $tipId[] = $val->id;
            }
        }
        $data = DB::table('tips')->where('id','=',$tipsId)->delete();
        if($data){
            return response()->json(['status'=>true,'data' => $data,'tipId' => $tipId]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    /**
     * 修改贴士
     */
    public function promptEditContent(Request $request){
        $input = $request->all();
        $input['createtime'] = Carbon::now();
        try {
            $input['tipscontent'] = Filter::filter($input['tipscontent']);
        } catch (\Exception $e) {
            $input['tipscontent'] = $request['tipscontent'];
        }

        $tipsTime = DB::table('tips')->where('id','=',$input['tipsId'])->first()->tipstime;

        $res = DB::table('tips')->where('id','=',$input['tipsId'])->update(['tipscontent' => $input['tipscontent'] ,  'createtime' => $input['createtime'] ]);
        if($res){
            $data = DB::table('tips')->where('id','=',$input['tipsId'])->first()->id;
            $d = ['id' => $data,'tipstime'=>$tipsTime,'tipscontent' => $input['tipscontent']];
            return response()->json(['status'=>true,'data' => $data,'d' => $d]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }

    /**
     * 验证贴士发布老师
     */
    public function courseTeacher($courseId,$chapterId,$thisTime){
        if($thisTime){
            $tipstime = floor($thisTime);
        }else{
            $tipstime = 0;
        }
        $tipsTime = DB::table('tips')->select('tipstime')->where('chapterId','=',$chapterId)->get();

        $sumTime = [];
        if($tipsTime){
            foreach($tipsTime as $key => $val){
                foreach($val as $k => $v){
                    $sumTime[] = $v;
                }
            }
        }
        $isTrue = null;
        if($sumTime){
            $isTrue = in_array($tipstime,$sumTime);
        }
        $data = DB::table('course')->where('id','=',$courseId)->first()->teacherId;
        if($data){
            return response()->json(['status'=>true,'data' => $data,'isTrue'=>$isTrue]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }

    /**
     * 提交贴士内容
     */
    public function courseSubmitTips(Request $request){
        $userId = Auth::user()->id;
        $input = $request->all();
        $input['createtime'] = Carbon::now();
        $input['teaId'] = $userId;
        $input['coursetype'] = DB::table('coursechapter')->where('id','=',$input['chapterId'])->first()->courseFormat;
        $courseType =$input['coursetype'];
        try {
            $input['tipscontent'] = Filter::filter($input['tipscontent']);
        } catch (\Exception $e) {
            $input['tipscontent'] = $request['tipscontent'];
        }
        $chapterId = $input['chapterId'];
        if($input['time']){
            $tipstime = floor($input['time']);
        }
//        if($input['time']){
//            if($courseType == 'mp4'){
//                $tipstime = floor($input['time']);
//            }else{
//                $tipstime = 0;
//            }
//        }else{
//            $tipstime = 0;
//        }
        $data = DB::table('tips')->insertGetId(['courseId' => $input['courseId'],'tipstime'=>$tipstime,'tipscontent'=>$input['tipscontent'],'teaId' => $userId , 'coursetype'=>$courseType,'chapterId' =>$chapterId,'createtime'=>Carbon::now()]);
        if($data){
            $d = ['id' => $data,'tipstime'=>$tipstime,'tipscontent' => $input['tipscontent']];
            return response()->json(['status'=>true, 'data' =>$d ]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }

    /**
     * 验证视频或者文档
     */
    public function courseDocumentVideo($chapterId){
        $data = DB::table('coursechapter')->where('id','=',$chapterId)->first()->courseFormat;
        $icontype = ['doc','docx','xls','xlsx','ppt','pptx','pdf','swf'];
        if(in_array($data,$icontype)){
            $data = 'document';
        }else{
            $data = 'video';
        }
        if($data){
            return response()->json(['status'=>true,'data' => $data]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /**
     * 提交笔记内容
     */
    public function courseSubmitNote(Request $request){
        $userId = Auth::user()->id;
        $input = $request->all();
        $input['createtime'] = Carbon::now();
        $input['stuId'] = $userId;
        $input['coursetype'] = DB::table('coursechapter')->where('id','=',$input['chapterId'])->first()->courseFormat;
        $courseType =$input['coursetype'];

        $chapterId = $input['chapterId'];
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
        $data = DB::table('notes')->insert(['courseId' => $input['courseId'],'public'=>$input['public'],'notetime'=>$notetime,'notecontent'=>$input['notecontent'],'stuId' => $userId , 'coursetype'=>$courseType,'chapterId' =>$chapterId,'createtime'=>Carbon::now()]);
        if($data){
            return response()->json(['status'=>true]);
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
                        if(in_array(strtolower($v->courseFormat),$icontype)){     //strtolower所有字符转换为小写
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

//        $data = DB::table('coursechapter')->where('id','=',$chapterId)->first();
//        if($data){
//            foreach($data as $key => $val){
//                $val->chapter = DB::table('coursechapter')->where('parentId','=',$val->id)->orderBy('id','desc')->first();
//            }
//        }
        $data = DB::table('coursechapter')->where('parentId','=',$chapterId)->first();
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





}
