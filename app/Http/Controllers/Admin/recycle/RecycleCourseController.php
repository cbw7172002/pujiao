<?php

namespace App\Http\Controllers\Admin\recycle;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class RecycleCourseController extends Controller
{
    /**
     *专题课程列表
     */
    public function recycleCourseList(Request $request){
        $query = DB::table('course as c');
        if($request['type'] == 1){
            $query = $query->where('c.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('c.courseTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('u.realname','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 4){ //上传的起止时间
            $query = $query->where('c.created_at','>=',$request['beginTime'])->where('c.created_at','<=',$request['endTime']);
        }
        $data = $query
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->leftJoin('schoolgrade as g','g.id','=','c.gradeId')
            ->leftJoin('studysubject as s','s.id','=','c.subjectId')
            ->leftJoin('studyedition as e','e.id','=','c.editionId')
            ->leftJoin('studyebook as b','b.id','=','c.bookId')
            ->leftJoin('chapter as p','p.id','=','c.chapterId')
            ->select('c.*','u.realname','g.gradeName','s.subjectName','e.editionName','b.bookName','p.chapterName')
            ->where('courseIsDel',1)
            ->orderBy('id','desc')
            ->paginate(15);
        $data->type = $request['type'];
        return view('admin.recycle.specialCourseList',['data'=>$data]);
    }

    /**
     *还原专题课程
     */
    public function editRecycleCourse($id){
        $data = DB::table('course')->where('id',$id)->update(['courseIsDel'=>0]);
        if($data){
            $this -> OperationLog('回收站还原了id为'.$id.'的创课课程');
            return redirect('admin/message')->with(['status'=>'创课课程还原成功','redirect'=>'recycle/recycleCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'创课课程还原失败','redirect'=>'recycle/recycleCourseList']);
        }
    }

    /**
     *彻底删除专题课程
     */
    public function delRecycleCourse($id){
        if(DB::table('course')->where('id',$id)->delete()){
            DB::table('coursechapter')->where('courseId',$id)->delete();//关联删除章节表
            DB::table('coursecomment')->where('courseId',$id)->delete();//关联删除评论表
            DB::table('coursefeedback')->where('courseId',$id)->where('courseType',0)->delete();//关联删除课程反馈内容表
            $this -> OperationLog('回收站删除了id为'.$id.'的创课课程');
            return redirect('admin/message')->with(['status'=>'创课课程删除成功','redirect'=>'recycle/recycleCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'创课课程删除失败','redirect'=>'recycle/recycleCourseList']);
        }
    }




    /**
     * 问答管理
     */
    public function recycleQuestionList(Request $request){
        $query = DB::table('question as q');

        if($request->type == 1){
            $query = $query->where('q.id','like','%'.trim($request['search']).'%');
            $query->leftjoin('users as u','u.id','=','q.stuId')
                ->leftjoin('subjects as s','s.id','=','q.type')
                ->select('q.id','q.stuId','q.teaId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer','s.subjectName','u.type')
                ->where('u.checks',0);
        }else if($request->type == 2){
            $query = $query->where('q.qestitle','like','%'.trim($request['search']).'%');
            $query->leftjoin('users as u','u.id','=','q.stuId')
                ->leftjoin('subjects as s','s.id','=','q.type')
                ->select('q.id','q.stuId','q.teaId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer','s.subjectName','u.type')
                ->where('u.checks',0);
        }else if($request->type == 3){
            $query = $query->where('u.realname','like','%'.trim($request['search']).'%');
            $query->leftjoin('users as u','u.id','=','q.stuId')
                ->leftjoin('subjects as s','s.id','=','q.type')
                ->select('q.id','q.stuId','q.teaId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer','s.subjectName','u.type')
                ->where('u.checks',0);
        }else if($request->type == 4){
            $query = $query->where('u.realname','like','%'.trim($request['search']).'%');
            $query->leftjoin('users as u','u.id','=','q.teaId')
                ->leftjoin('subjects as s','s.id','=','q.type')
                ->select('q.id','q.stuId','q.teaId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer','s.subjectName','u.type')
                ->where('u.checks',0);
        }else if($request['beginTime']){ //上传的起止时间
            $query = $query->where('q.asktime','>=',$request['beginTime']);
            $query->leftjoin('users as u','u.id','=','q.stuId')
                ->leftjoin('subjects as s','s.id','=','q.type')
                ->select('q.id','q.stuId','q.teaId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer','s.subjectName','u.type')
                ->where('u.checks',0);
        }else if($request['endTime']){ //上传的起止时间
            $query = $query->where('q.asktime','<=',$request['endTime']);
            $query->leftjoin('users as u','u.id','=','q.stuId')
                ->leftjoin('subjects as s','s.id','=','q.type')
                ->select('q.id','q.stuId','q.teaId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer','s.subjectName','u.type')
                ->where('u.checks',0);
        }else{
            $query->leftjoin('users as u','u.id','=','q.stuId')
                ->leftjoin('subjects as s','s.id','=','q.type')
                ->select('q.id','q.stuId','q.teaId','q.qestitle','q.asktime','q.anstime','u.id as uid','u.username','u.realname','q.status','q.content','q.answer','s.subjectName','u.type')
                ->where('u.checks',0);
        }

        $question = $query->orderBy('id','desc')->where('q.delete','=','1')->paginate(15);
        $question->type = $request['type'];
        $question->beginTime = $request['beginTime'];
        $question->endTime = $request['endTime'];
//        dd($question);
        foreach($question as $key=>$value) {
            $teaName = \DB::table('users')->select('realname')->where(['id' => $value->teaId ])->first();
            $question[$key]->teaId = $teaName->realname;
        }

        foreach($question as $key=>$value) {
            $stuName = \DB::table('users')->select('realname')->where(['id' => $value->stuId ])->first();
            $question[$key]->stuName = $stuName->realname;
            $name = \DB::table('users')->select('username')->where(['id' => $value->stuId ])->first();
            $question[$key]->name = $name->username;
        }

        return view('admin.recycle.recycleQuestionList')->with('data',$question);

    }



    /**
     * 还原
     */
    public  function editRecycleQuestion($id){
        $res = DB::table('question')->where('id',$id)->update(['delete' => 0]);
        if($res){
            $this -> OperationLog("还原了问答列表ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'recycle/recycleQuestionList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }


    /**
     * 彻底删除
     */
    public  function delRecycleQuestion($id){
        $res = DB::table('question')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了问答列表ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'recycle/recycleQuestionList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



        /**
     *演奏视频列表
     */
    public function recycleCommentCourseList(Request $request){
        $query = DB::table('applycourse as a');
        if($request['type'] == 1){
            $query = $query->where('a.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('a.courseTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('u.username','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 4){
            $query = $query->where('ut.realname','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 5){ //上传的起止时间
            $query = $query->where('a.created_at','>=',$request['beginTime'])->where('a.created_at','<=',$request['endTime']);
        }
        $data = $query
            ->leftJoin('users as u','a.userId','=','u.id')
            ->leftJoin('users as ut','a.teacherId','=','ut.id')
            ->where('a.courseIsDel',1)
            ->select('a.*','u.username','ut.realname as teachername')
            ->orderBy('a.id','desc')
            ->paginate(15);
        $data->type = $request['type'];
//        dd($data);
        return view('admin.recycle.commentCourseList',['data'=>$data]);
    }

    /**
     *还原演奏视频
     */
    public function editRecycleCommentCourse($id){
        $orderSn = DB::table('applycourse')->where('id',$id)->pluck('orderSn');
        DB::table('orders')->where('orderSn',$orderSn)->update(['isDelete'=>0]);
        DB::table('commentcourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>0]);

        $data = DB::table('applycourse')->where('id',$id)->update(['courseIsDel'=>0]);
        if($data){
            $this -> OperationLog('回收站还原了id为'.$id.'的申请辅导课程');
            return redirect('admin/message')->with(['status'=>'还原成功','redirect'=>'recycle/recycleCommentCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'还原失败','redirect'=>'recycle/recycleCommentCourseList']);
        }
    }

    /**
     *彻底删除演奏视频
     */
    public function delRecycleCommentCourse($id){
        $orderSn = DB::table('applycourse')->where('id',$id)->pluck('orderSn');
        if(DB::table('applycourse')->where('id',$id)->delete()){
            DB::table('orders')->where('orderSn',$orderSn)->delete();
            DB::table('commentcourse')->where('orderSn',$orderSn)->delete();
            $this -> OperationLog('回收站删除了id为'.$id.'的申请辅导课程');
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'recycle/recycleCommentCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'删除失败','redirect'=>'recycle/recycleCommentCourseList']);
        }
    }






    /**
     *名师点评列表
     */
    public function recycleTeacherCourseList(Request $request){
        $query = DB::table('commentcourse');
        if($request['type'] == 1){
            $query = $query->where('id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('courseTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('username','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 4){
            $query = $query->where('teachername','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 5){ //上传的起止时间
            $query = $query->where('created_at','>=',$request['beginTime'])->where('created_at','<=',$request['endTime']);
        }
        $data = $query
            ->where('courseIsDel',1)
            ->orderBy('id','desc')
            ->paginate(15);
        $data->type = $request['type'];
//        dd($data);
        return view('admin.recycle.teacherCourseList',['data'=>$data]);
    }

    /**
     *还原点评课程
     */
    public function editRecycleTeacherCourse($id){
        $orderSn = DB::table('commentcourse')->where('id',$id)->pluck('orderSn');
        DB::table('orders')->where('orderSn',$orderSn)->update(['isDelete'=>0]);
        DB::table('applycourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>0]);

        $data = DB::table('commentcourse')->where('id',$id)->update(['courseIsDel'=>0]);
        if($data){
            $this -> OperationLog('回收站还原了id为'.$id.'的辅导课程');
            return redirect('admin/message')->with(['status'=>'还原成功','redirect'=>'recycle/recycleTeacherCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'还原失败','redirect'=>'recycle/recycleTeacherCourseList']);
        }
    }

    /**
     *彻底删除点评课程
     */
    public function delRecycleTeacherCourse($id){
        $orderSn = DB::table('commentcourse')->where('id',$id)->pluck('orderSn');
        if(DB::table('commentcourse')->where('id',$id)->delete()){
            DB::table('orders')->where('orderSn',$orderSn)->delete();
            DB::table('applycourse')->where('orderSn',$orderSn)->delete();
            $this -> OperationLog('回收站删除了id为'.$id.'的申请辅导课程');
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'recycle/recycleTeacherCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'删除失败','redirect'=>'recycle/recycleTeacherCourseList']);
        }
    }




    /**
     *订单列表
     */
    public function recycleOrderList(Request $request){
        $query = DB::table('orders');
        if($request['type'] == 1){
            $query = $query->where('orderSn','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('orderTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('userId','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 4){
            $query = $query->where('userName','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 5){ //上传的起止时间
            $query = $query->where('payTime','>=',$request['beginTime'])->where('payTime','<=',$request['endTime']);
        }
        $data = $query
            ->where('isDelete',1)
            ->orderBy('id','desc')
            ->paginate(15);
        foreach($data as &$val){
            $val->orderPrice = $val->orderPrice / 1000;
            $val->payPrice = $val->payPrice / 1000;
            $val->refundableAmount = $val->refundableAmount / 1000;
            $val->refundAmount = $val->refundAmount / 1000;
        }
        //导出数据
        $excel = $query
            ->select('id','orderSn as 订单号','tradeSn as 交易编号','orderTitle as 订单名称','orderPrice as 订单价格','payPrice as 实付金额','payType as 支付方式(0:支付宝1:微信)','userId as 购买用户ID','userName as 购买用户','teacherId as 邀请人ID','teacherName as 邀请人','orderType as 订单类型(0:购买专题订单1:点评申请订单2:购买点评订单)','courseId as 专题课程ID(订单类型为0或1时为点评课程ID)','refundableAmount as 应退金额','refundAmount as 已退金额','payTime as 付款时间','status as 订单状态(0:已付款1:待点评2:已完成3:退款中4:已退款)')
            ->where('isDelete',0)
            ->orderBy('id','desc')
            ->get();
        foreach($excel as &$value){
            $value->订单价格 = $value->订单价格 / 1000;
            $value->实付金额 = $value->实付金额 / 1000;
            $value->应退金额 = $value->应退金额 / 1000;
            $value->已退金额 = $value->已退金额 / 1000;
        }
        $excel = json_encode($excel);
        $data->type = $request['type'];
//        dd($data);
        return view('admin.recycle.orderList',['data'=>$data,'excel'=>$excel]);
    }

    /**
     *还原订单
     */
    public function editRecycleOrder($id){
        $orderSn = DB::table('orders')->where('id',$id)->pluck('orderSn');
        DB::table('applycourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>0]);
        DB::table('commentcourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>0]);

        $data = DB::table('orders')->where('id',$id)->update(['isDelete'=>0]);
        if($data){
            $this -> OperationLog('回收站还原了id为'.$id.'的订单');
            return redirect('admin/message')->with(['status'=>'订单还原成功','redirect'=>'recycle/recycleOrderList']);
        }else{
            return redirect('admin/message')->with(['status'=>'订单还原失败','redirect'=>'recycle/recycleOrderList']);
        }
    }

    /**
     *彻底删除订单
     */
    public function delRecycleOrder($id){
        $orderSn = DB::table('orders')->where('id',$id)->pluck('orderSn');
        if(DB::table('orders')->where('id',$id)->delete()){
            DB::table('applycourse')->where('orderSn',$orderSn)->delete();
            DB::table('commentcourse')->where('orderSn',$orderSn)->delete();
            $this -> OperationLog('回收站删除了id为'.$id.'的订单');
            return redirect('admin/message')->with(['status'=>'订单删除成功','redirect'=>'recycle/recycleOrderList']);
        }else{
            return redirect('admin/message')->with(['status'=>'订单删除失败','redirect'=>'recycle/recycleOrderList']);
        }
    }

    /**
     *资源列表
     */
    public function recycleResourceList(Request $request){
        $query = DB::table('resource as r');

        if($request['type'] == 1){
            $query = $query->where('r.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('r.resourceTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('r.resourceAuthor','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('resourcetype as t','t.id','=','r.resourceType')
            ->leftJoin('studysection as x','x.id','=','r.resourceSection')
            ->leftJoin('schoolgrade as g','g.id','=','r.resourceGrade')
            ->leftJoin('studysubject as s','s.id','=','r.resourceSubject')
            ->leftJoin('studyedition as e','e.id','=','r.resourceEdition')
            ->leftJoin('studyebook as b','b.id','=','r.resourceBook')
            ->leftJoin('chapter as c','c.id','=','r.resourceChapter')
            ->where('resourceIsDel',1)
            ->orderBy('id','desc')
            ->select('r.*','t.resourceTypeName','g.gradeName','s.subjectName','e.editionName','b.bookName','c.chapterName','x.sectionName')
            ->paginate(10);

        $data->type = $request['type'];
        return view('admin.recycle.resourceList',['data'=>$data]);
    }

    /**
     *还原资源数据
     */
    public function editRecycleResource($id){
        $data = DB::table('resource')->where('id',$id)->update(['resourceIsDel'=>0]);
        if($data){
            $this -> OperationLog('回收站还原了id为'.$id.'的资源');
            return redirect('admin/message')->with(['status'=>'资源还原成功','redirect'=>'recycle/recycleResourceList']);
        }else{
            return redirect('admin/message')->with(['status'=>'资源还原失败','redirect'=>'recycle/recycleResourceList']);
        }
    }

    /**
     *彻底删除资源
     */
    public function delRecycleResource($id){
        if(DB::table('resource')->where('id',$id)->delete()){
            DB::table('resourcecomment')->where('resourceId',$id)->delete();//关联删除资源评论表
            $this -> OperationLog('回收站删除了id为'.$id.'的资源');
            return redirect('admin/message')->with(['status'=>'资源删除成功','redirect'=>'recycle/recycleResourceList']);
        }else{
            return redirect('admin/message')->with(['status'=>'资源删除失败','redirect'=>'recycle/recycleResourceList']);
        }
    }


    /**
     *清空回收站
     */
    public function deleteRecycle(){
        //删除回收站所有的演奏视频
        $applyids = DB::table('applycourse')->where('courseIsDel',1)->lists('id');
        DB::table('applycourse') ->whereIn('id',$applyids)->delete();

        //删除回收站所有的问答数据
        $questionids = DB::table('question')->where('delete',1)->lists('id');
        DB::table('question')->whereIn('id',$questionids)->delete();

        //删除回收站所有的资源
        $resIds = DB::table('resource')->where('resourceIsDel',1)->lists('id');
        DB::table('resource')->whereIn('id',$resIds)->delete();
        DB::table('resourcecomment')->whereIn('resourceId',$resIds)->delete();//关联删除资源评论表


        //删除回收站所有的点评视频
        $comids = DB::table('commentcourse')->where('courseIsDel',1)->lists('id');
        DB::table('commentcourse')->whereIn('id',$comids)->delete();
        DB::table('coursefeedback')->whereIn('courseId',$comids)->where('courseType',1)->delete();

        //删除回收站所有专题课程
        $speids = DB::table('course')->where('courseIsDel',1)->lists('id');
        DB::table('course')->whereIn('id',$speids)->delete();
        //专题课程下的章节表
        DB::table('coursechapter')->whereIn('courseId',$speids)->delete();
        //专题课程下的资料表
        DB::table('coursedata')->whereIn('courseId',$speids)->delete();
        //专题课程下的评论表
        DB::table('coursecomment')->whereIn('courseId',$speids)->delete();
        //专题课程下的反馈表
        DB::table('coursefeedback')->whereIn('courseId',$speids)->where('courseType',0)->delete();
        //专题推荐表
        DB::table('hotcourse')->whereIn('courseId',$speids)->delete();

        //删除回收站所有的订单
        $orderids = DB::table('orders')->where('isDelete',1)->lists('id');
        DB::table('orders')->whereIn('id',$orderids)->delete();
        //备注表
        DB::table('remarks')->whereIn('orderid',$orderids)->delete();
        $orderSns = DB::table('orders')->where('isDelete',1)->lists('orderSn');
        DB::table('refund')->whereIn('orderSn',$orderSns)->delete();

        $this -> OperationLog('清空了回收站');
        return redirect()->back()->with(['status'=>'回收站已清空']);
    }


}
