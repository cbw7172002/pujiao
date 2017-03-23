<?php

namespace App\Http\Controllers\Home\member;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Home\lessonComment\Gadget;
use Illuminate\Support\Facades\Auth;
use Mockery\CountValidator\Exception;
use PaasUser;
use Messages;
use Hash;
use DB;

/**
 * Class perSpaceController
 * @package App\Http\Controllers\Home\member
 */
class perSpaceController extends Controller
{
    use Gadget;
    /**
     * @param $id
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function studentHomePage($id)
    {
        $this -> http('http://127.0.0.1:9527/collect?name='.urlencode(\Auth::user() -> username).'&type='.urlencode(\Auth::user() -> type));
        $mineUsername = \Auth::check() ? \Auth::user()->username : null;
        return view('home.member.studentHomePage', compact('id', 'mineUsername'));
    }

    /**
     * @param $id
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function teacherHomePage($id)
    {
        $this -> http('http://127.0.0.1:9527/collect?name='.urlencode(\Auth::user() -> username).'&type='.urlencode(\Auth::user() -> type));
        $mineUsername = \Auth::check() ? \Auth::user()->username : null;
        return view('home.member.teacherHomePage', compact('id', 'mineUsername'));
    }

    /**
     * @param $id
     * 获取user信息
     *
     * @return \Illuminate\Http\Response
     */

    public function getUserInfo($id)
    {
        $data = \DB::table('users')
            -> select('username', 'realname', 'sex', 'pic', 'type', 'school', 'gradeId', 'classId')
            -> where(['id' => $id, 'checks' => 0])
            -> first();
        if($data && $data -> type == 1) {
            $data -> gradeId = $data->gradeId ? DB::table('schoolgrade') -> select('gradeName') -> where(['id' => $data->gradeId, 'status' => 1]) -> pluck('gradeName') : '';
            $data -> classId = $data->classId ? DB::table('schoolclass') -> select('classname as className') -> where(['id' => $data->classId, 'status' => 1]) -> pluck('classname') : '';
        } elseif ($data && $data -> type == 2) {
            $teach = DB::table('teacherteach as tea') -> join('studysubject as sub', 'tea.subjectId', '=', 'sub.id') -> distinct() -> select('sub.subjectName')  -> where('tea.tid', '=', $id) -> get();
            if($teach) {
                $data -> subjectName = '';
                $data -> subjectNames = '';

                foreach($teach as $key=>$val) {
                    $data -> subjectNames .= $val->subjectName.' ';
                    if($key < 4) {
                        if($key < 3) {
                            $data -> subjectName .= $val->subjectName.' ';
                        } else {
                            $data -> subjectName .= '...';
                        }
                    } else {
                        break;
                    }
                }

            } else {
                $data -> subjectNames = '';
                $data -> subjectName = '';
            }
        }
        return $this->returnResults($data);
    }

    /**
     * @param $request
     * 获取总数
     * @return \Illuminate\Http\Response
     */
    public function getCount(Request $request)
    {
        switch($request['action']) {
            case 1:
                $count = DB::table($request['table']) -> select('id') -> where($request['data']) -> count();
                break;
            case 2:
                $count = DB::table($request['table']) -> select('courseId') -> distinct() -> where($request['data']) -> get();
                $count = $count ? count($count) : 0 ;
                break;
        }

        return $this->returnResults($count);
    }

