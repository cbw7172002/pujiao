<?php
/**
 * Created by PhpStorm.
 * User: Mr.H
 * Date: 2017/2/14
 * Time: 11:36
 */

namespace App\Http\Controllers\Home\studentCourse;

use DB;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\commonApi\Filter\Filter as Filter;

class synchroTestController extends Controller
{

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
        if ($result) {
            foreach ($result as $key => $value) {
                $res = DB::table('examanswer')->where(['userId' => \Auth::user()->id, 'pId' => $value->paperId])->first();
                if ($res) {
                    $result[$key]->isAnswer = true;
                } else {
                    $result[$key]->isAnswer = false;
                }
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
            }
            return response()->json(['data' => $result, 'status' => true]);
        } else {
            return response()->json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 同步测验数据(课堂授课)
     * @return mixed
     */
    public function getClassTeach($id)
    {
//        $id = 1;
        $result = DB::table('coursechapter as c')
            ->join('exampaper as e', 'c.paperId', '=', 'e.id')
            ->select('e.id as paperId', 'e.title')
            ->where(['c.courseId' => $id, 'c.status' => 0, 'c.courseType' => 1])->get();
        if ($result) {
            foreach ($result as $key => $value) {
                $res = DB::table('examanswer')->where(['userId' => \Auth::user()->id, 'pId' => $value->paperId])->first();
                if ($res) {
                    $result[$key]->isAnswer = true;
                } else {
                    $result[$key]->isAnswer = false;
                }
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
            }
            return response()->json(['data' => $result, 'status' => true]);
        } else {
            return response()->json(['data' => false, 'status' => false]);
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
        if ($result) {
            foreach ($result as $key => $value) {
                $res = DB::table('examanswer')->where(['userId' => \Auth::user()->id, 'pId' => $value->paperId])->first();
                if ($res) {
                    $result[$key]->isAnswer = true;
                } else {
                    $result[$key]->isAnswer = false;
                }
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
            }
            return response()->json(['data' => $result, 'status' => true]);
        } else {
            return response()->json(['data' => false, 'status' => false]);
        }
    }

    /**
     * 获取作业试题详情(未答)内容
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
            $resultB[$key]->answer = explode('┼┼', $value->answer);
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
            $title = explode('_____', $value->title);
            $answer = explode('┼┼', $value->answer);
            $answerNum = count($title) - 1;
            for ($i = 0; $i < $answerNum; $i++) {
                $temp = 'ans' . $i;
                $tem = 'right' . $i;
                $resultD[$key]->$temp = '';
                $resultD[$key]->$tem = $answer[$i];
                $resultD[$key]->answerNum = $answerNum;
            }
        }
        // 解答
        $resultE = DB::table('examsubjectivegroup as es')
            ->leftJoin('examsubjective as e', 'e.id', '=', 'es.questionId')
            ->select('es.sort', 'e.*')->where(['es.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultE as $key => $value) {
            $resultE[$key]->type = 5;
        }
        $homeTemp = json_decode(DB::table('examhomework')->select('answer')->where(['userId' => \Auth::user()->id, 'pId' => $id, 'type' => 1])->pluck('answer'));
        $result = array_merge($resultA, $resultB, $resultC, $resultD, $resultE);
        $sort = [];
        if ($result && $basicInfo) {
            foreach ($result as $key => $value) {
                $sort[] = $value->sort;
            }
            array_multisort($sort, SORT_ASC, $result);
            foreach ($result as $key => $value) {
                if ($homeTemp) { // 存在
                    if ($value->type == 4) {
                        if ($homeTemp->$key->answer) {
                            $userAnswer = explode('┼┼', $homeTemp->$key->answer);
                            $answerNum = count($userAnswer);
                            if ($answerNum > 1) {
                                for ($i = 0; $i < $answerNum; $i++) {
                                    $temp = 'ans' . $i;
                                    $result[$key]->$temp = $userAnswer[$i];
                                }
                            } else {
                                $result[$key]->ans0 = $homeTemp->$key->answer;
                            }
                        } else {
                            $result[$key]->ans0 = $homeTemp->$key->answer;
                        }
                    } else if ($value->type == 5) {
                        $result[$key]->newAnswer = $homeTemp->$key->answer;
                    } else{
                        $result[$key]->newAnswer = $homeTemp->$key->answer;
                    }
                    $result[$key]->newScore = '';
                } else { // 不存在保存的作业
                    if ($value->type != 4) {
                        $result[$key]->newAnswer = '';
                        $result[$key]->newScore = '';
                    }
                }
            }
            return response()->json(['status' => true, 'data' => $result, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取作业试题详情(已答)内容
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomeWorkInfo($id, $userId)
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
        $examAnswer = json_decode(DB::table('examanswer')->select('answer')->where(['userId' => $userId, 'pId' => $id, 'type' => 1])->pluck('answer'));
        if ($result && $examAnswer) {
            foreach ($result as $key => $value) {
                if ($value->type == 2) {
                    $userAnswer = implode('、', explode('┼┼', $examAnswer->$key->answer));
                    $result[$key]->userAnswer = $userAnswer;
                    if ($userAnswer == $value->answer) {
                        $result[$key]->isRight = true;
                    } else {
                        $result[$key]->isRight = false;
                    }
                } else if ($value->type == 4) {
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
                } else if ($value->type == 5 && $examAnswer->$key->comment) {
                    $result[$key]->comment = $examAnswer->$key->comment;
                    $result[$key]->getScore = $examAnswer->$key->score;
                    $result[$key]->userAnswer = $examAnswer->$key->answer;
                } else{
                    $userAnswer = $examAnswer->$key->answer;
                    $result[$key]->userAnswer = $userAnswer;
                    if ($userAnswer == $value->answer) {
                        $result[$key]->isRight = true;
                    } else {
                        $result[$key]->isRight = false;
                    }
                }
            }
        }
        if ($result && $basicInfo && $userAnswer) {
            return response()->json(['status' => true, 'data' => $result, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 提交作业答案
     * @param Request $request
     * @return array
     */
    public function saveHomework(Request $request)
    {
        $data = $request->all();
        // 查询是否存在已保存的作业记录
        $flag = DB::table('examhomework')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => 1])->select('id')->pluck('id');
        if (!$flag) {
            $data['type'] = 1;
            $data['created_at'] = Carbon::now();
            $insertId = DB::table('examhomework')->insertGetId($data);
        } else {
            $data['type'] = 1;
            $data['created_at'] = Carbon::now();
            $insertId = DB::table('examhomework')->where('id', $flag)->update($data);
        }
        // 存入总分
        if ($insertId) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * 提交作业试题答案
     * @param Request $request
     * @return array
     */
    public function submitPaper(Request $request)
    {
        $filter = new Filter();
        $data = $request->all();
        $data['type'] = 1;
        $data['created_at'] = Carbon::now();
        $answer = json_decode($data['answer']);
        foreach ($answer as $key => $value) {
            if ($value->type == 4 || $value->type == 5) {
                $value->answer = $filter -> filter($value->answer);
            }
        }
        $data['answer'] = json_encode($answer);
        $insertId = DB::table('examanswer')->insertGetId($data);
        DB::table('examhomework')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => 1])->delete();
        // 组装错题信息
        $info['userId'] = $data['userId'];
        $info['pId'] = $data['pId'];
        $info['type'] = $data['type'];
        $info['created_at'] = $info['updated_at'] = Carbon::now();
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
            'starttime' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        foreach ($answer as $key => $value) {
            if ($value->type !== 5) {
                if ($value->score == 0 || $value->score === '') {
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
        if (count($wrong)) {
            $info['wrongQuestion'] = json_encode($wrong);
            DB::table('examwrong')->insertGetId($info);
        }
        // 存入总分
        $score['score'] = $score['score1'] + $score['score2'] + $score['score3'] + $score['score4'] + $score['score5'];
        DB::table('examscore')->insertGetId($score);
        if ($insertId && $info && $score) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * 获取测验试题详情(未答)内容
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
            ->select('exampaper.title', 'studysubject.subjectName', 'studyedition.editionName', 'schoolgrade.gradeName', 'studyebook.bookName', 'examinfo.submitTime', 'examinfo.completeTime')
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
            $resultB[$key]->answer = explode('┼┼', $value->answer);
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
            $title = explode('_____', $value->title);
            $answer = explode('┼┼', $value->answer);
            $answerNum = count($title) - 1;
            for ($i = 0; $i < $answerNum; $i++) {
                $temp = 'ans' . $i;
                $tem = 'right' . $i;
                $resultD[$key]->$temp = '';
                $resultD[$key]->$tem = $answer[$i];
                $resultD[$key]->answerNum = $answerNum;
            }
        }
        // 解答
        $resultE = DB::table('examsubjectivegroup as es')
            ->leftJoin('examsubjective as e', 'e.id', '=', 'es.questionId')
            ->select('es.sort', 'e.*')->where(['es.paperId' => $id, 'e.status' => 0])->get();
        foreach ($resultE as $key => $value) {
            $resultE[$key]->type = 5;
        }

        $homeTemp = DB::table('examhomework')->select('answer', 'first_time')->where(['userId' => \Auth::user()->id, 'pId' => $id, 'type' => 2])->first();
        $temp = [];
        if ($homeTemp) {
            $userTemp = json_decode($homeTemp->answer);
            foreach ($userTemp as $key => $value) {
                $temp = array_merge($temp, json_decode($value, true));
            }
            $timeFirst = $homeTemp->first_time;
            if ($basicInfo) {
                $basicInfo->timeLeave = (ceil((time() - $timeFirst) / 60)) * 1000 * 60;
                $basicInfo->showTime = (int)($basicInfo->completeTime - ceil((time() - $timeFirst) / 60));
                if ($basicInfo->showTime < 0) {
                    $basicInfo->showTime = 'noTime';
                }
            }
        } else {
            $basicInfo->timeLeave = 0;
            $basicInfo->showTime = $basicInfo->completeTime;
        }
        $result = array_merge($resultA, $resultB, $resultC, $resultD, $resultE);
        $sort = [];
        if ($result && $basicInfo) {
            foreach ($result as $key => $value) {
                $sort[] = $value->sort;
            }
            array_multisort($sort, SORT_ASC, $result);
            foreach ($result as $key => $value) {
                if ($temp) {
                    if ($temp[$key]['type'] == 4) {
                        $result[$key]->newAnswer = $temp[$key]['newAnswer'];
                        $result[$key]->newScore = $temp[$key]['newScore'];
                        if ($temp[$key]['newAnswer']) {
                            $userAnswer = explode('┼┼', $temp[$key]['newAnswer']);
                            $answerNum = count($userAnswer);
                            for ($i = 0; $i < $answerNum; $i++) {
                                $a = 'ans' . $i;
                                $result[$key]->$a = $userAnswer[$i];
                            }
                        }
                    } else {
                        $result[$key]->newAnswer = $temp[$key]['newAnswer'];
                        $result[$key]->newScore = $temp[$key]['newScore'];
                    }
                } else {
                    if ($value->type != 4) {
                        $result[$key]->newAnswer = '';
                        $result[$key]->newScore = '';
                    }
                }
            }
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
            return response()->json(['status' => true, 'data' => $res, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 提交测验试题答案
     * @param Request $request
     * @return array
     */
    public function submitTestPaper(Request $request)
    {
        $filter = new Filter();
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
            $info[$key]['answer'] = $filter->filter($temp[$key]['newAnswer']);
            $info[$key]['score'] = $temp[$key]['newScore'];
            if ($temp[$key]['type'] == 5){
                $info[$key]['comment'] = $temp[$key]['comment'];
            }
        }
        $data['answer'] = json_encode($info);
        $data['created_at'] = Carbon::now();
        $insertId = DB::table('examanswer')->insertGetId($data);

        // 组装错题信息
        $data2['userId'] = $data['userId'];
        $data2['pId'] = $data['pId'];
        $data2['type'] = $data['type'];
        $data2['created_at'] = $data2['updated_at'] = Carbon::now();
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
            'starttime' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        foreach ($info as $key => $value) {
            if ($value['type'] !== 5) {
                if ($value['score'] == 0 || $value['score'] === '') {
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
        if (count($wrong)) {
            $data2['wrongQuestion'] = json_encode($wrong);
            DB::table('examwrong')->insertGetId($data2);
        }
        // 存入总分
        $score['score'] = $score['score1'] + $score['score2'] + $score['score3'] + $score['score4'] + $score['score5'];
        DB::table('examscore')->insertGetId($score);
        if ($insertId && $info && $score) {
            DB::table('examhomework')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => 2])->delete();
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * 获取测验试题详情(已答)内容
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestAnswerInfo($id, $userId)
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
        $examAnswer = json_decode(DB::table('examanswer')->select('answer')->where(['userId' => $userId, 'pId' => $id, 'type' => 2])->pluck('answer'));
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
        if ($result && $basicInfo && $examAnswer) {
            return response()->json(['status' => true, 'data' => $res, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 保存测验答案
     * @param Request $request
     * @return array
     */
    public function saveTestAnswer(Request $request) {
        $data = empty($request['_url']) ? $request->all() : $request->except('_url');
        // 查询是否存在已保存的作业记录
        $flag = DB::table('examhomework')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => 2])->select('id')->pluck('id');
        if (!$flag) {
            $data['type'] = 2;
            $data['created_at'] = Carbon::now();
            $data['first_time'] = time();
            $insertId = DB::table('examhomework')->insertGetId($data);
        } else {
            $data['type'] = 2;
            $data['created_at'] = Carbon::now();
            $insertId = DB::table('examhomework')->where('id', $flag)->update($data);
        }
        if ($insertId) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * 将测验答案存入答案表
     * @param Request $request
     * @return array
     */
    public function moveAnswer(Request $request) {
        $filter = new Filter();
        $data['userId'] = $request['userId'];
        $data['pId'] = $request['pId'];
        $data['type'] = 2;
        $answer = json_decode(DB::table('examhomework')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => 2])->select('answer')->pluck('answer'));
        $temp = [];
        foreach ($answer as $key => $value) {
            $temp = array_merge($temp, json_decode($value, true));
        }
        $info = []; // 存放答案数组
        foreach ($temp as $key => $value) {
            $info[$key]['index'] = $temp[$key]['index'];
            $info[$key]['type'] = $temp[$key]['type'];
            $info[$key]['id'] = $temp[$key]['id'];
            $info[$key]['answer'] = $filter->filter($temp[$key]['newAnswer']);
            $info[$key]['score'] = $temp[$key]['newScore'];
            if ($temp[$key]['type'] == 5){
                $info[$key]['comment'] = $temp[$key]['comment'];
            }
        }
        $data['answer'] = json_encode($info);
        $data['created_at'] = Carbon::now();
        $insertId = DB::table('examanswer')->insertGetId($data);

        // 组装错题信息
        $data2['userId'] = $data['userId'];
        $data2['pId'] = $data['pId'];
        $data2['type'] = $data['type'];
        $data2['created_at'] = $data2['updated_at'] = Carbon::now();
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
            'starttime' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        foreach ($info as $key => $value) {
            if ($value['type'] !== 5) {
                if ($value['score'] == 0 || $value['score'] === '') {
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
        if (count($wrong) > 0) {
            $data2['wrongQuestion'] = json_encode($wrong);
            DB::table('examwrong')->insertGetId($data2);
        }
        // 存入总分
        $score['score'] = $score['score1'] + $score['score2'] + $score['score3'] + $score['score4'] + $score['score5'];
        DB::table('examscore')->insertGetId($score);
        if ($insertId && $info && $score) {
            DB::table('examhomework')->where(['userId' => $data['userId'], 'pId' => $data['pId'], 'type' => 2])->delete();
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}