<?php

namespace App\Http\Controllers\Home;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Messages;
use Hash;
use DB;
use URL;

class indexController extends Controller
{
    public function chuti()
    {
        return view('home.kaoshi.chuti');
    }
    public function test()
    {
        return view('home.yonghu.test');
    }
    /**
     * 首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $banners = DB::table('banner')->where('status',0)->select('path','url','bgColor')->get();
        $frids   = DB::table('partner')->select('title','path','url')->where('status',0)->orderBy('postion','asc')->get();
        if(Auth::check()){
            $url = Auth::user()->type == 2 ? '/member/teacherHomePage/'.Auth::user()->id.'#wholeNotice':'/member/studentHomePage/'.Auth::user()->id.'#wholeNotice';
            return redirect($url);
        }
        return view('home.index',compact('banners','frids'));
    }

    /**
     * 名师介绍接口
     *
     * @return json
     */
    public function getteachers()
    {

        $teachers = DB::table('hotteacher')
            ->leftJoin('users', 'users.id', '=', 'hotteacher.teacherId')
//            ->leftJoin('teacher', 'teacher.parentId', '=', 'users.id')
            ->select('users.id as id', 'users.realname as name', 'users.stuMajor as org','users.cover as img','users.intro as info')
            ->where('users.checks',0)
            ->where('hotteacher.sort','<>',0)
            ->orderBy('hotteacher.sort', 'asc')
            ->skip(0)->take(6)
            ->get();
        
//        $teachers = [
//            [
//                'id'    =>'1',
//                'img'   => 'home/image/index/js.jpg',
//                'name'   => '吴迎',
//                'org'   => '中央音乐学院',
//                'info'   => '吴迎，男，1957年3月1日出生于上海，5岁随母亲学习钢琴1976年毕业于中央音乐学院。1979年获中国北方地区钢琴比赛第一名；1980年获全国钢琴选拔赛第一名。1982年毕业于朱工一教授的研究班，获硕士学位。',
//            ]
//        ];
        
        if($teachers){
            return response()->json(['status'=>true,'data'=>$teachers]);
        }else{
            return response()->json(['status'=>false]);
        }
    }

    /**
     * 专题课程接口
     *
     * @return json
     */
    public function getSpecialLessons()
    {
        $courses = DB::table('hotcourse')
            ->leftJoin('course', 'course.id', '=', 'hotcourse.courseId')
            ->leftJoin('subjects', 'course.subjectId', '=', 'subjects.id')
            ->select('course.id as id', 'course.coursePic as img', 'course.courseTitle as title','course.courseStudyNum as countpeople','course.coursePrice as price','course.courseType as courseType','course.courseDiscount as courseDiscount','course.completecount','subjects.subjectname')
            ->where('course.courseStatus',0)
            ->where('course.courseIsDel',0)
            ->where('hotcourse.sort','<>',0)
            ->orderBy('hotcourse.sort', 'asc')
            ->skip(0)->take(12)
            ->get();

        if($courses){
            foreach ($courses as &$course){
                $course -> counttime = DB::table('coursechapter')->where('courseId',$course->id)->where('parentId','<>',0)->where('status',0)->count();
                $course -> countpeople = $course->countpeople + $course->completecount;
                if($course->courseDiscount == 0){
                    $course -> price = ceil($course->price/100);
                }else{
                    $course -> price = ceil($course->price/100*$course->courseDiscount/10000);
                }
//                $course -> countpeople = count(DB::table('courseview')->select('courseId','userId','courseType')->where(['courseId' => $course->id, 'courseType' => 0])->distinct()->get());
            }
        }

//        $data = [
//            [
//                'id'    =>'1',
//                'img'   => 'home/image/index/3.jpg',
//                'title'   => '钢琴演奏基础教程',
//                'counttime' => '10',
//                'countpeople' => '300',
//                'price'   => '600.00',
//            ],
//        ];
        if($courses){
            return response()->json(['status'=>true,'data'=>$courses]);
        }else{
            return response()->json(['status'=>false]);
        }
    }

