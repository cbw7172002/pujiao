<?php

namespace App\Http\Controllers\Home\resource;

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

class resourceController extends Controller
{
    use Gadget;

    /**
     * 资源首页
     *
     */
    public function index(){
        return view('home.resource.resource');
    }

    /**
     * 清空缓存
     */
    public function flush(){
        Cache::flush();
        return redirect('/');
    }

    /**
     * 资源列表获取选项接口
     */
    public function getRessels($type){
        switch ($type){
            case 1:
                if(Cache::has('App\Http\Controllers\Home\resource\getRessels\1')){
                    $data = Cache::get('App\Http\Controllers\Home\resource\getRessels\1');
                }else{
                    $data = DB::table('schoolgrade')->select('id','gradeName')->where('status',1)->orderBy('id','asc')->get();
                    Cache::add('App\Http\Controllers\Home\resource\getRessels\1', $data, 1440);
                }
                break;
            case 2:
                //$data = DB::table('studysubject')->select('id','subjectName')->orderBy('id','asc')->get();
                if(Cache::has('App\Http\Controllers\Home\resource\getRessels\2')){
                    $data = Cache::get('App\Http\Controllers\Home\resource\getRessels\2');
                }else{
                    $data = DB::table('studysubject')->select('id','subjectName')->orderBy('id','asc')->get();
                    Cache::add('App\Http\Controllers\Home\resource\getRessels\2', $data, 1440);
                }
                break;
            case 3:
                //$data = DB::table('studyedition')->select('id','editionName')->orderBy('id','asc')->get();
                if(Cache::has('App\Http\Controllers\Home\resource\getRessels\3')){
                    $data = Cache::get('App\Http\Controllers\Home\resource\getRessels\3');
                }else{
                    $data = DB::table('studyedition')->select('id','editionName')->orderBy('id','asc')->get();
                    Cache::add('App\Http\Controllers\Home\resource\getRessels\3', $data, 1440);
                }
                break;
            case 4:
                //$data = DB::table('studyebook')->select('id','bookName')->orderBy('id','asc')->get();
                if(Cache::has('App\Http\Controllers\Home\resource\getRessels\4')){
                    $data = Cache::get('App\Http\Controllers\Home\resource\getRessels\4');
                }else{
                    $data = DB::table('studyebook')->select('id','bookName')->orderBy('id','asc')->get();
                    Cache::add('App\Http\Controllers\Home\resource\getRessels\4', $data, 1440);
                }
                break;
        }
        return response()->json($data);
    }
    /**
     * 侧边栏选项接口
     */
    public function getSidesels($gradeId, $subjectId, $editionId, $bookId){
        if(Cache::has('App\Http\Controllers\Home\resource\getSidesels')){
            $grades = Cache::get('App\Http\Controllers\Home\resource\getSidesels');
        }else{
            $grades = DB::table('schoolgrade')->select('id','gradeName');
            if($gradeId) $grades->where('id',$gradeId);
            $grades = $grades->where('status',1)->orderBy('id','asc')->get();
            foreach ($grades as $keya => &$grade){
                $grade->subjects = DB::table('chapter')->leftJoin('studysubject','chapter.subjectId','=','studysubject.id')->select('studysubject.id','studysubject.subjectName');
                if($subjectId) $grade->subjects->where('studysubject.id',$subjectId);
                $grade->subjects =  $grade->subjects ->where('chapter.gradeId',$grade->id)->distinct()->get();
                foreach ($grade->subjects as $keyb => &$subject){
                    $subject->editions = DB::table('chapter')->leftJoin('studyedition','chapter.editionId','=','studyedition.id')->select('studyedition.id','studyedition.editionName');
                    if($editionId) $subject->editions->where('studyedition.id',$editionId);
                    $subject->editions = $subject->editions->where('chapter.gradeId',$grade->id)->where('chapter.subjectId',$subject->id)->distinct()->get();
                    foreach ($subject->editions as $keyc => &$edition){
                        $edition->books = DB::table('chapter')->leftJoin('studyebook','chapter.bookId','=','studyebook.id')->select('studyebook.id','studyebook.bookName');
                        if($bookId) $edition->books->where('studyebook.id',$bookId);
                        $edition->books = $edition->books->where('chapter.gradeId',$grade->id)->where('chapter.subjectId',$subject->id)->where('chapter.editionId',$edition->id)->distinct()->get();
                        foreach ($edition->books as $keyd => &$book){
                            $book->chapters = DB::table('chapter')->select('id','chapterName')->where(['gradeId'=>$grade->id,'subjectId'=>$subject->id,'editionId'=>$edition->id,'bookId'=>$book->id])->get();
                        }
                    }
                }
            }
            Cache::add('App\Http\Controllers\Home\resource\getSidesels', $grades, 1440);
        }
        //dd($grades);
        return response()->json($grades);
    }
    /**
     * 获取资源接口
     */
    public function getResource(){
//        dd($_POST);

        $isOnlineViem = DB::table('systemsttings')->where('type',0)->select('isTrue')->pluck('isTrue') ? true  : false;
        //dd($isOnlineViem);

        $skip = ($_POST['pageNumber'] - 1) * $_POST['pageSize'];
        if($isOnlineViem){
            $data = DB::table('resource')->where('passCode',2)->select('id','userId','resourcePic','resourceTitle','resourceGrade','resourceSubject','resourceEdition','resourceBook','created_at','resourceView','resourceDownload','resourceFav','isexpand')->where($_POST['where'])->where('resourceStatus',0)->where('resourceTitle','like','%'.$_POST['whereb']['resourceTitle'].'%')->orderBy($_POST['orderBy'],'desc')->skip($skip)->take($_POST['pageSize'])->get();
            $count = DB::table('resource')->where('passCode',2)->where($_POST['where'])->where('resourceStatus',0)->where('resourceTitle','like','%'.$_POST['whereb']['resourceTitle'].'%')->count();
        }else{
            $data = DB::table('resource')->where('passCode',2)->select('id','userId','resourcePic','resourceTitle','resourceGrade','resourceSubject','resourceEdition','resourceBook','created_at','resourceView','resourceDownload','resourceFav','isexpand')->where($_POST['where'])->where('resourceStatus',0)->where('resourceTitle','like','%'.$_POST['whereb']['resourceTitle'].'%')->orderBy($_POST['orderBy'],'desc')->skip($skip)->take($_POST['pageSize'])->get();
            $count = DB::table('resource')->where('passCode',2)->where($_POST['where'])->where('resourceStatus',0)->where('resourceTitle','like','%'.$_POST['whereb']['resourceTitle'].'%')->count();
        }
        //$data = DB::table('resource')->where('passCode',2)->select('id','userId','resourcePic','resourceTitle','resourceGrade','resourceSubject','resourceEdition','resourceBook','created_at','resourceView','resourceDownload','resourceFav','isexpand')->where($_POST['where'])->where('resourceStatus',0)->where('resourceTitle','like','%'.$_POST['whereb']['resourceTitle'].'%')->orderBy($_POST['orderBy'],'desc')->skip($skip)->take($_POST['pageSize'])->get();
        //$count = DB::table('resource')->where('passCode',2)->where($_POST['where'])->where('resourceStatus',0)->where('resourceTitle','like','%'.$_POST['whereb']['resourceTitle'].'%')->count();
        foreach ($data as &$val) {
            $val -> userId = DB::table('users')->select('username')->where('id',$val->userId)->pluck('username');
            $val -> resourceGrade = DB::table('schoolgrade')->select('gradeName')->where('id',$val->resourceGrade)->pluck('gradeName');
            $val -> resourceSubject = DB::table('studysubject')->select('subjectName')->where('id',$val->resourceSubject)->pluck('subjectName');
            $val -> resourceEdition = DB::table('studyedition')->select('editionName')->where('id',$val->resourceEdition)->pluck('editionName');
            $val -> resourceBook = DB::table('studyebook')->select('bookName')->where('id',$val->resourceBook)->pluck('bookName');
        }
        if($data) return response()->json(['total'=>$count,'data'=>$data,'type'=>true]);
        else return response()->json(['total'=>$count,'type'=>false]);

    }

