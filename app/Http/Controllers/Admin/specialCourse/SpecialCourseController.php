<?php

namespace App\Http\Controllers\Admin\specialCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\Http\Requests\specialCourse\specialCourseRequest;
use App\Http\Controllers\Home\lessonComment\Gadget;
use Illuminate\Support\Facades\Auth;
use PaasResource;
use PaasUser;
class SpecialCourseController extends Controller
{
    use Gadget;
    /**
     *专题课程列表
     */
    public function specialCourseList(Request $request){
        $query = DB::table('course as c');

        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('c.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('c.created_at','<=',$request['endTime']);
        }

        if($request['resourceGrade']){
            $query = $query->where('c.gradeId',$request['resourceGrade']);
        }
        if($request['resourceSubject']){
            $query = $query->where('c.subjectId',$request['resourceSubject']);
        }
        if($request['resourceEdition']){
            $query = $query->where('c.editionId',$request['resourceEdition']);
        }
        if($request['resourceBook']){
            $query = $query->where('c.bookId',$request['resourceBook']);
        }
        if($request['resourceChapter']){
            $query = $query->where('c.chapterId',$request['resourceChapter']);
        }

        if($request['type'] == 1){
            $query = $query->where('c.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('c.courseTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('u.username','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('users as u','u.id','=','c.teacherId')
            ->leftJoin('schoolgrade as g','g.id','=','c.gradeId')
            ->leftJoin('studysubject as s','s.id','=','c.subjectId')
            ->leftJoin('studyedition as e','e.id','=','c.editionId')
            ->leftJoin('studyebook as b','b.id','=','c.bookId')
            ->leftJoin('chapter as p','p.id','=','c.chapterId')
            ->select('c.*','u.username','g.gradeName','s.subjectName','e.editionName','b.bookName','p.chapterName')
            ->orderBy('id','desc')
            ->where('c.courseIsDel','=',0)
            ->paginate(15);

        //查看表字段(courseStatus)的默认值
        $default = DB::select('select default(courseStatus) as defaultStatus from course limit 1');
        $data->defaultStatus = $default[0]->defaultStatus;

        $type = ['png','jpg','jpeg','pdf','swf'];
        $document = ['doc','docx','xls','xlsx','ppt','pptx'];

        foreach($data as &$val){
            $val->coursePrice = $val->coursePrice / 100;
            $val->courseDiscount = $val->courseDiscount / 1000;


            $transcod = true;
            $chapter = DB::table('coursechapter')->where('courseId',$val->id)->orderBy('id','desc')->get();
            if($chapter){
                foreach($chapter as &$valChapter){

                    if(!in_array(strtolower($valChapter->courseFormat),$type)){
                        if(!$valChapter->courseLowPath || !$valChapter->courseMediumPath || !$valChapter->courseHighPath){
                            if($valChapter->fileID && $valChapter->parentId != 0){

                                if(strtolower($valChapter->courseFormat) == 'mp3'){
                                    $convertype = 1; //音频
                                }elseif(in_array(strtolower($valChapter->courseFormat),$document)){
                                    $convertype = 2; //文档
                                }else{
                                    $convertype = 0; //视频
                                }

                                $FileList = $this->transformations($valChapter->fileID,$convertype);
//                                dd($FileList);

                                //转换失败
                                if($FileList['code'] == 503){
                                    $courseStatus = DB::table('course')->where('id',$val->id)->pluck('courseStatus');
                                    if($courseStatus !=2){
                                        DB::table('course')->where('id',$valChapter->courseId)->update(['courseStatus'=>6]);
                                    }
                                }
                                //正在转码
                                if($FileList['code'] == 200 && $FileList['data']['Waiting'] == 0){
                                    $transcod = false;
                                }

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
                                        DB::table('coursechapter')->where('id',$valChapter->id)->update($lists);
                                        $courseStatus = DB::table('course')->where('id',$val->id)->pluck('courseStatus');
                                        if($courseStatus == 7){
                                            DB::table('course')->where('id',$val->id)->update(['courseStatus'=>$data->defaultStatus]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
            }
            //将正在转码的字段记录
            if($transcod == false){
                DB::table('course')->where('id',$val->id)->update(['courseStatus'=>7]);
            }

        }

        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        $data->resourceGrade = $request['resourceGrade'] ? $request['resourceGrade'] : 0;
        $data->resourceSubject = $request['resourceSubject'] ? $request['resourceSubject'] : 0;
        $data->resourceEdition = $request['resourceEdition'] ? $request['resourceEdition'] : 0;
        $data->resourceBook = $request['resourceBook'] ? $request['resourceBook'] : 0;
        $data->resourceType = $request['resourceType'] ? $request['resourceType'] : 0;
//        dd($data);
        return view('admin.specialCourse.specialCourseList',['data'=>$data]);
    }

    /**
     *添加专题课程
     */
    public function addSpecialCourse(){
        $data = [];
        $data['typeNames'] = DB::table('users')->where('type',2)->select('id','realname')->get();
        $data['coursetypes'] = DB::table('coursetype')->get();
        return view('admin.specialCourse.addSpecialCourse',['data'=>$data]);
    }

    /**
     *执行添加
     */
    public function doAddSpecialCourse(specialCourseRequest $request){
        $data = $request->except('_token');
        $data['coursePrice'] = $request['coursePrice'] * 100;
        $data['created_at'] = Carbon::now();
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
        }else{
            return redirect()->back()->withInput()->withErrors('请上传课程封面图');
        }
        if($id = DB::table('course')->insertGetId($data)){
            $this -> OperationLog('添加了id为'.$id.'的创课课程');
            return redirect('admin/message')->with(['status'=>'课程添加成功','redirect'=>'specialCourse/specialCourseList']);
        }else{
            return redirect()->back()->withInput()->withErrors('课程添加失败');
        }
    }

    /**
     *编辑专题课程
     */
    public function editSpecialCourse($id){
        $data = DB::table('course as c')
            ->leftJoin('users as u','c.teacherId','=','u.id')
            ->leftJoin('schoolgrade as g','g.id','=','c.gradeId')
            ->leftJoin('studysubject as s','s.id','=','c.subjectId')
            ->leftJoin('studyedition as e','e.id','=','c.editionId')
            ->leftJoin('studyebook as b','b.id','=','c.bookId')
            ->leftJoin('chapter as p','p.id','=','c.chapterId')
            ->where('c.id',$id)
            ->select('c.*','u.realname','g.gradeName','s.subjectName','e.editionName','b.bookName','p.chapterName')
            ->first();
//        dd($data);
        $data->coursePrice = $data->coursePrice / 100;
        $data->courseDiscount = $data->courseDiscount / 1000;

        $data->typeNames = DB::table('coursetype')->get();
        $data->teacherNames = DB::table('users')->where('type',2)->select('id','realname')->get();
        return view('admin.specialCourse.editSpecialCourse',['data'=>$data]);
    }

    /**
     *执行编辑
     */
    public function doEditSpecialCourse(specialCourseRequest $request){
        $validator = $this -> validator($request->all());
        if ($validator -> fails()){
            return Redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        $data = $request->except('_token');
        $data['coursePrice'] = $request['coursePrice'] * 100;
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
        if(DB::table('course')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的创课课程');
            return redirect('admin/message')->with(['status'=>'课程修改成功','redirect'=>'specialCourse/specialCourseList']);
        }else{
            return redirect()->back()->withInput()->withErrors('课程修改失败');
        }
    }

    /**
     *专题课程状态
     */
    public function specialCourseState(Request $request){
        $title = DB::table('course')->where('id',$request['id'])->pluck('courseTitle');

        if($request['courseStatus'] == 2){
            $where['actionId'] = $request['id'];
            $where['type'] = 10;
            if( DB::table('usermessage')->where($where)->first()){
                DB::table('usermessage')->where($where)->delete();
            }

            $info['actionId'] = $request['id'];
            $info['username'] = $request['username'];
            $info['toUsername'] = $request['username'];
            $info['fromUsername'] = $request['fromUsername'];
            $info['content'] = '您的课程'.$title.'审核未通过,原因:'.$request['content'];
            $info['type'] = 10;
            $info['client_ip'] = $_SERVER['REMOTE_ADDR'];
            $info['created_at'] = Carbon::now();
            DB::table('usermessage')->insert($info);
        }elseif($request['courseStatus'] == 0){
            //1.审核通过给发布课程的用户发送消息
            $pwhere['actionId'] = $request['id'];
            $pwhere['type'] = 9;
            if( DB::table('usermessage')->where($pwhere)->first()){
                DB::table('usermessage')->where($pwhere)->delete();
            }

            $info['actionId'] = $request['id'];
            $info['username'] = $request['username'];
            $info['toUsername'] = $request['username'];
            $info['fromUsername'] = Auth::user()->username;
            $info['content'] = '您的课程'.$title.'已通过审核';
            $info['type'] = 9;
            $info['client_ip'] = $_SERVER['REMOTE_ADDR'];
            $info['created_at'] = Carbon::now();
            DB::table('usermessage')->insert($info);

            //2.给下发班级所在的学生发布消息
            $class = DB::table('courseclass')->where('courseId',$request['id'])->first();
            if($class){
                $classusers = DB::table('users')
                    ->where(['sectionId'=>$class->sectionId,'gradeId'=>$class->gradeId,'classId'=>$class->classId])
                    ->select('id','username')
                    ->get(); //所在班级的学生
                foreach($classusers as &$val){
                    $cwhere['actionId'] = $request['id'];
                    $cwhere['username'] = $val->username;
                    $cwhere['toUsername'] = $val->username;
                    $cwhere['fromUsername'] = Auth::user()->username;
                    $cwhere['content'] = '您收到了'.$request['username'].'老师发布的新课程'.$title;
                    $cwhere['type'] = 1;
                    $cwhere['client_ip'] = $_SERVER['REMOTE_ADDR'];
                    $cwhere['created_at'] = Carbon::now();
                    if(DB::table('usermessage')->where(['username'=>$val->username,'actionId'=>$request['id'],'type'=>1])->first()){
                        DB::table('usermessage')->where(['username'=>$val->username,'actionId'=>$request['id'],'type'=>1])->delete();
                    }
                    DB::table('usermessage')->insert($cwhere);
                }
            }


            $userId = DB::table('users')->where('username',$request['username'])->pluck('id');
            //给此用户的所有粉丝发送消息
            $fans = DB::table('friends')->where('toUserId',$userId)->get();
            if($fans){
                foreach ($fans as $value) {
                    $fansId = $value->fromUserId;//取出粉丝的id
                    $fansname = DB::table('users')->where('id',$value->fromUserId)->where('checks',0)->select('username')->first();//取出粉丝的用户名
                    if($fansname){
                        $fdata['actionId'] = $request['id'];
                        $fdata['username'] = $fansname->username;
                        $fdata['content'] = $request['username'].'发布了新的课程'.$title;
                        $fdata['type'] = 5;
                        $fdata['toUsername'] = $request['username'];
                        $fdata['fromUsername'] = Auth::user()->username;
                        $fdata['client_ip'] = $_SERVER['REMOTE_ADDR'];
                        $fdata['created_at'] = Carbon::now();
                        //删除已有的通知
                        if(DB::table('usermessage')->where(['username'=>$fansname->username,'actionId'=>$request['id'],'type'=>5])->first()){
                            DB::table('usermessage')->where(['username'=>$fansname->username,'actionId'=>$request['id'],'type'=>5])->delete();
                        }
                        DB::table('usermessage')->insert($fdata);
                    }
                }
            }




        }
        $data['courseStatus'] = $request['courseStatus'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('course')->where('id',$request['id'])->update($data);
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *专题视频详情
     */
    public function detailSpecialCourse($id){
        $data = [];
        $info = DB::table('course as c')
            ->leftJoin('users as u','c.teacherId','=','u.id')
            ->where('c.id',$id)
            ->select('c.*','u.username')
            ->first();
        $info->coursePrice = $info->coursePrice / 100;
        $info->courseDiscount = $info->courseDiscount / 1000;

        $data['data'] = $info;
        if($info){
            $data['code'] = true;
        }else{
            $data['code'] = false;
        }
        return response()->json($data);
    }

    /**
     *删除专题课程
     */
    public function delSpecialCourse($id){
        $data = DB::table('course') -> where('id',$id) -> delete();
        if($data){
            DB::table('hotcourse')->where('courseId',$id)->delete();
            DB::table('coursechapter') -> where('courseId',$id) -> delete();
            $this -> OperationLog('删除了id为'.$id.'的创课课程');
            return redirect('admin/message')->with(['status'=>'课程删除成功','redirect'=>'specialCourse/specialCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'课程删除失败','redirect'=>'specialCourse/specialCourseList']);
        }
    }

    /**
     * @param $request
     * @return  resource
     * duo删除专题课程
     */
    public function delMultiSpecialCourse( Request $request){
        $rules = ['check' => 'required',];
        $messages = ['check.required' => '请选择删除项'];
        $validate = \Validator::make($request->all(),$rules,$messages);
        if($validate->fails()){
            return \Redirect::back()->withErrors($validate);
        }
        $data = DB::table('course') -> whereIn('id', $request['check']) -> delete();
        if($data){
            DB::table('hotcourse')->whereIn('courseId', $request['check']) -> delete();
            DB::table('coursechapter') -> whereIn('courseId', $request['check']) -> delete();
            foreach  ($request['check'] as $id) {
                $this -> OperationLog('删除了id为'.$id.'的创课课程');
            }
            return redirect('admin/message')->with(['status'=>'课程删除成功','redirect'=>'specialCourse/specialCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'课程删除失败','redirect'=>'specialCourse/specialCourseList']);
        }
    }

    /**
     *是否需要审核
     */
    public function isCheck($status){
        if($status == 'true'){
            DB::statement('ALTER TABLE course ALTER COLUMN courseStatus SET DEFAULT 1;');
//            $data = DB::table('course')->get();
//            foreach($data as &$val){
//                if($val->courseStatus == 0 || $val->courseStatus == 2 || $val->courseStatus == 5){
//                    DB::update('update course set courseStatus = 1 where id = ?',[$val->id]);
//                }
//            }
        }else{
            DB::statement('ALTER TABLE course ALTER COLUMN courseStatus SET DEFAULT 0;');
            $data = DB::table('course')->get();
            foreach($data as &$val){
                if($val->courseStatus == 1 || $val->courseStatus == 2 || $val->courseStatus == 5){
                    DB::update('update course set courseStatus = 0 where id = ?',[$val->id]);
                }
            }
        }
        echo 1;

    }

    /**
     * 表单验证
     */
    protected function validator(array $data){
        $rules = [
            'courseView' => 'integer',
            'coursePlayView' => 'integer',
            'courseFav' => 'integer',
            'courseStudyNum'=>'integer'

        ];

        $messages = [
            'courseView.integer' => '浏览数必须是整型',
            'coursePlayView.integer' => '观看数必须是整型',
            'courseFav.integer' => '收藏数必须是整型',
            'courseStudyNum.integer' => '学习数必须是整型',
        ];

        return \Validator::make($data, $rules, $messages);
    }
}
