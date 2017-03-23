@extends('layouts.layoutHome')

@section('title', '独立试题 作业结果')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageStu/studentPaperStu.css') }}">
@endsection

@section('content')
    <div style="height: 40px;width: 100%"></div>
    <div ms-controller="stuPaperStuController">
        <div class="student_paper hide"  ms-class="hide: !paperDisplay">
            <div class="student_paper_top">
                <div class="student_paper_top_title" ms-text="basicInfo.title"></div>
                <div class="student_paper_top_type" ms-text="basicInfo.subjectName + ' ' + basicInfo.editionName + ' ' + basicInfo.gradeName + ' ' + basicInfo.bookName"></div>
            </div>
            <div class="student_paper_content">
                <div class="student_paper_content_top">
                    {{--<div class="student_paper_content_top_left">--}}
                    {{--课前导学测试习题--}}
                    {{--</div>--}}
                    <div class="student_paper_content_top_right" ms-text="'作业提交截止时间为 : ' + basicInfo.submitTime"></div>
                </div>
                <div class="student_paper_content_bot">
                    <div ms-repeat-a="paperInfo">
                        {{-- 单选题1 --}}
                        <div class="student_paper_content_repeat_1" ms-if="a.type == 1">
                            <div class="student_paper_content_repeat_top">
                                <div class="student_paper_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">单选</div>
                                    <div class="three" ms-html="a.title"></div>
                                    <div class="four" ms-text="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_paper_content_repeat_cen">
                                <div ms-repeat="a.choice">
                                    <div class="student_paper_content_repeat_cen_answer">
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
                            <div class="student_paper_content_repeat_bot">
                                <div class="student_paper_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                        {{-- 多选题2 --}}
                        <div class="student_paper_content_repeat_2" ms-if="a.type == 2">
                            <div class="student_paper_content_repeat_top">
                                <div class="student_paper_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">多选</div>
                                    <div class="three" ms-html="a.title"></div>
                                    <div class="four" ms-text="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_paper_content_repeat_cen">
                                <div ms-repeat="a.choice">
                                    <div class="student_paper_content_repeat_cen_answer">
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
                            <div class="student_paper_content_repeat_bot">
                                <div class="student_paper_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                        {{-- 填空题4 --}}
                        <div class="student_paper_content_repeat_3" ms-if="a.type == 4">
                            <div class="student_paper_content_repeat_top">
                                <div class="student_paper_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">填空</div>
                                    <div class="three">
                                        <div ms-html="changeTitle($index)"></div>
                                    </div>
                                    <div class="four" ms-text="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_paper_content_repeat_bot">
                                <div class="student_paper_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                        {{-- 判断题3 --}}
                        <div class="student_paper_content_repeat_5" ms-if="a.type == 3">
                            <div class="student_paper_content_repeat_top">
                                <div class="student_paper_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">判断</div>
                                    <div class="three" ms-html="a.title"></div>
                                    <div class="four" ms-text="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_paper_content_repeat_cen">
                                <div class="student_paper_content_repeat_cen_answer">
                                    <div ms-if="a.userAnswer === 1" class="one has_choose"></div>
                                    <div ms-if="a.userAnswer === 0" class="one no_choose"></div>
                                    <div ms-if="a.userAnswer === ''" class="one no_choose"></div>
                                    <div class="two">正确</div>
                                </div>
                                <div class="student_paper_content_repeat_cen_answer">
                                    <div ms-if="a.userAnswer === 1" class="one no_choose"></div>
                                    <div ms-if="a.userAnswer === 0" class="one has_choose"></div>
                                    <div ms-if="a.userAnswer === ''" class="one no_choose"></div>
                                    <div class="two">错误</div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_paper_content_repeat_bot">
                                <div class="student_paper_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight">
                                    <span>正确答案 ：</span>
                                    <span ms-text="a.answer == 1 ? '正确' : '错误'"></span>
                                </div>
                                <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                            </div>
                            <div class="clear_my"></div>
                        </div>
                        {{-- 解答题5 --}}
                        <div class="student_paper_content_repeat_4" ms-if="a.type == 5">
                            <div class="student_paper_content_repeat_top">
                                <div class="student_paper_content_repeat_top_question">
                                    <div class="one" ms-text="a.sort + '.'"></div>
                                    <div class="two">解答</div>
                                    <div class="three" ms-html="a.title"></div>
                                    <div class="four" ms-text="'（' + a.score + '分）'"></div>
                                </div>
                            </div>
                            <div class="clear_my"></div>
                            <div class="student_paper_content_repeat_cen">
                                <div class="student_paper_content_repeat_cen_answer">
                                    {{--<div class="student_paper_content_repeat_cen_answer_top" ms-html="a.title"></div>--}}
                                    <div class="student_paper_content_repeat_cen_answer_cen" ms-html="'答：' + a.userAnswer"></div>
                                    <div class="student_paper_content_repeat_cen_answer_bot">
                                        <span class="right_color" ms-html="'参考答案：' + a.answer"></span>
                                    </div>
                                    <div class="question_detail_score" ms-if="a.getScore">
                                        <div class="question_detail_score_top">
                                            评分：<input type="text" ms-attr-value="a.getScore" disabled /> 分
                                        </div>
                                        <div class="question_detail_score_bot">
                                            <span>评语：</span><textarea ms-attr-value="a.comment" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear_my"></div>
                                {{--<div class="student_paper_content_repeat_cen_answer">--}}
                                {{--<div class="student_paper_content_repeat_cen_answer_top" ms-text="a.title"></div>--}}
                                {{--<div class="student_paper_content_repeat_cen_answer_cen" ms-text="'答：' + a.userAnswer"></div>--}}
                                {{--<div class="student_paper_content_repeat_cen_answer_bot">--}}
                                {{--<span class="right_color" ms-text="'参考答案：' + a.answer"></span>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </div>
                            <div class="clear_my"></div>
                        </div>
                        <div class="clear_my"></div>
                    </div>
                </div>
            </div>
            <div style="height: 150px;width: 100%"></div>
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
        require(['/evaluateManageStu/stuPaperStu'], function (stuPaperStu) {
            stuPaperStu.paperId = '{{$id}}' || null;
            stuPaperStu.userId = '{{$userId}}' || null;
            stuPaperStu.init();
            stuPaperStu.getData('/evaluateManageStu/getHomeWorkInfo/' + stuPaperStu.paperId + '/' + stuPaperStu.userId, 'GET', '', 'paperInfo');
            avalon.filters.changeCode = function (value) {
                return String.fromCharCode(parseInt(value) + 65);
            };
            avalon.scan();
        });
    </script>
@endsection