    /**
     * 添加资源页
     *
     */
    public function uploadRes(){
        \Auth::check() || abort(404);
        PaasUser::apply();
        return view('home.resource.uploadRes',['userId'=>\Auth::user()->id]);
    }

    /**
     * 资源详情页
     *
     */
    public function resDetail($id){
        \Auth::check() || abort(404);
        PaasUser::apply();
        return view('home.resource.resDetail')->with('detailId', $id);
    }

    /**
     *获取资源详情数据接口
     */
    public function getDetail($id){
        $data = DB::table('resource as r')
            ->leftJoin('users as u','u.id','=','r.userId')
            ->leftJoin('schoolgrade as g','g.id','=','r.resourceGrade')
            ->leftJoin('studysubject as s','s.id','=','r.resourceSubject')
            ->leftJoin('studyedition as e','e.id','=','r.resourceEdition')
            ->leftJoin('studyebook as b','b.id','=','r.resourceBook')
            ->select('r.*','u.username','g.gradeName','s.subjectName','e.editionName','b.bookName')
            ->where('r.id',$id)
            ->first();
        $data->created_at = date('Y-m-d',strtotime($data->created_at));

        //取出系统中文档是否需要转码
        $isTranscode = DB::table('systemsttings')->where('type',0)->pluck('isTrue');
        $data->isTranscode = $isTranscode;

        $download = $this->getdownload($data->fileID);
        if($download['code'] == '200'){
            $data->download = $download['data'];
        }else{
            $data->download = null;
        }
        if($data->courseLowPath){
            $data->courseLowPath = $this->getPlayUrl($data->courseLowPath);
        }
        if($data->courseMediumPath){
            $data->courseMediumPath = $this->getPlayUrl($data->courseMediumPath);
        }
        if($data->courseHighPath){
            $data->courseHighPath = $this->getPlayUrl($data->courseHighPath);
        }

        //是否收藏
        if(DB::table('resourcestore')->where(['resourceId'=>$data->id,'userId'=> Auth::user()->id,'type'=>0])->first()){
            $data->isCollection = 1;
        }else{
            $data->isCollection = 0;
        }

        //资源浏览数据+1
        DB::table('resource')->where('id',$id)->increment('resourceView');

//        dd($data);
        return response()->json($data);
    }

