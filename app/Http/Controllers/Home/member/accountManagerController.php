<?php

namespace App\Http\Controllers\Home\member;

use DB;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\commonApi\Filter\Filter as Filter;

class accountManagerController extends Controller
{
    /**
     * @param $id
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accountManagerTeacher($id)
    {
        //获取数据
        $data = \DB::table('users')->select('id', 'username', 'realname', 'phone', 'pic', 'education', 'professional', 'intro', 'sex', 'school')->where(['id' => $id, 'checks' => 0])->first();
        return view('home.accountManager.accountManagerTeacher', compact('id', 'data'));
    }

    /**
     * @param $id
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accountManagerStudent($id)
    {
        //获取个人数据
        $data = \DB::table('users')->select('id', 'username', 'realname', 'pic', 'phone', 'schoolYear', 'gradeId', 'classId', 'sex', 'school', 'sno')->where(['id' => $id, 'checks' => 0])->first();
        $data && $data->gradeId && $data->gradeId = DB::table('schoolgrade') -> select('gradeName') -> where('id',$data->gradeId) -> pluck('gradeName') ;
        $data && $data->classId && $data->classId = DB::table('schoolclass') -> select('classname') -> where('id',$data->classId) -> pluck('classname') ;
        return view('home.accountManager.accountManagerStudent', compact('id', 'data'));
    }

    /**
     * @param $request
     * @param $filter
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function infoUphold(Request $request, Filter $filter)
    {
        $data = [];
        if($request['data']) {
            foreach ($request['data'] as $key => $value) {
                $data[$key] = $value;
            }
            $data['updated_at'] = Carbon::now();
            try {
                $data['intro'] = $filter -> filter($data['intro']);
            }catch (\Exception $e) {
                $data['intro'] = $request['intro'];
            }
        }

        $result = DB::table($request['table']) -> where('id', $request['userId']) -> update($data);
        if ($result) {
            return response()->json(['data' => $result, 'intro' => $data['intro'], 'status' => true]);
        } else {
            return response()->json(['status' => false, 'intro' => false]);
        }
    }

    /**
     * @param $request
     * 1 检查密码
     * 2 修改密码
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkPassword(Request $request)
    {
        switch($request['action']){
            case 1:
                $password = DB::table($request['table']) -> where('id', $request['userId']) -> pluck('password');
                if(\Hash::check($request['password'],$password)){
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false]);
                }
                break;
            case 2:
                $data = [];
                $data['password'] = bcrypt($request['password']);
                $data['updated_at'] = Carbon::now();
                $result = DB::table($request['table']) -> where('id', $request['userId']) -> update($data);
                return $this->returnResult($result);
                break;
        }
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
     * changePhone
     * @param $request
     *
     */
    public function changePhone(Request $request)
    {
        $flag = \DB::table('users') -> where('id', '=', $request['userId']) -> update(['phone' => $request['phone'], 'updated_at' => Carbon::now()]);
        if($flag) {
            return response()->json(['status'=>true]);
        }else {
            return response()->json(['status'=>false]);
        }
    }
    /*
     * 新增--添加任课
     * @param $request
     *
     */
    public function bindSubjectsInfo(Request $request)
    {
        switch($request['action']){
            case 1: // 年级 班 科目
                $condition = '';
                $columnId = '';
                $tableName = '';
                $columnName = '';
                $where['basic.tId'] = \Auth::user() -> id;
                if($request['type'] == 1) { $columnName = 'table.gradeName'; $columnId = 'gradeId'; $tableName = 'schoolgrade'; $where['table.status'] = 1; $condition = 'table.id';}
                if($request['type'] == 2) { $columnName = 'table.classname'; $columnId = 'gradeId'; $tableName = 'schoolclass'; $where['table.status'] = 1; $where['basic.gradeId'] = $request['data']; $condition = 'table.parentId'; }
                if($request['type'] == 3) { $columnName = 'table.subjectName'; $columnId = 'subjectId'; $tableName = 'studysubject'; $where['basic.gradeId'] = $request['data']; $condition = 'table.id'; }

                $result = \DB::table("{$request['table']} as basic")
                    -> join("{$tableName} as table", $condition, '=', "basic.{$columnId}")
                    -> select("table.id", "{$columnName} as text")
                    -> distinct()
                    -> where($where)
                    -> orderBy('table.id', 'asc')
                    -> get();
                break;

            case 3: // 修改科目时候 查询详细
                $result = DB::table($request['table']) -> select('id', 'gradeId', 'classId', 'subjectId') -> where('id', $request['id']) -> first();
                if($result->gradeId) {
                    $result->gradeName = DB::table('schoolgrade') -> select('gradeName') -> where('id', $result->gradeId) -> pluck('gradeName');
                }
                if($result->classId) {
                    $result->className = DB::table('schoolclass') -> select('classname') -> where('id', $result->classId) -> pluck('classname');
                }
                if($result->subjectId) {
                    $result->subjectName = DB::table('studysubject') -> select('subjectName') -> where('id', $result->subjectId) -> pluck('subjectName');
                }

                break;
        }
        return $this->returnResult($result);
    }