    /**
     * @param $pageNumber
     * @param $pageSize
     * @param $id
     * 我的关注
     * @return \Illuminate\Http\Response
     */
    public function myFocus($pageNumber, $pageSize, $id)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $myFocus = DB::table('friends as f')
            ->select('u.id', 'u.username', 'u.pic', 'u.type')
            ->join('users as u', 'f.toUserId', '=', 'u.id')
            ->where('f.fromUserId', $id)
            ->where('u.checks', 0)
            ->orderBy('u.type', 'desc')
            ->skip($skip)
            ->take($pageSize)
            ->get();
        $count = DB::table('friends as f')
            ->join('users as u', 'f.toUserId', '=', 'u.id')
            ->select('f.id')
            ->where('f.fromUserId', $id)
            ->where('u.checks', 0)
            ->count();
        if ($myFocus) {
            return response()->json(['data' => $myFocus, 'total' => $count, 'type' => true]);
        } else {
            return response()->json(['total' => $count, 'type' => false]);
        }
    }

    /**
     * @param $pageNumber
     * @param $pageSize
     * @param $id
     * 我的好友
     * @return \Illuminate\Http\Response
     */
    public function myFriends($pageNumber, $pageSize, $id)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $myFriends = \DB::table('friends as f')
            ->join('users as u', 'f.fromUserId', '=', 'u.id')
            ->select('u.id', 'u.username', 'u.pic', 'u.type')
            ->where('f.toUserId', $id )
            ->where('u.checks', 0)
            ->orderBy('u.type', 'desc')
            ->skip($skip)
            ->take($pageSize)
            ->get();
        $count = \DB::table('friends as f')
            ->join('users as u', 'f.fromUserId', '=', 'u.id')
            ->select('u.id', 'u.username', 'u.pic', 'u.type')
            ->where('f.toUserId', $id)
            ->where('u.checks', 0)
            ->count();
        if ($myFriends) {
            return response()->json(['data' => $myFriends, 'total' => $count, 'type' => true]);
        } else {
            return response()->json(['total' => $count, 'type' => false]);
        }
    }

    /**
     * @param $request
     * 我的收藏 -- 资源分类
     * @return \Illuminate\Http\Response
     */
    public function getResourceType(Request $request)
    {
        $result = \DB::table($request['table']) -> select('id', 'resourceTypeName as name') -> where('status', '=', 0) -> get();
        return $this->returnResults($result) ;
    }

    /**
     * @param $request
     * @param $pageNumber
     * @param $pageSize
     * 我的资源--（教师）
     *
     * @return \Illuminate\Http\Response
     */
    public function myResource(Request $request, $pageNumber, $pageSize)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $condition = ['res.resourceIsDel' => 0 , 'res.userId' => \Auth::user()->id];
        $request['typeId'] && $condition['type.id'] = $request['typeId'] ;
        $myResource = \DB::table('resource as res')
            ->join('resourcetype as type', 'type.id', '=', 'res.resourceType')
            ->join('users as u', 'u.id', '=', 'res.userId')
            ->select('res.*', 'u.realname', 'u.username')
            ->orderBy('res.id', 'desc')
            ->where($condition)
            ->skip($skip)
            ->take($pageSize)
            ->get();
//dd($myResource);
        $count = \DB::table('resource as res')
            ->join('resourcetype as type', 'type.id', '=', 'res.resourceType')
            ->join('users as u', 'u.id', '=', 'res.userId')
            ->select('res.id')
            ->where($condition)
            ->count();

        if ($myResource) {
            $type = ['png','jpg','jpeg','pdf','swf'];
            $document = ['doc','docx','xls','xlsx','ppt','pptx'];

            foreach($myResource as $key => $val) {

                if(!in_array(strtolower($val->resourceFormat),$type)){
                    if(!$val->courseLowPath || !$val->courseMediumPath || !$val->courseHighPath){

                        if(strtolower($val->resourceFormat) == 'mp3'){
                            $convertype = 1; //音频
                        }elseif(in_array(strtolower($val->resourceFormat),$document)){
                            $convertype = 2; //文档
                        }else{
                            $convertype = 0; //视频
                        }

                        $FileList = $this->transformations($val->fileID,$convertype);
//                    dump($FileList);

                        //转换失败
                        if($FileList['code'] == 503){
                            DB::table('resource')->where('id',$val->id)->update(['passCode'=>3]);
                        }

                        //返回的状态值
                        $val->msg['message'] = $FileList['message'];
                        $val->msg['code'] = $FileList['code'];

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
                                $lists['passCode'] = 2;
                                DB::table('resource')->where('id',$val->id)->update($lists);
                            }
                        }
                        if(in_array(strtolower($val->resourceFormat),$document) && $val->courseLowPath){
                            if(!$val->resourcePath && $val->courseLowPath){
                                $paasdownUrl = $this->getdownload( $val->courseLowPath);

                                if($paasdownUrl['code'] == 200){
                                    $path = realpath(base_path('public')).'/PdfFile/'.$val->fileID.'.pdf';
                                    try{
                                        $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
                                    }catch(\Exception $e){
                                        Log::info('myResource '.$e->getMessage());
                                        abort(404);
                                    }
//                                $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
                                    if($curl){
                                        $curlPath = '/PdfFile/'.$val->fileID.'.pdf';
                                        DB::table('resource')->where('id',$val->id)->update(['resourcePath'=>$curlPath]);
                                    }
                                }
                            }
                        }



                    }

                }
