@extends('layouts.layoutHome')

@section('title', '创课课程列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageStu/indexStu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">
@endsection

@section('content')
    <div class="contain_lesson" ms-controller="indexStuController">
        <div class="shadow hide" ms-visible="popValue"></div>
        <div style="height: 40px;width: 100%;"></div>
        <div class="contain_lesson_top">
            <div class="contain_lesson_top_bk">
                <div style="height: 50px;"></div>
                <div class="title">
                    <i></i> 试题搜索
                </div>
                <div class="search">
                    <input type="text" ms-duplex-string="condition" name="search"/><span ms-click="conditionSearch(condition)">搜索</span>
                </div>
            </div>
        </div>
        <div style="height: 40px;width: 100%"></div>
        <div class="option_select">
            <div class="option_select_top">
                <div class="top_active" ms-changecolor ms-click="changeOption('exam')">我的试题</div>
                <div class="top_no_active" ms-changecolor ms-click="changeOption('error')">错题记录</div>
            </div>
            {{-- 我的试题筛选条件 --}}
            <div name="myTest" ms-visible="changeValue == 'exam'">
                <div class="option_select_type">
                    <div class="option_select_type_title">类型</div>
                    <div>
                        <div class="option_select_type_option option_active" name="myTestType" ms-click="selectAll('myTest','type');">全部</div>
                        <div class="option_select_type_option option_no_active" vid="1" flag="myTest" key="type" ms-addcolor>作业</div>
                        <div class="option_select_type_option option_no_active" vid="2" flag="myTest" key="type" ms-addcolor>测验</div>
                    </div>
                </div>
                <div class="option_select_status">
                    <div class="option_select_status_title">状态</div>
                    <div>
                        <div class="option_select_status_option option_active" name="myTestStatus" ms-click="selectAll('myTest','status');">全部</div>
                        <div class="option_select_status_option option_no_active" vid="0" flag="myTest" key="status" ms-addcolor>未完成</div>
                        <div class="option_select_status_option option_no_active" vid="1" flag="myTest" key="status" ms-addcolor>已完成</div>
                    </div>
                </div>
            </div>
            {{-- 我的错题筛选条件 --}}
            <div name="myError" class="hide" ms-visible="changeValue == 'error'">

                <div class="option_select_status">
                    <div class="option_select_status_title">科目</div>
                    <div>
                        <div class="option_select_status_option option_active" name="myErrorSubjectId" ms-click="selectAll('myError','subjectId');">全部</div>
                        <div class="option_select_status_option option_no_active" ms-repeat="subjectInfo" ms-text="el.subjectName" ms-attr-vid="el.id" flag="myError" key="subjectId"
                             ms-addcolor></div>
                    </div>
                </div>
                <div class="option_select_type">
                    <div class="option_select_type_title">类型</div>
                    <div>
                        <div class="option_select_type_option option_active" name="myErrorType" ms-click="selectAll('myError','type');">全部</div>
                        <div class="option_select_type_option option_no_active" vid="1" flag="myError" key="type" ms-addcolor>作业</div>
                        <div class="option_select_type_option option_no_active" vid="2" flag="myError" key="type" ms-addcolor>测验</div>
                    </div>
                </div>
            </div>
        </div>
        <div style="height: 30px;width: 1200px;margin: 0 auto;"></div>
        <div class="content">
            {{-- 我的试题内容 --}}
            <div name="myTestContent" class="my_test" ms-visible="changeValue == 'exam'">
                <div class="content_title">
                    <div class="content_title_num">序号</div>
                    <div class="content_title_title">标题</div>
                    <div class="content_title_question">题数</div>
                    <div class="content_title_attribute">属性</div>
                    <div class="content_title_type">类型</div>
                    <div class="content_title_score">分数</div>
                    <div class="content_title_status">状态</div>
                </div>
                <div class="spinner" ms-if="examLoading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div class="no_info_msg" ms-if="examMsg && !examLoading">暂无数据</div>
                <div class="content_content">
                    <div class="content_content_repeat" ms-repeat="examInfo">
                        <div class="content_content_repeat_num" ms-text="$index + 1"></div>
                        <div class="content_content_repeat_title" ms-text="el.title" ms-click="popUp('startTest', 1, el);"></div>
                        <div class="content_content_repeat_question" ms-text="el.count"></div>
                        <div class="content_content_repeat_attribute" ms-text="el.gradeName + ' ' + el.subjectName + ' ' + el.editionName + ' ' + el.bookName"></div>
                        <div class="content_content_repeat_type" ms-text="el.typeName"></div>
                        <div class="content_content_repeat_score" ms-text="el.score == null ? '一' : el.score"></div>
                        <div class="content_content_repeat_status" ms-text="el.answerId == null ? '未完成' : '已完成'"></div>
                    </div>
                </div>
            </div>
            {{-- 我的错题内容 --}}
            <div name="myTestContent" class="my_test hide" ms-visible="changeValue == 'error'">
                <div class="content_title">
                    <div class="content_title_num">序号</div>
                    <div class="content_title_title">标题</div>
                    <div class="content_title_attribute">属性</div>
                    <div class="content_title_question" style="width: 200px;">错题数</div>
                    <div class="content_title_type" style="width: 150px;">类型</div>
                </div>
                <div class="spinner" ms-if="errorLoading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div class="no_info_msg" ms-if="errorMsg && !errorLoading">暂无数据</div>
                <div class="content_content">
                    <div class="content_content_repeat" ms-repeat="errorInfo">
                        <div class="content_content_repeat_num" ms-text="$index + 1"></div>
                        <a ms-attr-href="'/evaluateManageStu/errorPaper/' + el.id +'/'+ userId">
                            <div class="content_content_repeat_title" ms-text="el.title"></div>
                        </a>
                        <div class="content_content_repeat_attribute" ms-text="el.gradeName + ' ' + el.subjectName + ' ' + el.editionName + ' ' + el.bookName"></div>
                        <div class="content_content_repeat_question" ms-text="el.num" style="width: 200px;"></div>
                        <div class="content_content_repeat_type" ms-text="el.type == 1 ? '作业' : '测验'" style="width: 150px;"></div>
                    </div>
                </div>
            </div>
            {{-- 开始测验弹窗 --}}
            <div name="startTest" class="start_test hide" ms-visible="popValue == 'startTest'">
                <div class="question_detail_title">
                    <div class="question_detail_title_right" ms-click="popUp(false, 2)"></div>
                </div>
                <div class="question_detail_content">
                    <div class="question_detail_content_top" ms-text="popInfo.title"></div>
                    <div class="question_detail_content_cen" ms-text="'提交截止时间：' + popInfo.submitTime"></div>
                    <div ms-if="popInfo.completeTime != 0" class="question_detail_content_bot" ms-text="'完成时限 : ' + popInfo.completeTime + '分钟'"></div>
                    {{--<a ms-if="popInfo.type == 1" target="_blank" ms-attr-href="'/evaluateManageStu/studentNoAnswer/' + popInfo.paperId"><div class="start_btn">开始测验</div></a>--}}
                    <div class="start_btn" ms-click="goToTest(popInfo.type, popInfo.paperId)">开始测验</div>
                    {{--<a ms-if="popInfo.type == 2" target="_blank" ms-attr-href="'/evaluateManageStu/studentTestNoAnswer/' + popInfo.paperId"><div class="start_btn">开始测验</div></a>--}}
                </div>
            </div>
        </div>
        <div class="clear" style="height: 30px;"></div>
        <div ms-if="examDisplay" class="pagecon_parent" ms-visible="changeValue == 'exam'">
            <div class="pagecon page_exam">
                <div id="page_exam"></div>
            </div>
        </div>
        <div ms-if="errorDisplay" class="pagecon_parent" ms-visible="changeValue == 'error'">
            <div class="pagecon page_error">
                <div id="page_error"></div>
            </div>
        </div>
    </div>
    <div class="clear" style="height: 80px;"></div>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script>
        require(['/evaluateManageStu/indexStu'], function (indexStu) {
            indexStu.userId = '{{$userId}}' || null;
            indexStu.getExamInfo(indexStu.userId);
            //获取科目
            indexStu.getData('/evaluateManageStu/getSubjectInfo/' + indexStu.userId, 'GET', '', 'subjectInfo');
            avalon.directive("addcolor", {
                init: function (binding) {
                    var elem = binding.element;
                    avalon(elem).bind("click",function () {
                        var flag = $(this).attr('flag');
                        var key = $(this).attr('key');
                        var value = $(this).attr('vid');
                        if($(this).hasClass('option_active')){
                            if(indexStu[flag][key].length <= 1) return false;
                            $(this).addClass('option_no_active').removeClass('option_active').siblings('div:first-child').addClass('option_no_active').removeClass('option_active');
                            indexStu.delNum(flag, key, value);
                        }else{
                            $(this).addClass('option_active').siblings('div:first-child').addClass('option_no_active').removeClass('option_active');
                            indexStu.addNum(flag, key, value);
                        }
                    })
                }
            });
            avalon.scan();
        });

    </script>
@endsection