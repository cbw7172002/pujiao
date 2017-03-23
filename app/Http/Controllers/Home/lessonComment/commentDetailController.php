<?php

namespace App\Http\Controllers\Home\lessonComment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use PaasResource;
use PaasUser;

class commentDetailController extends Controller
{
    use Gadget;


    public function __construct()
    {
        PaasUser::apply();
    }


    /**
     * 已完成点评详情
     *
     * @return \Illuminate\Http\Response
     */
    public function index($commentID)
    {
        DB::table('commentcourse') -> select('id') -> where(['state' => 2, 'courseStatus' => 0, 'courseIsDel' => 0, 'id' => $commentID]) -> first() || abort(404);
        if (\Auth::check() && \Auth::user() -> type != 3) {
            $mine = ['id' => \Auth::user() -> id, 'username' => \Auth::user() -> username, 'type' => \Auth::user() -> type, 'pic' => \Auth::user() -> pic];
            $result = DB::table('orders') -> join('commentcourse', 'orders.courseId', '=', 'commentcourse.id') -> select('orders.id', 'orders.orderType')
                    -> where(['orders.orderType' => 1, 'commentcourse.id' => $commentID, 'orders.userId' => \Auth::user() -> id, 'orders.status' => 2, 'orders.isDelete' => 0]) 
                    -> orWhere(['orders.orderType' => 2, 'commentcourse.id' => $commentID, 'orders.userId' => \Auth::user() -> id, 'orders.status' => 2, 'orders.isDelete' => 0]) -> first();
            if ($result) {
                $bought = 1;
				$orderType = $result -> orderType;
            } else {
                $invited = DB::table('users') -> join('commentcourse', 'users.id', '=', 'commentcourse.userId') -> select('users.id', 'users.fromyaoqingma') 
                         -> where(['commentcourse.id' => $commentID, 'users.fromyaoqingma' => \Auth::user() -> yaoqingma]) -> first();
                $bought = $invited ? 1 : 0;
				$orderType = 0;
            }
        } else {
            $mine = ['id' => 0, 'username' => 0, 'type' => 0, 'pic' => 0];
            $bought = 0;
			$orderType = 0;
        }
        return view('home.lessonComment.commentDetail.index') -> with('commentID', $commentID) -> with('mine', $mine) -> with('bought', $bought) -> with('orderType', $orderType);
    }


    /**
     * 获取已完成点评信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetailInfo($commentID, $type)
    {
        PaasUser::apply();
        $tableName = $type ? 'commentcourse' : 'applycourse';
        $joinField = $type ? 'teacherId' : 'userId';
        $extra = $type ? 'coursePrice' : 'courseTitle';
        $levelOrMessage = $type ? 'suitlevel' : 'message';
        $condition = $type ? 'id' : 'orderSn';
        if($type){
            $select = ['users.pic', 'users.username','users.learnYear','learnMonth', $tableName.'.courseLowPath', $tableName.'.courseMediumPath', $tableName.'.courseHighPath', $tableName.'.'.$levelOrMessage,
                $tableName.'.created_at', $tableName.'.'.$extra.' as extra', $tableName.'.'.$joinField, $tableName.'.orderSn', $tableName.'.coursePic', $tableName.'.score'];
        }else{
            $select = ['users.pic', 'users.username','users.learnYear','learnMonth', $tableName.'.courseLowPath', $tableName.'.courseMediumPath', $tableName.'.courseHighPath', $tableName.'.'.$levelOrMessage,
                $tableName.'.created_at', $tableName.'.'.$extra.' as extra', $tableName.'.'.$joinField, $tableName.'.orderSn', $tableName.'.coursePic'];
        }
		$type && array_push($select, $tableName.'.courseDiscount');

        $result = \DB::table($tableName) -> join('users', $tableName.'.'.$joinField, '=', 'users.id')
                -> select($select)
                -> where([$tableName.'.'.$condition => $commentID, $tableName.'.state' => 2, $tableName.'.courseStatus' => 0, $tableName.'.courseIsDel' => 0]) -> first();
        if($result){
            $monthes = (Carbon::now()->year - $result->learnYear)*12 + Carbon::now()->month - $result->learnMonth;

            $aa=$monthes/12;
            if($aa >= 1){
                $result -> learnYear  = floor($aa).'年';
                if($monthes%12){
                    $result -> learnMonth =($monthes%12).'个月';
                }else{
                    $result -> learnMonth ='';
                }
            }else{
                $result -> learnYear  = '';
                $result -> learnMonth =$monthes.'个月';
            }
        }
        if($type) {
            $everyNum = explode(',', $result->score);
            $result->aNum = $everyNum[0];
            $result->bNum = $everyNum[1];
            $result->cNum = $everyNum[2];
            $result->dNum = $everyNum[3];
        }
        if ($result) {
            $result -> time = floor((time() - strtotime($result -> created_at)) / 86400) + 1;
            $result -> created_at = explode(' ', $result -> created_at)[0];
            $result -> courseLowPath = $this -> getPlayUrl($result -> courseLowPath);
            $result -> courseMediumPath = $this -> getPlayUrl($result -> courseMediumPath);
            $result -> courseHighPath = $this -> getPlayUrl($result -> courseHighPath);
        }
        return $this -> returnResult($result);
    }


    /**
     * 最新点评推荐
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewComment()
    {
        $result = DB::table('commentcourse') -> select('courseTitle', 'id', 'teachername', 'coursePlayView', 'coursePrice') 
                -> where(['state' => 2, 'courseStatus' => 0, 'courseIsDel' => 0]) -> orderBy('id', 'desc') -> skip(0) -> take(5) -> get();
        return $this -> returnResult($result);
    }


    /**
     * 获取点评视频评论
     *
     * @return \Illuminate\Http\Response
     */
    public function getApplyComment($commentID)
    {
        $result = DB::table('applycoursecomment')
                 -> join('users', 'applycoursecomment.fromUserId', '=', 'users.id')
                 -> select('applycoursecomment.commentContent', 'applycoursecomment.created_at', 'applycoursecomment.fromUserId', 'applycoursecomment.likeNum', 'users.username', 'users.pic', 'users.type', 'applycoursecomment.id', 'applycoursecomment.parentId', 'applycoursecomment.toUserId') 
                 -> where(['applycoursecomment.courseId' => $commentID, 'applycoursecomment.checks' => 0])-> orderBy('applycoursecomment.id', 'desc') -> get();
        foreach ($result as $key => $value) {
            $result[$key] -> likeUser = $result[$key] -> likeNum ? array_filter(array_unique(explode(',', $result[$key] -> likeNum))) : [];
            $result[$key] -> isLike = \Auth::check() ? in_array(\Auth::user() -> id, $result[$key] -> likeUser) : true;
            $result[$key] -> likeNum = count($result[$key] -> likeUser);
            if ($result[$key] -> parentId) {
                $toUser = DB::table('users') -> select('username', 'type') -> where('id', $result[$key] -> toUserId) -> first();
                $result[$key] -> toUserName = $toUser -> username;
                $result[$key] -> toUserType = $toUser -> type;
            }
            $result[$key] -> created_at = Carbon::createFromFormat('Y-n-j G:i:s', $result[$key] -> created_at) -> diffForHumans();
        }
        return $this -> returnResult($result);
    }


