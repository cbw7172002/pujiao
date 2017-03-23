<?php

namespace App\Http\Controllers\Admin\excels;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Excel;
use Input;
use Auth;
use Carbon\Carbon;

class ExcelController extends Controller
{

    /**
     * 用户信息表的导入
     */
    public function userInfoImport($stat)
    {
        return $this->userImport('chuangke_c', 'users', $stat);
    }

    /**
     * 用户信息表-->导出下载模板
     * @param $type
     * @return mixed
     */
    public function userInfoTemplate($type)
    {
        //首先判斷用戶是否為超管權限
        $level = DB::table('role_user')->join('roles','roles.id','=','role_user.role_id')->where('role_user.user_id',\Auth::user()->id)->pluck('roles.level');
        if( !$level ){
            return Redirect() -> back() -> withErrors( '刪除操作需超管权限' );
        }
        $title = '';
        switch ($type) {
            case 'teacher' :
                $megs = ['username' => '用户名', 'realname' => '真实姓名', 'sex' => '性别sex(1:男,2:女)', 'phone' => '手机号phone' , 'school' => '所在学校(例:北京实验小学)', 'education' => '学历(例:大学)' , 'professional' => '职称(例:教授)', 'intro' => '个人简介(200字以内)'];
                $title = '教师信息导入模板';
                break;
            case 'instudent':
                $megs = ['username' => '用户名', 'realname' => '真实姓名', 'sex' => '性别sex(1:男,2:女)', 'phone' => '手机号phone' , 'school' => '所在学校(例:北京实验小学)', 'schoolYear' => '所属学级(如:2007级)' , 'gradeId' => '所在年级ID(例:1)', 'classId' => '所在班级ID(例:1)', 'sno' => '学号'];
                $title = '在校学生信息导入模板';
                break;
            case 'outstudent':
                $megs = ['username' => '用户名', 'realname' => '真实姓名', 'sex' => '性别sex(1:男,2:女)', 'phone' => '手机号phone' , 'school' => '所在学校(例:北京实验小学)', 'schoolYear' => '所属学级(如:2007级)'];
                $title = '离校学生信息导入模板';
                break;
        }
        //用户模板字段
        $data = $this->template('chuangke_c', 'users', $megs);

        //学生班级信息
        $info = DB::table('schoolgrade as gra')
            -> join('schoolclass as cla', 'cla.parentId', '=', 'gra.id')
            -> select('gra.id as 年级ID', 'gra.gradeName as 年级名称', 'cla.id as 班级ID', 'cla.classname as 班级名称')
            -> where(['gra.status' => 1, 'cla.status' => 1])
            -> get();
        foreach ($info as $v) {
            $result[] = get_object_vars($v);
        }
        $userInfo = [
            0 => ['职称列表' => '中专' , '学历列表' => '三级教师'],
            1 => ['职称列表' => '大专' , '学历列表' => '二级教师'],
            2 => ['职称列表' => '本科' , '学历列表' => '一级教师'],
            3 => ['职称列表' => '硕士' , '学历列表' => '高级教师'],
            4 => ['职称列表' => '博士' , '学历列表' => '正高级教师'],
            5 => ['职称列表' => '博士后' , '学历列表' => '']
        ];

        $titles = array_keys($result[0]);
        $titles = array_combine($titles, $titles);
        array_unshift($result, $titles);

        $tit = array_keys($userInfo[0]);
        $tit = array_combine($tit, $tit);
        array_unshift($userInfo, $tit);

        Excel::create($title, function ($excel) use ($data, $result, $type, $userInfo) {
            $excel->sheet('sheet', function ($sheet) use ($data) {
                $sheet->rows($data);
            });

            if($type == 'instudent' || $type == 'outstudent') {
                $excel->sheet('学生班级表' , function($sheet) use ($result) {
                    $sheet->rows($result);
                });
            }

            if($type == 'teacher') {
                $excel->sheet('教师学历职称表', function($sheet) use ($userInfo) {
                    $sheet->rows($userInfo);
                });
            }

        })->download('xlsx');
    }

    /**
     * 用户信息表的导出
     */
    public function userInfoExport()
    {
        //首先判斷用戶是否為超管權限
        $level = DB::table('role_user')->join('roles','roles.id','=','role_user.role_id')->where('role_user.user_id',\Auth::user()->id)->pluck('roles.level');
        if( !$level ){
            return Redirect() -> back() -> withErrors( '刪除操作需超管权限' );
        }
        $info = json_decode($_POST['excels']);
        return $this->export($info, '用户信息表');
    }

    /**
     *导出订单
     */
    public function orderExport(){
        $info = json_decode($_POST['excels']);
        return $this->export($info,'订单表');
    }

    /**
     *导出播放统计
     */
    public function specialCountExport(){
        $info = json_decode($_POST['excels']);
        return $this->export($info,'播放统计');
    }