//                else{
//
//                    //直接下载
//                    if(!$val->resourcePath && $val->fileID){
//                        $paasdownUrl = $this->getdownload( $val->fileID);
////                        dd($paasdownUrl);
//                        if($paasdownUrl['code'] == 200){
//
//                            $path = realpath(base_path('public')).'/PdfFile/'.$val->fileID.'.'.strtolower($val->resourceFormat);
//                            $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
//                            if($curl){
//                                $curlPath = '/PdfFile/'.$val->fileID.'.'.strtolower($val->resourceFormat);
//                                DB::table('resource')->where('id',$val->id)->update(['resourcePath'=>$curlPath, 'passCode'=>2]);
//                            }
//                        }
//                    }
//                }
                $myResource[$key] -> resourceEdition = DB::table('studyedition') -> where('id', '=', $val -> resourceEdition) -> pluck('editionName') ?: '';
                $myResource[$key] -> resourceGrade = DB::table('schoolgrade') -> where('id', '=', $val -> resourceGrade) -> pluck('gradeName') ?: '';
                $myResource[$key] -> resourceSubject = DB::table('studysubject') -> where('id', '=', $val -> resourceSubject) -> pluck('subjectName') ?: '';
                $myResource[$key] -> resourceBook = DB::table('studyebook') -> where('id', '=', $val -> resourceBook) -> pluck('bookName') ?: '';
            }
            return response()->json(['data' => $myResource, 'total' => $count, 'status' => true]);
        } else {
            return response()->json(['total' => $count, 'status' => false]);
        }
    }

    /**
     * @param $durl
     * @param $path
     * @return mixed
     */
    function curl_file_get_contents($durl,$path){
        ob_start();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $durl);
        curl_setopt($ch, CURLOPT_HEADER, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
//        $path = $path;
//        $path = $path.".pdf";
        $arrdata=fopen($path,"a");
        fwrite($arrdata,$r);
        fclose($arrdata);
        curl_close($ch);
        return $path;

    }


    /**
     * 我的资源删除
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMyResource(Request $request)
    {
        $result = \DB::table($request['table']) -> where($request['data']) -> delete();
        return $this->returnResults($result) ;
    }
    /**
     * @param $request
     * @param $pageNumber
     * @param $pageSize
     * 我的收藏 -- 资源收藏
     * @return \Illuminate\Http\Response
     */
    public function resourceStore(Request $request, $pageNumber, $pageSize)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $condition = ['sto.type' => 0 , 'sto.userId' => \Auth::user()->id];
        $request['typeId'] ? $condition['type.id'] = $request['typeId'] : '' ;
        $myResourceStore = \DB::table('resourcestore as sto')
            ->leftJoin('resource as res', 'res.id', '=', 'sto.resourceId')
            ->leftJoin('resourcetype as type', 'type.id', '=', 'res.resourceType')
            ->leftJoin('users as u', 'u.id', '=', 'sto.userId')
            ->select('res.id', 'res.resourceTitle', 'res.resourceAuthor', 'res.resourcePic', 'res.resourceEdition', 'res.resourceGrade', 'res.resourceSubject', 'res.resourceBook', 'res.resourceView', 'res.resourceDownload', 'res.resourceFav', 'res.created_at', 'res.passCode', 'u.realname', 'u.username', 'sto.resourcetitle', 'sto.id as storeId')
            ->orderBy('sto.id', 'desc')
            ->where($condition)
            ->skip($skip)
            ->take($pageSize)
            ->get();

        $count = \DB::table('resourcestore as sto')
            ->leftJoin('resource as res', 'res.id', '=', 'sto.resourceId')
            ->leftJoin('resourcetype as type', 'type.id', '=', 'res.resourceType')
            ->leftJoin('users as u', 'u.id', '=', 'sto.userId')
            ->select('res.id')
            ->where($condition)
            ->count();

        if ($myResourceStore) {
            foreach($myResourceStore as $key=>$value) {
                $myResourceStore[$key] -> resourceEdition = DB::table('studyedition') -> where('id', '=', $value -> resourceEdition) -> pluck('editionName') ?: '';
                $myResourceStore[$key] -> resourceGrade = DB::table('schoolgrade') -> where('id', '=', $value -> resourceGrade) -> pluck('gradeName') ?: '';
                $myResourceStore[$key] -> resourceSubject = DB::table('studysubject') -> where('id', '=', $value -> resourceSubject) -> pluck('subjectName') ?: '';
                $myResourceStore[$key] -> resourceBook = DB::table('studyebook') -> where('id', '=', $value -> resourceBook) -> pluck('bookName') ?: '';
            }
            return response()->json(['data' => $myResourceStore, 'total' => $count, 'status' => true]);
        } else {
            return response()->json(['total' => $count, 'status' => false]);
        }
    }


    /**
     * @param $pageNumber
     * @param $pageSize
     * 我的收藏 -- 课程收藏
     *
     * @return \Illuminate\Http\Response
     */
    public function courseStore($pageNumber, $pageSize)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $condition = ['sto.type' => 1, 'sto.userId' => \Auth::user()->id];
        $myCourseStore = \DB::table('resourcestore as sto')
            ->leftJoin('course as cou', 'cou.id', '=', 'sto.resourceId')
            ->leftJoin('users as u', 'u.id', '=', 'sto.userId')
            ->select('cou.id as courseId', 'cou.courseTitle', 'cou.editionId', 'cou.gradeId', 'cou.subjectId', 'cou.bookId', 'cou.coursePic', 'cou.courseStudyNum', 'cou.courseFav', 'cou.courseStatus', 'cou.created_at', 'u.realname', 'sto.resourcetitle', 'sto.id as storeId')
            ->orderBy('sto.id', 'desc')
            ->where($condition)
            ->skip($skip)
            ->take($pageSize)
            ->get();

        $count = \DB::table('resourcestore as sto')
            ->leftJoin('course as cou', 'cou.id', '=', 'sto.resourceId')
            ->leftJoin('users as u', 'u.id', '=', 'sto.userId')
            ->select('sto.id')
            ->where($condition)
            ->count();

        if ($myCourseStore) {
            foreach($myCourseStore as $key=>$value) {
                $myCourseStore[$key] -> editionId = DB::table('studyedition') -> where('id', '=', $value -> editionId) -> pluck('editionName') ?: '暂无';
                $myCourseStore[$key] -> gradeId = DB::table('schoolgrade') -> where('id', '=', $value -> gradeId) -> pluck('gradeName') ?: '';
                $myCourseStore[$key] -> subjectId = DB::table('studysubject') -> where('id', '=', $value -> subjectId) -> pluck('subjectName') ?: '';
                $myCourseStore[$key] -> bookId = DB::table('studyebook') -> where('id', '=', $value -> bookId) -> pluck('bookName') ?: '';
            }
            return response()->json(['data' => $myCourseStore, 'total' => $count, 'status' => true]);
        } else {
            return response()->json(['total' => $count, 'status' => false]);
        }
    }

    /**
     * @param $request
     * @param $pageNumber
     * @param $pageSize
     * 我的收藏 -- 试题收藏
     *
     * @return \Illuminate\Http\Response
     */
    public function examStore(Request $request, $pageNumber, $pageSize)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $condition = ['sto.type' => 2 , 'sto.userId' => \Auth::user()->id];
        if($request['condition']) {
            foreach($request['condition'] as $key => $value) {
                $condition['sto.'.$key] = $value;
            }
        }
        $myExamStore = \DB::table('resourcestore as sto') ->select('sto.id', 'sto.resourceId', 'sto.examType') -> where($condition) -> orderBy('sto.id','desc') -> take($pageSize) -> skip($skip) -> get();
        $count = \DB::table('resourcestore as sto') -> where($condition) -> count();
        $displayCount = \DB::table('resourcestore as sto') -> where(['sto.type' => 2 , 'sto.userId' => \Auth::user()->id]) -> count();
        if($myExamStore) {
            foreach($myExamStore as $key => &$value) {
                switch ($value->examType) {
                    case 1: //单选
                        $table = 'examschoose';
                        $select = ['id as examId','title','difficult','choice','analysis','answer','created_at'];
                        break;
                    case 2: //多选
                        $table = 'exammchoose';
                        $select = ['id as examId','title','difficult','choice','analysis','answer','created_at'];
                        break;
                    case 3: //判断
                        $table = 'examjudge';
                        $select = ['id as examId','title','difficult','analysis','answer','created_at'];
                        break;
                    case 4: //填空
                        $table = 'examcompletion';
                        $select = ['id as examId','title','difficult','analysis','answer','created_at'];
                        break;
                    case 5: //解答
                        $table = 'examsubjective';
                        $select = ['id as examId','title','difficult','analysis','answer','created_at'];
                        break;
                }
                if($value->resourceId) {
                    $result = DB::table($table)
                        -> select($select)
                        -> where('id', '=', $value->resourceId)
                        -> first();
                } else {
                    $result = [];
                }

                if($result) {
                    foreach($result as $key=>&$val){
                        if($key == 'choice') {
                            $value->$key = explode('┼┼', $val);
                        }else{
                            $value->$key = $val;
                        }
                    }
                }
            }
                return response()->json(['data' => $myExamStore, 'total' => $count, 'count' => $displayCount, 'status' => true]);
            } else {
                return response()->json(['total' => $count, 'count' => $displayCount, 'status' => false]);
            }
    }


    /**
     * 我的收藏 -- 试卷收藏科目
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function getSubjects(Request $request)
    {
        $data = [];
        if($request['data']) {
            foreach($request['data'] as $key => $value) {
                $data['sto.'. $key] = $value;
            }
        }
        $result = \DB::table('resourcestore as sto')
                -> join('studysubject as sub', 'sub.id', '=', 'sto.subjectId')
                -> select('sub.id', 'sub.subjectName')
                -> where($data)
                -> distinct()
                -> orderBy('sto.subjectId', 'asc')
                -> get();
        return $this->returnResults($result) ;
    }
    /**
     * @param $request
     * @param $pageNumber
     * @param $pageSize
     * 我的收藏 -- 试卷收藏
     *
     * @return \Illuminate\Http\Response
     */
    public function paperStore(Request $request, $pageNumber, $pageSize)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $condition = ['sto.type' => 3 , 'sto.userId' => \Auth::user()->id, 'pap.status' => 0];
        if($request['where']) {
         foreach($request['where'] as $key => $value) {
             $condition['pap.'.$key] = $value;
         }
        }
        $myPaperStore = \DB::table('resourcestore as sto')
            ->join('exampaper as pap', 'pap.id', '=', 'sto.resourceId')
            ->select('pap.id', 'pap.title', 'pap.gradeId', 'pap.subjectId', 'pap.bookId', 'pap.editionId')
            ->orderBy('pap.id', 'desc')
            ->where($condition)
            ->skip($skip)
            ->take($pageSize)
            ->get();

        $count = \DB::table('resourcestore as sto')
            ->join('exampaper as pap', 'pap.id', '=', 'sto.resourceId')
            ->where($condition)
            ->select('res.id')
            ->where($condition)
            ->count();
        $displayCount = \DB::table('resourcestore as sto')
            ->join('exampaper as pap', 'pap.id', '=', 'sto.resourceId')
            -> where(['sto.type' => 3 , 'sto.userId' => \Auth::user()->id, 'pap.status' => 0])
            -> count();

        if ($myPaperStore) {
            foreach($myPaperStore as $key=>&$value) {
                $myPaperStore[$key] -> editionId = DB::table('studyedition') -> where('id', '=', $value -> editionId) -> pluck('editionName') ?: '暂无';
                $myPaperStore[$key] -> gradeId = DB::table('schoolgrade') -> where('id', '=', $value -> gradeId) -> pluck('gradeName') ?: '';
                $myPaperStore[$key] -> subjectId = DB::table('studysubject') -> where('id', '=', $value -> subjectId) -> pluck('subjectName') ?: '';
                $myPaperStore[$key] -> bookId = DB::table('studyebook') -> where('id', '=', $value -> bookId) -> pluck('bookName') ?: '';
            }
            return response()->json(['data' => $myPaperStore, 'total' => $count, 'count' => $displayCount, 'status' => true]);
        } else {
            return response()->json(['total' => $count, 'count' => $displayCount, 'status' => false]);
        }
    }

    /*
     * @param $request
     * @param $pageNumber
     * @param $pageSize
     * 问答收藏
     */
    public function answerStore(Request $request, $pageNumber, $pageSize)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $condition = ['q.delete' => 0, 'fav.username' => \Auth::user()->username];
        $myAnswerStore = \DB::table('questionfav as fav')
            ->join('question as q', 'q.id', '=', 'fav.qesId')
            ->join('studysubject as sub', 'sub.id', '=', 'q.type')
            ->join('users as u', 'u.username', '=', 'fav.username')
            ->select('q.id as questionId', 'q.qestitle', 'fav.created_at', 'sub.subjectName', 'u.pic')
            ->where($condition)
            ->skip($skip)
            ->take($pageSize)
            ->get();

        $count = \DB::table('questionfav as fav')
            ->join('question as q', 'q.id', '=', 'fav.qesId')
            ->join('studysubject as sub', 'sub.id', '=', 'q.type')
            ->join('users as u', 'u.username', '=', 'fav.username')
            ->select('q.id')
            ->where($condition)
            ->count();

        if ($myAnswerStore) {
            return response()->json(['data' => $myAnswerStore, 'total' => $count, 'status' => true]);
        } else {
            return response()->json(['total' => $count, 'status' => false]);
        }

    }

    /*
     * @param $request
     * 1查看是否关注
     * 2关注
     * 3取消关注
     *
     */
    public function followUser(Request $request)
    {
        switch($request['action']) {
            case 1:
                $result = DB::table($request['table']) -> select('id') -> where($request['data']) -> first();
                $result = ($result && (bool) $result -> id);
                break;

            case 2:
                foreach ($request['data'] as $key => $value) {
                    $data[$key] = $value;
                }
                $data['created_at'] = Carbon::now();
                $result = DB::table($request['table']) -> insertGetId($data);
                break;

            case 3:
                $result = DB::table($request['table']) -> where($request['data']) -> delete();
                if ($result) {
                    return response() -> json(["status" => true, "data" => false]);
                }
                break;
        }
        return $this->returnResults($result);
    }


    /**
     * 获取全部通知
     * @param Request $request
     * @param $pageNumber
     * @param $pageSize
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNoticeInfo(Request $request, $pageNumber, $pageSize)
    {
        $username = $request['username'];
        $userType = DB::table('users')->where('username', $username)->select('type')->pluck('type');
        $skip = ($pageNumber - 1) * $pageSize;
        if ($userType == 1) { // 学生
            $info = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.*','t.tempName')->where(['u.username' => $username, 'u.display' => 1])
                ->whereNotIn('u.type',[6, 7])->orderBy('created_at','desc')->skip($skip)->take($pageSize)->get();
            $count = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.*','t.tempName')->where(['u.username' => $username, 'u.display' => 1])
                ->whereNotIn('u.type',[6, 7])->count();
            $noRead = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.id')->where(['u.username' => $username, 'u.display' => 1, 'u.isRead' => 0])
                ->whereNotIn('u.type',[6, 7])->count();
        } else {
            $info = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.*','t.tempName')->where(['u.username' => $username, 'u.display' => 1])
                ->whereNotIn('u.type',[6, 7, 8])->orderBy('created_at','desc')->skip($skip)->take($pageSize)->get();
            $count = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.*','t.tempName')->where(['u.username' => $username, 'u.display' => 1])
                ->whereNotIn('u.type',[6, 7, 8])->count();
            $noRead = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.id')->where(['u.username' => $username, 'u.display' => 1, 'u.isRead' => 0])
                ->whereNotIn('u.type',[6, 7, 8])->count();
        }
        if($info) {
            foreach ($info as $key => $value) {
                $info[$key]->created_at = $info[$key]->created_at ? explode(' ',$info[$key]->created_at)[0] : '';
                $info[$key]->userType = $userType;
            }
            return response()->json(['data' => $info, 'status' => true,'count' => $count, 'noRead' => $noRead]);
        }else{
            return response()->json(['status' => false,'count' => $count, 'noRead' => $noRead]);
        }
    }

    /**
     * 获取评论回复
     * @param Request $request
     * @param $pageNumber
     * @param $pageSize
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentInfo(Request $request, $pageNumber, $pageSize)
    {
        $username = $request['username'];
        $userType = DB::table('users')->where('username', $username)->select('type')->pluck('type');
        $skip = ($pageNumber - 1) * $pageSize;
        if ($userType == 1) { // 学生
            $info = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.*','t.tempName')->where('u.username', $username)
                ->whereIn('u.type',[6, 7])->orderBy('created_at','desc')->skip($skip)->take($pageSize)->get();
            $count = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.*','t.tempName')->where('u.username', $username)
                ->whereIn('u.type',[6, 7])->count();
            $noRead = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.id')->where(['u.username' => $username, 'u.isRead' => 0])
                ->whereIn('u.type',[6, 7])->count();
        } else {
            $info = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.*','t.tempName')->where('u.username', $username)
                ->whereIn('u.type',[6, 7, 8])->orderBy('created_at','desc')->skip($skip)->take($pageSize)->get();
            $count = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.*','t.tempName')->where('u.username', $username)
                ->whereIn('u.type',[6, 7, 8])->count();
            $noRead = DB::table('usermessage as u')->leftJoin('usermessagetem as t','u.tempId','=','t.id')->select('u.id')->where(['u.username' => $username, 'u.isRead' => 0])
                ->whereIn('u.type',[6, 7, 8])->count();
        }
        if($info){
            foreach($info as $key => $value){
                $info[$key]->created_at =$info[$key]->created_at ?  explode(' ',$info[$key]->created_at)[0] : '';
                $info[$key]->userType = $userType;
            }
            return response()->json(['data' => $info, 'status' => true, 'count' => $count, 'noRead' => $noRead]);
        }else{
            return response()->json(['status' => false, 'count' => $count, 'noRead' => $noRead]);
        }
    }

    /**
     * 通知消息更改状态
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeNoticeStatus($type)
    {
        $isRead['isRead'] = 1;
        $result = DB::table('usermessage')->where(['id' => $type])->update($isRead);
        if($result){
            return response()->json(['status' => true]);
        }else{
            return response()->json(['status' => false]);
        }
    }

    /**
     * 删除消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMessage(Request $request)
    {
        $result = DB::table('usermessage')->delete($request->id);
        if($result){
            return response()->json(['status' => true]);
        }else{
            return response()->json(['status' => false]);
        }
    }

    /*
     * 个人主页我的问答接口
     **/
    public function getQuestion($type,$pageNumber,$pageSize){

        $skip = ($pageNumber-1) * $pageSize;
        if($type == 1){
            $data = DB::table('question')->select('id','uId','qestitle','type','status','asktime')
                ->where('uId',\Auth::user()->id)
                ->where('delete',0)
//                ->where('status','2')
                ->skip($skip)->take($pageSize)
                ->orderBy('asktime','desc')
                ->get();
            $count = DB::table('question')
                ->where('uId',\Auth::user()->id)
                ->where('delete',0)
                ->count();

            foreach ($data as $k => &$v){
                if($aa=DB::table('users')->select('pic')->where('id',$v->uId)->first()){
                    $v->pic = $aa->pic;
                }else{
                    $v->pic = '/home/image/layout/default.png';
                }
                if($bb=DB::table('studysubject')->where('id',$v->type)->select('subjectName')->first()){
                    $v->type=$bb->subjectName;
                }
                $v->qa = 1;
            }
        }else{
            $data = DB::table('questioncomment')
                ->leftJoin('question','questioncomment.qesId','=','question.id')
                ->select('question.id','question.uId','question.qestitle','question.type','question.status','question.asktime')
                ->where('questioncomment.username',\Auth::user()->username)->where('questioncomment.parentId',0)
                ->where('question.delete',0)
                ->orderBy('question.asktime','desc')
                //->distinct()
                ->skip($skip)->take($pageSize)
                ->get();

            $count = DB::table('questioncomment')
                ->leftJoin('question','questioncomment.qesId','=','question.id')
                ->select('question.id')
                ->where('questioncomment.username',\Auth::user()->username)->where('questioncomment.parentId',0)
                ->where('question.delete',0)
                //->distinct()
                ->count();

            foreach ($data as $k => &$v){
                if($aa=DB::table('users')->select('pic')->where('id',$v->uId)->first()){
                    $v->pic = $aa->pic;
                }else{
                    $v->pic = '/home/image/layout/default.png';
                }
                if($bb=DB::table('studysubject')->where('id',$v->type)->select('subjectName')->first()){
                    $v->type=$bb->subjectName;
                }
            }
        }


        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }

    /*
     * 个人主页我的问答接口
     **/
    public function getWaitAnswer($type,$pageNumber,$pageSize){

        $skip = ($pageNumber-1) * $pageSize;
        if($type == 1){
            $data = DB::table('question')->select('id','uId','qestitle','type','status','asktime')
                ->where('uId',\Auth::user()->id)
                ->where('delete',0)
                ->where('status','=','1')
                ->skip($skip)->take($pageSize)
                ->orderBy('asktime','desc')
                ->get();
            $count = DB::table('question')
                ->where('uId',\Auth::user()->id)
                ->where('delete',0)
                ->count();

            foreach ($data as $k => &$v){
                if($aa=DB::table('users')->select('pic')->where('id',$v->uId)->first()){
                    $v->pic = $aa->pic;
                }else{
                    $v->pic = '/home/image/layout/default.png';
                }
                if($bb=DB::table('studysubject')->where('id',$v->type)->select('subjectName')->first()){
                    $v->type=$bb->subjectName;
                }
            }
        }

        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /*
     * 个人中心(公开)获取教师课程接口
     **/
    public function getTeacherCourse($type,$pageNumber,$pageSize,$uId){

        $skip = ($pageNumber-1) * $pageSize;
        if($type == 1){
            $data = DB::table('course as c')
                ->leftJoin('schoolgrade as g','c.gradeId','=','g.id')
                ->leftJoin('studysubject as s','c.subjectId','=','s.id')
                ->leftJoin('studyedition as e','c.editionId','=','e.id')
                ->leftJoin('studyebook as b','c.bookId','=','b.id')
                ->leftJoin('users as u','u.id','=','c.teacherId')
                ->select('c.*','g.gradeName','s.subjectName','e.editionName','b.bookname','u.username')
                ->where('c.teacherId',$uId)
                ->skip($skip)->take($pageSize)
                ->orderBy('c.created_at','desc')
                ->get();
            $count = DB::table('course')
                ->where('teacherId',$uId)
                ->count();
        }

        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    /*
     * 个人中心(公开)获取学生我的课程(正在学习)接口
     **/
    public function getStudentCourse($type,$pageNumber,$pageSize,$uId){

        $stuId = $uId;
//        $gradeId = Auth::user()->gradeId;
//        $classId = Auth::user()->classId;

        $gradeId = DB::table('users')->select('gradeId')->where('id','=',$uId)->first()->gradeId;
        $classId = DB::table('users')->select('classId')->where('id','=',$uId)->first()->classId;

        $skip = ($pageNumber-1) * $pageSize;

        if($type == 1){
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
                ->distinct('cv.courseId')
                ->get();

            $count = count($data);
        }


        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false]);
        }
    }


    /*
     * 个人中心(公开)获取教师问答接口
     **/

    public function getTeacherAnswer($type,$pageNumber,$pageSize,$uId){

        $skip = ($pageNumber-1) * $pageSize;
        if($type == 1){
            $data = DB::table('question')->select('id','uId','qestitle','type','status','asktime')
                ->where('uId',$uId)
                ->where('delete',0)
                ->skip($skip)->take($pageSize)
                ->orderBy('asktime','desc')
                ->get();
            $count = DB::table('question')
                ->where('uId',$uId)
                ->where('delete',0)
                ->count();

            foreach ($data as $k => &$v){
                if($aa=DB::table('users')->select('pic')->where('id',$v->uId)->first()){
                    $v->pic = $aa->pic;
                }else{
                    $v->pic = '/home/image/layout/default.png';
                }
                if($bb=DB::table('studysubject')->where('id',$v->type)->select('subjectName')->first()){
                    $v->type=$bb->subjectName;
                }
            }
        }
        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    /**
     * 课程问答(老师)
     */
    public function getTeacherCourseQa($type,$pageNumber,$pageSize){
        $userId = \Auth::user()->id;
        $skip = ($pageNumber-1) * $pageSize;

        if($type == 1){
            $data = DB::table('course as c')
                ->leftJoin('coursecomment as cc','c.id','=','cc.courseId')
                ->leftJoin('users as u','u.id','=','c.teacherId')
                ->select('c.id','cc.asktime','c.courseTitle','cc.content','u.pic')
                ->where('c.teacherId','=',$userId)
                ->where('cc.status','=',1)
                ->skip($skip)->take($pageSize)
                ->orderBy('cc.asktime','desc')
                ->get();
            $count = DB::table('course as c')
                ->leftJoin('coursecomment as cc','c.id','=','cc.courseId')
                ->where('c.teacherId','=',$userId)
                ->where('cc.status','=',1)
                ->count();
        }else{
            $data = DB::table('course as c')
                ->leftJoin('coursecomment as cc','c.id','=','cc.courseId')
                ->leftJoin('users as u','u.id','=','c.teacherId')
                ->select('c.id','cc.anstime as asktime','c.courseTitle','cc.content','u.pic')
                ->where('c.teacherId','=',$userId)
                ->where('cc.status','=',2)
                ->skip($skip)->take($pageSize)
                ->orderBy('cc.asktime','desc')
                ->get();
            $count = DB::table('course as c')
                ->leftJoin('coursecomment as cc','c.id','=','cc.courseId')
                ->where('c.teacherId','=',$userId)
                ->where('cc.status','=',2)
                ->count();
        }


        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    /**
     * 课程问答(学生)
     */
    public function getStudentCourseQa($type,$pageNumber,$pageSize){
        $userId = \Auth::user()->id;
        $skip = ($pageNumber-1) * $pageSize;

        if($type == 1){
            $data = DB::table('course as c')
                ->leftJoin('coursecomment as cc','c.id','=','cc.courseId')
                ->leftJoin('users as u','u.id','=','cc.stuId')
                ->select('c.id','cc.asktime','c.courseTitle','cc.content','u.pic')
                ->where('cc.stuId','=',$userId)
                ->where('cc.status','=',1)
                ->skip($skip)->take($pageSize)
                ->orderBy('cc.asktime','desc')
                ->get();
            $count = DB::table('course as c')
                ->leftJoin('coursecomment as cc','c.id','=','cc.courseId')
                ->where('cc.stuId','=',$userId)
                ->where('cc.status','=',1)
                ->count();
        }else{
            $data = DB::table('course as c')
                ->leftJoin('coursecomment as cc','c.id','=','cc.courseId')
                ->leftJoin('users as u','u.id','=','cc.stuId')
                ->select('c.id','cc.asktime','c.courseTitle','cc.content','u.pic')
                ->where('cc.stuId','=',$userId)
                ->where('cc.status','=',2)
                ->skip($skip)->take($pageSize)
                ->orderBy('cc.asktime','desc')
                ->get();
            $count = DB::table('course as c')
                ->leftJoin('coursecomment as cc','c.id','=','cc.courseId')
                ->where('cc.stuId','=',$userId)
                ->where('cc.status','=',2)
                ->count();
        }
//        dd($data);


        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    /*
     * @param $result
     * 返回数据
     **/
    protected function returnResults($result)
    {
        if ($result) {
            return response()->json(['data' => $result, 'status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
