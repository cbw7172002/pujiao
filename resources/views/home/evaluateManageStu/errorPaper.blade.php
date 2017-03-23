@extends('layouts.layoutHome')

@section('title', '独立试题 作业结果')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageStu/studentPaperStu.css') }}">
@endsection

@section('content')
    <div style="height: 40px;width: 100%"></div>
    <div class="student_paper" ms-controller="errorPaperController" ms-if="paperDisplay">
        <div class="student_paper_top">
            <div class="student_paper_top_title" ms-text="basicInfo.title"></div>
            <div class="student_paper_top_type" ms-text="basicInfo.subjectName + ' ' + basicInfo.editionName + ' ' + basicInfo.gradeName + ' ' + basicInfo.bookName"></div>
        </div>
        <div class="student_paper_content">
            <div class="student_paper_content_top" style="font-size: 16px;padding-top: 20px;height: 40px;">
                {{--<div class="student_paper_content_top_left">--}}
                {{--课前导学测试习题--}}
                {{--</div>--}}您的错题如下：
            </div>

            <div class="student_paper_content_bot">
                <div ms-repeat-a="paperInfo">
                    {{-- 单选题1 --}}
                    <div class="student_paper_content_repeat_1" ms-if="a.type == 1">
                        <div class="student_paper_content_repeat_top">
                            <div class="student_paper_content_repeat_top_question">
                                <div class="one" ms-text="a.sort + '.'"></div>
                                <div class="two">单选</div>
                                <div class="three" ms-html="a.title + '（' + a.score + '）'"></div>
                                <div class="four" style="float: right;padding-right: 20px;color: rgb(102, 102, 102);" ms-click="delQuestion(paperId,userId,$index,a.type,a.id)">删除</div>
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
                                <div class="three" ms-html="a.title + '（' + a.score + '）'"></div>
                                <div class="four" style="float: right;padding-right: 20px;color: rgb(102, 102, 102);cursor: pointer" ms-click="delQuestion(paperId,userId,$index,a.type,a.id)">删除</div>
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
                                <div class="three" ms-html="a.title + '（' + a.score + '）'"></div>
                                <div class="four" style="float: right;padding-right: 20px;color: rgb(102, 102, 102);cursor: pointer" ms-click="delQuestion(paperId,userId,$index,a.type,a.id)">删除</div>
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
                                <div class="three" ms-html="a.title + '（' + a.score + '）'"></div>
                                <div class="four" style="float: right;padding-right: 20px;color: rgb(102, 102, 102);cursor: pointer" ms-click="delQuestion(paperId,userId,$index,a.type,a.id)">删除</div>
                            </div>
                        </div>
                        <div class="clear_my"></div>
                        <div class="student_paper_content_repeat_cen">
                            <div class="student_paper_content_repeat_cen_answer">
                                <div ms-if="a.userAnswer === 1" class="one has_choose"></div>
                                <div ms-if="a.userAnswer === 0" class="one no_choose"></div>
                                <div class="two">正确</div>
                            </div>
                            <div class="student_paper_content_repeat_cen_answer">
                                <div ms-if="a.userAnswer === 1" class="one no_choose"></div>
                                <div ms-if="a.userAnswer === 0" class="one has_choose"></div>
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
                                <div class="three" ms-html="a.title + '（' + a.score + '）'"></div>
                                <div class="four" style="float: right;padding-right: 20px;color: rgb(102, 102, 102);cursor: pointer" ms-click="delQuestion(paperId,userId,$index,a.type,a.id)">删除</div>
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

                        <div class="student_paper_content_repeat_bot" style="width: 800px;height: 40px;background-color: #F5F5F5;">
                            <div class="student_paper_content_repeat_bot_left">

                            </div>
                            <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'回答错误'"></div>
                        </div>
                        <div class="clear_my"></div>
                    </div>
            </div>
        </div>
        <div style="height: 150px;width: 100%"></div>
    </div>
    <div class="clear_my" style="height: 150px;width:100%;"></div>
@endsection

@section('js')
    <script>
        require(['/evaluateManageStu/errorPaper'], function (errorPaper) {
            errorPaper.paperId = '{{$id}}' || null;
            errorPaper.userId = '{{$userId}}' || null;
            errorPaper.getData('/evaluateManageStu/getErrorInfo/' + errorPaper.paperId + '/' + errorPaper.userId, 'GET', '', 'paperInfo');
            avalon.filters.changeCode = function (value) {
                return String.fromCharCode(parseInt(value) + 65);
            };
            avalon.scan();
        });
    </script>
@endsection