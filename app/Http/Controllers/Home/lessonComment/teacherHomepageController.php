<?php

namespace App\Http\Controllers\Home\lessonComment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class teacherHomepageController extends Controller
{
    use Gadget;


    /**
     * 名师主页
     *
     * @return \Illuminate\Http\Response
     */
    public function index($teacherID)
    {
        $teacherInfo = DB::table('users') -> select('id', 'checks','stuMajor','school','answercount') -> where(['id' => $teacherID, 'type' => 2]) -> first();
        $teacherInfo || abort(404);
        $mineID = \Auth::check() ? \Auth::user() -> id : 0;
        $mineName = \Auth::check() ? \Auth::user() -> username : 0;

        $subjects = DB::table('teachersubjects')->where('user_id',$teacherID)->select('type_id')->get();
        $subjectsname = '';
        foreach ($subjects as $v){
            $subjectsname .= DB::table('subjects')->select('subjectname')->where('id',$v->type_id)->first()->subjectname.'、';
        }
        if($subjectsname){
            $subjectsname = rtrim($subjectsname, "、");
        }

        $anscount = DB::table('question')->where('teaId',$teacherID)->where('status',2)->count();

        return view('home.lessonComment.teacherHomepage.index')
            -> with('userID', $teacherID)
            -> with('mineID', $mineID)
            -> with('mineName', $mineName)
            -> with('checks', $teacherInfo -> checks)
            -> with('stuMajor', $teacherInfo -> stuMajor)
            -> with('school', $teacherInfo -> school)
            -> with('answercount', $teacherInfo -> answercount)
            -> with('subjectsname', $subjectsname)
            -> with('anscount',$anscount);
    }


    /**
     * 获取名师信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeacherInfo($teacherID)
    {
//        $teacherInfo = DB::table('users')
//                     -> join('teacher', 'users.id', '=', 'teacher.parentId')
//                     -> select('users.id', 'users.username', 'users.sex', 'users.created_at', 'users.type', 'users.cityId', 'users.pic', 'users.company',
//                        'teacher.intro', 'teacher.stock', 'teacher.price', 'teacher.cover')
//                     -> where(['users.id' => $teacherID, 'users.type' => 2]) -> first();
//        $teacherInfo && $teacherInfo -> created_at = floor((time() - strtotime($teacherInfo -> created_at)) / 86400);
//        $teacherInfo -> created_at || $teacherInfo -> created_at = 1;
//        $city = DB::table('city') -> select('name') -> where('code', $teacherInfo -> cityId) -> first();
//        $city && $teacherInfo -> city = $city -> name;
//        return $this -> returnResult($teacherInfo);

        $teacherInfo = DB::table('users')
            -> select('id', 'username', 'sex', 'created_at', 'type', 'cityId', 'pic', 'company', 'intro', 'cover')
            -> where(['id' => $teacherID, 'type' => 2]) -> first();
        $teacherInfo && $teacherInfo -> created_at = floor((time() - strtotime($teacherInfo -> created_at)) / 86400);
        $teacherInfo -> created_at || $teacherInfo -> created_at = 1;
        //$city = DB::table('city') -> select('name') -> where('code', $teacherInfo -> cityId) -> first();
        //$city && $teacherInfo -> city = $city -> name;
        return $this -> returnResult($teacherInfo);
    }


    /**
     * 获取名师视频
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeacherVideo(Request $request)
    {
        $tableName = $request['type'] ? 'commentcourse' : 'course';
        $order = $request['order'] ? $tableName.'.coursePlayView' : $tableName.'.id';

        $condition = [$tableName.'.id', $tableName.'.coursePrice', $tableName.'.courseTitle', $tableName.'.coursePic', $tableName.'.courseStudyNum'];
        $where = [$tableName.'.courseIsDel' => 0, $tableName.'.courseStatus' => 0];

		if ($request['type']) {
			array_push($condition, $tableName.'.teachername as extra');
			array_push($condition, $tableName.'.coursePlayView');
            array_push($condition, $tableName.'.courseDiscount');
			$where = array_merge($where, ['orders.teacherId' => $request['userid'], 'orders.status' => 2, 'orders.orderType' => 1, 'commentcourse.state' => 2]);
			$result = DB::table('orders') -> join($tableName, 'orders.courseId', '=', $tableName.'.id') -> select($condition) -> where($where)
					-> orderBy($order, "desc") -> skip($this -> getSkip($request['page'], $this->number)) -> take($this -> number) -> get();
		} else {
			array_push($condition, $tableName.'.completecount');
			$where[$tableName.'.teacherId'] = $request['userid'];
			$result = DB::table($tableName) -> select($condition) -> where($where) -> orderBy($order, "desc") -> skip($this -> getSkip($request['page'], $this->number)) -> take($this -> number) -> get();
			if ($result) {
				foreach ($result as $key => $value) {
					$result[$key] -> extra = DB::table('coursechapter') -> where('courseId', $result[$key] -> id) -> count();
				}
			}
		}

		return $this -> returnResult($result);
    }



    /**
     * 获取名师视频总数
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeacherVideoCount(Request $request)
    {
        $tableName = $request['type'] ? 'commentcourse' : 'course';
        $where = [$tableName.'.courseStatus' => 0, $tableName.'.courseIsDel' => 0];

		if ($request['type']) {
			$where = array_merge($where, ['orders.teacherId' => $request['userid'], 'orders.status' => 2, 'orders.orderType' => 1, 'commentcourse.state' => 2]);
			$result = DB::table('orders') -> join($tableName, 'orders.courseId', '=', $tableName.'.id') -> where($where) -> count();
		} else {
			$where[$tableName.'.teacherId'] = $request['userid'];
			$result = DB::table($tableName) -> where($where) -> count();
		}

        return $this -> returnResult($result);
    }
    
}
