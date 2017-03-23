<?php
/**
 * Created by PhpStorm.
 * User: Mr.H
 * Date: 2016/11/17
 * Time: 13:32
 */
namespace App\Http\Controllers\Admin\count;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class courseCountController extends Controller
{

    public function courseCountList()
    {
        $start = strtotime(date('Y-m-d 00:00:00', strtotime('-7 day')));
        $end = strtotime(date('Y-m-d 00:00:00', time()));
        $result = DB::select("SELECT courseview.courseId,count('courseId') AS num,course.courseTitle
                              FROM courseview JOIN course ON course.id = courseview.courseId
                              WHERE UNIX_TIMESTAMP(courseview.created_at) BETWEEN $start AND $end
                              AND course.courseIsDel = 0 AND course.courseStatus = 0
                              GROUP BY courseId ORDER BY num DESC LIMIT 10");

        $excel = DB::select("SELECT courseview.courseId AS '课程ID',count('courseId') AS '课程播放数',course.courseTitle AS '课程标题'
                              FROM courseview JOIN course ON course.id = courseview.courseId
                              WHERE UNIX_TIMESTAMP(courseview.created_at) BETWEEN $start AND $end
                              AND course.courseIsDel = 0 AND course.courseStatus = 0
                              GROUP BY courseId ORDER BY '课程播放数' DESC LIMIT 10");
        $excel = json_encode($excel);
        return view('admin.count.courseCountList', ['data' => $result, 'excel' => $excel]);
    }

    public function monthCountList()
    {
        $start = strtotime(date('Y-m-d 00:00:00', strtotime('-30 day')));
        $end = strtotime(date('Y-m-d 00:00:00', time()));
        $result = DB::select("SELECT courseview.courseId,count('courseId') AS num,course.courseTitle
                              FROM courseview JOIN course ON course.id = courseview.courseId
                              WHERE UNIX_TIMESTAMP(courseview.created_at) BETWEEN $start AND $end
                              AND course.courseIsDel = 0 AND course.courseStatus = 0
                              GROUP BY courseId ORDER BY num DESC LIMIT 10");

        $excel = DB::select("SELECT courseview.courseId AS '课程ID',count('courseId') AS '课程播放数',course.courseTitle AS '课程标题'
                              FROM courseview JOIN course ON course.id = courseview.courseId
                              WHERE UNIX_TIMESTAMP(courseview.created_at) BETWEEN $start AND $end
                              AND course.courseIsDel = 0 AND course.courseStatus = 0
                              GROUP BY courseId ORDER BY '课程播放数' DESC LIMIT 10");
        $excel = json_encode($excel);
        return view('admin.count.monthCountList', ['data' => $result, 'excel' => $excel]);
    }

    public function questionCountList()
    {
        $start = strtotime(date('Y-m-d 00:00:00', strtotime('-30 day')));
        $end = strtotime(date('Y-m-d 00:00:00', time()));
        $result = DB::select("SELECT subjects.id,count('type') AS num,subjects.subjectname
                              FROM question JOIN subjects ON subjects.id = question.type
                              WHERE UNIX_TIMESTAMP(question.asktime) BETWEEN $start AND $end
                              GROUP BY type ORDER BY num DESC LIMIT 10");

        $excel = DB::select("SELECT subjects.id as '所属科目ID',count('type') AS '提问次数',subjects.subjectname AS '所属分类名称'
                              FROM question JOIN subjects ON subjects.id = question.type
                              WHERE UNIX_TIMESTAMP(question.asktime) BETWEEN $start AND $end
                              GROUP BY type ORDER BY '提问次数' DESC LIMIT 10");
        $excel = json_encode($excel);
        return view('admin.count.questionCountList', ['data' => $result, 'excel' => $excel]);
    }
}
