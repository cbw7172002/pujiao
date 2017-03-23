<?php
/**
 * Created by PhpStorm.
 * User: Mr.H
 * Date: 2017/1/11
 * Time: 15:34
 */

namespace App\Http\Controllers\Home\evaluateManageStu;

use Carbon\Carbon;
use DB;
use GuzzleHttp\Message\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Home\lessonComment\Gadget;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\commonApi\Filter\Filter as Filter;

class evaluateManageStuController extends Controller
{
    use Gadget;
    /**
     * 我的测评首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function index()
    {
        $userId = Auth::user()->id;
        return view('home.evaluateManageStu.indexStu', compact('userId'));
    }

    /**
     * 获取我的试题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExamInfo(Request $request)
    {
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber - 1) * $pageSize;
        $userId = $request['userId'];

        $allType = [1, 2];
        $allStatus = [0, 1];
        $type = $request['type'] == 'all' ? $allType : $request['type'];
        $status = $request['status'] == 'all' ? $allStatus : $request['status'];
        if (count($type) == 2) {
            $temType = '(' . implode(',', $type) . ')';
        } else {
            $temType = '(' . $type[0] . ')';
        }
        if (count($status) == 2) {
            $temStatus = '(' . implode(',', $status) . ')';
        } else {
            $temStatus = '(' . $status[0] . ')';
        }
        $condition = '%'.$request['condition'].'%';

        $result = DB::select("SELECT exampaper.id, schoolgrade.gradeName, studysubject.subjectName, studyedition.editionName, studyebook.bookName, exampaper.title, examinfo.type, exampaper.type AS eType,
                                examinfo.submitTime, examinfo.completeTime, exampaper.count, (SELECT count(id) FROM examanswer WHERE userId = users.id AND pId = examinfo.paperId) as num FROM examinfo 
                                LEFT JOIN users ON examinfo.classId = users.classId
                                LEFT JOIN exampaper ON exampaper.id = examinfo.paperId
                                LEFT JOIN schoolgrade ON schoolgrade.id = exampaper.gradeId
                                LEFT JOIN studysubject ON studysubject.id = exampaper.subjectId
                                LEFT JOIN studyedition ON studyedition.id = exampaper.editionId
                                LEFT JOIN studyebook ON studyebook.id = exampaper.bookId
                                WHERE users.id = $userId AND (SELECT count(*) FROM examanswer WHERE userId = users.id AND pId = examinfo.paperId) IN $temStatus AND exampaper.title LIKE '$condition'
                                AND examinfo.type IN $temType limit $skip,$pageSize");
        $count = DB::select("SELECT count(*) as counts FROM examinfo 
                                LEFT JOIN users ON examinfo.classId = users.classId
                                LEFT JOIN exampaper ON exampaper.id = examinfo.paperId
                                LEFT JOIN schoolgrade ON schoolgrade.id = exampaper.gradeId
                                LEFT JOIN studysubject ON studysubject.id = exampaper.subjectId
                                LEFT JOIN studyedition ON studyedition.id = exampaper.editionId
                                LEFT JOIN studyebook ON studyebook.id = exampaper.bookId
                                WHERE users.id = $userId AND (SELECT count(*) FROM examanswer WHERE userId = users.id AND pId = examinfo.paperId) IN $temStatus AND exampaper.title LIKE '$condition'
                                AND examinfo.type IN $temType");
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key]->score = DB::table('examscore')->select('score')->where(['userId' => $userId, 'pId' => $value->id])->pluck('score');
                $result[$key]->answerId = DB::table('examanswer')->select('id')->where(['userId' => $userId, 'pId' => $value->id])->pluck('id');
                $value->type == 1 ? $type = '作业' : $type = '测验';
                $value->eType ? $eType = '测验试卷' : $eType = '同步练习';
                $result[$key]->typeName = $eType . '-' . $type;
            }
            return response()->json(['status' => true, 'data' => $result, 'count' => $count[0]->counts]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取我的错题包括的学科
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubjectInfo($userId)
    {
        $result = DB::table('examwrong')
            ->Join('exampaper', 'exampaper.id', '=', 'examwrong.pId')
            ->leftJoin('studysubject', 'studysubject.id', '=', 'exampaper.subjectId')
            ->select('studysubject.id', 'studysubject.subjectName')
            ->where('examwrong.userId', $userId)->where('examwrong.wrongQuestion', '!=', '')->distinct()->get();
        if ($result) {
            return response()->json(['status' => true, 'data' => $result]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 获取我的我的错题集
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExamError(Request $request)
    {
        $pageNumber = $request['pageNumber'];
        $pageSize = $request['pageSize'];
        $skip = ($pageNumber - 1) * $pageSize;

        $userId = $request['userId'];
        $subjectId = $request['subjectId'];
        $type = $request['type'];

        $query = DB::table('examwrong')
            ->Join('exampaper', 'exampaper.id', '=', 'examwrong.pId')
            ->leftJoin('schoolgrade', 'schoolgrade.id', '=', 'exampaper.gradeId')
            ->leftJoin('studysubject', 'studysubject.id', '=', 'exampaper.subjectId')
            ->leftJoin('studyedition', 'studyedition.id', '=', 'exampaper.editionId')
            ->leftJoin('studyebook', 'studyebook.id', '=', 'exampaper.bookId')
            ->select('exampaper.id', 'schoolgrade.gradeName', 'studysubject.subjectName', 'studyedition.editionName', 'studyebook.bookName', 'exampaper.title', 'examwrong.type', 'examwrong.wrongQuestion')
            ->where('examwrong.userId', $userId)->where('examwrong.wrongQuestion', '!=', '')->where('exampaper.title', 'like', '%' . $request['condition'] . '%');
        if ($type == 'all' && $subjectId == 'all') {
            $result = $query->skip($skip)->take($pageSize)->get();
            $count = $query->count();
        } elseif ($type != 'all' && $subjectId == 'all') {
            $result = $query->whereIn('examwrong.type', $type)->skip($skip)->take($pageSize)->get();
            $count = $query->whereIn('examwrong.type', $type)->count();
        } elseif ($type == 'all' && $subjectId != 'all') {
            $result = $query->whereIn('exampaper.subjectId', $subjectId)->skip($skip)->take($pageSize)->get();
            $count = $query->whereIn('exampaper.subjectId', $subjectId)->count();
        } else {
            $result = $query->whereIn('exampaper.subjectId', $subjectId)->whereIn('examwrong.type', $type)->skip($skip)->take($pageSize)->get();
            $count = $query->whereIn('exampaper.subjectId', $subjectId)->whereIn('examwrong.type', $type)->count();
        }
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key]->num = count(json_decode($result[$key]->wrongQuestion));
            }
            return response()->json(['status' => true, 'data' => $result, 'count' => $count]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     * 作业试题详情页  未答
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function studentNoAnswer($id)
    {
        $userId = \Auth::user()->id;
        return view('home.evaluateManageStu.studentNoAnswer', compact('id', 'userId'));
    }

    /**
     * 获取作业试题(未答)详情内容
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
            ->select('exampaper.title', 'studysubject.subjectName', 'studyedition.editionName', 'schoolgrade.gradeName', 'studyebook.bookName', 'examinfo.submitTime', 'examinfo.completeTime', 'examinfo.type')
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
            $resultB[$key]->answerStr = $value->answer;
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
                if (array_key_exists($i, $answer)) {
                    $resultD[$key]->$tem = $answer[$i];
                } else {
                    $answer[$i] = '';
                    $resultD[$key]->$tem = $answer[$i];
                }
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
                            for ($i = 0; $i < $answerNum; $i++) {
                                $temp = 'ans' . $i;
                                $result[$key]->$temp = $userAnswer[$i];
                            }
                        }
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
     * 作业试题详情页  已答
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function studentPaperStu($id, $userId)
    {
        return view('home.evaluateManageStu.studentPaperStu', compact('id', 'userId'));
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
                    if ($tempAnswer === $value->answer) {
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
        if ($result && $basicInfo && $examAnswer) {
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
    public function submitPaper(Request $request)
    {
        $filter = new Filter();
        $data = empty($request['_url']) ? $request->all() : $request->except('_url');
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
        if (count($wrong) > 0) {
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
     * 提交作业答案
     * @param Request $request
     * @return array
     */
    public function saveHomework(Request $request)
    {
        $data = empty($request['_url']) ? $request->all() : $request->except('_url');
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
     * 测验试题详情 未答
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function studentTestNoAnswer($id)
    {
        $userId = \Auth::user()->id;
        $completeTime = DB::table('examinfo')->select('completeTime')->where('paperId', $id)->pluck('completeTime');
        $timeFirst = DB::table('examhomework')->select('first_time')->where(['userId' => $userId, 'pId' => $id, 'type' => 2])->pluck('first_time');

        if ($timeFirst) {
            $timeLeave = (ceil((time() - $timeFirst) / 60)) * 1000 * 60;
            $completeTime = (int)($completeTime - ceil((time() - $timeFirst) / 60));
            if ($completeTime <= 0) {
                $completeTime = 'noTime';
            }
        } else {
            $timeLeave = 0;
        }
        return view('home.evaluateManageStu.studentTestNoAnswer', compact('id', 'completeTime', 'userId', 'timeLeave'));
    }

    /**
     * 获取测验试题详情(未答)内容
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestNoAnswer($id)
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
                if(array_key_exists($i, $answer)) {
                    $resultD[$key]->$tem = $answer[$i];
                } else {
                    $answer[$i] = '';
                    $resultD[$key]->$tem = $answer[$i];
                }
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
        $homeTemp = json_decode(DB::table('examhomework')->select('answer')->where(['userId' => \Auth::user()->id, 'pId' => $id, 'type' => 2])->pluck('answer'));
        $temp = [];
        if ($homeTemp) {
            foreach ($homeTemp as $key => $value) {
                $temp = array_merge($temp, json_decode($value, true));
            }
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
     * 测验试题详情  已答
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function studentTestPaperStu($id, $userId)
    {
        return view('home.evaluateManageStu.studentTestPaperStu', compact('id', 'userId'));
    }

    /**
     * 获取测验试题详情(已答)内容
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestPaperStu($id, $userId)
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
                        if($answerNum > 1) {
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
                    if ($tempAnswer === $value->answer) {
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
        if ($result && $basicInfo && $res) {
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
            $info[$key]['answer'] = $filter -> filter($temp[$key]['newAnswer']);
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

    /**
     * 作业试题错题详细记录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function errorPaper($id, $userId)
    {
        return view('home.evaluateManageStu.errorPaper', compact('id', 'userId'));
    }

    /**
     * 获取作业试题错题记录
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getErrorInfo($id, $userId)
    {
        // 获取某个有错题试卷的基本信息
        $basicInfo = DB::table('examwrong')
            ->leftJoin('exampaper', 'exampaper.id', '=', 'examwrong.pId')
            ->leftJoin('studysubject', 'studysubject.id', '=', 'exampaper.subjectId')
            ->leftJoin('studyedition', 'studyedition.id', '=', 'exampaper.editionId')
            ->leftJoin('schoolgrade', 'schoolgrade.id', '=', 'exampaper.gradeId')
            ->leftJoin('studyebook', 'studyebook.id', '=', 'exampaper.bookId')
            ->select('examwrong.wrongQuestion', 'exampaper.title', 'studysubject.subjectName', 'studyedition.editionName', 'schoolgrade.gradeName', 'studyebook.bookName')
            ->where('examwrong.pId', $id)->where('examwrong.userId', $userId)->first();
        $questionrray = json_decode($basicInfo->wrongQuestion);
        $resultA = [];
        $resultB = [];
        $resultC = [];
        $resultD = [];
        $resultE = [];
        if (count($questionrray) > 0) {
            $t1 =[];
            $t2 =[];
            $t3 =[];
            $t4 =[];
            $t5 =[];
            for ($i = 0; $i < count($questionrray); $i++) {

                if ($questionrray[$i]->type == 1) {
                    array_push($t1,$questionrray[$i]->id);
                }
                if ($questionrray[$i]->type == 2) {
                    array_push($t2,$questionrray[$i]->id);
                }
                if ($questionrray[$i]->type == 3) {
                    array_push($t3,$questionrray[$i]->id);
                }
                if ($questionrray[$i]->type == 4) {
                    array_push($t4,$questionrray[$i]->id);
                }
                if ($questionrray[$i]->type == 5) {
                    array_push($t5,$questionrray[$i]->id);
                }
            }
            $resultA = DB::table('examschoosegroup as es')
                ->leftJoin('examschoose as e', 'e.id', '=', 'es.questionId')
                ->select('es.sort', 'e.*')->where(['es.paperId' => $id,'e.status' => 0])->whereIn('es.questionId',$t1)->get();
            foreach ($resultA as $key => $value) {
                $resultA[$key]->choice = explode('┼┼', $value->choice);
                $resultA[$key]->type = 1;
            }
            // 多选
            $resultB = DB::table('exammchoosegroup as em')
                ->leftJoin('exammchoose as e', 'e.id', '=', 'em.questionId')
                ->select('em.sort', 'e.*')->where(['em.paperId' => $id,'e.status' => 0])->whereIn('em.questionId',$t2)->get();
            foreach ($resultB as $key => $value) {
                $resultB[$key]->choice = explode('┼┼', $value->choice);
                $resultB[$key]->type = 2;
                $answer = explode('┼┼', $value->answer);
                $resultB[$key]->answer = implode('、', $answer);
            }

            // 判断
            $resultC = DB::table('examjudgegroup as ej')
                ->leftJoin('examjudge as e', 'e.id', '=', 'ej.questionId')
                ->select('ej.sort', 'e.*')->where(['ej.paperId' => $id, 'e.status' => 0])->whereIn('ej.questionId',$t3)->get();
            foreach ($resultC as $key => $value) {
                $resultC[$key]->type = 3;
            }

            // 填空
            $resultD = DB::table('examcompletiongroup as ec')
                ->leftJoin('examcompletion as e', 'e.id', '=', 'ec.questionId')
                ->select('ec.sort', 'e.*')->where(['ec.paperId' => $id, 'e.status' => 0])->whereIn('ec.questionId',$t4)->get();
            foreach ($resultD as $key => $value) {
                $resultD[$key]->type = 4;
            }

            // 解答
            $resultE = DB::table('examsubjectivegroup as es')
                ->leftJoin('examsubjective as e', 'e.id', '=', 'es.questionId')
                ->select('es.sort', 'e.*')->where(['es.paperId' => $id, 'e.status' => 0])->whereIn('es.questionId',$t5)->get();
            foreach ($resultE as $key => $value) {
                $resultE[$key]->type = 5;
            }

        }

        $result = array_merge($resultA, $resultB, $resultC, $resultD, $resultE);
        $sort = [];
        if ($result) {
            $sort = [];
            foreach ($result as $key => $value) {
                $sort[] = $value->sort;
            }
            array_multisort($sort, SORT_ASC, $result);
        }

	
        $examAnswer = json_decode(DB::table('examanswer')->select('answer')->where(['userId' => $userId, 'pId' => $id])->pluck('answer'), TRUE);

        if ($result && $examAnswer) {
            foreach ($result as $key => $value) {
                for ($i = 0; $i < count($examAnswer); $i++) {
                    //获取正确答案
                    if ($result[$key]->id == $examAnswer[$i]['id'] && $result[$key]->type == $examAnswer[$i]['type']) {
                        $result[$key]->userAnswer = $examAnswer[$i]['answer'];
                    }
                }
                $result[$key]->isRight = false;
            }
        }
        if ($result && $basicInfo) {
            return response()->json(['status' => true, 'data' => $result, 'basicInfo' => $basicInfo]);
        } else {
            return response()->json(['status' => false, 'data' => false]);
        }
    }

    /**
     *  删除某个试卷下的错题
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delQuestion(Request $request, $result = false)
    {

        $results = DB::table('examwrong')->select('wrongQuestion', 'id')->where(['userId' => $request['userId'], 'pId' => $request['pId']])->first();
        $wrongQuestion = json_decode($results->wrongQuestion, TRUE);
        $resultNew = [];
        for ($i = 0; $i < count($wrongQuestion); $i++) {
            //获取正确答案1
            if ($wrongQuestion[$i]['id'] == $request['qId'] && $wrongQuestion[$i]['type'] == $request['type']) {
            } else {
                array_push($resultNew, Array("type" => $wrongQuestion[$i]['type'], "id" => $wrongQuestion[$i]['id']));
            }
        }

        if (count($resultNew) > 0) {
            $data['wrongQuestion'] = json_encode($resultNew);
        } else {
            $data['wrongQuestion'] = NULL;
        }
        $result = DB::table('examwrong')->where('id', $results->id)->update($data);
        return $this->returnResult($result);
    }
    // =====================================================================================
}