<?php

namespace App\Http\Controllers\Admin\commentCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Home\lessonComment\Gadget;
use PaasResource;
use PaasUser;
use Cache;
use Messages;

class teacherCourseController extends Controller
{
    use Gadget;
    public function __construct()
    {
        PaasUser::apply();
    }
    /**
     *名师点评列表
     */
    public function teacherCourseList(Request $request){
        $query = DB::table('commentcourse as a');
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('a.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('a.created_at','<=',$request['endTime']);
        }
        //审核状态
        if($request['state'] === '0' || $request['state'] === '1' || $request['state'] === '2'){
            $query = $query->where('a.state',$request['state']);
        }
        if($request['type'] == 1){
            $query = $query->where('a.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('a.orderSn','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('a.courseTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 4){
            $query = $query->where('a.username','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 5){
            $query = $query->where('ut.realname','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('users as u','a.userId','=','u.id')
            ->leftJoin('users as ut','a.teacherId','=','ut.id')
            ->where('a.courseIsDel',0)
            ->select('a.*','u.userName','ut.realname as teacherName','ut.username as teacherusername','u.phone as studentPhone')
            ->orderBy('a.id','desc')
            ->paginate(15);
        foreach($data as &$val){
            if($val->courseLowPath){
                if(!Cache::get($val->courseLowPath)){
                    $val->courseLowPathurl = $this->getPlayUrl($val->courseLowPath);
                    Cache::put($val->courseLowPath,$val->courseLowPathurl,1800);
                }else{
                    $val->courseLowPathurl = Cache::get($val->courseLowPath);
                }
            }

//            if($val->coursePic){
//                if(!Cache::get($val->coursePic)){
//                    $val->coursePic = $this->getPlayUrl(($val->coursePic));
//                    Cache::put($val->coursePic,$val->coursePic,1800);
//                }else{
//                    $val->coursePic = Cache::get($val->coursePic);
//                }
//            }

            if(!$val->courseLowPath || !$val->courseMediumPath || !$val->courseHighPath){
                $FileList = $this->transformations($val->fileID);
//                dump($FileList);
                //返回的状态值
                $val->msg['message'] = $FileList['message'];
                $val->msg['code'] = $FileList['code'];

                if($FileList['code'] == 200 && $FileList['data']['Waiting'] < 0){
                    $filelists = $FileList['data']['FileList']; //取出转好的码
//                    dump('aa');
                    $lists = [];
                    foreach($filelists as $value){
                        switch($value['Level']){
                            case 1:
                                $lists['courseLowPath'] = $value['FileID'];
//                                $lists['coursePic'] = $value['Cover'];
                                break;
                            case 2:
                                $lists['courseMediumPath'] = $value['FileID'];
//                                $lists['coursePic'] = $value['Cover'];
                                break;
                            case 3:
                                $lists['courseHighPath'] = $value['FileID'];
//                                $lists['coursePic'] = $value['Cover'];
                                break;
                        }
                    }
                    if($lists){
                        DB::table('commentcourse')->where('id',$val->id)->update($lists);
                    }
                }
            }

            $val->coursePrice = $val->coursePrice / 100;
            $val->courseDiscount = $val->courseDiscount / 1000;
            if($val->courseType ==1){
                $val->discountPrice = ceil(($val->coursePrice) * $val->courseDiscount / 10);
            }else{
                $val->discountPrice = $val->coursePrice;
            }

            if($val->courseType){
                $coursetype = DB::table('coursetype')->where('id',$val->courseType)->first();
                $val->typeName = $coursetype->typeName;
            }else{
                $val->typeName = '无促销';
            }
        }
        $data->type = $request['type'];
        $data->status = $request['state'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
//        dd($data);
        return view('admin.teacherCourse.teacherCourseList',['data'=>$data]);
    }

    /**
     *审核状态
     */
    public function teacherState(Request $request){
        $data['state'] = $request['state'];
        $data['lastCheckTime'] = Carbon::now();
        $data = DB::table('commentcourse')->where('id',$request['id'])->update($data);
        $this -> OperationLog('修改了id为'.$request['id'].'辅导课程的审核状态');
        if($request['state'] == 0){
            DB::table('orders')->where('orderSn',$request['orderSn'])->update(['status'=>1]);
            $arr = array('state'=>'0','msg'=>'审核未通过');
        }elseif($request['state'] == 1){
            //将发给名师的未通过原因删除
            if(DB::table('usermessage')->where(['username'=>$request['teachername'],'actionId'=>$request['id'],'type'=>0])->first()){
                DB::table('usermessage')->where(['username'=>$request['teachername'],'actionId'=>$request['id'],'type'=>0])->delete();
            }

            //如是是审核中时将发给学员的消息删除
            if(DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['id'],'type'=>2])->first()){
                DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['id'],'type'=>2])->delete();
            }
            //给学员的所有粉丝发送的消息一并删除
            $fans = DB::table('friends')->where('toUserId',$request['userId'])->get();
            if($fans){
                foreach ($fans as $val) {
                    $fansname = DB::table('users')->where('id',$val->fromUserId)->where('checks',0)->select('username')->first();//取出粉丝的用户名
                    //删除已有的通知
                    if(DB::table('usermessage')->where(['username'=>$fansname->username,'actionId'=>$request['id'],'type'=>4])->first()){
                        DB::table('usermessage')->where(['username'=>$fansname->username,'actionId'=>$request['id'],'type'=>4])->delete();
                    }
                }
            }
            DB::table('orders')->where('orderSn',$request['orderSn'])->update(['status'=>1]);
            $arr = array('state'=>'1','msg'=>'审核中');
        }elseif($request['state'] == 2){
            DB::table('orders')->where('orderSn',$request['orderSn'])->update(['status'=>2]);
            $arr = array('state'=>'2','msg'=>'审核通过');
        }else{
            $arr = array('state'=>'3','msg'=>'修改失败');
        }
        echo json_encode($arr);
    }

    /**
     *课程状态
     */
    public function teaccourseState(Request $request){
        $data['courseStatus'] = $request['courseStatus'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('commentcourse')->where('id',$request['id'])->update($data);
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *名师点评详情
     */
    public function detailTeacherCommentCourse($id){
        $data = [];
        $info = DB::table('commentcourse as c')
            ->leftJoin('users as u','c.userId','=','u.id')
            ->leftJoin('users as ut','c.teacherId','=','ut.id')
            ->where('c.courseIsDel',0)
            ->where('c.id',$id)
            ->select('c.*','u.username','u.phone','ut.realname as teachername','ut.phone as teacherphone')
            ->orderBy('c.id','desc')
            ->first();
        $data['data'] = $info;
        if($info){
            $data['code'] = true;
        }else{
            $data['code'] = false;
        }
        return response()->json($data);
    }

    /**
     *编辑点评视频
     */
    public function editTeacherCourse($id){
        $data = DB::table('commentcourse')
            ->where('courseIsDel',0)
            ->where('id',$id)
            ->orderBy('id','desc')
            ->first();
        $data->coursePrice = $data->coursePrice / 100;
        $data->courseDiscount = $data->courseDiscount / 1000;
        if($data->courseType){
            $coursetype = DB::table('coursetype')->where('id',$data->courseType)->first();
            $data->typeName = $coursetype->typeName;
        }else{
            $data->typeName = '无促销';
        }
        $data->typeNames = DB::table('coursetype')->get();
        return view('admin.teacherCourse.editTeacherCourse',['data'=>$data]);
    }

    /**
     *执行编辑
     */
    public function doEditTeacherCourse(Request $request){
        $validator = $this -> validator($request->all());
        if ($validator -> fails()){
            return Redirect()->back()->withInput()->withErrors($validator);
        }
        $data = $request->except('_token');
        $data['updated_at'] = Carbon::now();
        $data['coursePrice'] = $request['coursePrice'] * 100;
        if($request['courseType'] == 1){
            $data['courseDiscount'] = $request['courseDiscount'] * 1000;
        }else{
            $data['courseDiscount'] = 0;
        }
        if($request->hasFile('coursePic')){ //判断文件是否存在
            if($request->file('coursePic')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('coursePic')->getClientOriginalName();//获取图片名
                $entension = $request->file('coursePic')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('ymdhis'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('coursePic')->move('./home/image/lessonSubject',$newname)){
                    $data['coursePic'] = '/home/image/lessonSubject/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
        if(DB::table('commentcourse')->where('id',$request['id'])->update($data)){
            //修改点评标题时同时修改演奏表的标题
            $state = DB::table('commentcourse')->where('id',$request['id'])->select('state','orderSn')->first();
            if($state->state == 2){  //审核通过时修改
                DB::table('applycourse')->where('orderSn',$state->orderSn)->update(['courseTitle'=>$request['courseTitle']]);
            }
            $this -> OperationLog('修改了id为'.$request['id'].'的辅导课程信息');
            return redirect('admin/message')->with(['status'=>'辅导课程修改成功','redirect'=>'commentCourse/teacherCourseList']);
        }else{
            return redirect()->back()->withInput()->withErrors('辅导课程修改失败');
        }
    }

    /**
     *删除名师点评
     */
    public function delTeacherCourse($id){
        $orderSn = DB::table('commentcourse')->where('id',$id)->pluck('orderSn');
        $status = DB::table('orders')->where('orderSn',$orderSn)->pluck('status');
        if($status != 4){ //只有订单是已退款的才可以删除视频
            return redirect()->back()->withInput()->withErrors('只有已退款的订单才可以删除');
        }
        $data = DB::table('commentcourse')->where('id',$id)->update(['courseIsDel'=>1]);
        DB::table('hotreviewcourse')->where('courseId',$id)->delete();//关联删除首页推荐点评视频
        DB::table('orders')->where('orderSn',$orderSn)->update(['isDelete'=>1]); //已退款时关联删除订单表
        DB::table('applycourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>1]); //已退款时关联删除演奏视频表
        if($data){
            $this -> OperationLog('删除了id为'.$id.'的辅导课程');
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'commentCourse/teacherCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'删除失败','redirect'=>'commentCourse/teacherCourseList']);
        }
    }

    /**
     * 表单验证
     */
    protected function validator(array $data){
        $rules = [
            'courseView' => 'integer',
            'coursePlayView' => 'integer',
            'courseFav' => 'integer',

        ];

        $messages = [
            'courseView.integer' => '浏览数必须是整型',
            'coursePlayView.integer' => '观看数必须是整型',
            'courseFav.integer' => '收藏数必须是整型',
        ];

        return \Validator::make($data, $rules, $messages);
    }

    /**
     * 名师点评视频审核通过后给学员发送短信提示
     */
    public function sendStudentMessage(Messages $message,Request $request){
        //名师点评视频通过后将订单表状态改为已完成
        DB::table('orders')->where('orderSn',$request['orderSn'])->update(['status'=>2]);

        //删除已有的通知
        if(DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['actionId'],'type'=>2])->first()){
            DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['actionId'],'type'=>2])->delete();
        }

        //名师视频审核通过后消息中心发送消息
        $data['actionId'] = $request['actionId'];
        $data['username'] = $request['username'];
        $data['type'] = 2;
        $data['content'] = $request['fromUsername'].'老师辅导了您的作品  点击可进入该点评页的详情页';
        $data['client_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['fromUsername'] = $request['fromUsername'];
        $data['toUsername'] = $request['toUsername'];
        $data['created_at'] = Carbon::now();
        DB::table('usermessage')->insert($data);

        //给学员的所有粉丝发送消息
        $fans = DB::table('friends')->where('toUserId',$request['userId'])->get();
        if($fans){
            foreach ($fans as $val) {
                $fansId = $val->fromUserId;//取出粉丝的id
                $fansname = DB::table('users')->where('id',$val->fromUserId)->where('checks',0)->pluck('username');//取出粉丝的用户名
                $data['username'] = $fansname;
                $data['content'] = $request['fromUsername'].'老师点评了'.$request['username'].'的作品 点击可进入该辅导视频详情页面';
                $data['type'] = 4;
                $data['toUsername'] = $request['username'];
                //删除已有的通知
                if(DB::table('usermessage')->where(['username'=>$fansname,'actionId'=>$request['actionId'],'type'=>4])->first()){
                    DB::table('usermessage')->where(['username'=>$fansname,'actionId'=>$request['actionId'],'type'=>4])->delete();
                }
                DB::table('usermessage')->insert($data);
            }
        }

        //名师点评视频审核通过后给学员发送短信提示
        $code = '';
        $content = '您申请的辅导课程已得到名师回复，请及时查看【创课教育平台】';
        $message::setInfo($request['phone'],$content);
        $result = $message::sendMsg();
        //return $message::response($result,$code);
    }

}
