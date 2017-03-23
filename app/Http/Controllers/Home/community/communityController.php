<?php

namespace App\Http\Controllers\Home\community;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use DB;
use URL;
use Filter;

class communityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.community.community');
    }


    /**
     * 社区首页新闻数据接口
     */
    public function getlist(){
        $getlist = DB::table('news')->select('id','title','description','created_at')->where('status',0)->where('sort','!=',0)->orderBy('sort','asc')->limit(6)->get();
        if($getlist){
            foreach ($getlist as $k => $v) {
                //只保留 年月日
                $created_ats = explode(" ", $v->created_at);
                $data['data'][] = [
                    'id' => $v->id,
                    'title' => $v->title,
                    'description' => $v->description,
                    'time'  => $created_ats[0]
                ];
            }
            $data['statuss'] = true;
        }else {
            $data['statuss'] = false;
        }
        echo json_encode($data);
    }

    /**
     * 最热视频数据接口
     */
    public function gethotvideo(){
        $data = DB::table('hotvideo')->select('id','title','coursePath','cover')->where('sort','>',0)->where('status',0)->orderBy('sort','asc')
            ->limit(9)->get();
        if($data){
            return response()->json(['statuss'=>true,'data'=>$data]);
        }else{
            return response()->json(['statuss'=>false]);
        }
    }


    /**
     * 名师列表数据接口
     */
    public function getteacher(){
        $data = DB::table('users as u')
            ->join('recteacher as rec','u.id','=','rec.userId')
            ->select('u.id','u.realname','u.stuMajor as college','u.course','u.pic')
            ->where(['u.type' => 2 , 'u.checks' => 0])
            ->where('sort','<>',0)
            ->orderBy('rec.sort','asc')
            ->limit(6)
            ->get();
        if($data){
            foreach($data as $key=>$value){
                $data[$key] -> college ? $data[$key] -> college : $data[$key] -> college = '暂无';
                $data[$key] -> course ? $data[$key] -> course : $data[$key] -> course = '暂无';
                $data[$key]->count = DB::table('question')->where(['teaId'=>$data[$key]->id , 'status' => 2])->count();
            }
            return response()->json(['statuss'=>true,'data'=>$data]);
        }else{
            return response()->json(['statuss'=>false]);
        }
    }


    /**
     * 最新学员数据接口
     */
    public function getstudent(){
        $data = DB::table('users')->select('id','username as name','realname','pic')->where('type',2)->where('checks',0)
            ->orderBy('created_at','desc')
            ->limit(30)
            ->get();
        foreach ($data as &$v){
            $v->realname = $v->realname ? $v->realname : $v->name;
        }
        if($data){
            return response()->json(['statuss'=>true,'data'=>$data]);
        }else{
            return response()->json(['statuss'=>false]);
        }
    }



    //问答回复提交
    public function qesreply(Request $request){
        $input = $request->all();
        try {
            $_POST['answerContent'] = Filter::filter($_POST['answerContent']);
        } catch (\Exception $e) {
            $_POST['answerContent'] = $_POST['answerContent'];
        }
//        dd($input);
        $_POST['created_at'] = Carbon::now();
        $_POST['username'] = \Auth::user()->username;


        $a = [
            'username'=>$_POST['username'],
            'content'=>$_POST['username'] . '回答了您的提问' . $input['content'],
            'toUsername'=>$input['uname'],
            'courseType'=>'1',
            'fromUsername'=>\Auth::user()->username,
            'actionId'=>$input['qesId'],
            'type'=> 7,
            'created_at'=>Carbon::now()];
        DB::table('usermessage')->insertGetId($a);

        unset($_POST['uname']);
        unset($_POST['content']);
//        dd($_POST);

        if($res = DB::table('questioncomment')->insert($_POST)){
            //更新user表中回答数
            $anscount = DB::table('questioncomment')->where('username','=',\Auth::user()->username)->where('parentId','=',0)->count();
            DB::table('users')->where('username','=',\Auth::user()->username)->update(['answercount'=>$anscount]);
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    //问答评论提交
    public function qesreplys(Request $request){
        $input = $request->all();
        $userId = \Auth::user()->id;


        try {
            $_POST['answerContent'] = Filter::filter($_POST['answerContent']);
        } catch (\Exception $e) {
            $_POST['answerContent'] = $_POST['answerContent'];
        }

        $_POST['created_at'] = Carbon::now();
        $_POST['username'] = \Auth::user()->username;

        if(!isset($input['tousername'])){
            unset($_POST['stuName']);
            unset($_POST['teaName']);
        }

        $a = [
            'username'=>$_POST['username'],
            'content'=>$_POST['username'] . '评论了您的课程' . $input['answerContent'],
            'toUsername'=>$input['tousername'],
            'courseType'=>'1',
            'fromUsername'=>\Auth::user()->username,
            'actionId'=>$input['qesId'],
            'type'=> 6,
            'created_at'=>Carbon::now()];

        if($input['tousername'] != $_POST['username'] ){
            DB::table('usermessage')->insertGetId($a);
        }

//        dd($_POST);
        if($res = DB::table('questioncomment')->insert($_POST)){
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    //问答评论获取
    public function getqescomment($qesId){
        $res = DB::table('questioncomment')->where('qesId',$qesId)->where('parentId','=','0')->orderBy('isselected','desc')->orderBy('created_at','desc')->get();

        if($res){
            foreach ($res as $key => &$value) {
                $user = DB::table('users')->select('id','pic','realname','type')->where('username',$value->username)->first();
                if($user){
                    $value->uid = $user->id;
                    $value->realname = $user->realname;
                    $value->type = $user->type;
                    $value->nowloginUname = \Auth::check() ? \Auth::user()->username : '0';                               //当前登录用户
                    $value->pic = $user->pic;                                                                             //当前登录用户头像
                    $value->thumbNum = DB::table('questioncommentthumb')->where('commentId',$value->id)->count();         //该评论的点赞数量
                    $value->isthumb  = DB::table('questioncommentthumb')->where(['commentId'=>$value->id,'username'=>$value->nowloginUname])->first() ? true : false;  //判断当前用户是否赞过
                    $value->comTime  = Carbon::parse($value->created_at)->diffForHumans();
                    $value->commentCount = DB::table('questioncomment')->where('parentId','=',$value->id)->count();

                    $value->comment  = DB::table('questioncomment as q')
                        ->leftjoin('users','q.username','=','users.username')
                        ->select('q.id','q.answerContent','q.username','users.pic','q.created_at','users.realname','users.id as uids','users.type')
                        ->where('q.parentId',$value->id)
                        ->orderBy('created_at','desc')
                        ->get();
                    if($value->comment){
                        foreach($value->comment as $k => &$val){
                            $val->comTimes = Carbon::parse($val->created_at)->diffForHumans();
                        }
                    }

                    $value->commentUser = DB::table('users')->select('id','pic','username','realname')->where('id','=',\Auth::user()->id)->get();

                }
            }

            return response()->json(['status'=>true,'data'=>$res]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }



    //问答详情
    public function askDetail($qesId){
        //浏览数加一
        DB::table('question')->where('id',$qesId)->increment('views');

        if(\Auth::check()){
            //问答内容
            $data = DB::table('question')->select('id','uId','bestId','qestitle','content','answer','type','status','asktime','anstime')->where('id',$qesId)->first();

            $stu = DB::table('users')->select('id','username','pic','realname')->where('id',$data->uId)->first();
            $data->stuId = $stu->id;
            $data->uName = $stu->username;
            $data->rName = $stu->realname;
            $data->stuPic = $stu->pic;
            $data->asktime = substr($data->asktime,0,10);

            //问答类型
            if($type = DB::table('studysubject')->select('subjectName')->where('id',$data->type)->first()){
                $subtype = $type->subjectName;
            }else{
                $subtype = '';
            }

            //问答推荐 同类别，最新
            $tuijians = DB::table('question')->select('id','uId','bestId','qestitle','type','status','asktime','anstime','delete')
//            ->where('status',2)
                ->where('type',$data->type)
                ->where('delete','=',0)
                ->orderBy('asktime','desc')
                ->take(5)
                ->get();

            if($tuijians) {
                foreach ($tuijians as $key => &$value) {
                    $value->teaname = DB::table('users')->select('username','realname')->where('id',$value->uId)->first();
                    $value->answer = DB::table('questioncomment')->where('qesId',$value->id)->where('parentId','=',0)->count();
                    $value->type = $subtype;
                }
            }else{
                $tuijians = [];
            }

            return view('home.community.askdetail')->with('data',$data)->with('tuijians',$tuijians);
        }

    }




    //问答采纳
    public function cainaclick(Request $request){
        $input = $request->all();
        DB::table('questioncomment')->where('id',$input['queId'])->update(['isselected' => '1']);
//        $created_at = $_POST['created_at'] = Carbon::now();
        if($input){
            DB::table('question')->where('id',$input['qesId'])->update(['bestId'=>$input['queId'],'status'=>'2','anstime'=>date('Y-m-d H:i:s'), 'answer'=>$input['answerContent'] ]);

            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false]);
        }

    }



    /**
     * 问题反馈
     */
    public function feedback(){
        if(!\Auth::check()){
            return view('home.users.login');
        }
        return view('home.community.feedback');
    }

    public function dofeedback(){

        if(!$_POST['content']){
            return back()->with('wrong', ' 反馈内容不能为空!');
        }
        unset($_POST['_token']);
        $_POST['uid'] = \Auth::user()->id;
        $_POST['feedbacktime'] = Carbon::now();
        if(DB::table('complaint')->insert($_POST)){
            return back()->with('right', '反馈成功!');
        }else{
            return back()->with('wrong', '提交失败，请重新尝试!');
        }

    }

    //用户提问
    public function question(){
        return view('home.community.question');
    }

    public function doquestion(){
        if(!$_POST['qestitle']){
            return back()->with('wrong', ' 提问内容不能为空!');
        }
        try {
            $_POST['qestitle'] = Filter::filter($_POST['qestitle']);
        } catch (\Exception $e) {
            $_POST['qestitle'] = $_POST['qestitle'];
        }
        unset($_POST['_token']);
        $_POST['uId'] = \Auth::user()->id;
        $_POST['asktime'] = date('Y-m-d H:i:s');
        try {
            $_POST['content'] = Filter::filter($_POST['content']);
        } catch (\Exception $e) {
            $_POST['content'] = $_POST['content'];
        }
//        dd($_POST);
        if($qesId = DB::table('question')->insertGetId($_POST)){

            return back()->with('right', '提问成功!');
        }else{
            return back()->with('wrong', '提交失败，请重新尝试!');
        }

    }

    /**
     * 老师回答
     */
    public function answer($qesId,$msgId=0){
        //验证是否回答 回答了跳转到详情页，并删除通知
        if (DB::table('question')->select('id')->where('status',2)->where('id',$qesId)->first()){
            //DB::table('usermessage')->where('id',$msgId)->delete();
            return redirect()->action('Home\community\communityController@askDetail', [$qesId]);
        }

        $data = DB::table('question')->select('id','stuId','teaId','qestitle','content','type','status','asktime')
            ->where('id',$qesId)->first();

        $stu = DB::table('users')->select('id','username','pic')->where('id',$data->stuId)->first();
        $data->stuName = $stu->username;
        $data->stuid = $stu->id;
        $data->stuPic = $stu->pic;
        $data->asktime = substr($data->asktime,0,10);

        return view('home.community.answer')->with('data',$data);
    }

    public function doanswer(){
        if(!isset($_POST['answer']) || !$_POST['answer']){
            return back()->with('wrong', ' 回复内容不能为空!');
        }
        unset($_POST['_token']);

        if(DB::table('question')->where('id',$_POST['id'])->update(['answer'=>$_POST['answer'],'status'=>2,'anstime'=>date('Y-m-d H:i:s')])){
            //更新解决提问数
            $answercount = DB::table('question')->where('teaId',\Auth::user()->id)->where('status',2)->count();
            DB::table('users')->where('id',\Auth::user()->id)->update(['answercount'=>$answercount]);

            //通知
            $unanme = DB::table('users')->where('id',$_POST['stuid'])->select('username')->first()->username;
            DB::table('usermessage')->insert(
                ['username' => $unanme,'fromUsername'=>\Auth::user()->username,'type' => 2,'actionId'=>$_POST['id'], 'created_at' => Carbon::now()]
            );

            return redirect()->action('Home\community\communityController@askDetail', [$_POST['id']]);
        }else{
            return back()->with('wrong', '提交失败，请重新尝试!');
        }
    }




    //获取问答点赞、收藏状态
    public function getaskstatus($qesId){
        //dd('赞，收'.$qesId);
        $isthumb = false;
        $isfav   = false;
        if(DB::table('questionthumb')->where(['qesId'=>$qesId,'username'=>\Auth::user()->username])->first()){
            $isthumb = true;
        }
        if(DB::table('questionfav')->where(['qesId'=>$qesId,'username'=>\Auth::user()->username])->first()){
            $isfav   = true;
        }

        return response()->json(['isthumb'=>$isthumb,'isfav'=>$isfav]);

    }

    //问答点赞
    public function qesthumb($qesId){
        //dd('点赞'.$qesId);
        if($res = DB::table('questionthumb')->insert(['qesId'=>$qesId,'username'=>\Auth::user()->username])){
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false]);
        }

    }

    //问答收藏
    public function qesfav($qesId){
        //dd('收藏'.$qesId);
        $created_at = $_POST['created_at'] = Carbon::now();
        if($res = DB::table('questionfav')->insert(['qesId'=>$qesId,'username'=>\Auth::user()->username,'created_at'=>$created_at])){
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false]);
        }
    }
    //取消问答收藏
    public function qesdefav($qesId){
//        dd('取消收藏'.$qesId);
        if($res = DB::table('questionfav')->where(['qesId'=>$qesId,'username'=>\Auth::user()->username])->delete()){
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false]);
        }
    }

    //问答评论提交
    public function qescomment(Request $request){
        $input = $request->all();

        $_POST['created_at'] = Carbon::now();
        $_POST['username'] = \Auth::user()->username;

        if(!isset($input['tousername'])){
            unset($_POST['stuName']);
            unset($_POST['teaName']);
        }

        if($res = DB::table('questioncomment')->insert($_POST)){
            //发送评论回复通知
            if(isset($input['tousername'])){
                $info['toUsername'] = $input['tousername'];
                $info['fromUsername'] = \Auth::user()->username;
                $info['username'] = $input['tousername'];
                $info['type'] = 5;
                $info['actionId'] = $input['qesId'];
                $info['created_at'] = Carbon::now();
                $info['courseType'] = 1;
                DB::table('usermessage')->insertGetId($info);
            }else{//发送评论通知(分别给学生和老师发送)
                DB::table('usermessage')->insertGetId(['username'=>$input['stuName'],'fromUsername'=>\Auth::user()->username,
                    'toUsername'=>$input['stuName'],'actionId'=>$input['qesId'],'type'=>7,'created_at'=>Carbon::now()]);
                DB::table('usermessage')->insertGetId(['username'=>$input['teaName'],'fromUsername'=>\Auth::user()->username,
                    'toUsername'=>$input['teaName'],'actionId'=>$input['qesId'],'type'=>7,'created_at'=>Carbon::now()]);
            }

            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false,]);
        }

    }



    //问答评论删除
    public function delqescomment($cmId){
        // dd('删除'.$cmId);
        if(DB::table('questioncomment')->where('id',$cmId)->delete()){
            DB::table('questioncommentthumb')->where('commentId',$cmId)->delete();
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false]);
        }
    }

    //问答评论点赞
    public function favqescomment($cmId){
        if ($res = DB::table('questioncommentthumb')->insert(['commentId'=>$cmId,'username'=>\Auth::user()->username])) {
            return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false]);
        }
    }


    //个人中心我的问答接口
    public function getQuestion($type,$pageNumber,$pageSize){

        $skip = ($pageNumber-1) * $pageSize;
        if($type == 1){
            $data = DB::table('question')->select('id','uId','qestitle','type','status','asktime')
                ->where('uId',\Auth::user()->id)
                ->where('delete',0)
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

    //个人主页我的问答接口
    public function getQuestionb($uid,$usertype,$type,$pageNumber,$pageSize){

        $skip = ($pageNumber-1) * $pageSize;
        $data = DB::table('question')->select('id','uId','qestitle','type','status','asktime')
            ->where('uId',$uid)
            ->where('delete',0)
            ->skip($skip)->take($pageSize)
            ->orderBy('asktime','desc')
            ->get();
        $count = DB::table('question')
            ->where('uId',$uid)
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

        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }

    //个人主页我的收藏接口
    public function getCollectionInfo($uid,$pageNumber, $pageSize ,$ord = 'created_at')//completecount
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $course = [];
        $info = DB::table('collection')
            ->leftJoin('course','collection.courseId','=','course.id')
            ->select('collection.id','collection.courseId', 'collection.type')
            ->where('collection.userId', $uid)->orderBy('course.'.$ord, 'desc')
            ->skip($skip)->take($pageSize)->get();
        $count = DB::table('collection')->select('id','courseId', 'type')->where('userId', $uid)->count();
        if ($info) {
            foreach ($info as $key => $value) {
                if ($value->type == 0) { // 收藏课程为专题课程
                    $course[$key] = DB::table('course')->select('id', 'courseTitle', 'coursePic', 'coursePlayView', 'coursePrice', 'courseDiscount','courseStudyNum','completecount')->where('id', $value->courseId)->first();
                    if($course[$key]->courseDiscount){
                        $course[$key]->coursePrice = ceil(($course[$key]->courseDiscount/10000)*$course[$key]->coursePrice/100);
                    }else{
                        $course[$key]->coursePrice = ceil($course[$key]->coursePrice/100);
                    }
                    $course[$key]->classHour = DB::table('coursechapter')->where(['courseId' => $course[$key]->id, 'status' => 0])->where('parentId', '<>', '0')->count();
                    $course[$key]->isCourse = 0;
                    $course[$key]->href = '/lessonSubject/detail/' . $course[$key]->id;
                    $course[$key]->collectId = $value->id;
                    $course[$key]->coursePlayView = $course[$key]->courseStudyNum + $course[$key]->completecount;
                } else if($value->type == '1'){ // 收藏课程为点评课程
                    $course[$key] = DB::table('commentcourse')->select('id', 'courseTitle', 'coursePic', 'coursePlayView', 'coursePrice', 'teachername')->where('id', $value->courseId)->first();
                    $course[$key]->coursePrice = ceil($course[$key]->coursePrice / 100);
                    $course[$key]->isCourse = 1;
                    $course[$key]->href = '/lessonComment/detail/' . $course[$key]->id;
                    $course[$key]->collectId = $value->id;
                }
            }
            return response()->json(['data' => $course, 'total' => $count, 'status' => true]);
        } else {
            return response()->json(['total' => $count, 'status' => false]);
        }
    }

    //个人中心获取收藏问答接口
    public function getcolQuestion($pageNumber,$pageSize){
        //dd('获取收藏问答');
        $skip = ($pageNumber-1) * $pageSize;
        $data = DB::table('questionfav')
            ->leftJoin('question','questionfav.qesId','=','question.id')
            ->select('questionfav.id','questionfav.qesId','questionfav.username')
            ->where('questionfav.username',\Auth::user()->username)
            ->where('question.delete',0)
            ->skip($skip)->take($pageSize)
            ->orderBy('questionfav.id','desc')
            ->get();
        $count = DB::table('questionfav')
            ->leftJoin('question','questionfav.qesId','=','question.id')
            ->where('questionfav.username',\Auth::user()->username)
            ->where('question.delete',0)
            ->count();

        foreach ($data as $k => &$v){

            //获取提问信息
            if($aa=DB::table('question')->select('uId','qestitle','type','status','asktime')->where('id',$v->qesId)->first()){
                $v->qestitle = $aa->qestitle;
                //$v->type = $aa->type;
                $v->status = $aa->status;
                $v->asktime = $aa->asktime;

                if($bb=DB::table('studysubject')->where('id',$aa->type)->select('subjectName')->first()){
                    $v->type=$bb->subjectName;
                }
                //获取提问用户头像
                if($aa=DB::table('users')->select('pic')->where('id',$aa->uId)->first()){
                    $v->pic = $aa->pic;
                }else{
                    $v->pic = '/home/image/layout/default.png';
                }
            }
        }

        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }

    }

    //老师推荐(创客回答榜)
    public function getteachers()
    {

        $teachers = DB::table('users')
//            ->select('id','realname','pic','answercount as count','stuMajor')
            ->select('id','realname','pic','answercount as count','type')
            ->where('checks',0)
            ->where('type','!=','3')
            ->orderBy('count','desc')
            ->take(6)
            ->get();


        if($teachers){
            return response()->json(['status'=>true,'data'=>$teachers]);
        }else{
            return response()->json(['status'=>false]);
        }
    }

    //问答列表接口
    public function getquestions($type,$pageNumber,$pageSize,$subid,$iswaitans)
    {
        if($iswaitans == 'false'){
            $skip = ($pageNumber-1) * $pageSize;

            $havedata = true;

            if ($subid == 0) {
                $where = ['question.status'=>2];
            }else{
                $where = ['question.status'=>2,'studysubject.id'=>$subid];
            }

            $count = DB::table('question')
                ->leftJoin('studysubject','question.type', '=', 'studysubject.id')
                ->where('question.delete',0)
                ->where($where)
                ->count();


            if(!$count){
                $havedata = false;

                $count = DB::table('question')
                    ->leftJoin('studysubject','question.type', '=', 'studysubject.id')
                    ->where('question.delete',0)
                    ->where('question.status',2)
                    ->count();
                $data = DB::table('question')
                    ->leftJoin('studysubject','question.type', '=', 'studysubject.id')
                    ->select('question.id','question.qestitle','question.uId','question.bestId','studysubject.subjectName','question.answer','question.views')
                    ->where('question.delete',0)
                    ->where('question.status',2)
                    ->skip($skip)->take(4)
                    ->orderBy('question.'.$type,'desc')
                    ->get();
            }else{
                $data = DB::table('question')
                    ->leftJoin('studysubject','question.type', '=', 'studysubject.id')
                    ->select('question.id','question.qestitle','question.uId','question.bestId','studysubject.subjectName','question.answer','question.views')
                    ->where('question.delete',0)
                    ->where($where)
                    ->skip($skip)->take($pageSize)
                    ->orderBy('question.'.$type,'desc')
                    ->get();
            }

            foreach ($data as $k => &$v) {
                $bb = DB::table('questioncomment')
                    ->leftJoin('users','questioncomment.username','=','users.username')
                    ->select('users.username','users.realname')
                    ->where('questioncomment.id',$v->bestId)
                    ->first();
                if($bb){
                    $v->teaname = $bb->realname ? $bb->realname : $bb->username;
                }
                if($aa = DB::table('users')->select('pic')->where('id',$v->uId)->first()){
                    $v->stupic = $aa->pic;
                }else{
                    $v->stupic = '/home/image/layout/default.png';
                }
                $v->thumb = DB::table('questioncomment')->where('qesId',$v->id)->where('parentId',0)->count();
            }

            if($data){
                return response()->json(['status'=>true,'data'=>$data,'count'=>$count,'havedata'=>$havedata]);
            }else{
                return response()->json(['status'=>false]);
            }
        }else{//待回答提问列表
            //dd('待回答提问列表');
            $skip = ($pageNumber-1) * $pageSize;

            $havedata = true;

            if ($subid == 0) {
                $where = ['question.status'=>1];
            }else{
                $where = ['question.status'=>1,'studysubject.id'=>$subid];
            }

            $count = DB::table('question')
                ->leftJoin('studysubject','question.type', '=', 'studysubject.id')
                ->where('question.delete',0)
                ->where($where)
                ->count();

            if(!$count){

                $havedata = false;

                $count = DB::table('question')
                    ->leftJoin('studysubject','question.type', '=', 'studysubject.id')
                    ->where('question.delete',0)
                    ->where('question.status',1)
                    ->count();

                $data = DB::table('question')
                    ->leftJoin('studysubject','question.type', '=', 'studysubject.id')
                    ->select('question.id','question.qestitle','question.uId','studysubject.subjectName','question.views')
                    ->where('question.delete',0)
                    ->where('question.status',1)
                    ->skip($skip)->take(6)
                    ->orderBy('question.asktime','desc')
                    ->get();
            }else{
                $data = DB::table('question')
                    ->leftJoin('studysubject','question.type', '=', 'studysubject.id')
                    ->select('question.id','question.qestitle','question.uId','studysubject.subjectName','question.views')
                    ->where('question.delete',0)
                    ->where($where)
                    ->skip($skip)->take(8)
                    ->orderBy('question.asktime','desc')
                    ->get();
            }



            foreach ($data as $k => &$v) {
                if($aa = DB::table('users')->select('pic')->where('id',$v->uId)->first()){
                    $v->stupic = $aa->pic;
                }else{
                    $v->stupic = '/home/image/layout/default.png';
                }
            }

            if($data){
                return response()->json(['status'=>true,'data'=>$data,'count'=>$count,'havedata'=>$havedata]);
            }else{
                return response()->json(['status'=>false]);
            }

        }


    }


    //创客科目列表获取接口
    public function getSubjects(){
        $provinces = DB::table('studysubject')->select('id as id','subjectName as text')->get();
        array_unshift($provinces,['id'=>0,'text'=>'全部']);
        return response()->json($provinces);
    }

    //老师所受科目 列表获取接口
    public function getteaSubjects(){
//        $res = DB::table('teachersubjects')->where('user_id',$teaId)->select('type_id as id')->get();
//        foreach ($res as &$v){
//            $v->text = DB::table('subjects')->where('id',$v->id)->select('subjectname')->first()->subjectname;
//        }
        $res = DB::table('studysubject')->select('id','subjectName as text')->get();


        return response()->json($res);
    }



}