    /**
     * 点评课程接口
     *
     * @return json
     */
    public function getCommentlLessons()
    {
        $courses = DB::table('hotreviewcourse')
            ->leftJoin('commentcourse', 'commentcourse.id', '=', 'hotreviewcourse.courseId')
            ->select('commentcourse.id as id', 'commentcourse.coursePic as img', 'commentcourse.courseTitle as title','commentcourse.teachername as teacher','commentcourse.coursePlayView as countpeople','commentcourse.coursePrice as price','commentcourse.courseType as courseType','commentcourse.courseDiscount as courseDiscount')
            ->where('commentcourse.state',2)
            ->where('commentcourse.courseStatus',0)
            ->where('commentcourse.courseIsDel',0)
            ->where('hotreviewcourse.sort','<>',0)
            ->orderBy('hotreviewcourse.sort', 'asc')
            ->skip(0)->take(8)
            ->get();

        if($courses){
            foreach ($courses as &$course){
                if($course->courseDiscount==0){
                    $course -> price = ceil($course->price/100);
                }else{
                    $course -> price = ceil($course->price/100*$course->courseDiscount/10000);
                }
//                $course -> countpeople = count(DB::table('courseview')->select('courseId','userId','courseType')->where(['courseId' => $course->id, 'courseType' => 1])->distinct()->get());
            }
        }
//        $data = [
//            [
//                'id'    =>'1',
//                'img'   => 'home/image/index/2.jpg',
//                'title'   => '肖邦第三章',
//                'teacher' => '吴大海',
//                'countpeople' => '300',
//                'price'   => '600.00',
//            ]
//        ];

        if($courses){
            return response()->json(['status'=>true,'data'=>$courses]);
        }else{
            return response()->json(['status'=>false]);
        }
    }

    /**
     * 登陆
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $ignorearr = [
            'http://'.$_SERVER['HTTP_HOST'].'/index/login',
            'http://'.$_SERVER['HTTP_HOST'].'/index/register'
        ];
        if(!in_array(URL::previous(),$ignorearr)){
            if(!preg_match ('/http:\/\/'.$_SERVER['HTTP_HOST'].'\/index\/resetpsd\/\d{11}/', URL::previous()))
            session(['lastUrl' => URL::previous()]);
        }
        return view('home.users.login');
    }

    /**
     * 切换账号
     *
     * @return \Illuminate\Http\Response
     */
    public function switchs()
    {
        session(['lastUrl' => '/']);
        Auth::logout();
        return view('home.users.login');
    }

    /**
     * 介绍视频接口
     *
     * @return json
     */
    public function introVdo()
    {
        $data = DB::table('loginvideo')->select('pic','video')->orderBy('id','desc')->first();
        return response()->json($data);
    }

    /**
     * 登陆验证接口
     *
     * @return result
     */
    public function getCheckRes()
    {
        $uname = $_POST['username'];
        $password = $_POST['password'];

        //判断账号是否存在
        if($username = DB::table('users')->select('username')->where('username',$uname)->where('type','<>',3)->first()){
            $psd = DB::table('users')->select('password')->where('username',$uname)->first()->password;
            if(Hash::check($password,$psd)){
                if($userstatus = DB::table('users')->select('checks')->where('username',$uname)->where('type','<>',3)->first()->checks==0){
                    echo 2;  //正常用户
                }else{
                    echo 4;  //状态被锁
                }
            }else{
                echo 3;  //密码错误
            }
        }elseif($phone = DB::table('users')->select('phone')->where('phone',$uname)->where('type','<>',3)->first()){
            $psd = DB::table('users')->select('password')->where('phone',$uname)->first()->password;
            if(Hash::check($password,$psd)){
                if($userstatus = DB::table('users')->select('checks')->where('phone',$uname)->where('type','<>',3)->first()->checks==0){
                    echo 2;  //正常用户
                }else{
                    echo 4;  //状态被锁
                }
            }else{
                echo 3;  //密码错误
            }
        }else{
            echo 1; //账号不存在;
        }

    }

