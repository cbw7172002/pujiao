<?php
/**
 * Created by PhpStorm.
 * User: Mr.H
 * Date: 2017/1/6
 * Time: 17:18
 */

namespace App\Http\Controllers\Home\evaluateManageTea;

use DB;
use Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Home\lessonComment\Gadget;
use Exception;
use Excel;
use Log;
use Filter;

class evaluateManageTeaController extends Controller
{
    use Gadget;

    /**
     * 测评管理首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function index()
    {
        $teacherId = \Auth::check() ? \Auth::user()->id : '';
        return view('home.evaluateManageTea.indexTea', compact('teacherId'));
    }

    /**
     * 题库试卷详情页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function testPaperTea($id)
    {
        $result = DB::table('exampaper as e')
            ->leftJoin('schoolgrade as sg', 'e.gradeId', '=', 'sg.id')
            ->leftJoin('studysubject as ss', 'e.subjectId', '=', 'ss.id')
            ->leftJoin('studyedition as se', 'e.editionId', '=', 'se.id')
            ->leftJoin('studyebook as sb', 'e.bookId', '=', 'sb.id')
            ->select('sg.gradeName', 'ss.subjectName', 'ss.id as subjectId', 'se.editionName', 'sb.bookName', 'e.title', 'e.created_at', 'e.count')->where('e.id', $id)->first();
        $result || abort(404);
        $result->created_at = explode(' ', $result->created_at)[0];
        $title = $result->title;
        return view('home.evaluateManageTea.testPaperTea', compact('result', 'id', 'title', 'isCollect'));
    }

    /**
     * 教师批改学生作业
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function homeScore($id, $userId)
    {
        return view('home.evaluateManageTea.homeScore', compact('id', 'userId'));
    }
    /**
     * 教师批改学生作业数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomeScore($id, $userId)
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
        $classId = DB::table('users')->where('id', $userId)->select('classId')->pluck('classId');
        // 获取试卷的详细信息
        // 单选
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
            $answer = explode('┼┼', $value->answer);
            $resultB[$key]->answer = implode('、', $answer);
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
        if ($result) {
            $sort = [];
            foreach ($result as $key => $value) {
                $sort[] = $value->sort;
            }
            array_multisort($sort, SORT_ASC, $result);
        }
        $examAnswer = json_decode(DB::table('examanswer')->select('answer')->where(['userId' => $userId, 'pId' => $id])->pluck('answer'));
        if ($result && $examAnswer) {
            foreach ($result as $key => $value) {
                if ($value->type == 5) {
                    $result[$key]->comment = $examAnswer->$key->comment;
                    $result[$key]->getScore = $examAnswer->$key->score;
                    $result[$key]->userAnswer = $examAnswer->$key->answer;
                } else if ($value->type == 2) {
                    $userAnswer = implode('、', explode('┼┼', $examAnswer->$key->answer));
                    $result[$key]->userAnswer = $userAnswer;
                    $result[$key]->userAnswer2 = $examAnswer->$key->answer;
                    if ($userAnswer == $value->answer) {
                        $result[$key]->isRight = true;
                    } else {
                        $result[$key]->isRight = false;
                    }
                } else if ($value->type == 4) {
                    $result[$key]->userAnswer = $examAnswer->$key->answer;
                    if ($examAnswer->$key->answer) {
                        $userAnswer = explode('┼┼', $examAnswer->$key->answer);
                        $answerNum = count($userAnswer);
                        if ($answerNum > 1) {
                            for ($i = 0; $i < $answerNum; $i++) {
                                $temp = 'ans' . $i;
                                $result[$key]->$temp = $userAnswer[$i];
                            }
                        } else {
                            $result[$key]->ans0 = $examAnswer->$key->answer;
                        }
                    } else {
                        $result[$key]->ans0 = $examAnswer->$key->answer;
                    }
                    $tempAnswer = implode('、', explode('┼┼', $examAnswer->$key->answer));
                    if ($tempAnswer == $value->answer) {
                        $result[$key]->isRight = true;
                    } else {
                        $result[$key]->isRight = false;
                    }
                } else {
                    $userAnswer = $examAnswer->$key->answer;
                    $result[$key]->userAnswer = $userAnswer;
                    if ($userAnswer == $value->answer) {
                        $result[$key]->isRight = true;
                    } else {
                        $result[$key]->isRight = false;
                    }
                }
                $result[$key]->userScore = $examAnswer->$key->score;
            }
        }
        if ($result && $basicInfo) {
            $basicInfo->classId = $classId;
            return response()->json(['status' => true, 'data' => $result, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    public function submitHomeScore(Request $request){
        $data = $request->all();
        $data['type'] = 1;
        $answer = json_decode($data['answer']);
        foreach ($answer as $key => $value) {
            if ($value->type == 5) {
                $value->comment = Filter::filter($value->comment);
            }
        }
        $data['answer'] = json_encode($answer);
        $updateAnswer =  DB::table('examanswer')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => $data['type']])->update($data);;
        // 组装错题信息
        $info['userId'] = $data['userId'];
        $info['pId'] = $data['pId'];
        $info['type'] = $data['type'];
        $info['updated_at'] = Carbon::now();
        $wrong = [];
        // 组装分数表需要的信息
        $score = [
            'userId' => $data['userId'],
            'pId' => $data['pId'],
            'type' => $data['type'],
            'score1' => 0,
            'score2' => 0,
            'score3' => 0,
            'score4' => 0,
            'score5' => 0,
            'score' => 0,
            'updated_at' => Carbon::now()
        ];
        foreach ($answer as $key => $value) {
            if ($value->type == 5) {
                $trueScore = DB::table('examsubjective')->where('id', $value->id)->select('score')->pluck('score');
                if ($value->score != $trueScore) {
                    $wrong[] = ['id' => $value->id, 'type' => $value->type];
                }
            } else {
                if ($value->score === 0) {
                    $wrong[] = ['id' => $value->id, 'type' => $value->type];
                }
            }
            // 计算各类型题得分
            switch ($value->type) {
                case 1:
                    $score['score1'] += $value->score;
                    break;
                case 2:
                    $score['score2'] += $value->score;
                    break;
                case 3:
                    $score['score3'] += $value->score;
                    break;
                case 4:
                    $score['score4'] += $value->score;
                    break;
                case 5:
                    $score['score5'] += $value->score;
                    break;
            }
        }
        // 存入总分
        $score['score'] = $score['score1'] + $score['score2'] + $score['score3'] + $score['score4'] + $score['score5'];
        $updateScore = DB::table('examscore')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => $data['type']])->update($score);
        if (count($wrong)) {
            $info['wrongQuestion'] = json_encode($wrong);
            $updateWrong = DB::table('examwrong')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => $data['type']])->update($info);
        } else {
            $updateWrong = DB::table('examwrong')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => $data['type']])->delete();
        }
        if ($updateAnswer && $updateScore && $updateWrong) {
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }


    /**
     * 教师批改学生测验
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function testScore($id, $userId)
    {
        return view('home.evaluateManageTea.testScore', compact('id', 'userId'));
    }
    /**
     * 教师批改学生测验数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestScore($id, $userId)
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
        $classId = DB::table('users')->where('id', $userId)->select('classId')->pluck('classId');
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
            $answer = explode('┼┼', $value->answer);
            $resultB[$key]->answer = implode('、', $answer);
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
        $examAnswer = json_decode(DB::table('examanswer')->select('answer')->where(['userId' => $userId, 'pId' => $id])->pluck('answer'));
        $res = [];
        if ($result && $examAnswer) {
            foreach ($result as $key => $value) {
                if ($examAnswer[$key]->type == 2) {
                    $userAnswer = implode('、', explode('┼┼', $examAnswer[$key]->answer));
                    $result[$key]->userAnswer = $userAnswer;
                    if ($userAnswer == $value->answer) {
                        $result[$key]->isRight = true;
                    } else {
                        $result[$key]->isRight = false;
                    }
                } else if ($examAnswer[$key]->type == 4) {
                    if ($examAnswer[$key]->answer) {
                        $userAnswer = explode('┼┼', $examAnswer[$key]->answer);
                        $answerNum = count($userAnswer);
                        if ($answerNum > 1) {
                            for ($i = 0; $i < $answerNum; $i++) {
                                $temp = 'ans' . $i;
                                $result[$key]->$temp = $userAnswer[$i];
                            }
                        } else {
                            $result[$key]->ans0 = $examAnswer[$key]->answer;
                        }
                    } else {
                        $result[$key]->ans0 = $examAnswer[$key]->answer;
                    }
                    $tempAnswer = implode('、', explode('┼┼', $examAnswer[$key]->answer));
                    if ($tempAnswer == $value->answer) {
                        $result[$key]->isRight = true;
                    } else {
                        $result[$key]->isRight = false;
                    }
                } else if ($examAnswer[$key]->type == 5) {
                    $result[$key]->comment = $examAnswer[$key]->comment;
                    $result[$key]->getScore = $examAnswer[$key]->score;
                    $result[$key]->userAnswer = $examAnswer[$key]->answer;
                } else {
                    $userAnswer = $examAnswer[$key]->answer;
                    $result[$key]->userAnswer = $userAnswer;
                    if ($userAnswer == $value->answer) {
                        $result[$key]->isRight = true;
                    } else {
                        $result[$key]->isRight = false;
                    }
                }
                $result[$key]->userScore = $examAnswer[$key]->score;
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
        if ($result && $basicInfo && $res) {
            $basicInfo->classId = $classId;
            return response()->json(['status' => true, 'data' => $res, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    public function submitTestScore(Request $request){
        $data['userId'] = $request['userId'];
        $data['pId'] = $request['pId'];
        $data['type'] = 2;
        // 处理答案信息
        $answer = json_decode($request['answer']);
        $temp = [];
        foreach ($answer as $key => $value) {
            $temp = array_merge($temp, json_decode($value, true));
        }
        $info = []; // 存放答案数组
        foreach ($temp as $key => $value) {
            $info[$key]['index'] = $temp[$key]['index'];
            $info[$key]['type'] = $temp[$key]['type'];
            $info[$key]['id'] = $temp[$key]['id'];
            $info[$key]['answer'] = $temp[$key]['newAnswer'];
            $info[$key]['score'] = $temp[$key]['newScore'];
            if ($temp[$key]['type'] == 5){
                $info[$key]['comment'] = Filter::filter($temp[$key]['comment']);
            }
        }
        $data['answer'] = json_encode($info);
        $updateAnswer = DB::table('examanswer')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => $data['type']])->update($data);
        // 组装错题信息
        $data2['userId'] = $data['userId'];
        $data2['pId'] = $data['pId'];
        $data2['type'] = $data['type'];
        $data2['updated_at'] = Carbon::now();
        $wrong = [];
        // 组装分数表需要的信息
        $score = [
            'userId' => $data['userId'],
            'pId' => $data['pId'],
            'type' => $data['type'],
            'score1' => 0,
            'score2' => 0,
            'score3' => 0,
            'score4' => 0,
            'score5' => 0,
            'score' => 0,
            'updated_at' => Carbon::now()
        ];
        foreach ($info as $key => $value) {
            if ($value['type'] == 5) {
                $trueScore = DB::table('examsubjective')->where('id', $value['id'])->select('score')->pluck('score');
                if ($value['score'] != $trueScore) {
                    $wrong[] = ['id' => $value['id'], 'type' => $value['type']];
                }
            } else {
                if ($value['score'] === 0) {
                    $wrong[] = ['id' => $value['id'], 'type' => $value['type']];
                }
            }
            // 计算各类型题得分
            switch ($value['type']) {
                case 1:
                    $score['score1'] += $value['score'];
                    break;
                case 2:
                    $score['score2'] += $value['score'];
                    break;
                case 3:
                    $score['score3'] += $value['score'];
                    break;
                case 4:
                    $score['score4'] += $value['score'];
                    break;
                case 5:
                    $score['score5'] += $value['score'];
                    break;
            }
        }
        // 存入总分
        $score['score'] = $score['score1'] + $score['score2'] + $score['score3'] + $score['score4'] + $score['score5'];
        $updateScore = DB::table('examscore')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => $data['type']])->update($score);
        if(count($wrong)){
            $data2['wrongQuestion'] = json_encode($wrong);
            $updateWrong = DB::table('examwrong')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => $data['type']])->update($data2);
        }else{
            $updateWrong = DB::table('examwrong')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => $data['type']])->delete();
        }
        if ($updateAnswer && $updateScore && $updateWrong) {
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }
    /**
     * 获取在线题库的数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOnlineQuestion(Request $request)
    {
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber - 1) * $pageSize;
        $type = $request['type'];
        $gradeId = $request['gradeId'];
        $subjectId = $request['subjectId'];
        $sort = $request['sort'];
        $query = DB::table('exampaper')
            ->leftJoin('schoolgrade', 'schoolgrade.id', '=', 'exampaper.gradeId')
            ->leftJoin('studysubject', 'studysubject.id', '=', 'exampaper.subjectId')
            ->leftJoin('studyedition', 'studyedition.id', '=', 'exampaper.editionId')
            ->leftJoin('studyebook', 'studyebook.id', '=', 'exampaper.bookId')
            ->leftJoin('schoolclass', 'schoolclass.id', '=', 'exampaper.classId')
            ->leftJoin('users', 'users.id', '=', 'exampaper.userId')
            ->select('exampaper.id', 'exampaper.type', 'exampaper.title', 'exampaper.difficult', 'exampaper.created_at', 'exampaper.count', 'exampaper.paperView',
                'schoolgrade.gradeName', 'studysubject.subjectName', 'studyedition.editionName', 'studyebook.bookName', 'schoolclass.className', 'users.username')
            ->where('exampaper.status', 0)->where('exampaper.title', 'like', '%'.$request['condition'].'%');
        if ($type == 'all' && $gradeId == 'all' && $subjectId == 'all') {
            $temp = $query;
            $count = $query->count();

        } elseif ($type == 'all' && $gradeId != 'all' && $subjectId != 'all') {
            $temp = $query->whereIn('exampaper.gradeId', $gradeId)->whereIn('exampaper.subjectId', $subjectId);
            $count = $query->whereIn('exampaper.gradeId', $gradeId)->whereIn('exampaper.subjectId', $subjectId)->count();

        } elseif ($type != 'all' && $gradeId == 'all' && $subjectId != 'all') {
            $temp = $query->whereIn('exampaper.type', $type)->whereIn('exampaper.subjectId', $subjectId);
            $count = $query->whereIn('exampaper.type', $type)->whereIn('exampaper.subjectId', $subjectId)->count();

        } elseif ($type != 'all' && $gradeId != 'all' && $subjectId == 'all') {
            $temp = $query->whereIn('exampaper.type', $type)->whereIn('exampaper.gradeId', $gradeId);
            $count = $query->whereIn('exampaper.type', $type)->whereIn('exampaper.gradeId', $gradeId)->count();

        } elseif ($type != 'all' && $gradeId == 'all' && $subjectId == 'all') {
            $temp = $query->whereIn('exampaper.type', $type);
            $count = $query->whereIn('exampaper.type', $type)->count();

        } elseif ($type == 'all' && $gradeId != 'all' && $subjectId == 'all') {
            $temp = $query->whereIn('exampaper.gradeId', $gradeId);
            $count = $query->whereIn('exampaper.gradeId', $gradeId)->count();

        } elseif ($type == 'all' && $gradeId == 'all' && $subjectId != 'all') {
            $temp = $query->whereIn('exampaper.subjectId', $subjectId);
            $count = $query->whereIn('exampaper.subjectId', $subjectId)->count();

        } else {
            $temp = $query->whereIn('exampaper.type', $type)->whereIn('exampaper.gradeId', $gradeId)->whereIn('exampaper.subjectId', $subjectId);
            $count = $query->whereIn('exampaper.type', $type)->whereIn('exampaper.gradeId', $gradeId)->whereIn('exampaper.subjectId', $subjectId)->count();
        }
        if ($sort == 1) {
            $result = $temp->orderBy('exampaper.paperView', 'desc')->skip($skip)->take($pageSize)->get();
        } else {
            $result = $temp->orderBy('exampaper.created_at', 'desc')->skip($skip)->take($pageSize)->get();
        }
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key]->created_at = explode(' ', $value->created_at)[0];
            }
            return response()->json(['status' => true, 'data' => $result, 'count' => $count]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取试题布置的数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestInfo(Request $request)
    {
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber - 1) * $pageSize;

        $AllGrade = $this->getTeachInfo($request['teacherId'], 3);
        $AllSubject = $this->getTeachInfo($request['teacherId'], 2);
        $AllType = [0, 1];
        $gradeId = $request['gradeId'] == 'all' ? $AllGrade : $request['gradeId'];
        $subjectId = $request['subjectId'] == 'all' ? $AllSubject : $request['subjectId'];
        $type = $request['type'] == 'all' ? $AllType : $request['type'];

        $query = DB::table('exampaper')
            ->leftJoin('schoolgrade', 'schoolgrade.id', '=', 'exampaper.gradeId')
            ->leftJoin('studysubject', 'studysubject.id', '=', 'exampaper.subjectId')
            ->leftJoin('studyedition', 'studyedition.id', '=', 'exampaper.editionId')
            ->leftJoin('studyebook', 'studyebook.id', '=', 'exampaper.bookId')
            ->leftJoin('schoolclass', 'schoolclass.id', '=', 'exampaper.classId')
            ->select('exampaper.id', 'exampaper.type', 'exampaper.title', 'exampaper.difficult', 'exampaper.paperView', 'exampaper.created_at', 'exampaper.count', 'schoolgrade.gradeName', 'studysubject.subjectName', 'studyedition.editionName', 'studyebook.bookName', 'schoolclass.className')
            ->where('exampaper.status', 0)->where('exampaper.title', 'like', '%'.$request['condition'].'%')->whereIn('exampaper.gradeId', $gradeId)->whereIn('exampaper.subjectId', $subjectId)->whereIn('exampaper.type', $type);
        $result = $query->skip($skip)->take($pageSize)->get();
        $count = $query->count();
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key]->created_at = explode(' ', $value->created_at)[0];
            }
            return response()->json(['status' => true, 'data' => $result, 'count' => $count]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 试题批改 条件筛选数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeacherInfo(Request $request)
    {
        $teachClass = DB::table('teacherteach')
            ->join('schoolclass', 'schoolclass.id', '=', 'teacherteach.classId')
            ->join('schoolgrade', 'schoolgrade.id', '=', 'teacherteach.gradeId')
            ->select('schoolclass.id', 'schoolclass.classname', 'schoolgrade.gradeName')
            ->where('tId', $request['teacherId'])->distinct()->orderBy('schoolclass.id')->get();
        $teachSubject = DB::table('teacherteach')
            ->join('studysubject', 'studysubject.id', '=', 'teacherteach.subjectId')
            ->select('studysubject.id', 'studysubject.subjectName')
            ->where('tId', $request['teacherId'])->distinct()->orderBy('studysubject.id')->get();
        $teachGrade = DB::table('teacherteach')
            ->join('schoolgrade', 'schoolgrade.id', '=', 'teacherteach.gradeId')
            ->select('schoolgrade.id', 'schoolgrade.gradeName')
            ->where('tId', $request['teacherId'])->distinct()->orderBy('schoolgrade.id')->get();
        if ($teachClass) {
            return response()->json(['status' => true, 'class' => $teachClass, 'subject' => $teachSubject, 'grade' => $teachGrade]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    function getTeachInfo($teacherId, $type){
        switch ($type){
            case 1:
                $teachClass = DB::table('teacherteach')
                    ->join('schoolclass', 'schoolclass.id', '=', 'teacherteach.classId')
                    ->select('teacherteach.classId')
                    ->where('tId', $teacherId)->distinct()->get();
                $AllClass = [];
                foreach ($teachClass as $key => $value) {
                    array_push($AllClass, $value->classId);
                }
                return $AllClass;
            case 2:
                $teachSubject = DB::table('teacherteach')
                    ->join('studysubject', 'studysubject.id', '=', 'teacherteach.subjectId')
                    ->select('teacherteach.subjectId')
                    ->where('tId', $teacherId)->distinct()->get();
                $AllSubject = [];
                foreach ($teachSubject as $key => $value) {
                    array_push($AllSubject, $value->subjectId);
                }
                return $AllSubject;
            case 3:
                $teachSubject = DB::table('teacherteach')
                    ->join('schoolgrade', 'schoolgrade.id', '=', 'teacherteach.gradeId')
                    ->select('teacherteach.gradeId')
                    ->where('tId', $teacherId)->distinct()->get();
                $AllGrade = [];
                foreach ($teachSubject as $key => $value) {
                    array_push($AllGrade, $value->gradeId);
                }
                return $AllGrade;
        }
    }
    /**
     * 获取试题批改的数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuestionInfo(Request $request)
    {
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber - 1) * $pageSize;

        $AllClass = $this->getTeachInfo($request['teacherId'], 1);
        $AllSubject = $this->getTeachInfo($request['teacherId'], 2);

        $type = $request['type'];
        $status = $request['status'];
        $classId = $request['classId'] == 'all' ? $AllClass : $request['classId'];
        $subjectId = $request['subjectId'] == 'all' ? $AllSubject : $request['subjectId'];
        $query = DB::table('examinfo')
            ->join('schoolclass', 'schoolclass.id', '=', 'examinfo.classId')
            ->join('exampaper', 'exampaper.id', '=', 'examinfo.paperId')
            ->join('schoolgrade', 'schoolgrade.id', '=', 'schoolclass.parentId')
            ->join('examsubjectivegroup', 'examsubjectivegroup.paperId', '=', 'examinfo.paperId')
            ->select('exampaper.id', 'exampaper.type', 'exampaper.title', 'exampaper.difficult', 'exampaper.created_at', 'exampaper.count', 'schoolclass.classname', 'schoolgrade.gradeName', 'examinfo.classId', 'examinfo.type as eType')
            ->where('exampaper.status', 0)->whereIn('examinfo.classId', $classId)->whereIn('exampaper.subjectId', $subjectId)->where('exampaper.title', 'like', '%'.$request['condition'].'%');
        $query2 = DB::table('examinfo')
            ->join('schoolclass', 'schoolclass.id', '=', 'examinfo.classId')
            ->join('exampaper', 'exampaper.id', '=', 'examinfo.paperId')
            ->join('schoolgrade', 'schoolgrade.id', '=', 'schoolclass.parentId')
            ->join('examsubjectivegroup', 'examsubjectivegroup.paperId', '=', 'examinfo.paperId')
            ->select('exampaper.id', 'exampaper.type', 'exampaper.title', 'exampaper.difficult', 'exampaper.created_at', 'exampaper.count', 'schoolclass.classname', 'schoolgrade.gradeName', 'examinfo.classId', 'examinfo.type as eType')
            ->where('exampaper.status', 0)->whereIn('examinfo.classId', $classId)->whereIn('exampaper.subjectId', $subjectId)->where('exampaper.title', 'like', '%'.$request['condition'].'%');
        if ($type == 'all' && $status == 'all') {
            $result = $query->distinct()->skip($skip)->take($pageSize)->get();
            $count = $query2->distinct()->get();
        } elseif ($type == 'all' && $status != 'all') {
            $result = $query->whereIn('status', $status)->distinct()->skip($skip)->take($pageSize)->get();
            $count = $query2->whereIn('status', $status)->distinct()->get();
        } elseif ($type != 'all' && $status == 'all') {
            $result = $query->whereIn('examinfo.type', $type)->distinct()->skip($skip)->take($pageSize)->get();
            $count = $query2->whereIn('examinfo.type', $type)->distinct()->get();
        } else {
            $result = $query->whereIn('status', $status)->whereIn('examinfo.type', $type)->distinct()->skip($skip)->take($pageSize)->get();
            $count = $query2->whereIn('status', $status)->whereIn('examinfo.type', $type)->distinct()->get();
        }
        if ($result) {
            foreach ($result as $key => $value) {
                // 需要答题的总人数
                $aNum = DB::table('users')->where('classId', $value->classId)->select('id')->count();
                // 已答人数
                $bNum = DB::table('examanswer')
                    ->join('examinfo', 'examinfo.paperId', '=', 'examanswer.pId')
                    ->where(['examinfo.classId' => $value->classId, 'examinfo.paperId' => $value->id])->count();
                if ($aNum == $bNum) {
                    $result[$key]->isComplete = true;
                } else {
                    $result[$key]->isComplete = false;
                }
                $result[$key]->created_at = explode(' ', $value->created_at)[0];
                $value->type ? $type = '测验试卷' : $type = '同步练习';
                $value->eType == 1 ? $eType = '作业' : $eType = '测验';
                $result[$key]->typeName = $type . '-' . $eType;
            }
            return response()->json(['status' => true, 'data' => $result, 'count' => count($count)]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取成绩查询的数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQueryInfo(Request $request)
    {
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber - 1) * $pageSize;

        $AllClass = $this->getTeachInfo($request['teacherId'], 1);
        $AllSubject = $this->getTeachInfo($request['teacherId'], 2);

        $type = $request['type'];
        $classId = $request['classId'] == 'all' ? $AllClass : $request['classId'];
        $subjectId = $request['subjectId'] == 'all' ? $AllSubject : $request['subjectId'];

		$query = DB::table('examinfo')
            ->join('schoolclass', 'schoolclass.id', '=', 'examinfo.classId')
            ->join('schoolgrade', 'schoolgrade.id', '=', 'schoolclass.parentId')
            ->join('exampaper', 'exampaper.id', '=', 'examinfo.paperId')
            ->select('exampaper.id', 'examinfo.classId','exampaper.type', 'exampaper.title', 'exampaper.difficult', 'exampaper.created_at', 'exampaper.count', 'schoolclass.className', 'schoolgrade.gradeName', 'examinfo.type as eType')
            ->whereIn('examinfo.classId', $classId)->whereIn('exampaper.subjectId', $subjectId)
            ->where('exampaper.title', 'like', '%'.$request['condition'].'%')
            ->where('exampaper.userId', $request['teacherId']);
        if ($type == 'all') {
            $result = $query->skip($skip)->take($pageSize)->get();
            $count = $query->count();
        }  else {
            $result = $query->whereIn('examinfo.type', $type)->skip($skip)->take($pageSize)->get();
            $count = $query->whereIn('examinfo.type', $type)->count();
        }
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key]->created_at = explode(' ', $value->created_at)[0];
                $value->type ? $type = '测验试卷' : $type = '同步练习';
                $value->eType == 1 ? $eType = '作业' : $eType = '测验';
                $result[$key]->typeName = $type . '-' . $eType;
            }
            return response()->json(['status' => true, 'data' => $result, 'count' => $count]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取年级信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGradeInfo()
    {
        $result = DB::table('schoolgrade')->select('id', 'gradeName')->where('status', 1)->get();
        if ($result) {
            return response()->json(['status' => true, 'data' => $result]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取学科信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubjectInfo()
    {
        $result = DB::table('studysubject')->select('id', 'subjectName')->get();
        if ($result) {
            return response()->json(['status' => true, 'data' => $result]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取班级信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassInfo()
    {
        $result = DB::table('schoolclass')->select('id', 'classname')->get();
        if ($result) {
            return response()->json(['status' => true, 'data' => $result]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取试卷详细内容
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestPaperDetail($id)
    {
        $difficult = ['极易', '简单', '一般', '稍难', '困难'];

        // 单选
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
            $answer = explode('┼┼', $value->answer);
            $resultB[$key]->answer = implode('、', $answer);
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
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key]->created_at = explode(' ', $value->created_at)[0];
                $result[$key]->difficult = $difficult[$value->difficult];
                $sort[] = $value->sort;
                $collect = DB::table('resourcestore')->where(['type' => 2, 'userId' => \Auth::user()->id, 'resourceId' => $value->id, 'examType' => $value->type])->select('id')->first();
                $result[$key]->isCollectQ = $collect ? true : false;
            }
            array_multisort($sort, SORT_ASC, $result);

            return response()->json(['status' => true, 'data' => $result]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 是否收藏试卷
     * @param $id
     * @return mixed
     */
    public function isCollectPaper($id)
    {
        $collect = DB::table('resourcestore')->where(['type' => 3, 'userId' => \Auth::user()->id, 'resourceId' => $id])->select('id')->first();
        if ($collect) {
            return response()->json(['data' => true, 'status' => true]);
        } else {
            return response()->json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 收藏试卷
     * @param Request $request
     * @return mixed
     */
    public function collectPaper(Request $request)
    {
        $info['resourceId'] = $request['paperId'];
        $info['resourcetitle'] = $request['title'];
        $info['type'] = 3;
        $info['userId'] = \Auth::user()->id;
        $info['created_at'] = $info['updated_at'] = Carbon::now();
        $info['subjectId'] = $request['subjectId'];
        if (!DB::table('resourcestore')->where(['type' => 3, 'userId' => \Auth::user()->id, 'resourceId' => $request['paperId']])->select('id')->first()) {
            $result = DB::table('resourcestore')->insertGetId($info);
        } else {
            $result = DB::table('resourcestore')->where(['type' => 3, 'userId' => \Auth::user()->id, 'resourceId' => $request['paperId']])->delete();
        }
        if ($result) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * 收藏试题
     * @param Request $request
     * @return mixed
     */
    public function collectQuestion(Request $request)
    {
        $info['resourceId'] = $request['questionId'];
        $info['type'] = 2;
        $info['userId'] = \Auth::user()->id;
        $info['created_at'] = $info['updated_at'] = Carbon::now();
        $info['subjectId'] = $request['subjectId'];
        $info['chapterId'] = $request['chapterId'];
        $info['examType'] = $request['examType'];
        if (!DB::table('resourcestore')->where(['type' => 2, 'userId' => \Auth::user()->id, 'resourceId' => $request['questionId'], 'examType' => $request['examType']])->select('id')->first()) {
            $result = DB::table('resourcestore')->insertGetId($info);
        } else {
            $result = DB::table('resourcestore')->where(['type' => 2, 'userId' => \Auth::user()->id, 'resourceId' => $request['questionId'], 'examType' => $request['examType']])->delete();
        }
        if ($result) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * 试题批改 查看单道解答题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubjective(Request $request)
    {
        $result = DB::table('examsubjective')->select('id','title', 'answer', 'score')->where('id', $request['id'])->first();
        if ($result) {
            return response()->json(['data' => $result, 'status' => true]);
        } else {
            return response()->json(['data' => false, 'status' => false]);
        }
    }
    /**
     * 试题批改 查看全部学生试卷详细
     * @param $paperId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuestionCorrectDetail($paperId, $classId)
    {
        $result = DB::table('examinfo')->leftJoin('users', 'users.classId', '=', 'examinfo.classId')
            ->select('users.id', 'users.username', 'examinfo.type', 'examinfo.paperId')->where('examinfo.paperId', $paperId)->where('examinfo.classId', $classId)->get();
        // 筛选出解答题
        $temp = [];
        // 筛选出题号
        $tem = [];
        foreach ($result as $key => $value) {
            $userAnswer = DB::table('examanswer')->where(['userId' => $value->id, 'pId' => $paperId])->select('id as answerId', 'answer', 'pId')->first();
            if ($userAnswer) {
                $result[$key]->answerId = $userAnswer->answerId;
                $result[$key]->answer = $userAnswer->answer;
                $result[$key]->paperId = $userAnswer->pId;
            }else {
                $result[$key]->answerId = '';
                $result[$key]->answer = '';
                $result[$key]->paperId = '';
            }
            $answer = json_decode($result[$key]->answer);
            if ($answer) {
                foreach ($answer as $v) {
                    if ($v->type == 5){
                        $temp[$key][] = $v;
                    }
                }
            } else {
                $temp[$key][] = false;
            }
            if (count($temp) > 0) {
                $result[$key]->answer = $temp[$key];
            }
            if (is_array($result[$key]->answer)){
                foreach ($result[$key]->answer as $k => $v){
                    if ($v) {
                        $tem[$v->index][] = $v;
                    }
                }
            }
        }
        $num = [];
        if ($tem) {
            foreach ($tem as $key => $value) {
                $num[] = $key;
            }
        }
        $questionNum = count($num);
        if ($result && $num) {
            return response()->json(['status' => true, 'data' => $result, 'num' => $num]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 试题批改 提交单道解答题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitSubject(Request $request)
    {
        $result = DB::table('examanswer')->select('userId', 'answer', 'pId', 'type')->where('id', $request['info']['answerId'])->first();
        if ($result) {
            $answer = json_decode($result->answer);
            $score = 0;
            if ($result -> type == 1) {
                foreach ($answer as $key => $value) {
                    if ($value -> index == $request['info']['index']) { // 根据index定位到更新的数据
                        $answer->$key->score = $request['info']['score'];
                        $answer->$key->comment = Filter::filter($request['info']['comment']);
                    }
                    if ($answer->$key->type == 5) {
                       $score += $answer->$key->score;
                    }
                }
            } else {
                foreach ($answer as $key => $value) {
                    if ($value -> index == $request['info']['index']) { // 根据index定位到更新的数据
                        $answer[$key]->score = $request['info']['score'];
                        $answer[$key]->comment = Filter::filter($request['info']['comment']);
                    }
                    if ($answer[$key]->type == 5) {
                        $score += $answer[$key]->score;
                    }
                }
            }
            $scoreRes = DB::table('examscore')->where(['userId' => $result->userId, 'pId' => $result->pId, 'type' => $result->type])->select('score1','score2','score3', 'score4','score5','score')->first();
            $scoreIn = [
                'score1' => $scoreRes->score1,
                'score2' => $scoreRes->score2,
                'score3' => $scoreRes->score3,
                'score4' => $scoreRes->score4,
                'score5' => $score,
                'score' => $scoreRes->score1 + $scoreRes->score2 + $scoreRes->score3 + $scoreRes->score4 + $score
            ];
            DB::table('examscore')->where(['userId' => $result->userId, 'pId' => $result->pId, 'type' => $result->type])->update($scoreIn);
            $res = DB::table('examanswer')->where('id',$request['info']['answerId'])->update(['answer' => json_encode($answer)]);
            // 分值不一则更新错题表
            $wrong = DB::table('examwrong')->select('id', 'wrongQuestion')->where(['userId' => $result->userId, 'type' => $result->type, 'pId' => $result->pId])->first();
            if($wrong){ // 错题表有记录
                $temp = json_decode($wrong->wrongQuestion);
                if ($request['info']['aScore'] != $request['info']['score']) { // 未给满分添加数组
                    if (count($temp)) {
                        foreach ($temp as $key => $value) {
                            if (($request['info']['questionId'] == $value->id) && ($value->type == 5)){
                                $flag = false;
                            } else {
                                $flag = true;
                            }
                        }
                        if($flag){
                            array_push($temp, ['id' => $request['info']['questionId'], 'type' => 5]);
                        }
                    } else {
                        $temp[] = ['id' => $request['info']['questionId'], 'type' => 5];
                    }
                    DB::table('examwrong')->where('id', $wrong->id)->update(['wrongQuestion' => json_encode($temp)]);
                } else {
                    foreach ($temp as $key => $value) {
                        if ($value->id == $request['info']['questionId']) { // 给满分则删除该数组
                            unset($temp[$key]);
                        }
                    }
                    foreach ($temp as $key => $value) {
                        $tem[] = $value;
                    }
                    if(count($temp) == 0) {
                        DB::table('examwrong')->where(['userId' => $result->userId, 'type' => $result->type, 'pId' => $result->pId])->delete();
                    } else {
                        DB::table('examwrong')->where('id', $wrong->id)->update(['wrongQuestion' => json_encode($tem)]);
                    }
                }
            } else { // 错题表没有记录
                if ($request['info']['aScore'] != $request['info']['score']) { // 未给满分添加数组
                    $temp[] = ['id' => $request['info']['questionId'], 'type' => 5];
                    $info = [
                        'userId' => $result->userId,
                        'pId' => $result->pId,
                        'type' => $result->type,
                        'wrongQuestion' => json_encode($temp),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    DB::table('examwrong')->insertGetId($info);
                }
            }
            if ($res) {
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * 增加试卷浏览次数
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPaperView(Request $request)
    {
        $res = DB::table('exampaper')->where('id', $request['paperId'])->increment('paperView');
        if ($res) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
    // =====================================================================================

	/**
	 * 试卷详情页
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function paperDetail($importId)
	{
		\Auth::check() || abort(404);
		return view('home.evaluateManageTea.paperDetail') -> with('importId', $importId);
	}

	/**
	 * 试卷编辑页
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function editPaper($lessonInfo, $title, $importId)
	{
		\Auth::check() || abort(404);
		return view('home.evaluateManageTea.editPaper')
			-> with('userId', \Auth::user() -> id)
			-> with('lessonInfo', $lessonInfo)
			-> with('title', $title)
			-> with('importId', $importId);
	}


    /**
     * 上传试卷图片
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function uploadPaperImg(Request $request, $result = false)
    {
		try {
			$file = Input::file('file');
			if ($file -> isValid()) {
				$ext = $file -> getClientOriginalExtension();
				$newName = uniqid() . time() . '.' . $ext;
				$targetPath = realpath(base_path('public')) . '/uploads/paperImage/';
				file_exists($targetPath . $newName) && unlink($targetPath . $newName);
				$file -> move($targetPath, $newName) && $result = '/uploads/paperImage/' . $newName;
			}
		} catch (Exception $e) {
			Log::debug($e -> getMessage() . " --- uploadPaperImg Exception");
		}
		return $this -> returnResult($result);
    }


	/**
	 * 获取个人收藏引入试题
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function importQues(Request $request, $result = false)
    {
        try {
            switch (intval($request['type'])) {
                case 1: $join = 'examschoose'; break;
                case 2: $join = 'exammchoose'; break;
                case 3: $join = 'examjudge'; break;
                case 4: $join = 'examcompletion'; break;
                case 5: $join = 'examsubjective'; break;
            }

            $where = ['resourcestore.userId' => $request['id'], 'resourcestore.type' => 2, 'resourcestore.examType' => $request['type'], $join . '.status' => 0, $join . '.difficult' => $request['difficult'], $join . '.chapterId' => $request['chapterId']];

            if (empty($request['count'])) {
                $this -> number = 1;
                $request['page'] > 20 && $request['page'] = 20;
                $select = [$join . '.title', $join . '.analysis', $join . '.answer', $join . '.score', $join . '.created_at', $join . '.id'];
                $request['type'] < 3 && array_push($select, $join . '.choice');

                $result = DB::table('resourcestore') -> leftJoin($join, 'resourcestore.resourceId', '=', $join . '.id')
						-> select($select) -> where($where) -> orderBy('resourcestore.id', 'desc')
						-> skip($this -> getSkip($request['page'], $this -> number)) -> take($this -> number) -> first();

                if ($result) {
                    $result -> type = intval($request['type']);
                    $result -> difficult = intval($request['difficult']);
                    $result -> created_at = explode(' ', $result -> created_at)[0];
                }
            } else {
                $result = DB::table('resourcestore') -> leftJoin($join, 'resourcestore.resourceId', '=', $join . '.id') -> where($where) ->  count();
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- importQues Exception");
        }
		return $this -> returnResult($result);
	}


	/**
	 * 获取教师所属班级
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function getTeacherClass(Request $request, $result = false)
    {
        try {
            $result = DB::table('teacherteach')
                    -> join('schoolgrade', 'teacherteach.gradeId', '=', 'schoolgrade.id')
                    -> join('schoolclass', 'teacherteach.classId', '=', 'schoolclass.id')
                    -> select('schoolgrade.id as gradeId', 'schoolgrade.gradeName', 'schoolclass.id as classId', 'schoolclass.className', 'teacherteach.id as issel')
                    -> where(['teacherteach.tId' => Auth::user()->id, 'teacherteach.subjectId' => $request['subjectId'], 'schoolgrade.status' => 1, 'schoolclass.status' => 1, 'schoolgrade.id' => $request['gradeId']])
                    -> orderBy('teacherteach.id', 'asc') -> get();
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- getTeacherClass Exception");
        }
		return $this -> returnResult($result);
	}


	/**
	 * 发布试卷
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function publishPaper(Request $request, $result = false)
    {
        try {
            $time = Carbon::now();
            $paperInfo = $request['paperInfo'];
            $paperInfo['created_at'] = $time;
            try {
                $filter = Filter::filter($paperInfo['title']);
				$paperInfo['title'] = $filter;
            } catch (Exception $e) {
				Log::debug($e -> getMessage() . " --- publishPaper Filter Exception");
            }
            $paperId = DB::table('exampaper') -> insertGetId($paperInfo);
            if ($paperId) {
				$time = implode('+', explode(' ', $request['submitTime']));
				$title = implode('+', explode(' ', $paperInfo['title']));
                DB::beginTransaction();
                foreach ($request['question'] as $key => $value) {
                    switch (intval($value['type'])) {
                        case 1: $table = 'examschoose'; break;
                        case 2: $table = 'exammchoose'; break;
                        case 3: $table = 'examjudge'; break;
                        case 4: $table = 'examcompletion'; break;
                        case 5: $table = 'examsubjective'; break;
                    }
					try {
						$filter = Filter::filter($value['title']);
						$value['title'] = $filter;
					} catch (Exception $e) {
						Log::debug($e -> getMessage() . " --- publishPaper Filter Exception");
					}
                    $data = [
						'title' => $value['title'],
						'difficult' => $value['difficult'],
						'score' => $value['score'],
                        'answer' => $value['answer'],
						'analysis' => $value['analysis'],
						'created_at' => $time,
						'userId' => $paperInfo['userId'],
						'subjectId' => $paperInfo['subjectId'],
						'chapterId' => $paperInfo['chapterId'],
                    ];
                    $value['type'] < 3 && $data['choice'] = implode('┼┼', $value['choice']);
                    $result = DB::table($table) -> insertGetId($data);
                    if (!$result) {
                        DB::rollback();
                        $result = false;
                        break;
                    }
                    $result = DB::table($table . 'group') -> insertGetId([
                        'questionId' => $result,
                        'sort' => $value['index'],
                        'paperId' => $paperId
                    ]);
                    if (!$result) {
                        DB::rollback();
                        $result = false;
                        break;
                    }
                }
                if (!empty($request['dispatch']) && count($request['dispatch']) > 0) {
					foreach ($request['dispatch'] as $key => $value) {
						$data = [
							'classId' => $value,
							'paperId' => $paperId,
							'submitTime' => $request['submitTime'],
							'completeTime' => $request['completeTime'],
							'type' => $request['type'],
							'created_at' => $time
						];
						$result = DB::table('examinfo') -> insertGetId($data);
						if (!$result) {
							DB::rollback();
							$result = false;
							break;
						}
                        $this -> http('http://127.0.0.1:9527/remind?id='.$result.'&time='.$time.'&classId='.$value.'&type='.$request['type'].'&title='.urlencode($title));
                        $this -> http('http://127.0.0.1:9527/publish?id='.$result.'&name='.urlencode(\Auth::user() -> username).'&classId='.$value.'&type='.$request['type'].'&title='.urlencode($title));
					}
                }
                if ($result) {
					$result = $paperId;
					DB::commit();
				}
                $this -> http('http://127.0.0.1:9527/timeout?id='.$paperId.'&name='.urlencode(\Auth::user() -> username).'&type='.$request['type'].'&title='.urlencode($title).'&time='.$time);
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- publishPaper Exception");
        }
		return $this -> returnResult($result);
	}


	/**
	 * 获取个人课程归属
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function getLessonType(Request $request, $result = false)
	{
		try {
			$result = DB::table('teachersubject')
				-> join('schoolgrade', 'teachersubject.gradeId', '=', 'schoolgrade.id')
				-> join('studyedition', 'teachersubject.editionId', '=', 'studyedition.id')
				-> join('studyebook', 'teachersubject.bookId', '=', 'studyebook.id')
				-> join('studysubject', 'teachersubject.subjectId', '=', 'studysubject.id')
				-> select('schoolgrade.id as gradeId', 'schoolgrade.gradeName', 'studyedition.id as editionId', 'studyedition.editionName', 'studyebook.id as bookId', 'studyebook.bookName', 'studysubject.id as subjectId', 'studysubject.subjectName')
				-> where(['teachersubject.tId' => $request['id'], 'schoolgrade.status' => 1]) -> orderBy('teachersubject.id', 'desc') -> get();
			if ($result) {
				foreach ($result as $key => $value) {
					array_push($result, [
						'id' => $value -> gradeName . '-' . $value -> subjectName . '-' . $value -> bookName . '-' . $value -> editionName,
						'data' => $value -> gradeId . '-' . $value -> subjectId . '-' . $value -> bookId . '-' . $value -> editionId
					]);
					unset($result[$key]);
				}
				$result = array_values($result);
			}
		} catch (Exception $e) {
			Log::debug($e -> getMessage() . " --- getLessonType Exception");
		}
		return $this -> returnResult($result);
	}


	/**
	 * 获取个人课程所属章节
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function getLessonChapter(Request $request, $result = false)
	{
		try {
			$condition = explode('-', $request['id']);
			$result = DB::table('chapter') -> select('id', 'chapterName')
				-> where(['gradeId' => $condition[0], 'subjectId' => $condition[1], 'bookId' => $condition[2], 'editionId' => $condition[3]])
				-> orderBy('id', 'desc') -> get();
		} catch (Exception $e) {
			Log::debug($e -> getMessage() . " --- getLessonChapter Exception");
		}
		return $this -> returnResult($result);
	}


	/**
	 * 获取试卷
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function getPaper(Request $request, $result = false)
	{
		try {
			$this -> number = 12;
			if (intval($request['type'])) {
				$result = DB::table('exampaper')
					-> join('users', 'exampaper.userId', '=', 'users.id')
					-> join('resourcestore', 'exampaper.id', '=', 'resourcestore.resourceId')
					-> select('users.username', 'exampaper.title', 'exampaper.created_at', 'exampaper.id')
					-> where(['exampaper.chapterId' => $request['chapter'], 'exampaper.status' => 0, 'resourcestore.userId' => Auth::user()->id, 'resourcestore.type' => 3])
					-> orderBy('resourcestore.id', 'desc') -> skip($this -> getSkip($request['page'], $this -> number)) -> take($this -> number) -> get();
			} else {
				$result = DB::table('exampaper')
					-> join('users', 'exampaper.userId', '=', 'users.id')
					-> select('users.username', 'exampaper.title', 'exampaper.created_at', 'exampaper.id')
					-> where(['exampaper.chapterId' => $request['chapter'], 'exampaper.status' => 0])
					-> orderBy('exampaper.id', 'desc') -> skip($this -> getSkip($request['page'], $this -> number)) -> take($this -> number) -> get();
			}
			if ($result) {
				foreach ($result as $key => $value) {
					$value -> created_at = explode(' ', $value -> created_at)[0];
				}
			}
		} catch (Exception $e) {
			Log::debug($e -> getMessage() . " --- getLessonChapter Exception");
		}
		return $this -> returnResult($result);
	}


	/**
	 * 试卷编辑引入完整试题
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function importPaper(Request $request, $result = false)
    {
        try {
            $tables = ['examschoose', 'exammchoose', 'examjudge', 'examcompletion', 'examsubjective'];
            $type = ['examschoose' => 1, 'exammchoose' => 2, 'examjudge' => 3, 'examcompletion' => 4, 'examsubjective' => 5];
            $questions = [];
            $result = [];
            foreach ($tables as $key => $value) {
                $data = DB::table($value . 'group') -> select('questionId', 'sort') -> where(['paperId' => $request['paperId']]) -> get();
                $data && $questions[$value] = $data;
            }
            if (count($questions) > 0) {
                foreach ($questions as $key => $value) {
                    foreach ($value as $k => $v) {
                        $select = ['title', 'difficult', 'analysis', 'answer', 'score'];
                        ($key === 'examschoose' || $key === 'exammchoose') && array_push($select, 'choice');
                        $data = DB::table($key) -> select($select) -> where(['id' => $v -> questionId]) -> first();
                        if ($data) {
                            $data -> index = $v -> sort;
                            $data -> type = $type[$key];
                            array_push($result, $data);
                        }
                    }
                }
            }
            if (count($result) > 0) {
                usort($result, function ($a, $b) {
                    return $a -> index > $b -> index ? 1 : -1;
                });
            } else {
                $result = false;
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- importPaper Exception");
        }
		return $this -> returnResult($result);
	}

	/**
     * 成绩查询详细某个试卷详细分数
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function scoreQuery($id,$classid)
    {
        $result = DB::table('exampaper as e')
            ->leftJoin('schoolgrade as sg', 'e.gradeId', '=', 'sg.id')
            ->leftJoin('studysubject as ss', 'e.subjectId', '=', 'ss.id')
            ->leftJoin('studyedition as se', 'e.editionId', '=', 'se.id')
            ->leftJoin('studyebook as sb', 'e.bookId', '=', 'sb.id')
            ->select('sg.gradeName', 'ss.subjectName', 'ss.id as subjectId', 'se.editionName', 'sb.bookName', 'e.title', 'e.created_at', 'e.count')->where('e.id', $id)->first();
        $result || abort(404);
        $result->created_at = explode(' ', $result->created_at)[0];
        $title = $result->title;
        return view('home.evaluateManageTea.scoreQuery', compact('result', 'id', 'title','classid'));
    }
    /**
     * 成绩查询详细某个试卷总分 （未来提交已经提交）
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function getAllScore(Request $request, $result = false)
    {
        try {
            if (intval($request['id'])) {
                $result = DB::table('examinfo')
                    ->leftJoin('users', 'users.classId', '=', 'examinfo.classId')
					->leftJoin('examscore', function ($join) {
						$join->on('examscore.userId', '=', 'users.id')->on('examscore.pId', '=', 'examinfo.paperId');
					})->select('users.id', 'examinfo.paperId','users.username','examinfo.submitTime','examscore.starttime','examscore.score1', 'examscore.score1', 'examscore.score2', 'examscore.score3', 'examscore.score4', 'examscore.score5', 'examscore.score')->where('examinfo.paperId', $request['id'])->where('examinfo.classId', $request['classid'])->get();
                if ($result) {
                    foreach ($result as $key => $value) {
                        if(strtotime($result[$key]->starttime) - strtotime($result[$key]->submitTime) > 0   &&   strtotime($result[$key]->submitTime) > 0){
                            $result[$key]->late = 1;
                        }else{
                            $result[$key]->late = 0;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- getAllScore Exception");
        }
        return $this -> returnResult($result);
    }
    /**
     * 成绩查询详细某个试卷详细单选分
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function getqScore(Request $request, $result = false)
    {
        try {
            if (intval($request['id'])) {
                $result = DB::table('examanswer as e')
                    ->leftJoin('users as u', 'e.userId', '=', 'u.id')
                    ->select('e.pId','e.userId','u.username','e.answer')->where('e.pId', $request['id'])->where('u.classId', $request['classid'])->get();
                if(count($result) > 0) {
                    //定义一个数组 存放每一类题目的横向错对情况
                    $heng = [];
                    foreach ($result as $key => $value) {
                        //在这里获取单选的数量和每一题目的正确和错误
                        $subarray = json_decode($result[$key]->answer, true);
                        $arrays = [];
                        $indexs = [];
                        $ii = 0;
                        for ($i = 0; $i < count($subarray); $i++) {
                            if ($subarray[$i]['type'] == intval($request['type'])) {
                                $heng[$ii]  =  0;
                                array_push($indexs, $subarray[$i]['index']);
                                if (intval($subarray[$i]['score']) > 0) {
                                     array_push($arrays, 1);
                                } else {
                                    array_push($arrays, 0);
                                }
                                $ii++;
                            }
                        }
                        $result[$key]->num = count($indexs);
                        $result[$key]->number = $indexs;
                        $result[$key]->correct = $arrays;
                    }
                    foreach ($result as $key => $value) {
                        $subarrays = json_decode($result[$key]->answer, true);
                        $jj = 0;
                        for ($j = 0; $j < count($subarrays); $j++) {
                            if ($subarrays[$j]['type'] == intval($request['type'])) {
                                if (intval($subarrays[$j]['score']) > 0) {
                                    $heng[$jj] = intval($heng[$jj]) + 1;
                                }
                                $jj++;
                            }
                        }
                    }
                    for ($v = 0; $v < count($heng); $v++) {
                        if($heng[$v] == 0){
                            $heng[$v] = '0%';
                        }else{
                            $heng[$v]= round(intval($heng[$v])*100/count($result),2)."%";
                        }
                    }
                    $aa = count($result);
                    $result[$aa]['username'] = '正确率%';
                    $result[$aa]['correct'] = $heng;
                }else{
					$result = '';
				}
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- getqScore Exception");
        }
        return $this -> returnResult($result);
    }
    /**
     * 成绩查询详细某个试卷解答题分数
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function getq5Score(Request $request, $result = false)
    {
        try {
            if (intval($request['id'])) {
                $result = DB::table('examanswer as e')
                    ->leftJoin('users as u', 'e.userId', '=', 'u.id')
                    ->select('e.pId','e.userId','u.username','e.answer')->where('e.pId', $request['id'])->where('u.classId', $request['classid'])->get();
                if(count($result) > 0) {
                    foreach ($result as $key => $value) {
                        //在这里获取单选的数量和每一题目的正确和错误
                        $subarray = json_decode($result[$key]->answer, true);
                        $arrays = [];
                        $indexs = [];
                        for ($i = 0; $i < count($subarray); $i++) {
                            if ($subarray[$i]['type'] == 5) {
                                array_push($indexs, $subarray[$i]['index']);
                                if ($subarray[$i]['score'] == "") {
                                    array_push($arrays, "——");
                                } else if (intval($subarray[$i]['score']) > 0) {
                                    array_push($arrays, $subarray[$i]['score']);
                                } else {
                                    array_push($arrays, 0);
                                }
                            }
                        }
                        $result[$key]->num = count($indexs);
                        $result[$key]->number = $indexs;
                        $result[$key]->correct = $arrays;
                    }
                }else{
					$result = '';
				}
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- getq5Score Exception");
        }
        return $this -> returnResult($result);
    }

	/**
	 * 错题详细情况
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function wrongPaper(Request $request, $result = false)
    {
        try {
            $tables = ['examschoose', 'exammchoose', 'examjudge', 'examcompletion', 'examsubjective'];
            $type = ['examschoose' => 1, 'exammchoose' => 2, 'examjudge' => 3, 'examcompletion' => 4, 'examsubjective' => 5];
            $questions = [];
            $result = [];
            foreach ($tables as $key => $value) {
                $data = DB::table($value . 'group') -> select('questionId', 'sort') -> where(['paperId' => $request['paperId']]) -> get();
                $data && $questions[$value] = $data;
            }
            if (count($questions) > 0) {
                foreach ($questions as $key => $value) {
                    foreach ($value as $k => $v) {
                        $select = ['title', 'difficult', 'analysis', 'answer', 'score'];
                        ($key === 'examschoose' || $key === 'exammchoose') && array_push($select, 'choice');
                        $data = DB::table($key) -> select($select) -> where(['id' => $v -> questionId]) -> first();
                        if ($data) {
                            $data -> index = $v -> sort;
                            $data -> type = $type[$key];
                            array_push($result, $data);
                        }
                    }
                }
            }
            if (count($result) > 0) {
                usort($result, function ($a, $b) {
                    return $a -> index > $b -> index ? 1 : -1;
                });
            } else {
                $result = false;
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- wrongPaper Exception");
        }
		return $this -> returnResult($result);
	}

	/**
	 * 成绩统计
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function statistic($paperId)
	{
		\Auth::check() || abort(404);
		return view('home.evaluateManageTea.statistic') -> with('paperId', $paperId);
	}

	/**
	 * 获取试卷信息
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function paperInfo(Request $request, $result = false)
	{
        try {
			$result = DB::table('exampaper')
                    -> join('schoolgrade', 'exampaper.gradeId', '=', 'schoolgrade.id')
                    -> join('studyedition', 'exampaper.editionId', '=', 'studyedition.id')
                    -> join('studyebook', 'exampaper.bookId', '=', 'studyebook.id')
                    -> join('studysubject', 'exampaper.subjectId', '=', 'studysubject.id')
                    -> select('schoolgrade.gradeName', 'studyedition.editionName', 'studyebook.bookName', 'studysubject.subjectName', 'exampaper.title', 'exampaper.score', 'exampaper.created_at', 'exampaper.score')
                    -> where(['exampaper.id' => $request['id'], 'schoolgrade.status' => 1]) -> first();
        } catch(Exception $e) {
            Log::debug($e -> getMessage() . " --- paperInfo Exception");
        }
		return $this -> returnResult($result);
	}

	/**
	 * 获取试卷班级
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
	 */
	public function getPaperClass(Request $request, $result = false)
	{
        try {
			$result = DB::table('exampaper')
                    -> join('examinfo', 'exampaper.id', '=', 'examinfo.paperId')
                    -> join('schoolclass', 'examinfo.classId', '=', 'schoolclass.id')
                    -> join('schoolgrade', 'schoolclass.parentId', '=', 'schoolgrade.id')
                    -> select('schoolgrade.gradeName', 'schoolclass.className', 'schoolclass.id')
                    -> where(['exampaper.id' => $request['id'], 'schoolgrade.status' => 1]) -> get();
        } catch(Exception $e) {
            Log::debug($e -> getMessage() . " --- getPaperClass Exception");
        }
		return $this -> returnResult($result);
	}

    /**
     * 某个试卷下的全卷统计
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function getAllScores(Request $request, $res = false)
    {
        try {
            if (intval($request['pid'])) {
                $result = DB::table('examinfo')
                    ->leftJoin('users', 'users.classId', '=', 'examinfo.classId')
                    ->leftJoin('schoolclass', 'examinfo.classId', '=', 'schoolclass.id')
                    ->leftJoin('schoolgrade', 'schoolclass.parentId', '=', 'schoolgrade.id')
                    ->leftJoin('examscore', function ($join) {
                        $join->on('examscore.userId', '=', 'users.id')->on('examscore.pId', '=', 'examinfo.paperId');
                    })->select('examinfo.classId', 'schoolgrade.gradeName', 'schoolclass.className', 'schoolclass.id', 'users.id', 'examinfo.paperId', 'users.username', 'examinfo.submitTime', 'examscore.starttime', 'examscore.score1', 'examscore.score1', 'examscore.score2', 'examscore.score3', 'examscore.score4', 'examscore.score5', 'examscore.score','examscore.type')->where('examinfo.paperId', $request['pid'])->get();
                if ($result) {
                    foreach ($result as $key => $value) {
                        if (strtotime($result[$key]->starttime) - strtotime($result[$key]->submitTime) > 0 && strtotime($result[$key]->submitTime) > 0) {
                            $result[$key]->late = 1;
                        } else {
                            $result[$key]->late = 0;
                        }
                    }
                }
                $res = array();
                foreach ($result as $item) {
                    if ($item->score == null) {
                        $res[$item->classId]['unsubmit'][] = $item;
                    } else {
                        $res[$item->classId]['submit'][] = $item;
                    }
                }
            }
        } catch(Exception $e) {
            Log::debug($e -> getMessage() . " --- getAllScores Exception");
        }
        return $this -> returnResult($res);
    }


    /**
     * 成绩查询详细某个试卷详细单选分
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function getqScores(Request $request, $res = false)
    {
        try {
            if (intval($request['pid'])) {
                $result = DB::table('examanswer as e')
                    ->leftJoin('users as u', 'e.userId', '=', 'u.id')
                    ->leftJoin('schoolclass', 'u.classId', '=', 'schoolclass.id')
                    ->leftJoin('schoolgrade', 'schoolclass.parentId', '=', 'schoolgrade.id')
					->leftJoin('examinfo', function ($join) {
                        $join->on('u.classId', '=', 'examinfo.classId')->on('e.pId', '=', 'examinfo.paperId');
                    })
                    ->select('u.classId', 'schoolgrade.gradeName', 'schoolclass.className', 'e.type','e.pId','e.userId','u.username','e.answer','examinfo.submitTime', 'e.created_at')->where('e.pId', $request['pid'])->get();
                if(count($result) > 0) {
                    //定义一个数组 存放每一类题目的横向错对情况
                    foreach ($result as $key => $value) {
                        //在这里获取单选的数量和每一题目的正确和错误
                        $subarray = json_decode($result[$key]->answer, true);
                        $arrays = [];
                        $indexs = [];
                        $qid = [];
                        $answer = [];
                        $ii = 0;
                        for ($i = 0; $i < count($subarray); $i++) {
                            if ($subarray[$i]['type'] == intval($request['type'])) {
                                $heng[$ii]  =  0;
                                array_push($indexs, $subarray[$i]['index']);
                                array_push($qid, $subarray[$i]['id']);
                                array_push($answer, $subarray[$i]['answer']);
                                if (intval($subarray[$i]['score']) > 0) {
                                    array_push($arrays, 1);
                                } else {
                                    array_push($arrays, 0);
                                }
                                $ii++;
                            }
                        }
                        $result[$key]->num = count($indexs);
                        $result[$key]->number = $indexs;
                        $result[$key]->correct = $arrays;
                        $result[$key]->qid = $qid;
                        $result[$key]->answer = $answer;
                    }

					if ($result) {
						foreach ($result as $key => $value) {
							if (strtotime($result[$key]->created_at) - strtotime($result[$key]->submitTime) > 0 && strtotime($result[$key]->submitTime) > 0) {
								$result[$key]->late = 1;
							} else {
								$result[$key]->late = 0;
							}
						}
					}
                    $res = array();
                    foreach ($result as $item) {
                         $res[$item->classId][] = $item;
                    }
                }else{
                    $res = '';
                }
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- getqScores Exception");
        }
        return $this -> returnResult($res);
    }

    /**
     * 成绩查询详细某个试卷统计解答题分数
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function getq5Scores(Request $request, $res = false)
    {
        try {
            if (intval($pid)) {
                $result = DB::table('examanswer as e')
                    ->leftJoin('users as u', 'e.userId', '=', 'u.id')
                    ->leftJoin('schoolclass', 'u.classId', '=', 'schoolclass.id')
                    ->leftJoin('schoolgrade', 'schoolclass.parentId', '=', 'schoolgrade.id')
					->leftJoin('examinfo', function ($join) {
                        $join->on('u.classId', '=', 'examinfo.classId')->on('e.pId', '=', 'examinfo.paperId');
                    })
                    ->select('u.classId', 'schoolgrade.gradeName', 'schoolclass.className', 'e.type','e.pId','e.userId','u.username','e.answer','examinfo.submitTime', 'e.created_at')->where('e.pId', $request['pid'])->get();
                if(count($result) > 0) {
                    foreach ($result as $key => $value) {
                        //在这里获取单选的数量和每一题目的正确和错误
                        $subarray = json_decode($result[$key]->answer, true);
                        $arrays = [];
                        $indexs = [];
                        $qid = [];
                        for ($i = 0; $i < count($subarray); $i++) {
                            if ($subarray[$i]['type'] == intval($request['type'])) {
                                array_push($indexs, $subarray[$i]['index']);
                                array_push($qid, $subarray[$i]['id']);
								if ($subarray[$i]['answer'] == "") {
										array_push($arrays, 0);
								}else{
									if ($subarray[$i]['score'] == "") {
										array_push($arrays, "—");
									} else if (intval($subarray[$i]['score']) > 0) {
										array_push($arrays, $subarray[$i]['score']);
									}
								}
                            }
                        }
                        $result[$key]->num = count($indexs);
                        $result[$key]->number = $indexs;
                        $result[$key]->correct = $arrays;
                        $result[$key]->qid = $qid;
                    }
					if ($result) {
						foreach ($result as $key => $value) {
							if (strtotime($result[$key]->created_at) - strtotime($result[$key]->submitTime) > 0 && strtotime($result[$key]->submitTime) > 0) {
								$result[$key]->late = 1;
							} else {
								$result[$key]->late = 0;
							}
						}
					}
                    $res = array();
                    foreach ($result as $item) {
                        $res[$item->classId][] = $item;
                    }
                }else{
                    $res = '';
                }
            }
        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- getq5Scores Exception");
        }
        return $this -> returnResult($res);
    }

    /**
     * 获取每个试卷下的试题类型
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function getPaperType(Request $request, $arr = false)
    {
        try {
            $arr = array();
            $resultA = DB::table('examschoosegroup as es')
                ->leftJoin('examschoose as e', 'e.id', '=', 'es.questionId')
                ->select('e.id')->where(['es.paperId' => $request['pid'], 'e.status' => 0])->count();
            $arr[1]=$resultA;
            $resultB = DB::table('exammchoosegroup as em')
                ->leftJoin('exammchoose as e', 'e.id', '=', 'em.questionId')
                ->select('e.id')->where(['em.paperId' => $request['pid'], 'e.status' => 0])->count();
            $arr[2]=$resultB;
            $resultC = DB::table('examjudgegroup as ej')
                ->leftJoin('examjudge as e', 'e.id', '=', 'ej.questionId')
                ->select('e.id')->where(['ej.paperId' => $request['pid'], 'e.status' => 0])->count();
            $arr[3]=$resultC;
            $resultD = DB::table('examcompletiongroup as ec')
                ->leftJoin('examcompletion as e', 'e.id', '=', 'ec.questionId')
                ->select('e.id')->where(['ec.paperId' => $request['pid'], 'e.status' => 0])->count();
            $arr[4]=$resultD;
            $resultE = DB::table('examsubjectivegroup as es')
                ->leftJoin('examsubjective as e', 'e.id', '=', 'es.questionId')
                ->select('e.id')->where(['es.paperId' => $request['pid'], 'e.status' => 0])->count();
            $arr[5]=$resultE;

        } catch (Exception $e) {
            Log::debug($e -> getMessage() . " --- getPaperType Exception");
        }
        return $this -> returnResult($arr);
    }

    /**
     * 全卷统计数据导出
     */
    public function paperExport($pid, $classid)
    {
        try {
                $classid = explode(',', $classid);
                $result = DB::table('examinfo')
                    ->leftJoin('users', 'users.classId', '=', 'examinfo.classId')
                    ->leftJoin('schoolclass', 'examinfo.classId', '=', 'schoolclass.id')
                    ->leftJoin('schoolgrade', 'schoolclass.parentId', '=', 'schoolgrade.id')
                    ->leftJoin('examscore', function ($join) {
                        $join->on('examscore.userId', '=', 'users.id')->on('examscore.pId', '=', 'examinfo.paperId');
                    })->select('schoolgrade.gradeName as 年级名称', 'schoolclass.className as 班级名称','examinfo.paperId as 试卷ID', 'users.username as 学生','examscore.starttime as 提交时间', 'examscore.score1 as 单选分', 'examscore.score2 as 多选分', 'examscore.score3 as 判断分', 'examscore.score4 as 填空分', 'examscore.score5 as 解答分', 'examscore.score')->where('examinfo.paperId', $pid)->whereIn('examinfo.classId', $classid)->get();
                if ($result) {
                    $res = array();
                    foreach ($result as $item) {
                        if ($item->score == null) {
                            $res['unsubmit'][] = $item;
                        } else {
                            $res['submit'][] = $item;
                        }
                    }
                }
            return $this->export($res,'全卷考试信息表');

        } catch(Exception $e) {
            Log::debug($e -> getMessage() . " --- paperExport Exception");
        }
    }
    /**
     * @param $info
     * @param $title
     * @return file
     *封装导出
     */
    public function export($info,$title)
    {
        if(isset($info['submit'])){
            $this->submit = $info['submit'];
        }else{
            $this->submit = '';
        }
        if(isset($info['unsubmit'])){
            $this->unsubmit = $info['unsubmit'];
        }else{
            $this->unsubmit = '';
        }
        if($this->submit != ''){
            foreach ($info['submit'] as $v) {
                $data[] = get_object_vars($v);
            }
            $titles = array_keys($data[0]);
            $titles = array_combine($titles, $titles);
            array_unshift($data, $titles);
            $data[0]['score'] = '总分';
        }else if($this->unsubmit != '' ){
            foreach ($info['unsubmit'] as $v) {
                $data[] = get_object_vars($v);
            }
            $titles = array_keys($data[0]);
            $titles = array_combine($titles, $titles);
            array_unshift($data, $titles);
            $data[0]['score'] = '总分';
        }

        Excel::create(iconv('UTF-8', 'GBK',$title), function ($excel) use ($data) {
            $excel->sheet('已提交', function ($sheet) use ($data) {
                if($this->submit != ''){
                    $sheet->rows($data);
                }else{
                    $sheet->rows(array());
                }

                $sheet->setSize(array(
                    'A1' => array(
                        'width'     => 20,
                        'height'     => 20
                    ),
                    'B1' => array(
                        'width'     => 20,
                        'height'     => 20
                    ),
                    'C1' => array(
                        'width'     => 10,
                        'height'    => 20
                    ),
                    'D1' => array(
                        'width'     => 20,
                        'height'    => 20
                    ),
                    'E1' => array(
                        'width'     => 20,
                        'height'    => 20
                    ),
                    'F1' => array(
                        'width'     => 10,
                        'height'    => 20
                    ),
                    'G1' => array(
                        'width'     => 10,
                        'height'    => 20
                     ),
                    'H1' => array(
                        'width'     => 10,
                        'height'    => 20
                    ),
                    'I1' => array(
                        'width'     => 10,
                        'height'    => 20
                    ),
                    'J1' => array(
                        'width'     => 10,
                        'height'    => 20
                    ),
                    'K1' => array(
                        'width'     => 10,
                        'height'    => 20
                    )
                ));
            });
            if($this->unsubmit != '') {
                foreach ($this->unsubmit as $v) {
                    $datas[] = get_object_vars($v);
                }
                $datas[10] = '总分';
                $titles = array_keys($datas[0]);
                $titles = array_combine($titles, $titles);
                array_unshift($datas, $titles);
                $datas[0]['score'] = '总分';
                $excel->sheet('未提交', function ($sheet) use ($datas) {
                    if($this->unsubmit != ''){
                        $sheet->rows($datas);
                    }else{
                        $sheet->rows(array());
                    }
                });
            }else{
                $excel->sheet('未提交', function ($sheet) use ($data) {
                        $sheet->rows(array());
                });
            }
        })->export('xlsx');
    }


    /**
     * 成绩查询详细某个试卷详细单选分
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function getOneQuestion(Request $request, $res = false)
    {
        try {
            $pid = $request['pid'];
            $type = $request['type'];
            // 获取试卷的基本信息
            $basicInfo = DB::table('exampaper')->select('exampaper.title')->where('exampaper.id', $pid)->first();

            if ($type == 1) {
                // 获取试卷的详细信息
                $resultA = DB::table('examschoosegroup as es')
                    ->leftJoin('examschoose as e', 'e.id', '=', 'es.questionId')
                    ->select('es.sort', 'e.id', 'e.choice', 'e.answer')->where(['es.paperId' => $pid, 'e.status' => 0])->get();
                foreach ($resultA as $key => $value) {
                    $resultA[$key]->choice = explode('┼┼', $value->choice);
                    $resultA[$key]->type = 1;
                }
                $result = $resultA;
            }
            if ($type == 2) {
                // 多选
                $resultB = DB::table('exammchoosegroup as em')
                    ->leftJoin('exammchoose as e', 'e.id', '=', 'em.questionId')
                    ->select('em.sort', 'e.id', 'e.choice', 'e.answer')->where(['em.paperId' => $pid, 'e.status' => 0])->get();
                foreach ($resultB as $key => $value) {
                    $resultB[$key]->choice = explode('┼┼', $value->choice);
                    $resultB[$key]->answer = explode('┼┼', $value->answer);
                    $resultB[$key]->type = 2;
                }
                $result = $resultB;
            }
            if ($type == 3) {
                // 判断
                $resultC = DB::table('examjudgegroup as ej')
                    ->leftJoin('examjudge as e', 'e.id', '=', 'ej.questionId')
                    ->select('ej.sort', 'e.id', 'e.answer')->where(['ej.paperId' => $pid, 'e.status' => 0])->get();
                foreach ($resultC as $key => $value) {
                    $resultC[$key]->type = 3;
                }
                $result = $resultC;
            }
            if ($result && $basicInfo) {
                $sort = [];
                foreach ($result as $key => $value) {
                    $sort[] = $value->sort;
                }
                array_multisort($sort, SORT_ASC, $result);
            }
            $res = array();
            foreach ($result as $item) {
                if (intval($item->type) < 3) {
                    $res[$item->sort] = $item;
                    $options = array();
                    for ($j = 0; $j < count($item->choice); $j++) {
                        array_push($options, chr($j + 65));
                    }
                    $res[$item->sort]->option = $options;
                    unset($res[$item->sort]->choice);
                } else {
                    $res[$item->sort] = $item->answer;
                }
                //unset($res[$item->sort]->sort);
                unset($res[$item->sort]->id);
                unset($res[$item->sort]->type);
            }
            return $this->returnResult($res);
        } catch (Exception $e) {
            Log::debug($e->getMessage() . " --- getOneQuestion Exception");
        }
    }

    /**
     * 查看某一道题的
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuestionAnswer(Request $request, $result = false)
    {
        try {
            $qid = intval($request['qid']);
            $type = intval($request['type']);
            if ($type == 1) {
                $result = DB::table('examschoose as es')->select('es.*')->where('id', $qid)->first();
                $result->choice = explode('┼┼', $result->choice);
                $result->type = 1;
            } else if ($type == 2) {
                $result = DB::table('exammchoose as em')->select('em.*')->where('id', $qid)->first();
                $result->choice = explode('┼┼', $result->choice);
                $answer = explode('┼┼', $result->answer);
                $result->answer = implode('、', $answer);
                $result->type = 2;
            } else if ($type == 3) {
                $result = DB::table('examjudge as ej')->select('ej.*')->where('id', $qid)->first();
            } else if ($type == 4) {
                $result = DB::table('examcompletion as ex')->select('ex.*')->where('id', $qid)->first();
            } else if ($type == 5) {
                $result = DB::table('examsubjective as es')->select('es.*')->where('id', $qid)->first();
            }
            return $this->returnResult($result);

        } catch (Exception $e) {
            Log::debug($e->getMessage() . " --- getQuestionAnswer Exception");
        }
    }
}