    /**
     *导出近7日课程播放统计
     */
    public function courseCountExport(){
        $info = json_decode($_POST['excels']);
        return $this->export($info,'近7日课程播放统计');
    }

    /**
     *导出近30日课程播放统计
     */
    public function monthCountExport(){
        $info = json_decode($_POST['excels']);
        return $this->export($info,'近30日课程播放统计');
    }

    /**
     *导出近30日提问分类统计
     */
    public function questionCountExport(){
        $info = json_decode($_POST['excels']);
        return $this->export($info,'近30日提问分类所属科目统计');
    }

    /**
     *导出用户统计数
     */
    public function userCountExport(){
        $infos = json_decode($_POST['excels']);
        $categories = $infos->categories;
        $data = $infos->data;
        $combine[] = array_combine($categories,$data);
//        dd($combine);
//        foreach($combine as $key=>$val){
//            $combines[][$key] = $val;
//        }
//        foreach($combines as $val){
//            $info[] = (object)$val;
//        }
//        dd($info);
        return $this->exportCount($combine,'用户数据统计');
    }

	/**
	 *导出资源历史统计
	 */
	public function resourcehistCountExport(){
		$info = json_decode($_POST['excels']);
		return $this->export($info,'资源历史统计');
	}

	/**
	 *导出课程历史统计
	 */
	public function coursehistCountExport(){
		$info = json_decode($_POST['excels']);
		return $this->export($info,'课程历史统计');
	}

	/**
	 *导出教师资源发布量排名统计
	 */
	public function tresourceRankExport(){
		$info = json_decode($_POST['excels']);
		return $this->export($info,'教师资源发布量排名');
	}

	/**
	 *导出教师课程发布量排名统计
	 */
	public function tcourseRankExport(){
		$info = json_decode($_POST['excels']);
		return $this->export($info,'教师课程发布量排名');
	}

    /**
     *封装导入
     */
    public function import($table)
    {
        if (Input::hasFile('excel')) { //判断是否止传文件
            $entension = Input::file('excel')->getClientOriginalExtension();//上传文件的后缀
//            dd($entension);
            if ($entension == 'xls' || $entension == 'xlsx') { //判断上传格式是否是excel格式
                Excel::load(Input::file('excel'), function ($reader) use ($table,&$result) {
                    $reader = $reader->getSheet(0);//获取excel的第几张表
                    $results = $reader->toArray();//获取表中的数据

                    $names = array_shift($results);//将数组中第一条数组取出

                    $info = DB::select("select column_name from information_schema.columns where `TABLE_SCHEMA` = 'chuangke' and `TABLE_NAME` = ? ", [$table]);
                    foreach ($info as $val) {
                        $datas[$val->column_name] = $val->column_name;
                    }
                    $array = $datas;
                    unset($results[0]);
                    $c = array_diff($names,$array);
                    $flag = empty($c)?1:0;
                    if($flag){
                        foreach ($results as $key => $val) {
                            $data = array_combine($names, $val);

                            if(array_key_exists('startTime',$data)){
                                $time = substr(strrchr($data['startTime'],'-'),1,2).'-'.$data['startTime'];
                                $data['startTime'] = substr($time,0,8);
                            }
                            if(array_key_exists('endTime',$data)){
                                $time = substr(strrchr($data['endTime'],'-'),1,2).'-'.$data['endTime'];
                                $data['endTime'] = substr($time,0,8);
                            }

                            $data['created_at'] = date('Y-m-d H:i:s', time());
                            $data['updated_at'] = date('Y-m-d H:i:s', time());

                            DB::table($table)->insert($data);
                        }
                        $result = '1';
                    }else{
                        $result = '0';
                    }
                });

                if($result){
                    return redirect()->back()->with('status', '信息导入成功');

                }else{
                    return redirect()->back()->withInput()->withErrors('上传模板不匹配');
                }
            } else {
                return redirect()->back()->withInput()->withErrors('上传格式只支持excel');
            }
        } else {
            return redirect()->back()->withInput()->withErrors('没有导入文件');
        }
    }