    /*
     * 新增--绑定学科 (旧)
     * @param $request
     *
     */
    public function getBindSubjects(Request $request)
    {
        switch($request['action']){
            case 1: // 年级
                $where = ['chapter.status' => 0];
                $request['tableName'] = $request['column'].'Name';

                $request['tableId'] = $request['column'].'Id';
                if($request['column'] == 'grade') $where['table.status'] = 1;
                if($request['data']) {
                    foreach($request['data'] as $key=>$v) {
                        $where['chapter.'.$key] = $v;
                    }
                }

                $result = \DB::table('chapter')
                    -> join("{$request['table']} as table", 'table.id', '=', "chapter.{$request['tableId']}")
                    -> select('table.id as id', "table.{$request['tableName']} as text")
                    -> distinct()
                    -> where($where)
                    -> get();
                break;

            case 2: // 添加到数据库
                $result = DB::table($request['table']) -> select('id') -> where($request['data']) -> first();
                if($result) {
                    return response()->json(['data' => $result, 'type' => true]);
                }else{
                    foreach ($request['data'] as $key => $value) {
                        $data[$key] = $value;
                    }
                    $result = DB::table($request['table']) -> insertGetId($data);
                }
                break;

            case 3: // 修改科目时候 查询详细
                $result = DB::table($request['table']) -> select('id', 'gradeId', 'subjectId', 'bookId', 'editionId') -> where('id', $request['id']) -> first();
                if($result->gradeId) {
                    $result->gradeName = DB::table('schoolgrade') -> select('gradeName') -> where('id', $result->gradeId) -> pluck('gradeName');
                }
                if($result->subjectId) {
                    $result->subjectName = DB::table('studysubject') -> select('subjectName') -> where('id', $result->subjectId) -> pluck('subjectName');
                }
                 if($result->bookId) {
                    $result->bookName = DB::table('studyebook') -> select('bookName') -> where('id', $result->bookId) -> pluck('bookName');
                }
                 if($result->editionId) {
                    $result->editionName = DB::table('studyedition') -> select('editionName') -> where('id', $result->editionId) -> pluck('editionName');
                }
                break;

            case 4: // 修改绑定学科 -- 执行修改
                $result = DB::table($request['table']) -> select('id') -> where($request['data']) -> where('id', '<>', $request['id']) -> first();
                if($result) {
                    return response()->json(['data' => $result, 'type' => true]);
                }else{
                    foreach ($request['data'] as $key => $value) {
                        $data[$key] = $value;
                    }
                    $result = DB::table($request['table']) -> where('id', $request['id']) -> update($data);
                    if($result !== FALSE) {
                        return response()->json(['data' => $result, 'status' => true]);
                    } else {
                        return response()->json(['status' => false]);
                    }
                }
                break;
            case 5: // 绑定学科 -- 删除
                $result = DB::table($request['table']) -> where('id', $request['id']) -> delete();
                break;
        }
        return $this->returnResult($result);
    }

