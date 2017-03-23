@extends('layouts.layoutHome')

@section('title', '独立试题 测验结果')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageStu/studentTestPaperStu.css') }}">
@endsection

@section('content')
    <div style="height: 40px;width: 100%"></div>
    <div ms-controller="stuTestPaperStu">
        <div class="student_test hide" ms-class="hide: !paperDisplay">
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
                        <div ms-repeat-a="testInfo.sChoose">
                            <div class="student_test_content_repeat_1">
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
                                            <div class="one" ms-class-1="has_choose:isTrue($index, a.userAnswer, 1)" ms-class-2="no_choose:isTrue($index, a.userAnswer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span>
                                                <span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear_my"></div>
                                    </div>
                                </div>
                                <div class="clear_my"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_test_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                                <div class="clear_my"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    {{-- 多选题 --}}
                    <div class="student_test_content_repeat_two" ms-if="testInfo.mChoose">
                        <div class="student_test_content_repeat_two_top">
                            二、多选题
                        </div>
                        <div ms-repeat-a="testInfo.mChoose">
                            <div class="student_test_content_repeat_2">
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
                                            <div class="one" ms-class-1="has_choose:isMTrue($index, a.userAnswer, 1)" ms-class-2="no_choose:isMTrue($index, a.userAnswer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span>
                                                <span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear_my"></div>
                                    </div>
                                </div>
                                <div class="clear_my"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_test_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                                <div class="clear_my"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    {{-- 判断题 --}}
                    <div class="student_test_content_repeat_five" ms-if="testInfo.judge">
                        <div class="student_test_content_repeat_five_top">
                            三、判断题
                        </div>
                        <div ms-repeat-a="testInfo.judge">
                            <div class="student_test_content_repeat_5">
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
                                        <div ms-if="a.userAnswer === 1" class="one has_choose"></div>
                                        <div ms-if="a.userAnswer === 0" class="one no_choose"></div>
                                        <div ms-if="a.userAnswer === ''" class="one no_choose"></div>
                                        <div class="two">正确</div>
                                    </div>
                                    <div class="student_test_content_repeat_cen_answer">
                                        <div ms-if="a.userAnswer === 1" class="one no_choose"></div>
                                        <div ms-if="a.userAnswer === 0" class="one has_choose"></div>
                                        <div ms-if="a.userAnswer === ''" class="one no_choose"></div>
                                        <div class="two">错误</div>
                                    </div>
                                </div>
                                <div class="clear_my"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight">
                                        <span>正确答案 ：</span>
                                        <span ms-text="a.answer == 1 ? '正确' : '错误'"></span>
                                    </div>
                                    <div class="student_test_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                                <div class="clear_my"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    {{-- 填空题 --}}
                    <div class="student_test_content_repeat_three" ms-if="testInfo.completion">
                        <div class="student_test_content_repeat_three_top">
                            四、填空题
                        </div>
                        <div ms-repeat-a="testInfo.completion">
                            <div class="student_test_content_repeat_3">
                                <div class="student_test_content_repeat_top">
                                    <div class="student_test_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">填空</div>
                                        <div class="three">
                                            <div ms-html="changeTitle($index)"></div>
                                        </div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear_my"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_test_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                                <div class="clear_my"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                    </div>
                    {{-- 解答题 --}}
                    <div class="student_test_content_repeat_four" ms-if="testInfo.subjective">
                        <div class="student_test_content_repeat_four_top">
                            五、解答题
                        </div>
                        <div ms-repeat-a="testInfo.subjective">
                            <div class="student_test_content_repeat_4">
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
                                        <div class="student_test_content_repeat_cen_answer_cen" ms-html="'答：' + a.userAnswer"></div>
                                        <div class="student_test_content_repeat_cen_answer_bot">
                                            <span class="right_color" ms-html="'参考答案：' + a.answer"></span>
                                        </div>
                                        <div class="question_detail_score">
                                            <div class="question_detail_score_top">
                                                评分：<input type="text" ms-duplex-string="a.getScore" /> 分
                                            </div>
                                            <div class="question_detail_score_bot">
                                                <span>评语：</span>
                                                <div class="hah">
                                                    <textarea ms-duplex-string="a.comment" maxlength="50"></textarea>
                                                    <div ms-text="(a.comment).length"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clear_my"></div>
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
            <div class="question_detail_score_submit" ms-click="submitAnswer()">完成</div>
            <div style="height: 50px;width: 100%"></div>
            <div class="imgZoom hide" ms-class="hide: !showImg">
                <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
                <img ms-attr-src="imgZoom">
            </div>
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
        require(['/evaluateManageTea/testScore'], function (stuTestPaperStu) {
            stuTestPaperStu.paperId = '{{$id}}' || null;
            stuTestPaperStu.userId = '{{$userId}}' || null;
            stuTestPaperStu.init();
            stuTestPaperStu.getData('/evaluateManageTea/getTestScore/' + stuTestPaperStu.paperId + '/' + stuTestPaperStu.userId, 'GET', '', 'testInfo');
            // 答案数字转字母
            avalon.filters.changeCode = function(value){
                return String.fromCharCode(parseInt(value) + 65);
            };
            avalon.scan();
        });
    </script>
@endsection