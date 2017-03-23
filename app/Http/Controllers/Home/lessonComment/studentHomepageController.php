<?php

namespace App\Http\Controllers\Home\lessonComment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class studentHomepageController extends Controller
{
    use Gadget;


    /**
     * 学员主页
     *
     * @return \Illuminate\Http\Response
     */
    public function index($studentID)
    {
        $studentInfo = DB::table('users') -> select('id','stuMajor','school') -> where(['id' => $studentID]) ->where('type','<>',2) -> first();
        $studentInfo || abort(404);
        $mineID = \Auth::check() ? \Auth::user() -> id : 0;
        $mineName = \Auth::check() ? \Auth::user() -> username : 0;
        $askcount = DB::table('question')->where('stuId',$studentID)->where('status',2)->count();
        return view('home.lessonComment.studentHomePage.index') 
              -> with('userID', $studentID) 
              -> with('mineID', $mineID) 
              -> with('stuMajor', $studentInfo -> stuMajor)
              -> with('school', $studentInfo -> school)
              -> with('askcount',$askcount)
              -> with('mineName', $mineName);
    }


    /**
     * 获取学员信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getStuInfo($studentID)
    {
        $studentInfo = DB::table('users') -> select('username', 'sex', 'cityId', 'created_at', 'type', 'pic') 
                     -> where(['id' => $studentID]) -> where('users.type', '!=', 2) -> first();
        $studentInfo && $studentInfo -> created_at = floor((time() - strtotime($studentInfo -> created_at)) / 86400);
        $studentInfo -> created_at || $studentInfo -> created_at = 1;
        $city = DB::table('city') -> select('name') -> where('code', $studentInfo -> cityId) -> first();
        $city && $studentInfo -> city = $city -> name;
        return $this -> returnResult($studentInfo);
    }


    /**
     * 获取总数
     *
     * @return \Illuminate\Http\Response
     */
    public function getCount(Request $request)
    {
        $result = DB::table($request['table']) -> where($request['data']) -> count();
        return $this -> returnResult($result);
    }


    /**
     * 查看是否关注
     *
     * @return \Illuminate\Http\Response
     */
    public function getFirst(Request $request)
    {
        switch ($request['action']) {
            case '1':
                $result = DB::table($request['table']) -> select('id') -> where($request['data']) -> first();
                $result = $result && (bool) $result -> id;
                break;
            case '2':
                foreach ($request['data'] as $key => $value) {
					$data[$key] = $value;
				}
				empty($request['create_time']) && $data['created_at'] = Carbon::now();
				$result = DB::table($request['table']) -> insertGetId($data);
				break;
            case '3':
                $result = DB::table($request['table']) -> where($request['data']) -> delete();
                if ($result) {
					return Response() -> json(["type" => true, "data" => false]);
				}
                break;
            case '4':
                foreach ($request['data'] as $key => $value) {
					$data[$key] = $value;
				}
                $data['updated_at'] = Carbon::now();
                $result = DB::table($request['table']) -> where($request['condition']) -> update($data);
                break;
        }
        return $this -> returnResult($result);
    }



    /**
     * 获取课程视频
     *
     * @return \Illuminate\Http\Response
     */
    public function getVideo(Request $request)
    {
        $tableName = $request['type'] ? 'commentcourse' : 'course';
        $order = $request['order'] ? 'coursePlayView' : 'created_at';

        $condition = ['orders.orderType', $tableName.'.id', $tableName.'.coursePrice', $tableName.'.courseTitle', $tableName.'.coursePic', $tableName.'.courseStudyNum'];
        $where = ['orders.userId' => $request['userid'], $tableName.'.courseIsDel' => 0, $tableName.'.courseStatus' => 0, 'orders.status' => 2];

		if ($request['type']) {
			array_push($condition, $tableName.'.teachername as extra');
			array_push($condition, $tableName.'.coursePlayView');
			array_push($condition, $tableName.'.courseDiscount');
			$where['commentcourse.state'] = 2;
		} else {
			array_push($condition, $tableName.'.completecount');
			$where['orders.orderType'] = $request['type'];
		}

        $result = DB::table('orders') -> join($tableName, 'orders.courseId', '=', $tableName.'.id') -> select($condition) -> where($where) 
                -> orderBy("{$tableName}.{$order}", "desc") -> skip($this -> getSkip($request['page'], $this -> number)) -> take($this -> number)-> get();

        if (!$request['type'] && $result) {
            foreach ($result as $key => $value) {
                $result[$key] -> extra = DB::table('coursechapter') -> where('courseId', $result[$key] -> id) -> count();
            }
        }

        return $this -> returnResult($result); 
    }


    /**
     * 获取课程视频总数
     *
     * @return \Illuminate\Http\Response
     */
    public function getVideoCount(Request $request)
    {
        $tableName = $request['type'] ? 'commentcourse' : 'course';
        $where = ['orders.userId' => $request['userid'], 'orders.status' => 2, $tableName.'.courseStatus' => 0, $tableName.'.courseIsDel' => 0];
        $request['type'] ? $where['commentcourse.state'] = 2 : $where['orders.orderType'] = $request['type'];
        $result = DB::table('orders') -> join($tableName, 'orders.courseId', '=', $tableName.'.id') -> where($where) -> count();
        return $this -> returnResult($result);
    }

}
