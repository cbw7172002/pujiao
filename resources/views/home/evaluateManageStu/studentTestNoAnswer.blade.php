@extends('layouts.layoutHome')

@section('title', '独立试题 测验试卷')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageStu/studentTestNoAnswer.css') }}">
@endsection

@section('content')
    <div style="height: 40px;width: 100%"></div>
    <div ms-controller="stuTestNoAnswerController">
        <div class="student_test hide" ms-class="hide: !paperDisplay">
            <div class="shadow hide"></div>
            <div class="timer" ms-if="saveFlag">
                <div class="icon-clock"></div>
                <div class="time_limit">倒计时：<span id="m">00</span><span class="bd">:</span><span id="s">00</span></div>
            </div>
            <div class="student_test_top">
                <div class="student_test_top_title" ms-text="basicInfo.title"></div>
                <div class="student_test_top_type" ms-text="basicInfo.subjectName + ' ' + basicInfo.editionName + ' ' + basicInfo.gradeName + ' ' + basicInfo.bookName"></div>
            </div>
            <div class="student_test_content">
                <div class="student_test_content_top">
                    <div class="student_test_content_top_left">
                        测验试卷
                    </div>
                    <div class="student_test_content_top_right" ms-text="'作业提交截止时间为 : ' + basicInfo.submitTime"></div>
                </div>
                <div class="student_test_content_bot">
                    {{-- 单选题 --}}
                    <div class="student_test_content_repeat_one" ms-if="testInfo.sChoose">
                        <div class="student_test_content_repeat_one_top">
                            一、单选题
                        </div>
                        <div class="student_test_content_repeat_1" ms-repeat-a="testInfo.sChoose">
                            <div class="student_test_content_repeat_top">
                                <div class="student_test_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">单选</div>
                                    <div class="three" ms-html="a.title"></div>
                                    <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_test_content_repeat_cen">
                                <div ms-repeat="a.choice">
                                    <div class="student_test_content_repeat_cen_answer">
                                        <input type="radio" ms-click="tSingleChoose($index, $outer.$index, a.answer, a.score);" />
                                        <div class="one" ms-class-1="has_choose:isTrue($index, a.newAnswer, 1)" ms-class-2="no_choose:isTrue($index, a.newAnswer, 2)"></div>
                                        <div class="two">
                                            <span ms-text="$index|changeCode"></span><span ms-html="'、' + el"></span>
                                        </div>
                                    </div>
                                    <div class="clear_my"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    <div class="clear_my"></div>
                    {{-- 多选题 --}}
                    <div class="student_test_content_repeat_two" ms-if="testInfo.mChoose">
                        <div class="student_test_content_repeat_two_top">
                            二、多选题
                        </div>
                        <div class="student_test_content_repeat_2" ms-repeat-a="testInfo.mChoose">
                            <div class="student_test_content_repeat_top">
                                <div class="student_test_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">多选</div>
                                    <div class="three" ms-html="a.title"></div>
                                    <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_test_content_repeat_cen">
                                <div ms-repeat="a.choice">
                                    <div class="student_test_content_repeat_cen_answer">
                                        <input type="checkbox" ms-click="tManyChoose($index, $outer.$index, a.answer, a.score);" />
                                        <div class="one" ms-class-1="has_choose:isMTrue($index, a.newAnswer, 1)" ms-class-2="no_choose:isMTrue($index, a.newAnswer, 2)"></div>
                                        <div class="two">
                                            <span ms-text="$index|changeCode"></span><span ms-html="'、' + el"></span>
                                        </div>
                                    </div>
                                    <div class="clear_my"></div>
                                </div>

                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    <div class="clear_my"></div>
                    {{-- 判断题 --}}
                    <div class="student_test_content_repeat_five" ms-if="testInfo.judge">
                        <div class="student_test_content_repeat_five_top">
                            三、判断题
                        </div>
                        <div class="student_test_content_repeat_5" ms-repeat-a="testInfo.judge">
                            <div class="student_test_content_repeat_top">
                                <div class="student_test_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">判断</div>
                                    <div class="three" ms-html="a.title"></div>
                                    <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_test_content_repeat_cen">
                                <div class="student_test_content_repeat_cen_answer">
                                    <input type="radio" ms-click="tJudgeTest(1, $index, a.answer, a.score);" />
                                    <div ms-if="a.newAnswer !== ''" class="one" ms-class-1="has_choose:a.newAnswer" ms-class-2="no_choose:!a.newAnswer"></div>
                                    <div ms-if="a.newAnswer === ''" class="one no_choose"></div>
                                    <div class="two">正确</div>
                                </div>
                                <div class="student_test_content_repeat_cen_answer">
                                    <input type="radio" ms-click="tJudgeTest(0, $index, a.answer, a.score);" />
                                    <div ms-if="a.newAnswer !== ''" class="one" ms-class-1="has_choose:!a.newAnswer" ms-class-2="no_choose:a.newAnswer"></div>
                                    <div ms-if="a.newAnswer === ''" class="one no_choose"></div>
                                    <div class="two">错误</div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    <div class="clear_my"></div>
                    {{-- 填空题 --}}
                    <div class="student_test_content_repeat_three" ms-if="testInfo.completion">
                        <div class="student_test_content_repeat_three_top">
                            四、填空题
                        </div>
                        <div class="student_test_content_repeat_3" ms-repeat-a="testInfo.completion">
                            <div class="student_test_content_repeat_top">
                                <div class="student_test_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">填空</div>
                                    <div class="three">
                                        <span ms-html="changeTitle($index)"></span>
                                    </div>
                                    <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    <div class="clear_my"></div>
                    {{-- 解答题 --}}
                    <div class="student_test_content_repeat_four" ms-if="testInfo.subjective">
                        <div class="student_test_content_repeat_four_top">
                            五、解答题
                        </div>
                        <div class="student_test_content_repeat_4" ms-repeat-a="testInfo.subjective">
                            <div class="student_test_content_repeat_top">
                                <div class="student_test_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">解答</div>
                                    <div class="three" ms-html="a.title"></div>
                                    <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_test_content_repeat_cen">
                                <div class="student_test_content_repeat_cen_answer">
                                    {{--<div class="student_test_content_repeat_cen_answer_top" ms-html="a.title"></div>--}}
                                    <div class="student_test_content_repeat_cen_answer_bot">
                                        <textarea ms-duplex-string="a.newAnswer" >答：</textarea>
                                    </div>
                                </div>
                                <div class="clear_my"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    <div class="clear_my"></div>
                </div>
            </div>
            <div style="height: 50px;width: 100%"></div>
            <div class="question_detail_score_submit" ms-click="submitAnswer()">提交</div>
            <div style="height: 50px;width: 100%"></div>
            <div class="imgZoom hide" ms-class="hide: !showImg">
                <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
                <img ms-attr-src="imgZoom">
            </div>
            {{-- ======= 测试报告 start ======= --}}
            <div class="report hide">
                <div class="report_top">
                    <div class="report_top_close">
                        <div ms-click="goToPaper(2)">×</div>
                    </div>
                    <div class="report_top_title">作业报告</div>
                </div>
                <div class="clear_my"></div>
                <div class="report_center">
                    <div class="report_center_right">
                        <div class="report_center_right_top" ms-text="'正确' + report.TNum"></div>
                        <div class="report_center_right_bot" ms-text="report.tPercent"></div>
                    </div>
                    <div class="report_center_error">
                        <div class="report_center_error_top" ms-text="'错误' + report.FNum"></div>
                        <div class="report_center_error_bot" ms-text="report.fPercent"></div>
                    </div>
                    <div class="report_center_no_answer">
                        <div class="report_center_no_answer_top" ms-text="'未答' + report.NNum"></div>
                        <div class="report_center_no_answer_bot" ms-text="report.nPercent"></div>
                    </div>
                </div>
                <div class="clear_my"></div>
                <div class="report_tip">以上为客观题作答统计</div>
                <div class="clear_my"></div>
                <div class="report_count">
                    <div class="report_count_left" ms-text="'共作答：' + report.kNum + '题'"></div>
                    <div class="report_count_right" ms-text="'用时：' + report.time"></div>
                </div>
                <div class="clear_my"></div>
                <div class="report_explain" ms-click="goToPaper(1)">查看解析</div>
                <div class="clear_my"></div>
                <div class="report_bot_tip">
                    提示：如试卷中包含主观题，请等待教师批改后在查看相关题目成绩
                </div>
                <div class="clear_my"></div>
            </div>
            {{-- ======= 测试报告 end ======= --}}
            {{-- ========================================= 成绩提示窗口 ========================================= --}}
            <div class="notice hide">
                <div class="top">提示</div>
                <div class="cen">答题时间已结束，系统自动提交</div>
                <div class="bot" ms-click="seeScore()">查看解析</div>
            </div>
            {{-- ========================================= 成绩提示窗口 ========================================= --}}
        </div>
        <div class="spinner hide" ms-class="hide: paperDisplay">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
    <div class="clear_my" style="height: 150px;width:100%;"></div>