    /**
     * @param $request
     * @param $pageNumber
     * @param $pageSize
     * 获取绑定学科列表
     *
     * @return \Illuminate\Http\Response
     */
    public function bindSubject(Request $request, $pageNumber, $pageSize)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $bindSubject = \DB::table('teachersubject as sub')
            ->leftJoin('schoolgrade as gra', 'gra.id', '=', 'sub.gradeId')
            ->leftJoin('studysubject as stu', 'stu.id', '=', 'sub.subjectId')
            ->leftJoin('studyebook as book', 'book.id', '=', 'sub.bookId')
            ->leftJoin('studyedition as edition', 'edition.id', '=', 'sub.editionId')
            ->select('sub.id', 'gra.gradeName', 'stu.subjectName', 'book.bookName', 'edition.editionName')
            ->where(['sub.tId' => $request['mineId']])
            ->orderBy('sub.id','asc')
            ->skip($skip)
            ->take($pageSize)
            ->get();

        $count = \DB::table('teachersubject as sub')
            ->leftJoin('schoolgrade as gra', 'gra.id', '=', 'sub.gradeId')
            ->leftJoin('studysubject as stu', 'stu.id', '=', 'sub.subjectId')
            ->leftJoin('studyebook as book', 'book.id', '=', 'sub.bookId')
            ->leftJoin('studyedition as edition', 'edition.id', '=', 'sub.editionId')
            ->where(['sub.tId' => $request['mineId']])
            ->count();
        if ($bindSubject) {
            return response()->json(['data' => $bindSubject, 'total' => $count, 'status' => true]);
        } else {
            return response()->json(['total' => $count, 'status' => false]);
        }
    }
 /**
 * @param $request
 * @param $pageNumber
 * @param $pageSize
 * 新增任课
 *
 * @return \Illuminate\Http\Response
 */
    public function addCourse(Request $request, $pageNumber, $pageSize)
    {
        $skip = ($pageNumber - 1) * $pageSize;
        $addCourse = \DB::table('teacherteach as tea')
            ->leftJoin('schoolgrade as gra', 'gra.id', '=', 'tea.gradeId')
            ->leftJoin('schoolclass as cla', 'cla.id', '=', 'tea.classId')
            ->leftJoin('studysubject as sub', 'sub.id', '=', 'tea.subjectId')
            ->select('tea.id', 'gra.gradeName', 'sub.subjectName', 'cla.classname as className')
            ->where(['tea.tId' => $request['mineId']])
            ->orderBy('tea.id','asc')
            ->skip($skip)
            ->take($pageSize)
            ->get();

        $count = \DB::table('teacherteach as tea')
            ->leftJoin('schoolgrade as gra', 'gra.id', '=', 'tea.gradeId')
            ->leftJoin('schoolclass as cla', 'cla.id', '=', 'tea.classId')
            ->leftJoin('studysubject as sub', 'sub.id', '=', 'tea.subjectId')
            ->where(['tea.tId' => $request['mineId']])
            ->count();
        if ($addCourse) {
            return response()->json(['data' => $addCourse, 'total' => $count, 'status' => true]);
        } else {
            return response()->json(['total' => $count, 'status' => false]);
        }
    }


    /**
     * @param $request
     * 上传头像
     * @return \Illuminate\Http\Response
     */
    public function addImg(Request $request)
    {
        //获取文件后缀名
        $ext = strrchr($_FILES['Filedata']['name'], '.');
        if ($request->hasFile('Filedata')) {
            if ($request->file('Filedata')->isValid()) {

                $newname = time() . $ext;

                // if ($request->file('Filedata')->move('/uploads/temporary/', $newname)) {
                if ($request->file('Filedata')->move(realpath(base_path('public')) . '/uploads/temporary/', $newname)) {

                    $a = $this->suofang('/uploads/temporary/' . $newname, 480, 330);

                    $arr = [
                        "src" => $a,
                        "width" => getimagesize(realpath(base_path('public')) . $a)[0],
                        "height" => getimagesize(realpath(base_path('public')) . $a)[1],
                    ];

                    if (file_exists(realpath(base_path('public')) . '/uploads/temporary/' . $newname)) {
                        unlink(realpath(base_path('public')) . '/uploads/temporary/' . $newname);
                    }

                    return response()->json($arr);

                }
            }
        } else {
            echo 0;  //没有文件上传
        }
    }

    /**
     * 保存裁剪
     * @return \Illuminate\Http\Response
     */
    public function cutImg()
    {
        $headImgSrc = $this->cut($_POST['imgsrc'], $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h']);
        if (file_exists(realpath(base_path('public')) . $_POST['imgsrc'])) {
            unlink(realpath(base_path('public')) . $_POST['imgsrc']);
        }

        if (\Auth::check()) {
            $id = \Auth::user()->id;
            $pic = \Auth::user()->pic;

            if (DB::table('users')->where('id', $id)->update(['pic' => $headImgSrc])) {
                if ($pic != '/home/image/layout/default.png') {
                    if (file_exists(realpath(base_path('public')) . $pic)) {
                        unlink(realpath(base_path('public')) . $pic);
                    }
                }
                echo 1; //修改成功
            } else {
                echo 2; //修改失败
            }
        }

    }

    /**
     * 保存裁剪(返回裁剪后头像的路径)
     * @return \Illuminate\Http\Response
     */
    public function trimImg()
    {
        $headImgSrc = $this->cut($_POST['imgsrc'], $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h']);
        if (file_exists(realpath(base_path('public')) . $_POST['imgsrc'])) {
            unlink(realpath(base_path('public')) . $_POST['imgsrc']);
        }
        return Response()->json($headImgSrc);
    }


    /*
     * @param $path 图片url
     * @param $width 目标图宽
     * @param $height 目标图高
     */
    function suofang($path, $width, $height)
    {
        $name = '/uploads/temporary/suofang' . time() . ".png";
        $src = $this->getimagetype($path);

        if ($src['width'] < $width && $src['height'] < $height) {
            copy(realpath(base_path('public')) . $path, realpath(base_path('public')) . $name);
            return $name;
        }


        if ($src['width'] > $src['height']) {
            $height = $src['height'] * ($width / $src['width']);
        } else {
            $width = $src['width'] * ($height / $src['height']);
        }

        $des = imagecreatetruecolor($width, $height);

        imagecopyresampled($des, $src['res'], 0, 0, 0, 0, $width, $height, $src['width'], $src['height']);

        imagepng($des, realpath(base_path('public')) . $name);


        imagedestroy($src['res']);
        imagedestroy($des);

        return $name;

    }

    /*
     * @param $path 图片url
     * @param $x 原图x坐标
     * @param $y 原图y坐标
     * @param $w 原图宽
     * @param $h 原图高
     */
    function cut($path, $x, $y, $w, $h)
    {
        $name = '/uploads/heads/cut' . time() . ".png";
        $src = $this->getimagetype($path);

        $des = imagecreatetruecolor(100, 100);

        imagecopyresampled($des, $src['res'], 0, 0, $x, $y, 100, 100, $w, $h);

        imagepng($des, realpath(base_path('public')) . $name);


        imagedestroy($src['res']);
        imagedestroy($des);

        return $name;

    }

    function getimagetype($path)
    {

        $path = realpath(base_path('public')) . $path;
        $imgarr = getimagesize($path);
        switch ($imgarr['mime']) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/pjpeg':
                $img = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $img = imagecreatefrompng($path);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($path);
                break;
        }
        //echo $imgarr['mime'];exit;
        $info['res'] = $img;
        $info['width'] = $imgarr[0];
        $info['height'] = $imgarr[1];


        return $info;
    }


    /*
       * @param $result
       * 返回数据
       **/
    protected function returnResult($result)
    {
        if ($result) {
            return response()->json(['data' => $result, 'status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }


}