    /**
     * 评论点赞
     *
     * @return \Illuminate\Http\Response
     */
    public function likesComment(Request $request)
    {
        $likeUser = $request['likeUser'] ? $request['likeUser'] : [];
        \Auth::check() && array_push($likeUser, \Auth::user() -> id);
        $result = DB::table('applycoursecomment') -> where("id", $request['id']) -> update(["likeNum" => implode(',', $likeUser)]);
        return $this -> returnResult($result);
    }


    /**
     * 待点评详情
     *
     * @return \Illuminate\Http\Response
     */
    public function wait($applyID, $messageID = '')
    {
        $userType = \Auth::user() -> type != 2 ? 'userId' : 'teacherId';
        $result = DB::table('applycourse') -> select('orderSn', 'created_at','userId')
                -> where(['state' => 2, 'courseStatus' => 0, 'courseIsDel' => 0, $userType => \Auth::user() -> id, 'id' => $applyID]) -> first();
        $result || abort(404);

        $type = DB::table('users')->select('type')->where('id',$result->userId)->first()->type;

        return view('home.lessonComment.commentDetail.wait', [
            'created_at' => floor((time() - strtotime($result -> created_at))/86400),
            'commentID' => $applyID,
            'orderSn' => $result -> orderSn,
            'messageID' => $messageID,
            'type' => $type
        ]);
    }


    /**
     * 上传点评视频
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadComment($orderSn, $messageID = null)
    {
        $result = DB::table('orders') -> join('applycourse', 'orders.orderSn', '=', 'applycourse.orderSn') 
                -> select('orders.id', 'orders.userName', 'orders.userId', 'orders.teacherId', 'orders.teacherName', 'applycourse.courseTitle', 'applycourse.id as applyID')
                -> where(['orders.orderSn' => $orderSn, 'orders.status' => 1, 'orders.isDelete' => 0, 'orders.teacherId' => \Auth::user() -> id]) -> first();
        $result || abort(404);
        return view('home.lessonComment.commentDetail.uploadComment') -> with('orderSn', $orderSn) -> with('info', $result) -> with('messageID', $messageID) -> with('mineID', \Auth::user() -> id);
    }


    /**
     * 审核未通过重新上传视频
     *
     * @return \Illuminate\Http\Response
     */
    public function reUploadComment($commentID, $messageID = null)
    {
        $result = DB::table('commentcourse') -> select('id', 'suitlevel','score')
                -> where(['state' => 0, 'courseStatus' => 0, 'courseIsDel' => 0, 'id' => $commentID, 'teacherId' => \Auth::user() -> id]) -> first();
        $result || abort(404);
        $everyNum = explode(',',$result->score);
        return view('home.lessonComment.commentDetail.reUploadComment')
            -> with('info', $result) -> with('messageID', $messageID) -> with('mineID', \Auth::user() -> id)
            -> with('aNum', $everyNum[0])-> with('bNum', $everyNum[1])-> with('cNum', $everyNum[2])-> with('dNum', $everyNum[3]);
    }


    /**
     * 完成点评视频上传
     *
     * @return \Illuminate\Http\Response
     */
    public function finishComment(Request $request)
    {
        $arr = [$request->aNum,$request->bNum,$request->cNum,$request->dNum];
        $request['score'] = implode(',',$arr);
        $request['created_at'] = Carbon::now();
        $request['updated_at'] = Carbon::now();
        $result = DB::table('commentcourse') -> insertGetId($request -> except('aNum','bNum','cNum','dNum'));
        if (!$result) return $this -> returnResult(false);
        DB::table('orders') -> where('orderSn', $request['orderSn']) -> update(['courseId' => $result]) || $result = !(DB::table('commentcourse') -> where('id', $result) -> delete());
        return $this -> returnResult($result);
    }


    /**
     * 递增视频观看数
     *
     * @return \Illuminate\Http\Response
     */
    public function videoIncrement(Request $request)
    {
        if ($request['action']) {
            $result = DB::table($request['table']) -> where($request['condition']) -> increment($request['field']);
        } else {
            $result = DB::table($request['table']) -> where($request['condition']) -> decrement($request['field']);
        }
        return $this -> returnResult($result);
    }
}
