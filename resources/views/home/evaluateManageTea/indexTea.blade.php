@extends('layouts.layoutHome')

@section('title', '创课课程列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageTea/indexTea.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">
@endsection

@section('content')
    <div class="contain_lesson" ms-controller="indexTeaController">
        <div class="shadow hide"></div>
        <div style="height: 40px;width: 100%;"></div>
        <div class="contain_lesson_top hide" ms-class="hide: nsb">
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
        <div class="option_select hide" ms-class="hide: nsb">
            <div class="option_select_top">
                <div ms-class-1="top_active: changeValue == 'onlineQuestion'" ms-class-2="top_no_active: changeValue != 'onlineQuestion'" ms-changecolor ms-click="changeOption('onlineQuestion');">在线题库</div>
                <div ms-class-1="top_active: changeValue == 'testDecorated'" ms-class-2="top_no_active: changeValue != 'testDecorated'" ms-changecolor ms-click="changeOption('testDecorated');">试题布置</div>
                <div ms-class-1="top_active: changeValue == 'questionCorrect'" ms-class-2="top_no_active: changeValue != 'questionCorrect'" ms-changecolor ms-click="changeOption('questionCorrect');">试题批改</div>
                <div ms-class-1="top_active: changeValue == 'queryResult'" ms-class-2="top_no_active: changeValue != 'queryResult'" ms-changecolor ms-click="changeOption('queryResult');">成绩查询</div>
            </div>
        </div>
        <div class="option_select2 hide" ms-class="hide: nsb">
            {{-- 在线题库筛选条件 --}}
            <div ms-visible="changeValue == 'onlineQuestion'">
                <div class="option_select_subject">
                    <div class="option_select_subject_title">年级</div>
                    <div>
                        <div class="option_select_subject_option option_active" name="onlineGradeId" ms-click="selectAll('online','gradeId');">全部</div>
                        <div class="option_select_subject_option option_no_active" ms-repeat="gradeInfo" ms-text="el.gradeName" ms-attr-vid="el.id" flag="online" key="gradeId" ms-addcolor></div>
                    </div>
                </div>
                <div class="option_select_class">
                    <div class="option_select_class_title">科目</div>
                    <div>
                        <div class="option_select_class_option option_active" name="onlineSubjectId" ms-click="selectAll('online','subjectId');">全部</div>
                        <div class="option_select_class_option option_no_active" ms-repeat="subjectInfo" ms-text="el.subjectName" ms-attr-vid="el.id" flag="online" key="subjectId" ms-addcolor></div>
                    </div>
                </div>
                <div class="option_select_type">
                    <div class="option_select_type_title">类型</div>
                    <div>
                        <div class="option_select_type_option option_active" name="onlineType" ms-click="selectAll('online','type');">全部</div>
                        <div class="option_select_type_option option_no_active" vid="0" flag="online" key="type" ms-addcolor>作业</div>
                        <div class="option_select_type_option option_no_active" vid="1" flag="online" key="type" ms-addcolor>测验</div>
                    </div>
                </div>
            </div>

            {{-- 试题布置筛选条件 --}}
            <div class="hide" ms-visible="changeValue == 'testDecorated'">
                <div class="option_select_subject">
                    <div class="option_select_subject_title">年级</div>
                    <div>
                        <div class="option_select_subject_option option_active" name="testGradeId" ms-click="selectAll('test', 'gradeId')">全部</div>
                        <div class="option_select_subject_option option_no_active" ms-repeat="teacherGrade" ms-text="el.gradeName" ms-attr-vid="el.id" flag="test" key="gradeId"
                             ms-addcolor></div>
                    </div>
                </div>
                <div class="option_select_class">
                    <div class="option_select_class_title">科目</div>
                    <div>
                        <div class="option_select_class_option option_active" name="testSubjectId" ms-click="selectAll('test','subjectId');">全部</div>
                        <div class="option_select_class_option option_no_active" ms-repeat="teacherSubject" ms-text="el.subjectName" ms-attr-vid="el.id" flag="test" key="subjectId"
                             ms-addcolor></div>
                    </div>
                </div>
                <div class="option_select_type">
                    <div class="option_select_type_title">类型</div>
                    <div>
                        <div class="option_select_type_option option_active" name="testType" ms-click="selectAll('test','type');">全部</div>
                        <div class="option_select_type_option option_no_active" vid="0" flag="test" key="type" ms-addcolor>作业</div>
                        <div class="option_select_type_option option_no_active" vid="1" flag="test" key="type" ms-addcolor>测验</div>
                    </div>
                </div>
                <div class="add_decorated">
                    <span ms-click="showQuestion('test_decorated_pop', 1);">新增布置</span>
                </div>
            </div>

            {{-- 试题批改筛选条件 --}}
            <div class="hide" name="questionCorrect" ms-visible="changeValue == 'questionCorrect'">
                <div class="option_select_subject">
                    <div class="option_select_subject_title">学科</div>
                    <div>
                        <div class="option_select_subject_option option_active" name="questionSubjectId" ms-click="selectAll('question','subjectId');">全部</div>
                        <div class="option_select_subject_option option_no_active" ms-repeat="teacherSubject" ms-text="el.subjectName" ms-attr-vid="el.id" flag="question"
                             key="subjectId" ms-addcolor></div>
                    </div>
                </div>
                <div class="option_select_class">
                    <div class="option_select_class_title">班级</div>
                    <div>
                        <div class="option_select_class_option option_active" name="questionClassId" ms-click="selectAll('question','classId');">全部</div>
                        <div class="option_select_class_option option_no_active" ms-repeat="teacherClass" ms-text="el.gradeName + el.classname" ms-attr-vid="el.id" flag="question" key="classId"
                             ms-addcolor></div>
                    </div>
                </div>
                {{--<div class="option_select_status">--}}
                {{--<div class="option_select_status_title">状态</div>--}}
                {{--<div>--}}
                {{--<div class="option_select_status_option option_active" name="questionStatus" ms-click="selectAll('question','status');">全部</div>--}}
                {{--<div class="option_select_status_option option_no_active" vid="0" flag="question" key="status" ms-addcolor>未完成</div>--}}
                {{--<div class="option_select_status_option option_no_active" vid="1" flag="question" key="status" ms-addcolor>已完成</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="option_select_type">
                    <div class="option_select_type_title">类型</div>
                    <div>
                        <div class="option_select_type_option option_active" name="questionType" ms-click="selectAll('question','type');">全部</div>
                        <div class="option_select_type_option option_no_active" vid="1" flag="question" key="type" ms-addcolor>作业</div>
                        <div class="option_select_type_option option_no_active" vid="2" flag="question" key="type" ms-addcolor>测验</div>
                    </div>
                </div>
            </div>

            {{-- 成绩查询筛选条件 --}}
            <div class="hide" ms-visible="changeValue == 'queryResult'">
                <div class="option_select_subject">
                    <div class="option_select_subject_title">学科</div>
                    <div>
                        <div class="option_select_subject_option option_active" name="querySubjectId" ms-click="selectAll('query','subjectId');">全部</div>
                        <div class="option_select_subject_option option_no_active" ms-repeat="teacherSubject" ms-text="el.subjectName" ms-attr-vid="el.id" flag="query"
                             key="subjectId"
                             ms-addcolor></div>
                    </div>
                </div>
                <div class="option_select_class">
                    <div class="option_select_class_title">班级</div>
                    <div>
                        <div class="option_select_class_option option_active" name="queryClassId" ms-click="selectAll('query','classId');">全部</div>
                        <div class="option_select_class_option option_no_active" ms-repeat="teacherClass" ms-text="el.gradeName + el.classname" ms-attr-vid="el.id" flag="query" key="classId"
                             ms-addcolor></div>
                    </div>
                </div>
                <div class="option_select_type">
                    <div class="option_select_type_title">类型</div>
                    <div>
                        <div class="option_select_type_option option_active" name="queryType" ms-click="selectAll('query','type');">全部</div>
                        <div class="option_select_type_option option_no_active" vid="1" flag="query" key="type" ms-addcolor>作业</div>
                        <div class="option_select_type_option option_no_active" vid="2" flag="query" key="type" ms-addcolor>测验</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hide" style="height: 50px;width: 1200px;margin: 0 auto;" ms-class="hide: nsb">
            <div class="sort" ms-visible="changeValue == 'onlineQuestion'">
                <span class="hot" ms-class="option_active:colorOption" ms-click="changeSort(1)">热门</span> - <span class="new" ms-class="option_active:!colorOption" ms-click="changeSort(2)">最新</span>
            </div>
        </div>
        <div class="content">
            {{-- 在线题库内容 --}}
            <div class="online_question hide" ms-class="hide: nsb" ms-visible="changeValue == 'onlineQuestion'">
                <div class="content_title">
                    <div class="content_title_num">序号</div>
                    <div class="content_title_title">试卷标题</div>
                    <div class="content_title_attribute">试卷属性</div>
                    <div class="content_title_question">题数</div>
                    <div class="content_title_view">浏览</div>
                    <div class="content_title_author">发布人</div>
                    <div class="content_title_time">发布日期</div>
                </div>
                <div class="spinner" ms-if="onlineLoading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div class="no_info_msg" ms-if="onlineMsg && !onlineLoading">暂无数据</div>
                <div class="content_content">
                    <div class="content_content_repeat" ms-repeat="onlineInfo">
                        <div class="content_content_repeat_num" ms-text="$index + 1"></div>
                        <a ms-attr-href="'/evaluateManageTea/testPaperTea/' + el.id" target="_blank">
                            <div class="content_content_repeat_title" ms-text="el.title"></div>
                        </a>
                        <div class="content_content_repeat_attribute" ms-text="el.gradeName + ' ' + el.subjectName + ' ' + el.editionName + ' ' + el.bookName"></div>
                        <div class="content_content_repeat_question" ms-text="el.count"></div>
                        <div class="content_content_repeat_view" ms-text="el.paperView"></div>
                        <div class="content_content_repeat_author" ms-text="el.username"></div>
                        <div class="content_content_repeat_time" ms-text="el.created_at"></div>
                    </div>
                </div>
                <div class="clear_my" style="height: 30px;"></div>
                <div class="pagecon_parent" ms-if="onlineDisplay">
                    <div class="pagecon page_online">
                        <div id="page_online"></div>
                    </div>
                </div>
            </div>
            {{-- 试题布置内容 --}}
            <div class="test_decorated hide" ms-visible="changeValue == 'testDecorated'">
                <div class="content_title">
                    <div class="content_title_num">序号</div>
                    <div class="content_title_title">试卷标题</div>
                    <div class="content_title_attribute">试卷属性</div>
                    <div class="content_title_question">题数</div>
                    <div class="content_title_view">浏览</div>
                    <div class="content_title_time">发布日期</div>
                </div>
                <div class="spinner" ms-if="testLoading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div class="no_info_msg" ms-if="testMsg && !testLoading">暂无数据</div>
                <div class="content_content">
                    <div class="content_content_repeat" ms-repeat="testInfo">
                        <div class="content_content_repeat_num" ms-text="el.id"></div>
                        <a ms-attr-href="'/evaluateManageTea/testPaperTea/' + el.id" target="_blank">
                            <div class="content_content_repeat_title" ms-text="el.title"></div>
                        </a>
                        <div class="content_content_repeat_attribute" ms-text="el.gradeName + ' ' + el.subjectName + ' ' + el.editionName + ' ' + el.bookName"></div>
                        <div class="content_content_repeat_question" ms-text="el.count"></div>
                        <div class="content_content_repeat_view" ms-text="el.paperView"></div>
                        <div class="content_content_repeat_time" ms-text="el.created_at"></div>
                    </div>
                </div>
                <div class="clear_my" style="height: 30px;"></div>
                <div class="pagecon_parent" ms-if="testDisplay">
                    <div class="pagecon page_test">
                        <div id="page_test"></div>
                    </div>
                </div>
            </div>
            {{-- 试题批改内容 --}}
            <div class="question_correct hide" ms-visible="changeValue == 'questionCorrect'">
                <div class="content_title">
                    <div class="content_title_num">序号</div>
                    <div class="content_title_title">标题</div>
                    <div class="content_title_number">题数</div>
                    <div class="content_title_class">下发班级</div>
                    <div class="content_title_status">状态</div>
                    <div class="content_title_type">类型</div>
                    <div class="content_title_time">发布日期</div>
                </div>
                <div class="spinner" ms-if="questionLoading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div class="no_info_msg" ms-if="questionMsg && !questionLoading">暂无数据</div>
                <div class="content_content">
                    <div class="content_content_repeat" ms-repeat="questionInfo">
                        <div class="content_content_repeat_num" ms-text="el.id"></div>
                        <div class="content_content_repeat_title" ms-text="el.title" ms-click="showPaper('question_correct', el.id, el.classId, 1);"></div>
                        <div class="content_content_repeat_number" ms-text="el.count"></div>
                        <div class="content_content_repeat_class" ms-text="el.gradeName + el.classname"></div>
                        <div class="content_content_repeat_status" ms-text="el.isComplete ? '已完成' : '未完成'"></div>
                        <div class="content_content_repeat_type" ms-text="el.typeName"></div>
                        <div class="content_content_repeat_time" ms-text="el.created_at"></div>
                    </div>
                </div>
                <div class="clear_my" style="height: 30px;"></div>
                <div class="pagecon_parent" ms-if="questionDisplay">
                    <div class="pagecon page_question">
                        <div id="page_question"></div>
                    </div>
                </div>
            </div>
            {{-- 成绩查询内容 --}}
            <div class="query_result hide" ms-visible="changeValue == 'queryResult'">
                <div class="content_title">
                    <div class="content_title_num">序号</div>
                    <div class="content_title_title">标题</div>
                    <div class="content_title_question">题数</div>
                    <div class="content_title_view">下发班级</div>
                    <div class="content_title_type">类型</div>
                    <div class="content_title_time">发布日期</div>
                </div>
                <div class="spinner" ms-if="queryLoading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div class="no_info_msg" ms-if="queryMsg && !queryLoading">暂无数据</div>
                <div class="content_content">
                    <div class="content_content_repeat" ms-repeat="queryInfo">
                        <div class="content_content_repeat_num" ms-text="el.id"></div>
                        <a ms-attr-href="'/evaluateManageTea/statistic/' + el.id" target="_blank">
                            <div class="content_content_repeat_title" ms-text="el.title"></div>
                        </a>
                        <div class="content_content_repeat_question" ms-text="el.count"></div>
                        <div class="content_content_repeat_view" ms-text="el.gradeName + el.className"></div>
                        <div class="content_content_repeat_type" ms-text="el.typeName"></div>
                        <div class="content_content_repeat_time" ms-text="el.created_at"></div>
                    </div>
                </div>
                <div class="clear_my" style="height: 30px;"></div>
                <div class="pagecon_parent" ms-if="queryDisplay">
                    <div class="pagecon page_query">
                        <div id="page_query"></div>
                    </div>
                </div>
            </div>
            {{-- 试题批改试卷内容 --}}
            <div class="question_correct_detail hide">
                <div class="content_title">问答题</div>
                <div style="width: 100%;height: 10px;"></div>
                <div class="content_table_title">
                    <div class="content_table_title_first">
                        <div class="content_table_title_first_num">题号</div>
                        <div class="content_table_title_first_name">姓名</div>
                    </div>
                    <div class="content_table_number_repeat">
                        <div class="content_table_number" ms-repeat="num" ms-text="el"></div>
                    </div>
                </div>
                <div class="content_table_content">
                    <div class="content_table_content_repeat" ms-repeat-a="questionCorrectDetail">
                        <div class="content_table_content_repeat_name" ms-text="a.username" ms-click="goStudentPaper(a.id, a.type, a.paperId)"></div>
                        <div ms-repeat="a.answer" class="content_table_content_repeat_answer">
                            <span class="see_detail" ms-if="!el.score && el.answer" ms-click="showQuestion('question_detail', 1, el, a.answerId);">查看</span>
                            <span class="score_green" ms-if="el.score && el.answer" ms-click="showQuestion('question_detail', 1, el, a.answerId);" ms-text="el.score + '分'"></span>
                            <span ms-if="!el.score && !el.answer">一</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 试题批改某试题弹窗 --}}
            <div class="question_detail hide">
                <div class="question_detail_title">
                    <div class="question_detail_title_left" ms-text="'题号：' + subjective.index + '(' + subjective.score + '分)'"></div>
                    <div class="question_detail_title_right" ms-click="showQuestion('question_detail', 2);"></div>
                </div>
                <div class="question_detail_content">
                    <div class="question_detail_content_top" ms-html="subjective.title">
                    </div>
                    <div class="question_detail_content_cen">
                        <div class="left">回答：</div>
                        <div class="right" ms-html="subjective.answer"></div>
                    </div>
                    <div class="clear_my"></div>
                    <div class="question_detail_content_bot">
                        <div class="left">答案：</div>
                        <div class="right" ms-html="subjective.trueAnswer"></div>
                    </div>
                    <div class="clear_my"></div>
                </div>
                <div class="clear_my"></div>
                <div class="question_detail_bot">
                    <div class="question_detail_score">
                        <div class="question_detail_score_top">
                            评分：<input type="number" ms-duplex-number="submitScore.score"/> 分
                        </div>
                        <div class="question_detail_score_bot">
                            <span>评语：</span>
                            <div class="hah">
                                <textarea ms-duplex-string="submitScore.comment" maxlength="50"></textarea>
                                <div ms-text="(submitScore.comment).length"></div>
                            </div>
                        </div>
                        <div class="question_detail_score_submit" ms-click="submitSubject(subjective.index, subjective.score, submitScore.comment)">
                            完成
                        </div>
                    </div>
                </div>
            </div>
            {{-- 试题布置第一步弹窗 --}}
            <div class="test_decorated_pop hide">
                <div class="test_decorated_pop_top">
                    <div class="test_decorated_pop_top_title">新增布置</div>
                    <div class="test_decorated_pop_top_close" ms-click="showQuestion('test_decorated_pop', 2);"></div>
                </div>
                <div class="test_decorated_pop_center">
                    <div style="width: 100%;height: 60px;"></div>
                    <div class="test_decorated_pop_center_1">
                        <div>课程归属</div>
                        <select ms-duplex="lessonTypeCheck" data-duplex-changed="selectType">
                            <option value="">-- 请选择 --</option>
                            <option ms-repeat="lessonType" ms-attr-value="el.data" ms-text="el.id"></option>
                        </select>
                    </div>
                    <span class="warning_msg" ms-if="typeWarn">请选择课程归属</span>
                    <div class="test_decorated_pop_center_2">
                        <div>所属章节</div>
                        <select ms-duplex="lessonChapterCheck" data-duplex-changed="selectChapter">
                            <option value="0">-- 请选择 --</option>
                            <option ms-repeat="lessonChapter" ms-attr-value="el.id" ms-text="el.chapterName"></option>
                        </select>
                    </div>
                    <span class="warning_msg" ms-if="chapterWarn">请选择所属章节</span>
                    <div class="test_decorated_pop_center_3">
                        <div>试卷标题</div>
                        <input type="text" ms-duplex-string="lessonTitle" />
                    </div>
                    <span class="warning_msg" ms-if="titleWarn">请填写试卷标题</span>
                </div>
                <div class="clear_my" style="height: 135px;"></div>
                <div class="test_decorated_pop_bot">
                    <div ms-click="choosePaper('test_decorated_pop', 'choose_paper_pop');">下一步</div>
                </div>
            </div>
            {{-- 试题布置第二步弹窗 --}}
            <div class="choose_paper_pop hide">
                <div class="choose_paper_pop_top">
                    <div class="choose_paper_pop_top_title">
                        <div ms-class="option_checked:blueOption" ms-click="getPaperInfo(1)">平台题库</div>
                        <div ms-class="option_checked:!blueOption" ms-click="getPaperInfo(2)">我的收藏</div>
                    </div>
                    <div class="choose_paper_pop_top_close" ms-click="showQuestion('choose_paper_pop', 2);"></div>
                </div>
                <div class="choose_paper_pop_center">
                    <div class="choose_paper_pop_center_repeat" ms-repeat="paperInfo">
                        <a ms-attr-href="'/evaluateManageTea/paperDetail/' + el.id" target="_blank">
                            <div class="choose_paper_pop_center_repeat_title" ms-text="el.title"></div>
                        </a>
                        <div class="choose_paper_pop_center_repeat_author" ms-text="'上传者：' + el.username"></div>
                        <div class="choose_paper_pop_center_repeat_time" ms-text="'上传时间：' + el.created_at"></div>
                        <a ms-attr-href="'/evaluateManageTea/editPaper/' + lessonTemp + '/' + el.id">
                            <div class="choose_paper_pop_center_repeat_icon" ms-attr-id="el.id"></div>
                        </a>
                    </div>
                </div>
                <div class="choose_paper_pop_bot">
                    <a ms-attr-href="'/evaluateManageTea/editPaper/' + lessonTemp + '/0'">
                        <div>+新建试卷</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="clear_my" style="height: 80px;"></div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script>
        require(['/evaluateManageTea/indexTea'], function (indexTea) {
            indexTea.teacherId = '{{$teacherId}}' || null;
            indexTea.getData('/evaluateManageTea/getGradeInfo', 'GET', '', 'gradeInfo');

            indexTea.getData('/evaluateManageTea/getSubjectInfo', 'GET', '', 'subjectInfo');

            indexTea.getData('/evaluateManageTea/getClassInfo', 'GET', '', 'classInfo');

            indexTea.getData('/evaluateManageTea/getTeacherInfo', 'POST', {teacherId: indexTea.teacherId}, 'teacherInfo');

            indexTea.getData('/evaluateManageTea/getLessonType', 'POST', {id: indexTea.teacherId}, 'lessonType');
            if (window.location.hash.split('#').length == 4) {
                indexTea.nsb = true;
                indexTea.changeValue = '';
                indexTea.hashInfo = window.location.hash.split('#');
                if ((indexTea.hashInfo[1] == 'questionCorrect') && (window.location.hash.split('#').length == 4)) {
                    $('.question_correct').css('display', 'none');
                    $('div[name="questionCorrect"]').css('display', 'none');
                    $('.question_correct_detail').css('display', 'block');
                    indexTea.getData('/evaluateManageTea/getQuestionCorrectDetail/' + indexTea.hashInfo[2] + '/' + indexTea.hashInfo[3], 'GET', '', 'questionCorrectDetail');
                }
            }else {
                indexTea.changeValue = window.location.hash ? window.location.hash.split('#')[1] : 'onlineQuestion';
                switch (indexTea.changeValue) {
                    case 'onlineQuestion':
                        if (!indexTea.onlineInfo.length) {
                            indexTea.getOnlineInfo();
                        }
                        break;
                    case 'testDecorated':
                        if (!indexTea.testInfo.length) {
                            indexTea.getTestInfo();
                        }
                        break;
                    case 'questionCorrect':
                        if (!indexTea.questionInfo.length) {
                            indexTea.getQuestionInfo();
                        }
                        break;
                    case 'queryResult':
                        if (!indexTea.queryInfo.length) {
                            indexTea.getQueryInfo();
                        }
                        break;
                }
            }
            avalon.directive("addcolor", {
                init: function (binding) {
                    var elem = binding.element;
                    avalon(elem).bind("click", function () {
                        var flag = $(this).attr('flag');
                        var key = $(this).attr('key');
                        var value = $(this).attr('vid');
                        if ($(this).hasClass('option_active')) {
                            if (indexTea[flag][key].length <= 1) return false;
                            $(this).addClass('option_no_active').removeClass('option_active').siblings('div:first-child').addClass('option_no_active').removeClass('option_active');
                            indexTea.delNum(flag, key, value);
                        } else {
                            $(this).addClass('option_active').siblings('div:first-child').addClass('option_no_active').removeClass('option_active');
                            indexTea.addNum(flag, key, value);
                        }
                    })
                }
            });
            avalon.scan();
        });
    </script>
@endsection