    /**
     * 注册
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('home.users.register');
    }

    /**
     * 注册用户名验证接口
     *
     * @return result
     */
    public function getCheckUname()
    {
        $uname = $_POST['username'];
        if ($username = DB::table('users')->select('username')->where('username',$uname)->first()){
            echo 1;  //改用户名已被注册
        }else{
            echo 2;  //用户名可用
        }
    }

    /**
     * 注册-手机号验证接口
     *
     * @return result
     */
    public function getCheckUphone()
    {
        $uphone = $_POST['phone'];
        if ($username = DB::table('users')->select('phone')->where('phone',$uphone)->first()){
            echo 1;  //改手机号已被注册
        }else{
            echo 2;  //手机号可用
        }
    }

    /**
     * 获取手机验证码接口
     *
     * @return String
     */
    public function getMessage(Messages $message, $telephone)
    {
//        $value = $request->cookie('code');
//        if ($value) {
//            dd($value);
//        }
//        $response = new Response('Hello Wudi');
//        $response->withCookie('code', 'test', 1);
//        return $response;


        $code    = $message::createCode();
        $content = '您好，欢迎使用创课在线云教育平台：您的手机验证码'.$code.'【创课教育】';
        $message::setInfo($telephone,$content);
        $result = $message::sendMsg();
        //return $message::response($result,$code);
        if($result > 1){
            $response = new Response('Hello Wudi');
            $response->withCookie('code', $code, 1.5);
            return $response;
        }else{
            $response = new Response($result);
            return $response;
        }
    }

