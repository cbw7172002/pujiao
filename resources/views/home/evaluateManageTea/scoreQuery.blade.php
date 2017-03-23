@extends('layouts.layoutHome')

@section('title', '试卷详情')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageTea/scoreQuery.css') }}">
@endsection

@section('content')
    <div class="contain_lessonDetail" ms-controller="scoreQueryController">
        <div style="width: 100%;height: 15px;"></div>
        <div class="contain_lessonDetail_top">
            <div class="contain_lessonDetail_top_breadcrumb">
                <div id="changeVideo" style="width: 1px;height: 1px;position: absolute;z-index: 1;top: 0px;"></div>
                <a href="">首页</a> >
                <a href="">成绩查询</a> > 试卷详情
            </div>
        </div>
        <div>


            <div class="test_paper_title">
                <div style="width: 100%;height: 18px;"></div>
                <div class="test_paper_title_top">
                    {{$result->title}}
                </div>
                <div class="test_paper_title_bot">
                    {{$result->gradeName}} | {{$result->subjectName}} | {{$result->editionName}} | {{$result->bookName}} | 上传日期 : {{$result->created_at}}
                </div>

            </div>
            <div class="test_paper_container">
                    <div class="question_correct_detail">
                        <div class="content_title">
                            <div style="width:600px;height:46px;left:580px;position:absolute">
                                <li  class="actives" ms-changed  ms-click="changeOption('alls');">全卷</li>
                                <li ms-changed ms-click="changeOption('q1s');">单选题</li>
                                <li ms-changed ms-click="changeOption('q2s');">多选题</li>
                                <li ms-changed ms-click="changeOption('q3s');">判断题</li>
                                <li ms-changed ms-click="changeOption('q4s');">填空题</li>
                                <li ms-changed ms-click="changeOption('q5s');">解答题</li>
                            </div>
                        </div>

                        <div class="clear"></div>
                        <div style="width: 100%;height: 10px;"></div>

                        {{-- 全卷部分 --}}
                        <div class="alls"  ms-visible="changeValue == 'alls'">
                                <div class="content_table_title">
                                    <div class="content_table_title_first">
                                        <div class="content_table_content_repeat_name">姓名</div>
                                    </div>
                                    <div class="content_table_number_repeat">
                                        <div class="content_table_number">单选题</div>
                                        <div class="content_table_number">多选题</div>
                                        <div class="content_table_number">判断题</div>
                                        <div class="content_table_number">填空题</div>
                                        <div class="content_table_number">解答题</div>
                                        <div class="content_table_number">总分</div>
                                    </div>
                                </div>
                                <div class="content_table_content">
                                    <div class="content_table_content_repeat" ms-repeat="all">
                                            <div style="cursor: pointer;" ms-class="gray:el.late==1" class="content_table_content_repeat_name" ms-text="el.username" ms-click="goinfo(el.paperId,el.id,el.score)"></div>
                                        </a>
                                        <div ms-class="gray:el.late==1" class="content_table_content_repeat_answer score_black" ms-text="el.score1 == null ? '—'  : el.score1"></div>
                                        <div ms-class="gray:el.late==1" class="content_table_content_repeat_answer score_black" ms-text="el.score2 == null ? '—'  : el.score2"></div>
                                        <div ms-class="gray:el.late==1" class="content_table_content_repeat_answer score_black" ms-text="el.score3 == null ? '—'  : el.score3"></div>
                                        <div ms-class="gray:el.late==1" class="content_table_content_repeat_answer score_black" ms-text="el.score4 == null ? '—'  : el.score4"></div>
                                        <div ms-class="gray:el.late==1" class="content_table_content_repeat_answer score_black" ms-text="el.score5 == null ? '—'  : el.score5"></div>
                                        <div ms-class="gray:el.late==1" class="content_table_content_repeat_answer score_green" ms-text="el.score  == null ? '—'  : el.score" ></div>
                                    </div>
                                </div>
                        </div>

                        {{-- 单选题部分 --}}
                        <div class="q1s hide"  ms-visible="changeValue == 'q1s'">
                            <center>
                            <table style="text-align: center">
                                <thead>
                                <tr style="background-color: #C3E5F9">
                                    <th width="78"  style="background: url('/home/image/evaluateManageTea/namenumber.png') no-repeat;" >
                                        <div class="content_table_title_first1">
                                            <div class="content_table_title_first_num">题号</div>
                                            <div class="content_table_title_first_name">姓名</div>
                                        </div>
                                    </th>
                                    <td ms-repeat="q1[0].number" ms-text="el"></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ms-visible="q1[0].number == ''" class="hide">
                                    <td  ms-text="message"></td>
                                </tr>
                                <tr ms-visible="q1[0].number != ''" ms-repeat="q1">
                                     <th style="cursor: pointer;" width="78" ms-text="el.username" ms-click="goinfo(el.pId,el.userId,1)" ></th>
                                     <td ms-repeat-i="el.correct" ms-if="el.username != '正确率%'"  ms-html="i === 1 ? '<img src=/home/image/evaluateManageTea/right.png>' : '<img src=/home/image/evaluateManageTea/wrong.png>'"></td>
                                     <td ms-repeat-i="el.correct" ms-if="el.username == '正确率%'"  ms-html="i"></td>
                                </tr>
                                </tbody>
                            </table>
                            </center>
                        </div>

                        {{-- 多选题部分 --}}
                        <div class="q2s hide" ms-visible="changeValue == 'q2s'">
                            <center>
                                <table style="text-align: center">
                                    <thead>
                                    <tr style="background-color: #C3E5F9">
                                        <th width="78"  style="background: url('/home/image/evaluateManageTea/namenumber.png') no-repeat;" >
                                            <div class="content_table_title_first1">
                                                <div class="content_table_title_first_num">题号</div>
                                                <div class="content_table_title_first_name">姓名</div>
                                            </div>
                                        </th>
                                        <td ms-repeat="q2[0].number" ms-text="el"></td>
                                    </tr>
                                    </thead>
                                    <tbody>
									
                                    <tr ms-visible="q2[0].number == ''" class="hide">
                                        <td  ms-text="message"></td>
                                    </tr>
                                    <tr ms-visible="q2[0].number != ''" ms-repeat="q2">
                                        <th style="cursor: pointer" width="78" ms-text="el.username" ms-click="goinfo(el.pId,el.userId,1)"></th>
                                        <td ms-repeat-i="el.correct" ms-if="el.username != '正确率%'"  ms-html="i === 1 ? '<img src=/home/image/evaluateManageTea/right.png>' : '<img src=/home/image/evaluateManageTea/wrong.png>'"></td>
                                        <td ms-repeat-i="el.correct" ms-if="el.username == '正确率%'"  ms-html="i"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </center>
                        </div>

                        {{-- 填空题目部分 --}}
                        <div class="q3s hide" ms-visible="changeValue == 'q3s'">
                            <center>
                                <table style="text-align: center">
                                    <thead>
                                    <tr style="background-color: #C3E5F9">
                                        <th width="78"  style="background: url('/home/image/evaluateManageTea/namenumber.png') no-repeat;" >
                                            <div class="content_table_title_first1">
                                                <div class="content_table_title_first_num">题号</div>
                                                <div class="content_table_title_first_name">姓名</div>
                                            </div>
                                        </th>
                                        <td ms-repeat="q3[0].number" ms-text="el"></td>
                                    </tr>
                                    </thead>
                                    <tbody>

									
                                    <tr ms-visible="q3[0].number == ''" class="hide">
                                        <td  ms-text="message"></td>
                                    </tr>

                                    <tr ms-visible="q3[0].number != ''" ms-repeat="q3">
                                        <th style="cursor: pointer" width="78" ms-text="el.username" ms-click="goinfo(el.pId,el.userId,1)"></th>
                                        <td ms-repeat-i="el.correct" ms-if="el.username != '正确率%'"  ms-html="i === 1 ? '<img src=/home/image/evaluateManageTea/right.png>' : '<img src=/home/image/evaluateManageTea/wrong.png>'"></td>
                                        <td ms-repeat-i="el.correct" ms-if="el.username == '正确率%'"  ms-html="i"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </center>
                        </div>

                        {{-- 判断题目部分 --}}
                        <div class="q4s hide" ms-visible="changeValue == 'q4s'">
                            <center>
                                <table style="text-align: center">
                                    <thead>
                                    <tr style="background-color: #C3E5F9">
                                        <th width="78"  style="background: url('/home/image/evaluateManageTea/namenumber.png') no-repeat;" >
                                            <div class="content_table_title_first1">
                                                <div class="content_table_title_first_num">题号</div>
                                                <div class="content_table_title_first_name">姓名</div>
                                            </div>
                                        </th>
                                        <td ms-repeat="q4[0].number" ms-text="el"></td>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    <tr ms-visible="q4[0].number == ''" class="hide">
                                        <td  ms-text="message"></td>
                                    </tr>

                                    <tr ms-visible="q4[0].number != ''" ms-repeat="q4">
                                        <th style="cursor: pointer" width="78" ms-text="el.username" ms-click="goinfo(el.pId,el.userId,1)"></th>
                                        <td ms-repeat-i="el.correct" ms-if="el.username != '正确率%'"  ms-html="i === 1 ? '<img src=/home/image/evaluateManageTea/right.png>' : '<img src=/home/image/evaluateManageTea/wrong.png>'"></td>
                                        <td ms-repeat-i="el.correct" ms-if="el.username == '正确率%'"  ms-html="i"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </center>
                            </center>
                        </div>

                        {{-- 解答题目部分 --}}
                        <div class="q5s hide" ms-visible="changeValue == 'q5s'">
                            <center>
                                <table style="text-align: center">
                                    <thead>
                                    <tr style="background-color: #C3E5F9">
                                        <th width="78"  style="background: url('/home/image/evaluateManageTea/namenumber.png') no-repeat;" >
                                            <div class="content_table_title_first1">
                                                <div class="content_table_title_first_num">题号</div>
                                                <div class="content_table_title_first_name">姓名</div>
                                            </div>
                                        </th>
                                        <td ms-repeat="q5[0].number" ms-text="el"></td>
                                    </tr>
                                    </thead>
                                    <tbody>

								
                                    <tr ms-visible="q5[0].number == ''" class="hide">
                                        <td  ms-text="message"></td>
                                    </tr>

                                    <tr ms-visible="q5[0].number != ''"  ms-repeat="q5">
                                        <th style="cursor: pointer" width="78" ms-text="el.username" ms-click="goinfo(el.pId,el.userId,1)"></th>
                                        <td ms-repeat-i="el.correct"  ms-html="i"></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </center>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="clear" style="height: 30px;"></div>
            </div>
        </div>
        <div class="clear" style="height: 80px;"></div>
    </div>
@endsection

@section('js')
    <script>
        require(['/evaluateManageTea/scoreQuery'], function (scoreQuery) {
            scoreQuery.paperId = '{{$id}}' || null;
			scoreQuery.classId = '{{$classid}}' || null;
            scoreQuery.title = '{{$title}}' || null;
            scoreQuery.subjectId = '{{$result->subjectId}}' || null;
            //获取全部分数
            scoreQuery.request('/evaluateManageTea/getAllScore',{ id:scoreQuery.paperId ,classid:scoreQuery.classId },function (err, res) {
                if (!err) {
                    scoreQuery.all = res;
                    console.log(res);
                }
            });
            avalon.scan();
        });

    </script>
@endsection