@endsection

@section('js')
    <script>
        require(['/evaluateManageStu/stuTestNoAnswer'], function (stuTestNoAnswer) {
            stuTestNoAnswer.paperId = '{{$id}}' || null;
            stuTestNoAnswer.userId = '{{$userId}}' || null;
            stuTestNoAnswer.timeLeave = '{{$timeLeave}}';
            var min = '{{$completeTime}}';
            var sec = 0;
            function showtime() {
                if (min > 0) {
                    if (sec > 0) {
                        sec = sec - 1;
                    } else {
                        min = min - 1;
                        sec = 59;
                        if (min < 10 && min > 0) {
                            min = '0' + min;
                        }
                    }
                } else {

                    sec = sec - 1;
                }
                if (min == 0) {
                    min = '00';
                }
                if (sec < 10) {
                    sec = '0' + sec;
                }
                document.getElementById('m').innerHTML = min;
                document.getElementById('s').innerHTML = sec;
                if (min == '00' && sec == 0) {
                    clearInterval(setTime);
                    stuTestNoAnswer.submitAnswer();
                }
            }
            if (min == 'noTime') {
                document.getElementById('m').innerHTML = '00';
                document.getElementById('s').innerHTML = '00';
                $('.shadow').show();
                $('.notice').show();
                stuTestNoAnswer.moveAnswer();
            } else {
                var setTime = setInterval(function () {
                    showtime();
                    stuTestNoAnswer.saveAnswer();
                }, 1000);
            }
            stuTestNoAnswer.init();
            stuTestNoAnswer.getData('/evaluateManageStu/getTestNoAnswer/' + stuTestNoAnswer.paperId, 'GET', '', 'testInfo');
            avalon.filters.changeCode = function(value){
                return String.fromCharCode(parseInt(value) + 65);
            };
            avalon.scan();
        });
    </script>
@endsection