    /**
     *获取相关资源推荐
     */
    public function getRealtion($id){
        $res = DB::table('resource')->where('id',$id)
            ->select('resourceGrade','resourceSubject','resourceEdition','resourceBook','resourceChapter')
            ->first();
//        dd($res);

        $query = DB::table('resource');
        $data = $query
            ->where('resourceGrade',$res->resourceGrade)
            ->where('resourceSubject',$res->resourceSubject)
            ->where('resourceEdition',$res->resourceEdition)
            ->where('resourceBook',$res->resourceBook)
            ->where('resourceChapter',$res->resourceChapter)
            ->where('id','<>',$id)
            ->where('resourceIsDel',0)
            ->select('id','resourceTitle','resourcePic','created_at')
            ->limit(10)
            ->orderBy('id','desc')
            ->get();
//        dd($data);
        foreach($data as $val){
            $val->created_at = Carbon::now()->toDateString();
        }
        return response()->json($data);

    }

    /**
     *获取评论列表
     */
    public function getCommentInfo($id){
        //用户评论
        $data = DB::table('resourcecomment')->where('resourceId',$id)->orderBy('id','desc')->get();
        foreach($data as &$val){
            if($val->parentId != 0){
                $val->tousername = DB::table('resourcecomment')->where('id',$val->parentId)->pluck('username');
            }
            $val->comPic = DB::table('users')->where('id',$val->userId)->pluck('pic');
            $val->created_at = Carbon::parse($val->created_at)->diffForHumans();
            $val->likeUser = $val->likeNum ? array_filter(explode(',', $val->likeNum)) : [];
            $val->isLike = Auth::check() ? in_array(Auth::user()->id,$val->likeUser) : true;
            $val->likeTotal = count($val->likeUser);
            $val->isSelf = Auth::check() && Auth::user()->username == $val->username ? true : false;
        }
        return response()->json($data);

    }

