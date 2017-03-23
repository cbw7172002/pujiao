@extends('layouts.layoutHome')

@section('title', '试卷详情')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageTea/testPaperTea.css') }}">
@endsection

@section('content')
    <div class="contain_lessonDetail" ms-controller="testPaperTeaController">
        <div style="width: 100%;height: 15px;"></div>
        <div class="contain_lessonDetail_top">
            <div class="contain_lessonDetail_top_breadcrumb">
                <a href="">首页</a> >
                <a href="">测评管理</a> > 试卷详情
            </div>
        </div>
        <div class="test_paper_title">
            <div style="width: 100%;height: 18px;"></div>
            <div class="test_paper_title_top">
                {{$result->title}}
            </div>
            <div class="test_paper_title_bot">
                {{$result->gradeName}} | {{$result->subjectName}} | {{$result->editionName}} | {{$result->bookName}} | 上传日期 : {{$result->created_at}}
            </div>
            <div class="test_paper_title_right" ms-text="isCollection ? '取消收藏' : '收藏试卷'" ms-click="collectPaper(paperId)"></div>
        </div>
        <div class="test_paper_container hide" ms-class="hide: !paperDisplay">
            <div class="test_paper_content">
                <div class="test_paper_content_count">共计{{$result->count}}题</div>
                <div class="test_paper_content_repeat" ms-repeat="paperInfo" ms-if="paperDisplay">
                    {{-- 单选题 --}}
                    <div class="test_paper_content_repeat_type_1" ms-if="el.type == 1">
                        <div class="test_paper_content_repeat_top">
                            <div class="test_paper_content_repeat_top_num" ms-text="'题号：'+ el.sort"></div>
                            <div class="test_paper_content_repeat_top_type">题型：单选题</div>
                            <div class="test_paper_content_repeat_top_date" ms-text="'日期：' + el.created_at"></div>
                            <div class="test_paper_content_repeat_top_grade" ms-text="'难易度：' + el.difficult"></div>
                            <div class="test_paper_content_repeat_top_collect">
                                <i ms-if="el.isCollectQ" class="collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <i ms-if="!el.isCollectQ" class="no_collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <span ms-text="el.isCollectQ ? '取消收藏' : '收藏试题'"></span>
                            </div>
                        </div>
                        <div class="test_paper_content_repeat_cen" ms-shoudetail>
                            <div class="test_paper_content_repeat_cen_question" ms-html="el.title"></div>
                            <div class="test_paper_content_repeat_cen_option" ms-repeat="el.choice">
                                <span ms-text="$index|changeCode"></span>
                                <span ms-html="'、' + el"></span>
                            </div>
                        </div>
                        <div class="test_paper_content_repeat_bot hide">
                            <div class="test_paper_content_repeat_bot_answer">
                                <span class="explain">答案</span><span class="answer" ms-html="el.answer"></span>
                            </div>
                            <div class="clear_my"></div>
                            <div class="test_paper_content_repeat_bot_explain">
                                <span class="explain">解析</span>
                                <span class="answer" ms-html="el.analysis"></span>
                            </div>
                        </div>
                        <div class="clear_my"></div>
                    </div>
                    {{-- 多选题 --}}
                    <div class="test_paper_content_repeat_type_1" ms-if="el.type == 2">
                        <div class="test_paper_content_repeat_top">
                            <div class="test_paper_content_repeat_top_num" ms-text="'题号：'+ el.sort"></div>
                            <div class="test_paper_content_repeat_top_type">题型：多选题</div>
                            <div class="test_paper_content_repeat_top_date" ms-text="'日期：' + el.created_at"></div>
                            <div class="test_paper_content_repeat_top_grade" ms-text="'难易度：' + el.difficult"></div>
                            <div class="test_paper_content_repeat_top_collect">
                                <i ms-if="el.isCollectQ" class="collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <i ms-if="!el.isCollectQ" class="no_collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <span ms-text="el.isCollectQ ? '取消收藏' : '收藏试题'"></span>
                            </div>
                        </div>
                        <div class="test_paper_content_repeat_cen" ms-shoudetail>
                            <div class="test_paper_content_repeat_cen_question" ms-html="el.title"></div>
                            <div class="test_paper_content_repeat_cen_option" ms-repeat="el.choice">
                                <span ms-text="$index|changeCode"></span>
                                <span ms-html="'、' + el"></span>
                            </div>
                        </div>
                        <div class="test_paper_content_repeat_bot hide">
                            <div class="test_paper_content_repeat_bot_answer">
                                <span class="explain">答案</span><span class="answer" ms-html="el.answer"></span>
                            </div>
                            <div class="clear_my"></div>
                            <div class="test_paper_content_repeat_bot_explain">
                                <span class="explain">解析</span>
                                <span class="answer" ms-html="el.analysis"></span>
                            </div>
                        </div>
                        <div class="clear_my"></div>
                    </div>
                    {{-- 判断题 --}}
                    <div class="test_paper_content_repeat_type_3" ms-if="el.type == 3">
                        <div class="test_paper_content_repeat_top">
                            <div class="test_paper_content_repeat_top_num" ms-text="'题号：'+ el.sort"></div>
                            <div class="test_paper_content_repeat_top_type">题型：判断题</div>
                            <div class="test_paper_content_repeat_top_date" ms-text="'日期：' + el.created_at"></div>
                            <div class="test_paper_content_repeat_top_grade" ms-text="'难易度：' + el.difficult"></div>
                            <div class="test_paper_content_repeat_top_collect">
                                <i ms-if="el.isCollectQ" class="collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <i ms-if="!el.isCollectQ" class="no_collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <span ms-text="el.isCollectQ ? '取消收藏' : '收藏试题'"></span>
                            </div>
                        </div>
                        <div class="test_paper_content_repeat_cen" ms-shoudetail>
                            <div class="test_paper_content_repeat_cen_question" ms-html="el.title">
                            </div>
                        </div>
                        <div class="test_paper_content_repeat_bot hide">
                            <div class="test_paper_content_repeat_bot_answer">
                                <span class="explain">答案</span>
                                <span class="answer" ms-html="el.answer == 1 ? '对' : '错'"></span>
                            </div>
                            <div class="clear_my"></div>
                            <div class="test_paper_content_repeat_bot_explain">
                                <span class="explain">解析</span>
                                <span class="answer" ms-html="el.analysis"></span>
                            </div>
                        </div>
                        <div class="clear_my"></div>
                    </div>
                    {{-- 填空题 --}}
                    <div class="test_paper_content_repeat_type_2" ms-if="el.type == 4">
                        <div class="test_paper_content_repeat_top">
                            <div class="test_paper_content_repeat_top_num" ms-text="'题号：'+ el.sort"></div>
                            <div class="test_paper_content_repeat_top_type">题型：填空题</div>
                            <div class="test_paper_content_repeat_top_date" ms-text="'日期：' + el.created_at"></div>
                            <div class="test_paper_content_repeat_top_grade" ms-text="'难易度：' + el.difficult"></div>
                            <div class="test_paper_content_repeat_top_collect">
                                <i ms-if="el.isCollectQ" class="collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <i ms-if="!el.isCollectQ" class="no_collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <span ms-text="el.isCollectQ ? '取消收藏' : '收藏试题'"></span>
                            </div>
                        </div>
                        <div class="test_paper_content_repeat_cen" ms-shoudetail>
                            <div class="test_paper_content_repeat_cen_question" ms-html="el.title"></div>
                        </div>
                        <div class="test_paper_content_repeat_bot hide">
                            <div class="test_paper_content_repeat_bot_answer">
                                <span class="explain">答案</span>
                                <span class="answer" ms-html="el.answer"></span>
                            </div>
                            <div class="clear_my"></div>
                            <div class="test_paper_content_repeat_bot_explain">
                                <span class="explain">解析</span>
                                <span class="answer" ms-html="el.analysis"></span>
                            </div>
                        </div>
                        <div class="clear_my"></div>
                    </div>
                    {{-- 解答题 --}}
                    <div class="test_paper_content_repeat_type_4" ms-if="el.type == 5">
                        <div class="test_paper_content_repeat_top">
                            <div class="test_paper_content_repeat_top_num" ms-text="'题号：'+ el.sort"></div>
                            <div class="test_paper_content_repeat_top_type">题型：解答题</div>
                            <div class="test_paper_content_repeat_top_date" ms-text="'日期：' + el.created_at"></div>
                            <div class="test_paper_content_repeat_top_grade" ms-text="'难易度：' + el.difficult"></div>
                            <div class="test_paper_content_repeat_top_collect">
                                <i ms-if="el.isCollectQ" class="collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <i ms-if="!el.isCollectQ" class="no_collect" ms-attr-value="el.id" ms-attr-type="el.type" ms-attr-subjectId="el.subjectId" ms-attr-chapterId="el.chapterId" ms-collectq></i>
                                <span ms-text="el.isCollectQ ? '取消收藏' : '收藏试题'"></span>
                            </div>
                        </div>
                        <div class="test_paper_content_repeat_cen" ms-shoudetail>
                            <div class="test_paper_content_repeat_cen_question" ms-html="el.title"></div>
                        </div>
                        <div class="test_paper_content_repeat_bot hide">
                            <div class="test_paper_content_repeat_bot_answer">
                                <span class="explain">答案</span>
                                <span class="answer" ms-html="el.answer"></span>
                            </div>
                            <div class="clear_my"></div>
                            <div class="test_paper_content_repeat_bot_explain">
                                <span class="explain">解析</span>
                                <span class="answer" ms-html="el.analysis"></span>
                            </div>
                        </div>
                        <div class="clear_my"></div>
                    </div>
                </div>
            </div>
            <div class="clear_my" style="height: 30px;"></div>
        </div>
        <div class="imgZoom hide" ms-class="hide: !showImg">
            <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
            <img ms-attr-src="imgZoom">
        </div>
        <div class="spinner hide" ms-class="hide: paperDisplay">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
        <div class="clear_my" style="height: 80px;"></div>
    </div>