    /**
     * @param $database
     * @param $table
     * @return response
     *封装导入
     */
    public function userImport($database, $table, $stat)
    {
        if (Input::hasFile('excel')) { //判断是否止传文件
            $entension = Input::file('excel')->getClientOriginalExtension();//上传文件的后缀
            if ($entension == 'xls' || $entension == 'xlsx') { //判断上传格式是否是excel格式
                Excel::load(Input::file('excel'), function ($reader) use ($database,$table,$stat,&$result,&$res,&$username,&$phone,&$userFlag,&$phoneFlag) {
                    $reader = $reader->getSheet(0);//获取excel的第几张表
                    $results = $reader->toArray();//获取表中的数据

                    $names = array_shift($results);//将数组中第一条数组取出

                    $info = DB::select("select column_name from information_schema.columns where `TABLE_SCHEMA` = '{$database}' and `TABLE_NAME` = ? ", [$table]);
                    foreach ($info as $val) {
                        $datas[$val->column_name] = $val->column_name;
                    }
                    $array = $datas;
                    unset($results[0]);//去除注释行
                    $c = array_diff($names,$array);
                    $flag = empty($c)?1:0;
                    if($flag){
                        //过滤数据库唯一字段重复导入的问题
                        foreach($results as $key=>$val){
                            $res = array_combine($names,$val);
                            if(!empty($res['username'])){
                                if(DB::table('users')->where('username',$res['username'])->where('type','<>','3')->first()){
                                    $username = true;
                                    return $username;
                                }
                                //验证用户名格式
                                if(!preg_match("/^[\x80-\xff_a-zA-Z0-9]{4,16}$/",$res['username'])){
                                    $userFlag = true;
                                    return $userFlag;
                                }
                            }
                            if(!empty($res['phone'])){
                                if(DB::table('users')->where('phone',$res['phone'])->first()){
                                    $phone = true;
                                    return $phone;
                                }
                                //验证手机号格式
                                if(!preg_match('/^[1][358][0-9]{9}$/',$res['phone']) && !preg_match('/^[1][7][07][0-9]{8}$/',$res['phone'])){
                                    $phoneFlag = true;
                                    return $phoneFlag;
                                }
                            }
                        }
                        //导入数据入库
                        foreach ($results as $key => $val) {
                            $data = array_combine($names, $val);
                            $data['created_at'] = Carbon::now();
                            $data['updated_at'] = Carbon::now();
                            $data['password'] = bcrypt('123456');
                            $data['checks'] = 0;
                            if($stat == 'teacher') {
                                $data['type'] = 2;
                            } elseif ($stat == 'instudent') {
                                $data['type'] = 1;
                                $data['isleave'] = 1;
                            } elseif($stat == 'outstudent') {
                                $data['type'] = 1;
                                $data['isleave'] = 2;
                            }

                            DB::table($table)->insert($data);

                        }
                        $result = '1';
                    }else{
                        $result = '0';
                    }
                });
                //如果数据库唯一字段已存在，那么就不导入，直接报错
                if($username) return redirect()->back()->with('errors', "用户名 {$res['username']} 已存在");

                if($phone) return redirect()->back()->with('errors', "手机号 {$res['phone']} 已存在");

                if($userFlag) return redirect()->back()->with('errors', "用户名 {$res['username']} 格式不正确");

                if($phoneFlag) return redirect()->back()->with('errors', "手机号 {$res['phone']} 格式不正确");

                if($result){
                    return redirect()->back()->with('status', '信息导入成功');
                }else{
                    return redirect()->back()->withInput()->withErrors('上传模板不匹配');
                }
            } else {
                return redirect()->back()->withInput()->withErrors('上传格式只支持excel');
            }
        } else {
            return redirect()->back()->withInput()->withErrors('没有导入文件');
        }
    }


    /**
     * @param $info
     * @param $title
     * @return mixed
     *封装导出
     */
    public function export($info, $title)
    {
        if(!$info){
            return redirect()->back()->withInput()->withErrors('没有数据可导出');
        }
        foreach ($info as $v) {
            $data[] = get_object_vars($v);
        }
        $titles = array_keys($data[0]);
        $titles = array_combine($titles, $titles);
        array_unshift($data, $titles);
        Excel::create(iconv('UTF-8', 'GBK',$title), function ($excel) use ($data) {
            $excel->sheet('sheet', function ($sheet) use ($data) {
                $sheet->rows($data);
            });
        })->download('xlsx');
    }

    /**
     * 封装模板所需数据
     * @param $database
     * @param $table
     * @param $msg
     * @return response
     */
    public function template($database, $table, $msg)
    {
        $messages = [];
        $array = [];
        $info = DB::select("select column_name from information_schema.columns where `TABLE_SCHEMA` = '{$database}' and `TABLE_NAME` = ? ", [$table]);
        if($info) {
            foreach ($info as $val) {
                if(array_key_exists($val->column_name, $msg)) {
//                    if($val->column_name == 'phone' || $val->column_name == 'intro') {
//                        $array[$val->column_name] = '选填';
//                    } else {
//                        $array[$val->column_name] = '必填';
//                    }
                    $array[$val->column_name] = $val->column_name;
                    $messages[$val->column_name] = $msg[$val->column_name];
                }
            }
        }
        $data[0] = $array;
        $data[1] = $messages;
        return  $data;
    }


    /**
     *封装导出
     */
    public function exportCount($info, $title)
    {
        if(!$info){
            return redirect()->back()->withInput()->withErrors('没有数据可导出');
        }
        $data = $info;
        $titles = array_keys($data[0]);
        $titles = array_combine($titles, $titles);
        array_unshift($data, $titles);
        Excel::create(iconv('UTF-8', 'GBK',$title), function ($excel) use ($data) {
            $excel->sheet('sheet', function ($sheet) use ($data) {
                $sheet->rows($data);
            });
        })->download('xlsx');
    }
}
