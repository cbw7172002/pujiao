<?php

namespace App\Http\Controllers\Admin\commentCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\Home\lessonComment\Gadget;
use PaasResource;
use PaasUser;
use Cache;
use Messages;

class commentCourseController extends Controller
{
    use Gadget;
    public function __construct()
    {
        PaasUser::apply();
    }
    /**
     *演奏视频列表
     */
    public function commentCourseList(Request $request){
        $query = DB::table('applycourse as a');
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
            $query = $query->where('u.username','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 5){
            $query = $query->where('ut.realname','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('users as u','a.userId','=','u.id')
            ->leftJoin('users as ut','a.teacherId','=','ut.id')
            ->where('a.courseIsDel',0)
            ->select('a.*','u.username','ut.realname as teachername','ut.username as teacherusername','ut.phone as teacherPhone')
            ->orderBy('a.id','desc')
            ->paginate(15);
        foreach($data as &$val){
            //查看是否有相应的点评视频
            $com = DB::table('commentcourse')->where('orderSn',$val->orderSn)->first();
            if($com){
                $val->comstatus = true;
            }else{
                $val->comstatus = false;
            }

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

            //如果没有转码成功则请求转码并写入表中
            if(!$val->courseLowPath || !$val->courseMediumPath || !$val->courseHighPath){
                $FileList = $this->transformations($val->fileID);
//                dump($FileList);

                //返回的状态值
                $val->msg['message'] = $FileList['message'];
                $val->msg['code'] = $FileList['code'];

                if($FileList['code'] == 200 && $FileList['data']['Waiting'] < 0){
                    $filelists = $FileList['data']['FileList']; //取出转好的码
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
                        DB::table('applycourse')->where('id',$val->id)->update($lists);
                    }
                }
            }
        }
        $data->type = $request['type'];
        $data->status = $request['state'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
//        dd($data);
        return view('admin.commentCourse.commentCourseList',['data'=>$data]);
    }

    /**
     *审核状态
     */
    public function commentState(Request $request){
        $data['state'] = $request['state'];
        $data['lastCheckTime'] = Carbon::now();
        $data = DB::table('applycourse')->where('id',$request['id'])->update($data);
        $this -> OperationLog('修改了id为'.$request['id'].'申请辅导课程的审核状态');
        if($request['state'] == 0){
//            DB::table('orders')->where('orderSn',$request['orderSn'])->update(['status'=>0]);
            $arr = array('state'=>'0','msg'=>'审核未通过');
        }elseif($request['state'] == 1){
            //如是是审核中时将发给名师的消息删除
            if(DB::table('usermessage')->where(['username'=>$request['teachername'],'actionId'=>$request['id'],'type'=>7])->first()){
                DB::table('usermessage')->where(['username'=>$request['teachername'],'actionId'=>$request['id'],'type'=>7])->delete();
            }
            //将发给学员的未通过原因删除
            if(DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['id'],'type'=>0])->first()){
                DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['id'],'type'=>0])->delete();
            }
//            DB::table('orders')->where('orderSn',$request['orderSn'])->update(['status'=>0]);
            $arr = array('state'=>'1','msg'=>'审核中');
        }elseif($request['state'] == 2){
            DB::table('orders')->where('orderSn',$request['orderSn'])->update(['status'=>1]);
            $arr = array('state'=>'2','msg'=>'审核通过');
        }else{
            $arr = array('state'=>'3','msg'=>'修改失败');
        }
        echo json_encode($arr);
    }

    /**
     *课程状态
     */
    public function comcourseState(Request $request){
        $data['courseStatus'] = $request['courseStatus'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('applycourse')->where('id',$request['id'])->update($data);
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 编辑演奏视频
     */
    public function editCommentCourse($id){
        $data = DB::table('applycourse as a')
            ->leftJoin('users as u','a.userId','=','u.id')
            ->leftJoin('users as ut','a.teacherId','=','ut.id')
            ->where('a.courseIsDel',0)
            ->where('a.id',$id)
            ->select('a.*','u.username','ut.realname as teachername')
            ->orderBy('a.id','desc')
            ->first();
//        dd($data);
        return view('admin.commentCourse.editCommentCourse',['data'=>$data]);
    }

    /**
     *执行编辑
     */
    public function doEditCommentCourse(Request $request){
        $validator = $this -> validator($request->all());
        if ($validator -> fails()){
            return Redirect()->back()->withInput()->withErrors($validator);
        }
        $data = $request->except('_token');
        $data['updated_at'] = Carbon::now();
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
        if(DB::table('applycourse')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'申请辅导课程的信息');
            return redirect('admin/message')->with(['status'=>'修改成功','redirect'=>'commentCourse/commentCourseList']);
        }else{
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    /**
     * 删除演奏视频
     */
    public function delCommentCourse($id){
        $orderSn = DB::table('applycourse')->where('id',$id)->pluck('orderSn');
        $status = DB::table('orders')->where('orderSn',$orderSn)->pluck('status');
        if($status != 4){ //只有订单是已退款的才可以删除视频
            return redirect()->back()->withInput()->withErrors('只有已退款的订单才可以删除');
        }
        $data = DB::table('applycourse')->where('id',$id)->update(['courseIsDel'=>1]);
        DB::table('orders')->where('orderSn',$orderSn)->update(['isDelete'=>1]); //已退款时关联删除订单表
        DB::table('commentcourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>1]); //已退款时关联删除名师点评表
        if($data){
            $this -> OperationLog('删除了id为'.$id.'申请辅导课程');
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'commentCourse/commentCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'删除失败','redirect'=>'commentCourse/commentCourseList']);
        }
    }

    /**
     *审核未通过时向学员发送信息
     */
    public function noPassMsg(Request $request){
        if(DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['actionId'],'type'=>0])->first()){
            DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['actionId'],'type'=>0])->delete();
        }
        $data['username'] = $request['username'];
        $data['actionId'] = $request['actionId'];
        $data['fromUsername'] = $request['fromUsername'];
        $data['toUsername'] = $request['toUsername'];
        $data['content'] = $request['content'];
        $data['type'] = 0;
        $data['client_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['created_at'] = Carbon::now();
        $data = DB::table('usermessage')->insert($data);
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *视频详情
     */
    public function detailCommentCourse($id){
        $data = [];
        $info = DB::table('applycourse as a')
            ->leftJoin('users as u','a.userId','=','u.id')
            ->leftJoin('users as ut','a.teacherId','=','ut.id')
            ->where('a.courseIsDel',0)
            ->where('a.id',$id)
            ->select('a.*','u.username','u.phone','ut.realname as teachername','ut.phone as teacherphone')
            ->orderBy('a.id','desc')
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
     * 给名师发送短信提示
     */
    public function sendMessage(Messages $message,Request $request)
    {
        if(DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['actionId'],'type'=>7])->first()){
            DB::table('usermessage')->where(['username'=>$request['username'],'actionId'=>$request['actionId'],'type'=>7])->delete();
        }
        //审核通过后给名师发送个人通知
        $data['username'] = $request['username'];
        $data['actionId'] = $request['actionId'];
        $data['type'] = 7;
        $data['content'] = '学员'.$request['fromUsername'].'向您发起辅导邀请';
        $data['client_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['fromUsername'] = $request['fromUsername'];
        $data['toUsername'] = $request['toUsername'];
        $data['created_at'] = Carbon::now();
        DB::table('usermessage')->insert($data);

        //演奏视频通过后将订单表状态改为待点评
        DB::table('orders')->where('orderSn',$request['orderSn'])->update(['status'=>1]);

        $code = '';
        $content = '老师您好,您现有一个新的辅导申请,请及时查看【创课教育平台】';
        $message::setInfo($request['phone'],$content);
        $result = $message::sendMsg();
        //return $message::response($result,$code);
    }
}