    /**
     * 手机验证码验证接口
     *
     * @return json
     */
    public function checkCode(Request $request,$code)
    {
        $value = $request->cookie('code');
        if ($value == $code) {
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 注册-邀请码验证接口
     *
     * @return result
     */
    public function getInviteCode($code)
    {
        if ($username = DB::table('users')->select('yaoqingma')->where('yaoqingma',$code)->first()){
            echo 1;  //改邀请码存在
        }else{
            echo 2;  //改邀请码不存在
        }
    }

    /**
     * 判断用户类型
     *
     * @return \Illuminate\Http\Response
     */
    public function judge()
    {
        $url = Auth::user()->type == 2 ? '/member/teacherHomePage/'.Auth::user()->id.'#wholeNotice':'/member/studentHomePage/'.Auth::user()->id.'#wholeNotice';
        return redirect($url);
    }

    /**
     * 注册成功（老师）
     *
     * @return \Illuminate\Http\Response
     */
    public function registsucesstea()
    {
        return view('home.users.registsucesstea');
    }

    /**
     * 注册成功（学生）
     *
     * @return \Illuminate\Http\Response
     */
    public function registsucessstu()
    {
        return view('home.users.registsucessstu');
    }

    /**
     * 完善信息
     *
     * @return \Illuminate\Http\Response
     */
    public function infotea()
    {
        if(Auth::check()){
            //完善信息
            $id = Auth::user()->id;
            unset($_POST['_token']);
            DB::table('users')->where('id', $id)->update($_POST);
            //添加用户通知
            $cIP = getenv('REMOTE_ADDR');
            $cIP1 = getenv('HTTP_X_FORWARDED_FOR');
            $cIP2 = getenv('HTTP_CLIENT_IP');
            $cIP1 ? $cIP = $cIP1 : null;
            $cIP2 ? $cIP = $cIP2 : null;

            DB::table('usermessage')->insert(
                ['username' => Auth::user()->username, 'type' => 1,'content'=>"恭喜您成功加入创课在线云教育", 'created_at' => Carbon::now()]
            );

            //注册有邀请码
            if(Auth::user()->fromyaoqingma){
                $fromcode = Auth::user()->fromyaoqingma;
                $selfcode = Auth::user()->yaoqingma;
                if($touser = DB::table('users')->where('yaoqingma', $fromcode)->select('id')->first()){
                    $toid   = $touser->id;
                    $fromid = Auth::user()->id;
                    DB::table('friends')->insert([
                        ['fromUserId'=>$fromid,'toUserId'=>$toid,'created_at'=>Carbon::now()],
                        ['fromUserId'=>$toid,'toUserId'=>$fromid,'created_at'=>Carbon::now()]
                    ]);
                }
            }
        }

        return redirect('/');
    }

    /**
     * 完善信息（学生）
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public function infostu()
    {
        //完善信息
        if(Auth::check()){
            $id = Auth::user()->id;
            unset($_POST['_token']);
            DB::table('users')->where('id', $id)->update($_POST);
            //添加用户通知
            $cIP = getenv('REMOTE_ADDR');
            $cIP1 = getenv('HTTP_X_FORWARDED_FOR');
            $cIP2 = getenv('HTTP_CLIENT_IP');
            $cIP1 ? $cIP = $cIP1 : null;
            $cIP2 ? $cIP = $cIP2 : null;

            DB::table('usermessage')->insert(
                ['username' => Auth::user()->username, 'type' => 1,'content'=>"恭喜您成功加入创课在线云教育，您的邀请码是：".Auth::user()->yaoqingma,'client_ip'=>$cIP, 'created_at' => Carbon::now()]
            );

            //注册有邀请码
            if(Auth::user()->fromyaoqingma){
                $fromcode = Auth::user()->fromyaoqingma;
                $selfcode = Auth::user()->yaoqingma;
                if($touser = DB::table('users')->where('yaoqingma', $fromcode)->select('id')->first()){
                    $toid   = $touser->id;
                    $fromid = Auth::user()->id;
                    DB::table('friends')->insert([
                        ['fromUserId'=>$fromid,'toUserId'=>$toid,'created_at'=>Carbon::now()],
                        ['fromUserId'=>$toid,'toUserId'=>$fromid,'created_at'=>Carbon::now()]
                    ]);
                }
            }
        }

        return redirect('/');
    }
    */
    
    /**
     * 获取省接口
     *
     * @return result
     */
    public function getProvince()
    {
        $provinces = DB::table('province')->select('code as id','name as text')->get();
        return response()->json($provinces);
    }

    /**
     * 获取市接口
     *
     * @return result
     */
    public function getCity($code)
    {
        $citys = DB::table('city')->select('code as id','name as text')->where('provincecode',$code)->get();
        return response()->json($citys);
    }

    /**
     * 找回密码
     *
     * @return \Illuminate\Http\Response
     */
    public function retrievepsd()
    {
        return view('home.users.retrievepsd');
    }

    /**
     * 重新设置密码
     *
     * @return \Illuminate\Http\Response
     */
    public function resetpsd($phone)
    {
        return view('home.users.resetpsd');
    }

    /**
     * 重新设置接口
     *
     * @return result
     */
    public function resetPassword()
    {
        $uphone = $_POST['phone'];
        $upassword = bcrypt($_POST['password']);
        if(DB::table('users')->where('phone',$uphone)->update(['password'=>$upassword])){
            echo 1;  //修改成功
        }else{
            echo 2;  //修改失败
        }

    }

    /**
     * 活动赛事
     *
     * @return \Illuminate\Http\Response
     */
    public function games()
    {
        return view('home.games.games');
    }

    /**
     * 活动赛事接口
     *
     * @return json
     */
    public function getgames($type,$pageNumber,$pageSize)
    {
        $skip = ($pageNumber-1) * $pageSize;
        $data = DB::table('activity')->select('id','path as img','title','beginTime as starttime','endTime as endtime','activityRrom as org','url','created_at')
            ->where('status',$type)
            ->skip($skip)->take($pageSize)
            ->orderBy('created_at','desc')
            ->get();
        $count = DB::table('activity')->select('id')
            ->where('status',$type)
            ->count();
//        $data1 = [
//            [
//                'id'    =>'1',
//                'img'   => 'home/image/index/ss.png',
//                'title' => '2016年亚洲国际青少年杯邀请赛1',
//                'starttime'  => '2016-02-29 15:22:00',
//                'endtime'  => '2016-02-29 16:22:00',
//                'org'   => '中央音乐学院'
//            ]
//        ];

//        return response()->json($data);


        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }

    /**
     * 搜索
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $search = $_POST['search'];
        $type   = $_POST['type'];

        $searchInfo = $this->substr_cut($search,6);

        if($type == 'all'){
            return view('home.search.search',compact('search','searchInfo'));
        }elseif($type == 'course'){
            return view('home.search.searcha',compact('search','searchInfo'));
        }else{
            return view('home.search.searchb',compact('search','searchInfo'));
        }
    }

    function substr_cut($str_cut,$length)
    {
        if (mb_strlen($str_cut,'utf-8') > $length)
        {
            $str_cut = mb_substr($str_cut,0,6,'utf-8')."..";
        }
        return $str_cut;
    }

    /**
     * 搜索专题课程接口
     *
     * @return json
     */
    public function getCourseaa($search,$pageNumber,$pageSize,$order = 0)
    {
        $search = trim($search);
        $skip = ($pageNumber-1) * $pageSize;
        $count = DB::table('course')
            ->select('id')
            ->where('courseTitle','like','%'.$search.'%')
            ->where('courseStatus',0)
            ->where('courseIsDel',0)
            ->count();
        if($order == 0){
            $data = DB::table('course')
                ->select('id as id', 'coursePic as img', 'courseTitle as title','coursePlayView as countpeople','coursePrice as price','courseDiscount')
                ->where('courseTitle','like','%'.$search.'%')
                ->where('courseStatus',0)
                ->where('courseIsDel',0)
                ->skip($skip)->take($pageSize)
                ->get();
        }elseif($order == 1){
            $data = DB::table('course')
                ->select('id as id', 'coursePic as img', 'courseTitle as title','coursePlayView as countpeople','coursePrice as price','courseDiscount')
                ->where('courseTitle','like','%'.$search.'%')
                ->where('courseStatus',0)
                ->where('courseIsDel',0)
                ->orderBy('created_at', 'desc')
                ->skip($skip)->take($pageSize)
                ->get();
        }else{
            $data = DB::table('course')
                ->select('id as id', 'coursePic as img', 'courseTitle as title','coursePlayView as countpeople','coursePrice as price','courseDiscount')
                ->where('courseTitle','like','%'.$search.'%')
                ->where('courseStatus',0)
                ->where('courseIsDel',0)
                ->orderBy('coursePlayView', 'desc')
                ->skip($skip)->take($pageSize)
                ->get();
        }

        if($data){
            foreach ($data as &$course){
                $course -> counttime = DB::table('coursechapter')->where('courseId',$course->id)->where('parentId','<>',0)->where('status',0)->count();
                if($course->courseDiscount == 0){
                    $course -> price = ceil($course->price/100);
                }else{
                    $course -> price = ceil($course->price/100*$course->courseDiscount/10000);
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
     * 搜索点评课程接口
     *
     * @return json
     */
    public function getCoursebb($search,$pageNumber,$pageSize,$order = 0)
    {
        $search = trim($search);
        $skip = ($pageNumber-1) * $pageSize;
        $count = DB::table('commentcourse')
            ->select('id')
            ->where('courseTitle','like','%'.$search.'%')
            ->where('state',2)
            ->where('courseStatus',0)
            ->where('courseIsDel',0)
            ->count();
        if($order == 0){
            $data = DB::table('commentcourse')
                ->select('id', 'coursePic as img', 'courseTitle as title','teachername as teacher','coursePlayView as countpeople','coursePrice as price','courseDiscount')
                ->where('courseTitle','like','%'.$search.'%')
                ->where('state',2)
                ->where('courseStatus',0)
                ->where('courseIsDel',0)
                ->skip($skip)->take($pageSize)
                ->get();
        }elseif($order == 1){
            $data = DB::table('commentcourse')
                ->select('id', 'coursePic as img', 'courseTitle as title','teachername as teacher','coursePlayView as countpeople','coursePrice as price','courseDiscount')
                ->where('courseTitle','like','%'.$search.'%')
                ->where('state',2)
                ->where('courseStatus',0)
                ->where('courseIsDel',0)
                ->orderBy('created_at', 'desc')
                ->skip($skip)->take($pageSize)
                ->get();
        }else{
            $data = DB::table('commentcourse')
                ->select('id', 'coursePic as img', 'courseTitle as title','teachername as teacher','coursePlayView as countpeople','coursePrice as price','courseDiscount')
                ->where('courseTitle','like','%'.$search.'%')
                ->where('state',2)
                ->where('courseStatus',0)
                ->where('courseIsDel',0)
                ->orderBy('coursePlayView', 'desc')
                ->skip($skip)->take($pageSize)
                ->get();
        }

        if($data){
            foreach ($data as &$course){
                if($course->courseDiscount == 0){
                    $course -> price = ceil($course->price/100);
                }else{
                    $course -> price = ceil($course->price/100*$course->courseDiscount/10000);
                }
            }
        }

        if($data){
            return response()->json(['status'=>true,'data'=>$data,'count'=>$count]);
        }else{
            return response()->json(['status'=>false,]);
        }
    }


    //头像上传（移动端接口）
    public function editHeadImg(Request $request){
        try {
            $uid = isset($request['uid']) ? $request['uid'] : false;

            if ($request->hasFile('img')) { //判断文件是否存在
                if ($request->file('img')->isValid()) { //判断文件在上传过程中是否出错
                    $name = $request->file('img')->getClientOriginalName();//获取图片名
                    $entension = $request->file('img')->getClientOriginalExtension();//上传文件的后缀
                    $newname = md5(date('ymdhis' . $name)) . '.' . $entension;//拼接新的图片名
                    if ($request->file('img')->move('uploads/heads', $newname)) {
                        $path = '/uploads/heads/' . $newname;
                        if ($uid){
                            if (DB::table('users')->where('id', $uid)->update(['pic' => $path])) {
                                return response()->json(['state' => 1, 'message' => '成功', 'filepath' => $path]);
                            } else {
                                return response()->json(['state' => 0, 'message' => '数据库保存失败']);
                            }
                        }else{
                            return response()->json(['state' => 1, 'message' => '成功', 'filepath' => $path]);
                        }
                    } else {
                        return response()->json(['state' => 0, 'message' => '文件保存失败']);
                    }
                } else {
                    return response()->json(['state' => 0, 'message' => '上传失败']);
                }
            } else {
                return response()->json(['state' => 0, 'message' => '请选择文件']);
            }
        }catch (\Exception $e){
            Log::info($e -> getMessage() . " --- teachers 抛出异常");
            return $this -> throwError();
        }
    }

    /**
     * 获取手机验证码接口(移动端)
     *
     * @return json
     */
    public function getMessages(Messages $message)
    {
        $telephone = $_POST['phone'];
        $type = $_POST['type'];

        $code    = $message::createCode();
        if ($type == 1) {    //注册
            if ($username = DB::table('users')->select('phone')->where('phone',$telephone)->first()){
                return response()->json(['type' => false, 'info' => '该手机号已被注册']);
            }
            $content = '您好，欢迎使用创课在线云教育平台：您的手机验证码'.$code. '【创课教育】';
        }elseif($type == 2){ //找回密码
            $content = '您好，创课在线云教育平台找回密码：您的手机验证码' . $code . '【创课教育】';
        }else{               //切换手机号
            $content = '您好，创课在线云教育平台修改手机号：您的手机验证码' . $code . '【创课教育】';
        }
        $message::setInfo($telephone,$content);
        $result = $message::sendMsg();

        if($result > 1){
            return Response() -> json(["type" => true, "info" => $code]);
        }else{
            return Response() -> json(["type" => false, "info" => '错误状态值'.$result]);
        }
    }
}