@endsection

@section('js')
    <script>
        require(['/evaluateManageTea/testPaperTea'], function (testPaperTea) {
            testPaperTea.paperId = '{{$id}}' || null;
            testPaperTea.title = '{{$title}}' || null;
            testPaperTea.subjectId = '{{$result->subjectId}}' || null;
            testPaperTea.init();
            avalon.directive("collectq", {
                init: function (binding) {
                    var elem = binding.element;
                    var value = avalon(elem).attr('value');
                    var type = avalon(elem).attr('type');
                    var subjectId = avalon(elem).attr('subjectId');
                    var chapterId = avalon(elem).attr('chapterId');
                    avalon(elem).bind("click", function () {
                        if ($(this).hasClass('no_collect')) {
                            $(this).addClass('collect').removeClass('no_collect');
                        } else {
                            $(this).addClass('no_collect').removeClass('collect');
                        }
                        if ($(this).next('span').html() == '取消收藏') {
                            $(this).next('span').html('收藏试题');
                        } else {
                            $(this).next('span').html('取消收藏');
                        }
                        testPaperTea.collectQuestion(value, type, subjectId, chapterId);
                    })

                }
            });
            avalon.directive('shoudetail', {
                init: function (binding) {
                    var elem = binding.element;
                    avalon(elem).bind("click", function () {
                        if ($(this).parent('div').hasClass('add_border')) {
                            $(this).parent('div').removeClass('add_border').addClass('del_border');
                        } else {
                            $(this).parent('div').removeClass('del_border').addClass('add_border');
                        }
                        if ($(this).next('.test_paper_content_repeat_bot').hasClass('hide')) {
                            $(this).next('.test_paper_content_repeat_bot').removeClass('hide');
                        } else {
                            $(this).next('.test_paper_content_repeat_bot').addClass('hide');
                        }
                    })
                }
            });
            // 答案数字转字母
            avalon.filters.changeCode = function(value){
                return String.fromCharCode(parseInt(value) + 65);
            };
            testPaperTea.getData('/evaluateManageTea/getTestPaperDetail/' + testPaperTea.paperId, 'GET', '', 'paperInfo');
            testPaperTea.getData('/evaluateManageTea/isCollectPaper/' + testPaperTea.paperId, 'GET', '', 'isCollection');
            testPaperTea.getData('/evaluateManageTea/addPaperView', 'POST', {'paperId': testPaperTea.paperId}, '');
            avalon.scan();
        });
    </script>
@endsection