    /**
     *发布评论
     */
    public function publishComment(Request $request){
        $data = $request->except('tousername');
        $data['userId'] = Auth::user()->id;
        $data['username'] = Auth::user()->username;
        $data['checks'] = 0;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        try{
            $data['commentContent'] = Filter::filter($request['commentContent']);
        }catch (Exception $e){
            Log::info('资源评论敏感词过滤报错：'.$e->getMessage());
        }

        if($insertId = DB::table('resourcecomment')->insertGetId($data)){
            //评论回复
            $resusername = DB::table('resource')
                ->leftJoin('users','users.id','=','resource.userId')
                ->where('resource.id',$request['resourceId'])
                ->select('users.username','resource.resourceTitle')
                ->first();
            $info['username'] = $resusername ? $resusername->username : '';
            $restitle = $resusername ? $resusername->resourceTitle : '';
            $info['fromUsername'] = $data['username'];
//                     $info['username'] = $data['username'];
            $info['type'] = 8;
            $info['content'] = $info['username'].'评论了您的资源'.$restitle;
            $info['client_ip'] = $_SERVER["REMOTE_ADDR"];
            $info['actionId'] = $request['resourceId'];
            $info['created_at'] = Carbon::now();
            DB::table('usermessage')->insertGetId($info);
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *删除评论
     */
    public function deleteComment($id){
        $result = DB::table('resourcecomment')->delete($id);
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *点赞
     */
    public function addLike($id){
        $data['updated_at'] = Carbon::now();
        $likeNum = DB::table('resourcecomment')->where('id',$id)->lists('likeNum');
        if($likeNum[0] == null){
            $new = ['0'=> Auth::user()->id];
        }else{
            $new = ['0'=> $likeNum[0],'1'=> Auth::user()->id];
        }
        $data['likeNum'] = implode(',',$new);
        $result = DB::table('resourcecomment')->where('id', $id)->update($data);
        return $this->returnResult($result);
        dd($data);
    }

    /**
     *下载接口
     */
    public function getDown($id){
        $resource = DB::table('resource')->where('id',$id)->first();
        if($resource->courseHighPath){
            $fileId = $resource->courseHighPath;
        }elseif($resource->courseMediumPath){
            $fileId = $resource->courseMediumPath;
        }elseif($resource->courseLowPath){
            $fileId = $resource->courseLowPath;
        }else{
            $fileId = $resource->fileID;
        }
        if(!Cache::get($fileId)){
            $path = $this->getdownload($fileId);
            Cache::put($fileId,$path,1800);
        }else{
            $path = Cache::get($fileId);
        }
        //资源下载数据+1
        DB::table('resource')->where('id',$id)->increment('resourceDownload');
        return response()->json($path);
    }

    /**
     *添加收藏接口
     */
    public function addCollection($resId){
        if(DB::table('resourcestore')->where(['resourceId'=>$resId,'userId'=> Auth::user()->id,'type'=>0])->first()){
            $data = DB::table('resourcestore')->where(['resourceId'=>$resId,'userId'=> Auth::user()->id,'type'=>0])->delete();

            //资源收藏数据-1
            DB::table('resource')->where('id',$resId)->decrement('resourceFav');

            if($data){
                return response()->json(['code'=>1,'msg'=>'取消收藏']);
            }else{
                return response()->json(['code'=>0,'msg'=>'数据错误']);
            }
        }else{
            $resourceTitle = DB::table('resource')->where('id',$resId)->pluck('resourceTitle');
            $data['resourceId'] = $resId;
            $data['userId'] = Auth::user()->id;
            $data['type'] = 0;
            $data['resourcetitle'] = $resourceTitle;
            $data['created_at'] = Carbon::now();
            $info = DB::table('resourcestore')->insert($data);

            //资源收藏数据+1
            DB::table('resource')->where('id',$resId)->increment('resourceFav');

            if($info){
                return response()->json(['code'=>1,'msg'=>'成功收藏']);
            }else{
                return response()->json(['code'=>0,'msg'=>'数据错误']);
            }
        }
    }

    /**
     * 上传资源类型选择接口
     */
    public function getType($type, $grade=0, $subject=0, $edition=0, $book=0){
        switch ($type){
            case 1://类型
                //$data  = DB::table('resourcetype')->select('id','resourceTypeName as text')->where('status',0)->get();
                if(Cache::has('App\Http\Controllers\Home\resource\getType\1')){
                    $data = Cache::get('App\Http\Controllers\Home\resource\getType\1');
                }else{
                    $data  = DB::table('resourcetype')->select('id','resourceTypeName as text')->where('status',0)->orderBy('id','asc')->get();
                    Cache::add('App\Http\Controllers\Home\resource\getType\1', $data, 1440);
                }
                break;
            case 2://年级
                $data = DB::table('chapter')->leftJoin('schoolgrade', 'chapter.gradeId', '=', 'schoolgrade.id')->select('schoolgrade.id as id','schoolgrade.gradeName as text')->where('schoolgrade.status',1)->distinct()->get();
                break;
            case 3://学科
                $data = DB::table('chapter')->leftJoin('studysubject','chapter.subjectId','=','studysubject.id')->select('studysubject.id as id','studysubject.subjectName as text')->where('chapter.gradeId',$grade)->distinct()->get();
                break;
            case 4://版本
                $data = DB::table('chapter')->leftJoin('studyedition','chapter.editionId','=','studyedition.id')->select('studyedition.id as id','studyedition.editionName as text')->where('chapter.gradeId',$grade)->where('chapter.subjectId',$subject)->distinct()->get();
                break;
            case 5://册别
                $data = DB::table('chapter')->leftJoin('studyebook','chapter.bookId','=','studyebook.id')->select('studyebook.id as id','studyebook.bookName as text')->where('chapter.gradeId',$grade)->where('chapter.subjectId',$subject)->where('chapter.editionId',$edition)->distinct()->get();
                break;
            case 6:
                $data = DB::table('chapter')->select('id','chapterName as text')->where(['gradeId'=>$grade,'subjectId'=>$subject,'editionId'=>$edition,'bookId'=>$book])->get();
                break;
        }
        return response()->json($data);
    }

    /**
     * 添加资源接口
     */
    public function addResource(){
        try{
            foreach ($_POST['data'] as &$v){
                $v['resourceTitle'] = Filter::filter($v['resourceTitle']);
                $v['resourceIntro'] = Filter::filter($v['resourceIntro']);
            }
        }catch (Exception $e){
            Log::info('添加资源敏感词过滤报错：'.$e->getMessage());
        }
        if(DB::table('resource')->insert($_POST['data']))
            return response()->json(['status'=>true]);
        else
            return response()->json(['status'=>false]);
    }
}