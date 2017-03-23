<?php
/**
 * Created by PhpStorm.
 * User: Mr.H
 * Date: 2017/2/14
 * Time: 11:36
 */

namespace App\Http\Controllers\Home\teacherCourse;

use DB;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;


class synchroTestController extends Controller{

    /**
     * 同步测验数据(课前导学)
     * @return mixed
     */
    public function getLeadLearn($id)
    {
//        $id = 1;
        $result = DB::table('coursechapter as c')
            ->join('exampaper as e', 'c.paperId', '=', 'e.id')
            ->select('e.id as paperId', 'e.title')
            ->where(['c.courseId' => $id, 'c.status' => 0, 'c.courseType' => 0])->get();
        $teacherId = DB::table('course')->select('teacherId')->where('id', $id)->pluck('teacherId');
        $isAuthor = ($teacherId == \Auth::user()->id) ? true : false;
        if ($result) {
            foreach ($result as $key => $value) {
                $info = DB::table('examinfo')->where('paperId', $value->paperId)->select('examinfo.completeTime', 'examinfo.submitTime', 'examinfo.type')->first();
                if ($info) {
                    $result[$key]->completeTime = $info->completeTime;
                    $result[$key]->submitTime = $info->submitTime;
                    $result[$key]->type = $info->type;
                } else {
                    $result[$key]->completeTime = '';
                    $result[$key]->submitTime = '';
                    $result[$key]->type = '';
                }
                $res = DB::table('examinfo')->where('paperId', $value->paperId)->select('classId')->get();
                if ($res) {
                    foreach ($res as $k => $v) {
                        $isAnswer = DB::table('examanswer')->join('users', 'users.id', '=', 'examanswer.userId')->where(['users.classId' => $v->classId, 'pId' => $value->paperId])->first();
                        $isAnswer ? $flag = true : $flag = false;
                    }
                    $result[$key]->isAnswer = $flag;
                } else {
                    $result[$key]->isAnswer = false;
                }

            }
            return response() -> json(['data' => $result, 'status' => true, 'isAuthor' => $isAuthor]);
        } else {
            return response() -> json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 同步测验数据(课堂授课)
     * @return mixed
     */
    public function getClassTeach($id)
    {
        $result = DB::table('coursechapter as c')
            ->join('exampaper as e', 'c.paperId', '=', 'e.id')
            ->select('e.id as paperId', 'e.title')
            ->where(['c.courseId' => $id, 'c.status' => 0, 'c.courseType' => 1])->get();
        $teacherId = DB::table('course')->select('teacherId')->where('id', $id)->pluck('teacherId');
        $isAuthor = ($teacherId == \Auth::user()->id) ? true : false;
        if ($result) {
            foreach ($result as $key => $value) {
                $info = DB::table('examinfo')->where('paperId', $value->paperId)->select('examinfo.completeTime', 'examinfo.submitTime', 'examinfo.type')->first();
                if ($info) {
                    $result[$key]->completeTime = $info->completeTime;
                    $result[$key]->submitTime = $info->submitTime;
                    $result[$key]->type = $info->type;
                } else {
                    $result[$key]->completeTime = '';
                    $result[$key]->submitTime = '';
                    $result[$key]->type = '';
                }
                $res = DB::table('examinfo')->where('paperId', $value->paperId)->select('classId')->get();
                if ($res) {
                    foreach ($res as $k => $v) {
                        $isAnswer = DB::table('examanswer')->join('users', 'users.id', '=', 'examanswer.userId')->where(['users.classId' => $v->classId, 'pId' => $value->paperId])->first();
                        $isAnswer ? $flag = true : $flag = false;
                    }
                    $result[$key]->isAnswer = $flag;
                } else {
                    $result[$key]->isAnswer = false;
                }
            }
            return response() -> json(['data' => $result, 'status' => true, 'isAuthor' => $isAuthor]);
        } else {
            return response() -> json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 同步测验数据(课后指导)
     * @return mixed
     */
    public function getAfterClass($id)
    {
//        $id = 1;
        $result = DB::table('coursechapter as c')
            ->join('exampaper as e', 'c.paperId', '=', 'e.id')
            ->select('e.id as paperId', 'e.title')
            ->where(['c.courseId' => $id, 'c.status' => 0, 'c.courseType' => 2])->get();
        $teacherId = DB::table('course')->select('teacherId')->where('id', $id)->pluck('teacherId');
        $isAuthor = ($teacherId == \Auth::user()->id) ? true : false;
        if ($result) {
            foreach ($result as $key => $value) {
                $info = DB::table('examinfo')->where('paperId', $value->paperId)->select('examinfo.completeTime', 'examinfo.submitTime', 'examinfo.type')->first();
                if ($info) {
                    $result[$key]->completeTime = $info->completeTime;
                    $result[$key]->submitTime = $info->submitTime;
                    $result[$key]->type = $info->type;
                } else {
                    $result[$key]->completeTime = '';
                    $result[$key]->submitTime = '';
                    $result[$key]->type = '';
                }
                $res = DB::table('examinfo')->where('paperId', $value->paperId)->select('classId')->get();
                if ($res) {
                    foreach ($res as $k => $v) {
                        $isAnswer = DB::table('examanswer')->join('users', 'users.id', '=', 'examanswer.userId')->where(['users.classId' => $v->classId, 'pId' => $value->paperId])->first();
                        $isAnswer ? $flag = true : $flag = false;
                    }
                    $result[$key]->isAnswer = $flag;
                } else {
                    $result[$key]->isAnswer = false;
                }
            }
            return response() -> json(['data' => $result, 'status' => true, 'isAuthor' => $isAuthor]);
        } else {
            return response() -> json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 获取作业(已答)试卷详细内容
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaperInfo($id)
    {
        // 获取试卷的基本信息
        $basicInfo = DB::table('exampaper')
            ->leftJoin('examinfo', 'exampaper.id', '=', 'examinfo.paperId')
            ->leftJoin('studysubject', 'studysubject.id', '=', 'exampaper.subjectId')
            ->leftJoin('studyedition', 'studyedition.id', '=', 'exampaper.editionId')
            ->leftJoin('schoolgrade', 'schoolgrade.id', '=', 'exampaper.gradeId')
            ->leftJoin('studyebook', 'studyebook.id', '=', 'exampaper.bookId')
            ->select('exampaper.title', 'studysubject.subjectName', 'studyedition.editionName', 'schoolgrade.gradeName', 'studyebook.bookName', 'examinfo.submitTime')
            ->where('exampaper.id',$id)->first();
        // 获取试卷的详细信息
        $resultA = DB::table('examschoosegroup as es')
            ->leftJoin('examschoose as e', 'e.id', '=', 'es.questionId')
            ->select('es.sort', 'e.*')->where(['es.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultA as $key => $value) {
            $resultA[$key]->choice = explode('┼┼', $value->choice);
            $resultA[$key]->type = 1;
        }
        // 多选
        $resultB = DB::table('exammchoosegroup as em')
            ->leftJoin('exammchoose as e', 'e.id', '=', 'em.questionId')
            ->select('em.sort', 'e.*')->where(['em.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultB as $key => $value) {
            $resultB[$key]->choice = explode('┼┼', $value->choice);
            $resultB[$key]->type = 2;
        }
        // 判断
        $resultC = DB::table('examjudgegroup as ej')
            ->leftJoin('examjudge as e', 'e.id', '=', 'ej.questionId')
            ->select('ej.sort', 'e.*')->where(['ej.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultC as $key => $value) {
            $resultC[$key]->type = 3;
        }
        // 填空
        $resultD = DB::table('examcompletiongroup as ec')
            ->leftJoin('examcompletion as e', 'e.id', '=', 'ec.questionId')
            ->select('ec.sort', 'e.*')->where(['ec.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultD as $key => $value) {
            $resultD[$key]->type = 4;
            $answer = explode('┼┼', $value->answer);
            $resultD[$key]->answer = implode('、', $answer);
        }
        // 解答
        $resultE = DB::table('examsubjectivegroup as es')
            ->leftJoin('examsubjective as e', 'e.id', '=', 'es.questionId')
            ->select('es.sort', 'e.*')->where(['es.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultE as $key => $value) {
            $resultE[$key]->type = 5;
        }

        $result = array_merge($resultA, $resultB, $resultC, $resultD, $resultE);
        $sort = [];
        if ($result && $basicInfo) {
            foreach ($result as $key => $value) {
                $sort[] = $value->sort;
            }
            array_multisort($sort, SORT_ASC, $result);
            return response()->json(['status' => true, 'data' => $result, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取测验试题详情(已答)内容
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestPaperInfo($id)
    {
        // 获取试卷的基本信息
        $basicInfo = DB::table('exampaper')
            ->leftJoin('examinfo', 'exampaper.id', '=', 'examinfo.paperId')
            ->leftJoin('studysubject', 'studysubject.id', '=', 'exampaper.subjectId')
            ->leftJoin('studyedition', 'studyedition.id', '=', 'exampaper.editionId')
            ->leftJoin('schoolgrade', 'schoolgrade.id', '=', 'exampaper.gradeId')
            ->leftJoin('studyebook', 'studyebook.id', '=', 'exampaper.bookId')
            ->select('exampaper.title', 'studysubject.subjectName', 'studyedition.editionName', 'schoolgrade.gradeName', 'studyebook.bookName', 'examinfo.submitTime')
            ->where('exampaper.id', $id)->first();
        // 获取试卷的详细信息
        $resultA = DB::table('examschoosegroup as es')
            ->leftJoin('examschoose as e', 'e.id', '=', 'es.questionId')
            ->select('es.sort', 'e.*')->where(['es.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultA as $key => $value) {
            $resultA[$key]->choice = explode('┼┼', $value->choice);
            $resultA[$key]->type = 1;
        }
        // 多选
        $resultB = DB::table('exammchoosegroup as em')
            ->leftJoin('exammchoose as e', 'e.id', '=', 'em.questionId')
            ->select('em.sort', 'e.*')->where(['em.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultB as $key => $value) {
            $resultB[$key]->choice = explode('┼┼', $value->choice);
            $resultB[$key]->type = 2;
        }
        // 判断
        $resultC = DB::table('examjudgegroup as ej')
            ->leftJoin('examjudge as e', 'e.id', '=', 'ej.questionId')
            ->select('ej.sort', 'e.*')->where(['ej.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultC as $key => $value) {
            $resultC[$key]->type = 3;
        }
        // 填空
        $resultD = DB::table('examcompletiongroup as ec')
            ->leftJoin('examcompletion as e', 'e.id', '=', 'ec.questionId')
            ->select('ec.sort', 'e.*')->where(['ec.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultD as $key => $value) {
            $resultD[$key]->type = 4;
            $answer = explode('┼┼', $value->answer);
            $resultD[$key]->answer = implode('、', $answer);
        }
        // 解答
        $resultE = DB::table('examsubjectivegroup as es')
            ->leftJoin('examsubjective as e', 'e.id', '=', 'es.questionId')
            ->select('es.sort', 'e.*')->where(['es.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultE as $key => $value) {
            $resultE[$key]->type = 5;
        }

        $result = array_merge($resultA, $resultB, $resultC, $resultD, $resultE);
        if ($result && $basicInfo) {
            $sort = [];
            foreach ($result as $key => $value) {
                $sort[] = $value->sort;
            }
            array_multisort($sort, SORT_ASC, $result);
        }
        if ($result) {
            foreach ($result as $key => $value) {
                switch ($value->type) {
                    case 1:
                        $res['sChoose'][] = $value;
                        break;
                    case 2:
                        $res['mChoose'][] = $value;
                        break;
                    case 3:
                        $res['judge'][] = $value;
                        break;
                    case 4:
                        $res['completion'][] = $value;
                        break;
                    case 5:
                        $res['subjective'][] = $value;
                        break;
                }
            }
        }
        if ($result && $basicInfo) {
            return response()->json(['status' => true, 'data' => $res, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }
}