<?php
namespace App\Http\Controllers\Admin\users;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class indexController extends Controller
{
    /**
     * 教师列表
     * @param $request
     * @return response
     */
    public function teacherList(Request $request)
    {
        //定义搜索初值
        $search = [];
        $search['type'] = '';
        $search['beginTime'] = '';
        $search['endTime'] = '';

        //搜索
        $query = \DB::table('users as u')
            ->where('u.type', '=', 2)
            ->orderBy('u.id','desc');
        //用户名
        if($request->type == 1){
            if($request->search){
                $query = $query->where('u.username','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }
            $search['type'] = 1;
        }
        //姓名
        if($request->type == 2){
            if($request->search){
                $query = $query->where('u.realname','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }
            $search['type'] = 2;
        }
        //手机号
        if($request->type == 3){
            if($request->search){
                $query = $query->where('u.phone','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }

            $search['type'] = 2;
        }
        //时间筛选
        if($request->beginTime){
            $query = $query->where('u.created_at','>=',trim($request->beginTime));
            $search['beginTime'] = $request->beginTime;
        }

        if($request->endTime){
            $query = $query->where('u.created_at','<=',trim($request->endTime));
            $search['endTime'] = $request->endTime;
        }


        //全部
        if($request->type == 4){
            $query = $query;
            $search['type'] = 4;
        }
        //导出数据处理
        $excels = $query->select('u.id as 用户ID','u.username as 用户名','u.realname as 真实姓名','u.checks as 审核状态','u.phone as 手机号','u.type as 用户类型','u.school as 所在学校' ,'u.education as 学历','u.professional as 职称', 'u.created_at as 创建时间', 'u.updated_at as 更新时间')->get();
        foreach($excels as $key=>$value){
            $excels[$key]->审核状态 == 0 ?  $excels[$key]->审核状态 = '激活' :  $excels[$key]->审核状态 = '禁用';

            if($excels[$key]->用户类型 == 1){
                $excels[$key]->用户类型 = '学生';
            }elseif($excels[$key]->用户类型 == 2) {
                $excels[$key]->用户类型 = '教师';
            }
        }
        $excels = json_encode($excels);

        //列表页展示数据
        $data = $query->select('u.id','u.realname','u.username','u.checks','u.phone','u.type','u.pic', 'u.sex','u.created_at','u.updated_at')->paginate();
        if($data) {
            foreach($data as $key=>&$val) {
                $val->teacherTeach = DB::table('teacherteach as tea')
                    -> leftJoin('schoolgrade as gra', 'gra.id', '=', 'tea.gradeId')
                    -> leftJoin('schoolclass as cla', 'cla.id', '=', 'tea.classId')
                    -> leftJoin('studysubject as sub', 'sub.id', '=', 'tea.subjectId')
                    -> select('tea.id','gra.id as gradeId', 'gra.gradeName', 'cla.id as classId', 'cla.classname as className', 'sub.id as subjectId', 'sub.subjectName')
                    -> where(['gra.status' => 1, 'cla.status' => 1, 'tea.tid' => $val->id])
                    -> get();
//                echo '<pre>';
//                var_dump($val);
            }
//            exit();
        }
        return view('admin.users.teacherList',compact('data','search','excels'));
    }

    /**
     * @param $request
     * @return response
     * 在校学生列表
     */
    //用户列表
    public function inStudentList(Request $request)
    {
        //定义搜索初值
        $search = [];
        $search['type'] = '';
        $search['beginTime'] = '';
        $search['endTime'] = '';

        //搜索 , 'gra.status' => 1, 'cla.status' => 1
        $query = \DB::table('users as u')
            -> leftJoin('schoolgrade as gra', 'gra.id', '=', 'u.gradeId')
            -> leftJoin('schoolclass as cla', 'cla.id', '=', 'u.classId')
            ->where(['u.type' => 1, 'u.isleave' => 1])
            ->orderBy('u.id','desc');
        //用户名
        if($request->type == 1){
            if($request->search){
                $query = $query->where('u.username','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }
            $search['type'] = 1;
        }
        //姓名
        if($request->type == 2){
            if($request->search){
                $query = $query->where('u.realname','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }
            $search['type'] = 2;
        }
        //手机号
        if($request->type == 3){
            if($request->search){
                $query = $query->where('u.phone','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }

            $search['type'] = 3;
        }

        //时间筛选
        if($request->beginTime){
            $query = $query->where('u.created_at', '>=', trim($request->beginTime));
            $search['beginTime'] = $request->beginTime;
        }

        if($request->endTime){
            $query = $query->where('u.created_at','<=',trim($request->endTime));
            $search['endTime'] = $request->endTime;
        }


        //全部
        if($request->type == 4){
            $query = $query;
            $search['type'] = 4;
        }
        //导出数据处理
        $excels = $query->select('u.id as 用户ID','u.username as 用户名','u.realname as 真实姓名','u.checks as 审核状态','u.sno as 学号','u.type as 用户类型', 'u.school as 所在学校', 'u.schoolYear as 所属学级', 'u.sno as 学号', 'gra.gradeName as 所在年级', 'cla.classname as 所在班级', 'u.created_at as 创建时间', 'u.updated_at as 更新时间')->get();
        foreach($excels as $key=>$value){
            $excels[$key]->审核状态 == 0 ?  $excels[$key]->审核状态 = '激活' :  $excels[$key]->审核状态 = '禁用';

            if($excels[$key]->用户类型 == 1) {
                $excels[$key]->用户类型 = '学生';
            } elseif($excels[$key]->用户类型 == 2) {
                $excels[$key]->用户类型 = '教师';
            }
        }
        $excels = json_encode($excels);

        //列表页展示数据
        $data = $query -> select('u.id', 'u.username', 'u.pic', 'u.checks', 'u.phone', 'u.schoolYear', 'u.created_at','u.updated_at', 'gra.gradeName', 'cla.className', 'u.sno')->paginate();
        return view('admin.users.inStudentList',compact('data','search','excels'));
    }

    /**
     * 离校学生列表
     * @param $request
     * @return response
     */
    //用户列表
    public function outStudentList(Request $request)
    {
        //定义搜索初值
        $search = [];
        $search['type'] = '';
        $search['beginTime'] = '';
        $search['endTime'] = '';

        //搜索 , 'gra.status' => 1, 'cla.status' => 1
        $query = \DB::table('users as u')
            ->where(['u.type' => 1, 'u.isleave' => 2])
            ->orderBy('u.id','desc');
        //用户名
        if($request->type == 1){
            if($request->search){
                $query = $query->where('u.username','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }
            $search['type'] = 1;
        }
        //姓名
        if($request->type == 2){
            if($request->search){
                $query = $query->where('u.realname','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }
            $search['type'] = 2;
        }
        //手机号
        if($request->type == 3){
            if($request->search){
                $query = $query->where('u.phone','like','%'.trim($request->search).'%');
            }else{
                $query = $query;
            }

            $search['type'] = 3;
        }

        //时间筛选
        if($request->beginTime){
            $query = $query->where('u.created_at', '>=', trim($request->beginTime));
            $search['beginTime'] = $request->beginTime;
        }

        if($request->endTime){
            $query = $query->where('u.created_at','<=',trim($request->endTime));
            $search['endTime'] = $request->endTime;
        }


        //全部
        if($request->type == 4){
            $query = $query;
            $search['type'] = 4;
        }
        //导出数据处理
        $excels = $query->select('u.id as 用户ID','u.username as 用户名','u.realname as 真实姓名','u.checks as 审核状态','u.sno as 学号','u.type as 用户类型', 'u.school as 所在学校', 'u.schoolYear as 所属学级', 'u.created_at as 创建时间', 'u.updated_at as 更新时间')->get();
        foreach($excels as $key=>$value){
            $excels[$key]->审核状态 == 0 ?  $excels[$key]->审核状态 = '激活' :  $excels[$key]->审核状态 = '禁用';

            if($excels[$key]->用户类型 == 1){
                $excels[$key]->用户类型 = '学生';
            }elseif($excels[$key]->用户类型 == 2) {
                $excels[$key]->用户类型 = '教师';
            }
        }
        $excels = json_encode($excels);

        //列表页展示数据
        $data = $query -> select('u.id', 'u.username', 'u.pic', 'u.checks', 'u.phone', 'u.schoolYear', 'u.created_at','u.updated_at', 'u.sno')->paginate();
        return view('admin.users.outStudentList',compact('data','search','excels'));
    }

    /*
     * @param $request
     * @param $table
     * @param $column
     * 检查唯一性接口
     */
    public function unique(Request $request,$table,$column)
    {
        $data = DB::table($table)->where([$column=>$request->$column])->first();
        if($data){
            return Response()->json(['status'=>true]);
        }else{
            return Response()->json(['status'=>false]);
        }
    }
    /*
    * @param $stat
    * @param $id
    * 修改授课
    */
    public function editTeach($stat, $id)
    {
        //教师所授科目详情
        $data = \DB::table('teacherteach as tea')
            -> leftJoin('schoolgrade as gra', 'gra.id', '=', 'tea.gradeId')
            -> leftJoin('schoolclass as cla', 'cla.id', '=', 'tea.classId')
            -> leftJoin('studysubject as sub', 'sub.id', '=', 'tea.subjectId')
            -> select('tea.id','gra.id as gradeId', 'gra.gradeName', 'cla.id as classId', 'cla.classname as className', 'sub.id as subjectId', 'sub.subjectName')
            -> where(['tea.id' => $id])
            -> first();
        if($data) {
            $data->grades = DB::table('schoolgrade') -> select('id', 'gradeName') -> where('status', '=', 1) -> get();
        }
        return view('admin.users.editTeach', compact('data', 'stat'));
    }

    /*
     * @param $stat
     * @param $request
     * @param $id
     * 修改授课
     */
    public function updateTeach(Request $request, $id)
    {
        if(FALSE !== \DB::table('teacherteach') -> where('id', '=', $id) -> update( $request -> except('_token') )) {
            return Redirect() -> to('/admin/users/teacherList') -> with( 'status' , '修改授课成功' );
        }else{
            return Redirect() -> back() -> withInput( $request -> except('_token') ) -> withErrors( '修改授课失败' );
        }

    }

    /*
     * @param $request
     * @param $id
     * 删除授课
     */
    public function deleteTeach(Request $request, $id)
    {
        if(\DB::table('teacherteach') -> where('id', '=', $id) -> delete()) {
            return Redirect() -> to('/admin/users/teacherList') -> with( 'status' , '删除授课成功' );
        }else{
            return Redirect() -> back() -> withInput( $request -> except('_token') ) -> withErrors( '删除授课失败' );
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //学生所属学级
        $schoolYear = [];
        $nowYear = Carbon::now() -> year;
        for($i = 0; $i < 15; $i++) {
            array_push( $schoolYear, $nowYear);
            $nowYear -- ;
        }
        //学生所在年级
        $grades = \DB::table('schoolgrade') -> where('status', 1) -> select('id', 'gradeName') -> get();
        return view('admin.users.addUser', compact('schoolYear', 'grades'));
    }

    public function insert(Request $request)
    {
        $data = $request->except('_token');
        //调用验证
        $validate = $this->validator($data);
        //验证信息
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        //加密
        $data['phone'] = $data['phone'] ?: null;
        $data['password'] = bcrypt('123456');
        $data['checks'] = 0;
        $data['isleave'] = 1;
        $data['sectionId'] = 1;
        $data['pic'] || $data['pic'] = '/home/image/layout/default.png';
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        if($id = \DB::table('users')->insertGetId($data)){
            //写入日志
            if($request['type'] == 1) {
                $this -> OperationLog("新增了用户ID为{$id}的学员", 1);
                $string = 'inStudentList';
            } elseif($request['type'] == 2) {
                $this -> OperationLog("新增了用户ID为{$id}的教师", 1);
                $string = 'teacherList';
            }
            return Redirect()->to("/admin/users/{$string}")->with('status','添加用户成功') ;
        }else{
            return Redirect()->back()->withInput($request->except('_token'))->withErrors('添加用户失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($stat, $id)
    {
        //查询个人信息
        $data = User::findOrFail($id);
        if($data) {
            if($data->type == 1 && $data->gradeId && $data->classId) {
                $data->gradeName = \DB::table('schoolgrade') -> select('gradeName') -> where(['id' => $data->gradeId, 'status' => 1]) -> pluck('gradeName');
                $data->className = \DB::table('schoolclass') -> select('classname as className') -> where(['id' => $data->classId, 'status' => 1]) -> pluck('classname');
            }
        }
        return view('admin.users.showUser',compact('data', 'stat'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param $stat
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($stat, $id)
    {
        //修改数据
        $data = User::select('id','username','realname','sex','phone','pic','type', 'schoolYear', 'school', 'gradeId', 'classId', 'education','professional', 'isleave', 'intro', 'sno')->findOrFail($id);
        $data->pic || $data->pic = '/home/image/layout/default.png';
        if($data && $data->type == 1) {
            //学生所属学级
            $schoolYears = [];
            $nowYear = Carbon::now() -> year;
            for($i = 0; $i < 15; $i++) {
                array_push( $schoolYears, $nowYear);
                $nowYear -- ;
            }
            //学生所在年级
            $data -> grades = \DB::table('schoolgrade') -> where('status', 1) -> select('id', 'gradeName') -> get();
            if($data -> gradeId) {
                $data -> classes = \DB::table('schoolclass') -> where(['status' => 1, 'parentId' => $data->gradeId]) -> select('id', 'classname as className') -> get();
            }else{
                $data -> classes = \DB::table('schoolclass') -> where(['status' => 1]) -> select('id', 'classname as className') -> get();
            }
        }
        return view('admin.users.editUser',compact('data', 'stat', 'schoolYears'));
    }

    /**
     * Update the specified resource in storage.
     * @param $id
     * @param $stat
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $stat, $id)
    {
        $input = $request->except('_token','type');

        $validate = $this->validator($input);
        //验证信息
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }

        //头像为空，去除
        $input = array_filter($input);
        $input['updated_at'] = Carbon::now();
        if(FALSE !== DB::table('users') -> where(['id' => $id]) ->update($input)){
            $this -> OperationLog("修改了用户ID为{$id}的信息", 1);
            return Redirect() -> to('/admin/users/'.$stat.'List') -> with( 'status' , '修改用户信息成功' );
        }else{
            return Redirect() -> back() -> withInput( $request -> except('_token') ) -> withErrors( '修改用户信息失败' );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //首先判斷用戶是否為超管權限
        $level = DB::table('role_user')->join('roles','roles.id','=','role_user.role_id')->where('role_user.user_id',\Auth::user()->id)->pluck('roles.level');
        if( !$level ){
            return Redirect() -> back() -> withErrors( '刪除操作需超管权限' );
        }
        $username = DB::table('users')->where('id',$id)->pluck('username');
        //删除用户
        if(\DB::table('users')->delete($id)){
            //删除用户对应的其他表数据
            DB::table('friends')->where('fromUserId',$id)->delete();//关注表
            DB::table('friends')->where('toUserId',$id)->delete();//关注表
            DB::table('collection')->where('userId',$id)->delete();//收藏表
            DB::table('course')->where('teacherId',$id)->delete();//演奏视频表
            DB::table('coursecomment')->where('teaId',$id)->delete();//演奏视频表
            DB::table('coursecomment')->where('stuId',$id)->delete();//演奏视频表
            DB::table('courseview')->where('userId',$id)->delete();//视频观看表
            DB::table('examanswer')->where('userId',$id)->delete();//视频观看表
            DB::table('examcompletion')->where('userId',$id)->delete();
            DB::table('examjudge')->where('userId',$id)->delete();
            DB::table('exammchoose')->where('userId',$id)->delete();
            DB::table('exampaper')->where('userId',$id)->delete();
            DB::table('examschoose')->where('userId',$id)->delete();
            DB::table('examscore')->where('userId',$id)->delete();
            DB::table('examsubjective')->where('userId',$id)->delete();
            DB::table('examwrong')->where('userId',$id)->delete();
            DB::table('notes')->where('stuId',$id)->delete();
            DB::table('role_user')->where('user_id',$id)->delete();//角色-用户表
            DB::table('question')->where('uId',$id)->delete();//问答表
            DB::table('questioncomment')->where('username',$username)->delete();//问答评论表
            DB::table('questioncomment')->where('tousername',$username)->delete();//问答评论表
            DB::table('questioncommentthumb')->where('username',$username)->delete();//问答评论表
            DB::table('questionfav')->where('username',$username)->delete();//问答评论表
            DB::table('questionthumb')->where('username',$username)->delete();//问答评论表
            DB::table('resource')->where('userId',$id)->delete();//资源表
            DB::table('resourcecomment')->where('userId',$id)->delete();//资源表
            DB::table('resourcecomment')->where('username',$username)->delete();//资源表
            DB::table('resourcestore')->where('userId',$id)->delete();//资源表
            DB::table('teachersubject')->where('tId',$id)->delete();//资源表
            DB::table('teacherteach')->where('tId',$id)->delete();//资源表


            DB::table('systemmessage')->where('username',$username)->delete();//系统通知表
            DB::table('usermessage')->where('username',$username)->delete();//系统通知表
            DB::table('usermessage')->where('fromUsername',$username)->delete();//系统通知表
            DB::table('usermessage')->where('toUsername',$username)->delete();//系统通知表

            $this -> OperationLog("删除了用户ID为{$id}的学员", 1);
            return Redirect() -> back() -> with( 'status', '删除用户成功' );
        }else{
            return Redirect() -> back() -> withErrors( '删除用户失败' );
        }
    }

    /**
     * 用户重置密码
     * @param $stat
     * @param $id
     * @return mixed
     */
    public function resetPass($stat, $id)
    {
        if($input = \Input::all()){
            //首先判断管理员密码，正确向下执行，否则报错，在页面显示错误信息
            if(\Hash::check($input['managerPass'],\Auth::user()->password)){
                //如果有数据，执行重置动作
                $rules = ['password' => 'required|min:6|max:16|confirmed',];
                $messages = ['password.required' => '请输入密码', 'password.min' => '密码最少6位', 'password.max' => '密码最多16位', 'password.confirmed' => '新密码和确认密码不一致',];
                $validate = \Validator::make($input,$rules,$messages);
                if($validate->fails()){
                    return \Redirect::back()->withErrors($validate);
                }else{
                    //输入信息正确
                    $input['password'] = bcrypt($input['password']);
                    if(User::where('id','=',$id)->update(['password'=>$input['password']])){
                        $this -> OperationLog("重置了用户ID为{$id}的密码", 1);
                        return redirect('admin/message')->with(['status'=>'重置密码成功','redirect'=>$input['pathUrl']]);
                    }else{
                        return redirect('admin/message')->with(['status'=>'重置密码失败','redirect'=>$input['pathUrl']]);
                    }
                }
            }else if(!$input['managerPass']){
                return \Redirect::back()->withErrors(['errors'=>'管理员密码不能为空']);
            }else{
                return \Redirect::back()->withErrors(['errors'=>'管理员密码不正确']);
            }

        }else{
            //如果没数据，显示重置密码页面
            $data = User::findOrFail($id);
            return view('admin.users.resetPass',compact('data', 'stat'));
        }
    }


    /**
     * 更改用户状态
     * @param $id
     * @param $status
     * @return Response
     */
    public function changeStatus($id, $status)
    {
        if(User::where('id','=',$id) -> update(['checks'=>$status])){
            $this -> OperationLog("更改了用户ID为{$id}的状态", 1);
            return Redirect() -> back() -> with( 'status' , '修改成功' );
        }else{
            return Redirect() -> back() -> with( 'status' , '修改失败' );

        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function personDetail($id)
    {
        //修改数据
        $data = DB::table('users')
            ->leftJoin('post','post.id','=','users.postId')
            ->leftJoin('department','department.id','=','users.departId')
            ->select('users.id','users.username','users.realname','department.departName','post.postName','users.phone','users.email','users.checks')
            ->where('users.id',$id)
            ->first();
        return view('admin.users.personDetail',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePersonDetail(Request $request,$id)
    {
        $input = $request->except('_token');
        //头像为空，去除
        $input = array_filter($input);

        if(FALSE !== DB::table('users')->where(['id'=>$id])->update($input)){
            return Redirect() -> back() -> with( 'status' , '修改信息成功' );
        }else{
            return Redirect() -> back() -> withInput( $request -> except('_token') ) -> withErrors( '修改信息失败' );
        }
    }
    /**
     * 获取所属班级
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getClass(Request $request)
    {
        //获取所属班级
        $classes = \DB::table('schoolclass') -> select('id', 'classname as className') -> where(['parentId' => $request['parentId'], 'status' => 1]) -> get();
        if($classes) {
            return response()->json(['data' => $classes, 'status' => true]);
        }else{
            return response()->json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 获取所属班级
     * @return \Illuminate\Http\Response
     */
    public function getSubject()
    {
        //获取所属班级
        $subjects = \DB::table('studysubject') -> select('id', 'subjectName') -> get();
        if($subjects) {
            return response()->json(['data' => $subjects, 'status' => true]);
        }else{
            return response()->json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 在校学生 一键升级
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function oneClickUpgrades()
    {
        //首先判斷用戶是否為超管權限
        $level = DB::table('role_user')->join('roles','roles.id','=','role_user.role_id')->where('role_user.user_id',\Auth::user()->id)->pluck('roles.level');
        if( !$level ){
            return Redirect() -> back() -> withErrors( '一键升级需超管权限' );
        }
        //基础年级数
//        $max = DB::table('schoolgrade') -> select('id') -> where(['parentId' => 1, 'status' => 1]) -> count();
        $maxId = DB::table('schoolgrade') -> select('id') -> where(['parentId' => 1, 'status' => 1]) -> orderBy('id', 'desc') -> pluck('id');
        DB::beginTransaction();
        try {
            DB::table('users') -> where(['type' => 1, 'isleave' => 1, 'gradeId' => $maxId]) -> update(['gradeId' => 0, 'classId' => 0, 'isleave' => 2]);
            DB::table('users') -> where(['type' => 1, 'isleave' => 1]) -> where('gradeId', '<' , $maxId) -> increment('gradeId', 1);
            DB::commit();
        }catch (\Exception $e) {
//            return \Redirect() -> back()->withErrors('一键升级失败');
            DB::rollBack();
            \Log::debug($e -> getMessage() . " --- oneClickUpgrades Exception");
        }
        return \Redirect() -> back() -> with( 'status', '一键升级成功' );

    }




    /**
     * verify the specified resource from storage.
     *
     */
    protected function validator(array $data)
    {
        $rules = [
            'username' => 'required|min:2|max:8',
            'password' => 'sometimes|required|min:6|max:16',
//            'phone' => 'required|digits:11',
            'sex' => 'required',
        ];

        $messages = [
            'username.required' => '请输入用户名',
            'username.min' => '用户名最少2位',
            'username.max' => '用户名最多8位',
            'password.required' => '请输入密码',
            'password.min' => '密码最少6位',
            'password.max' => '密码最多16位',
//            'phone.required' => '请输入手机号',
//            'phone.digits' => '手机号为11位数字',
            'sex.required' => '性别不能为空',
        ];

        return \Validator::make($data, $rules, $messages);
    }
}