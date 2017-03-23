@extends('layouts.layoutHome')

@section('title', '课程中心')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/teacherCourse/detail.css') }}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageTea/statistic.css')}}">--}}

@endsection

@section('content')

    <div style="height:40px"></div>
    <div class="content" ms-controller="teacherCourseDetail">
        <div class="shadow hide"></div>
        {{--左部分--}}
        <div class="content_left">
            <div class="content_left_top">
                <div class="content_left_top_img">
                    <img src="{{asset('home/image/teacherCourse/1.png')}}" alt="" width="221px" height="166px">
                </div>
            </div>
            <span class="span_hover"></span>
            <div class="courseintro course_back" ms-click="tabs('intro')">课程介绍</div>
            <span class="span_hover"></span>
            <div class="courselist course_back" ms-click="tabs('list')">课程目录</div>
            <span class="span_hover"></span>
            <div class="synchrotest course_back" ms-click="tabs('test')">同步测验</div>
            <span class="span_hover"></span>
            <div class="coursenote course_back" ms-click="tabs('note')">随堂笔记</div>
            <span class="span_hover"></span>
            <div class="coursequestion course_back" ms-click="tabs('question')">课程问答</div>
            @if($teacherId == \Auth::user()->id)
            <span class="span_hover"></span>
            <div class="coursestudy course_back" ms-click="tabs('study')">学习情况</div>
            @endif
        </div>
        {{--右侧部分--}}
        <div class="content_right" ms-controller="synchroTest">
            <div class="content_right_courseintro">
                <div class="courseintro_top" ms-if="currentIndex != 'study'">
                    <div class="courseintro_top_detail">
                        <div class="courseintro_top_detail_title">{{$data->courseTitle}}</div>
                        <div class="courseintro_top_detail_author">{{$data->username}}</div>
                        <div class="courseintro_top_detail_subject">{{$data->subjectName}}</div>
                        <div class="courseintro_top_detail_version">{{$data->editionName}}</div>
                        <div class="courseintro_top_detail_grade">{{$data->gradeName}}</div>
                        <div class="courseintro_top_detail_book">{{$data->bookname}}</div>
                        <div class="courseintro_top_detail_collect">收藏课程</div>
                        @if(!isset($isset_courseFav))
                            <div class="courseintro_top_detail_img " name="{{$data->id}}">

                            </div>
                            <div class="courseintro_top_detail_img_del hide " name="{{$data->id}}">

                            </div>
                        @else
                            <div class="courseintro_top_detail_img hide" name="{{$data->id}}">

                            </div>
                            <div class="courseintro_top_detail_img_del  " name="{{$data->id}}">

                            </div>
                        @endif
                    </div>
                    <div style="clear: both"></div>
                </div>

                {{--课程介绍--}}
                <div class="content_right_courseintro_tabs " ms-visible="currentIndex=='intro'">
                    <div class="course_intro_title">
                        课程介绍
                    </div>
                    <div style="height:26px"></div>
                    <div class="content_right_content">
                        {{--知识点--}}
                        <div class="content_right_content_point_title">知识点 :</div>
                        <div style="height:8px;"></div>
                        <div class="content_right_content_point"> {{$data->courseIntro}}</div>
                        <div style="height:35px"></div>
                        @if($data->type == 2)
                            <div class="content_right_content_point_title">教案信息 :</div>
                            <div style="height:8px;"></div>

                            <div class="content_right_content_information"> {!!$data->courseContent!!}</div>
                        @endif
                    </div>
                </div>
                {{--课程目录--}}
                <div class="content_right_courselist_tabs none" ms-visible="currentIndex=='list'">
                    <div class="content_right_synchrotest_top">课程目录</div>
                    <div class="content_right_synchrotest_one" ms-if="CourseChapter.duidance.size()">
                        <div class="content_right_synchrotest_one_top">
                            <div class="content_right_synchrotest_one_top_1"></div>
                            <div class="content_right_synchrotest_one_top_2">第一部分</div>
                            <div class="content_right_synchrotest_one_top_3">课前导学</div>
                        </div>
                        <div class="content_right_synchrotest_one_repeat" ms-if-loop="el.courseType == 0" ms-repeat="CourseChapter.duidance">
                            <div class="content_right_synchrocatalog_one_repeat_img"></div>
                            <div class="content_right_synchrocatalog_one_repeat_left" ms-html="'1.' +( $index + 1)"></div>
                            <div class="content_right_synchrocatalog_one_repeat_center"><a ms-attr-href="'/teacherCourse/teaCatalog/' + courseId + '/' + el.id" ms-html="el.title"></a></div>
                            <div class="content_right_synchrocatalog_one_repeat_right">
                                <a class="content_right_synchrocatalogtest_two_repeat_righta a" ms-class="b:item.icontype" ms-repeat-item="el.chapter"></a>
                            </div>
                        </div>

                        <div class="content_right_synchrotest_one_repeat" ms-repeat="leadLearnInfo">
                            <div class="content_right_synchrotest_one_repeat_left" ms-text="el.title"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="!el.submitTime && !el.completeTime && (el.type == 2)" ms-text="'暂无截止日期与完成时限'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="!el.submitTime && (el.type == 1)" ms-text="'暂无截止日期'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="el.submitTime && el.completeTime && (el.type == 2)" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="el.submitTime && (el.type == 1)" ms-text="'截止日期' + el.submitTime"></div>
                            <div class="content_right_synchrotest_one_repeat_right_2" ms-if="isAuthor" ms-click="seeScore(true, el.isAnswer, el.paperId)">查看成绩</div>
                            <div class="content_right_synchrotest_one_repeat_right_3" ms-if="!isAuthor"></div>
                            <div class="content_right_synchrotest_one_repeat_right" ms-click="showPaper(1, el);">进去测试</div>
                        </div>

                    </div>
                    <div class="content_right_synchrotest_two" ms-if="CourseChapter.teaching.size()">
                        <div class="content_right_synchrotest_two_top">
                            <div class="content_right_synchrotest_two_top_1"></div>
                            <div class="content_right_synchrotest_two_top_2">第二部分</div>
                            <div class="content_right_synchrotest_two_top_3">课堂授课</div>
                        </div>
                        <div class="" ms-if-loop="el.courseType == 1" ms-repeat="CourseChapter.teaching">
                            <div class="content_right_synchrotest_two_repeat">
                                <div class="content_right_synchrocatalog_two_repeat_left" style="margin-left: 55px;" ms-html="'2.' +( $index + 1)"></div>
                                <div class="content_right_synchrocatalog_two_repeat_center" ms-html="el.title"></div>
                                <div class="content_right_synchrocatalog_two_repeat_right">
                                    <a class="content_right_synchrocatalogtest_two_repeat_righta"></a>
                                    {{--<a class="content_right_synchrocatalogtest_two_repeat_righta a" href="{{url('/studentCourse/stuCatalog/'.$data->id)}}" ms-class="b:item.icontype" ms-repeat-item="el.chapter"></a>--}}
                                </div>
                            </div>
                            <div class="content_right_synchrotest_two_repeat" ms-repeat-chap="el.chapter">
                                <div class="content_right_synchrocatalog_one_repeat_left_img"></div>
                                <div class="content_right_synchrocatalogtest_two_repeat_left" ms-html="('2.' +( $outer.$index + 1)) + '.' + ($index + 1)"></div>
                                <div class="content_right_synchrocatalogtest_two_repeat_center"><a ms-attr-href="'/teacherCourse/teaCatalog/' + courseId + '/' + chap.id"
                                                                                                   ms-html="chap.title"></a></div>
                                <div class="content_right_synchrocatalogtest_two_repeat_right">
                                    <a class="content_right_synchrocatalogtest_two_repeat_righta a" ms-class="b:node.icontype" ms-repeat-node="chap.node"></a>
                                    {{--<div class="content_right_synchrocatalogtest_two_repeat_right test">进去测试</div>--}}
                                </div>
                            </div>

                            {{--<div class="content_right_synchrotest_two_repeat">--}}
                            {{--<div class="content_right_synchrocatalogtest_two_repeat_left">2.12</div>--}}
                            {{--<div class="content_right_synchrocatalogtest_two_repeat_center">本章测试&nbsp;&nbsp;&nbsp;<span style="color: rgb(153, 153, 153);;">截止日期2017-02-14 15:14:37</span></div>--}}
                            {{--<div class="content_right_synchrocatalogtest_two_repeat_right test">进去测试</div>--}}
                            {{--</div>--}}
                        </div>

                        <div class="content_right_synchrotest_one_repeat" ms-repeat="classTeachInfo">
                            <div class="content_right_synchrotest_one_repeat_left" ms-text="el.title"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="!el.submitTime && !el.completeTime && (el.type == 2)" ms-text="'暂无截止日期与完成时限'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="!el.submitTime && (el.type == 1)" ms-text="'暂无截止日期'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="el.submitTime && el.completeTime && (el.type == 2)" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="el.submitTime && (el.type == 1)" ms-text="'截止日期' + el.submitTime"></div>
                            <div class="content_right_synchrotest_two_repeat_right_2" ms-if="isAuthor" ms-click="seeScore(true, el.isAnswer, el.paperId)">查看成绩</div>
                            <div class="content_right_synchrotest_two_repeat_right_3" ms-if="!isAuthor"></div>
                            <div class="content_right_synchrotest_one_repeat_right" ms-click="showPaper(2, el);">进去测试</div>
                        </div>

                    </div>
                    <div class="content_right_synchrotest_three" ms-if="CourseChapter.guidance.size()">
                        <div class="content_right_synchrotest_three_top">
                            <div class="content_right_synchrotest_three_top_1"></div>
                            <div class="content_right_synchrotest_three_top_2">第三部分</div>
                            <div class="content_right_synchrotest_three_top_3">课后指导</div>
                        </div>
                        <div class="content_right_synchrotest_three_repeat" ms-if-loop="el.courseType == 2" ms-repeat="CourseChapter.guidance">
                            <div class="content_right_synchrocatalog_one_repeat_img"></div>
                            <div class="content_right_synchrocatalog_three_repeat_left" ms-html="'3.' +( $index + 1)">3.1</div>
                            <div class="content_right_synchrocatalog_three_repeat_center"><a ms-attr-href="'/teacherCourse/teaCatalog/' + courseId + '/' + el.id"
                                                                                             ms-html="el.title"></a></div>
                            <div class="content_right_synchrocatalog_three_repeat_right">
                                <a class="content_right_synchrocatalogtest_two_repeat_righta a" ms-class="b:item.icontype" ms-repeat-item="el.chapter"></a>
                            </div>
                        </div>

                        <div class="content_right_synchrotest_one_repeat" ms-repeat="afterClassInfo">
                            <div class="content_right_synchrotest_one_repeat_left" ms-text="el.title"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="!el.submitTime && !el.completeTime && (el.type == 2)" ms-text="'暂无截止日期与完成时限'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="!el.submitTime && (el.type == 1)" ms-text="'暂无截止日期'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="el.submitTime && el.completeTime && (el.type == 2)" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" ms-if="el.submitTime && (el.type == 1)" ms-text="'截止日期' + el.submitTime"></div>
                            <div class="content_right_synchrotest_three_repeat_right_2" ms-if="isAuthor" ms-click="seeScore(true, el.isAnswer, el.paperId)">查看成绩</div>
                            <div class="content_right_synchrotest_three_repeat_right_3" ms-if="!isAuthor"></div>
                            <div class="content_right_synchrotest_one_repeat_right" ms-click="showPaper(3, el);">进去测试</div>
                        </div>

                    </div>
                    {{-- ========================================= 成绩提示窗口 ========================================= --}}
                    <div class="notice hide">
                        <div class="top">提示</div>
                        <div class="cen">尚无成绩记录</div>
                        <div class="bot" ms-click="seeScore(false)">知道了</div>
                    </div>
                    {{-- ========================================= 成绩提示窗口 ========================================= --}}
                </div>
                {{--同步测验--}}
                <div class="content_right_synchrotest_tabs none" ms-visible="currentIndex=='test'">
                    <div>
                        <div class="content_right_synchrotest_top">同步测验</div>
                        <div class="no_data_show" ms-if="(!leadLearnInfo.size()) && (!classTeachInfo.size()) && (!afterClassInfo.size())">暂无同步测验内容</div>
                        <div class="content_right_synchrotest_one" ms-if="leadLearnInfo.size()">
                            <div class="content_right_synchrotest_one_top">
                                <div class="content_right_synchrotest_one_top_1"></div>
                                <div class="content_right_synchrotest_one_top_2">第一部分</div>
                                <div class="content_right_synchrotest_one_top_3">课前导学</div>
                            </div>
                            <div class="content_right_synchrotest_one_repeat" ms-repeat="leadLearnInfo">
                                <div class="content_right_synchrotest_one_repeat_left" ms-attr-title="el.title" ms-text="el.title"></div>
                                <div class="content_right_synchrotest_one_repeat_center" ms-if="!el.submitTime && !el.completeTime && (el.type == 2)" ms-text="'暂无截止日期与完成时限'"></div>
                                <div class="content_right_synchrotest_one_repeat_center" ms-if="!el.submitTime && (el.type == 1)" ms-text="'暂无截止日期'"></div>
                                <div class="content_right_synchrotest_one_repeat_center" ms-if="el.submitTime && el.completeTime && (el.type == 2)" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                                <div class="content_right_synchrotest_one_repeat_center" ms-if="el.submitTime && (el.type == 1)" ms-text="'截止日期' + el.submitTime"></div>
                                <div class="content_right_synchrotest_one_repeat_right_2" ms-if="isAuthor" ms-click="seeScore(true, el.isAnswer, el.paperId)">查看成绩</div>
                                <div class="content_right_synchrotest_one_repeat_right_3" ms-if="!isAuthor"></div>
                                <div class="content_right_synchrotest_one_repeat_right" ms-click="showPaper(1, el);">查看试卷</div>
                            </div>
                        </div>
                        <div class="content_right_synchrotest_two" ms-if="classTeachInfo.size()">
                            <div class="content_right_synchrotest_two_top">
                                <div class="content_right_synchrotest_two_top_1"></div>
                                <div class="content_right_synchrotest_two_top_2">第二部分</div>
                                <div class="content_right_synchrotest_two_top_3">课堂授课</div>
                            </div>
                            <div class="content_right_synchrotest_two_repeat" ms-repeat="classTeachInfo">
                                <div class="content_right_synchrotest_two_repeat_left" ms-attr-title="el.title" ms-text="el.title"></div>
                                <div class="content_right_synchrotest_two_repeat_center" ms-if="!el.submitTime && !el.completeTime && (el.type == 2)" ms-text="'暂无截止日期与完成时限'"></div>
                                <div class="content_right_synchrotest_two_repeat_center" ms-if="!el.submitTime && (el.type == 1)" ms-text="'暂无截止日期'"></div>
                                <div class="content_right_synchrotest_two_repeat_center" ms-if="el.submitTime && el.completeTime && (el.type == 2)" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                                <div class="content_right_synchrotest_two_repeat_center" ms-if="el.submitTime && (el.type == 1)" ms-text="'截止日期' + el.submitTime"></div>
                                <div class="content_right_synchrotest_two_repeat_right_2" ms-if="isAuthor" ms-click="seeScore(true, el.isAnswer, el.paperId)">查看成绩</div>
                                <div class="content_right_synchrotest_two_repeat_right_3" ms-if="!isAuthor"></div>
                                <div class="content_right_synchrotest_two_repeat_right" ms-click="showPaper(2, el);">查看试卷</div>
                            </div>
                        </div>
                        <div class="content_right_synchrotest_three" ms-if="afterClassInfo.size()">
                            <div class="content_right_synchrotest_three_top">
                                <div class="content_right_synchrotest_three_top_1"></div>
                                <div class="content_right_synchrotest_three_top_2">第三部分</div>
                                <div class="content_right_synchrotest_three_top_3">课后指导</div>
                            </div>
                            <div class="content_right_synchrotest_three_repeat" ms-repeat="afterClassInfo">
                                <div class="content_right_synchrotest_three_repeat_left" ms-attr-title="el.title" ms-text="el.title"></div>
                                <div class="content_right_synchrotest_three_repeat_center" ms-if="!el.submitTime && !el.completeTime && (el.type == 2)" ms-text="'暂无截止日期与完成时限'"></div>
                                <div class="content_right_synchrotest_three_repeat_center" ms-if="!el.submitTime && (el.type == 1)" ms-text="'暂无截止日期'"></div>
                                <div class="content_right_synchrotest_three_repeat_center" ms-if="el.submitTime && el.completeTime && (el.type == 2)" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                                <div class="content_right_synchrotest_three_repeat_center" ms-if="el.submitTime && (el.type == 1)" ms-text="'截止日期' + el.submitTime"></div>
                                <div class="content_right_synchrotest_three_repeat_right_2" ms-if="isAuthor" ms-click="seeScore(true, el.isAnswer, el.paperId)">查看成绩</div>
                                <div class="content_right_synchrotest_three_repeat_right_3" ms-if="!isAuthor"></div>
                                <div class="content_right_synchrotest_three_repeat_right" ms-click="showPaper(3, el);">查看试卷</div>
                            </div>
                        </div>
                    </div>
                    {{-- ========================================= 成绩提示窗口 ========================================= --}}
                    <div class="notice hide">
                        <div class="top">提示</div>
                        <div class="cen">尚无成绩记录</div>
                        <div class="bot" ms-click="seeScore(false)">知道了</div>
                    </div>
                    {{-- ========================================= 成绩提示窗口 ========================================= --}}
                </div>
                {{--随堂笔记--}}
                <div class="content_right_coursenote_tabs none" ms-visible="currentIndex=='note'">
                    <div class="course_note_title">
                        {{--我的笔记--}}
                        <div class="course_note_my blue">我的笔记</div>
                        {{--共享笔记--}}
                        <div class="course_note_share">共享笔记</div>
                    </div>
                    <div style="height:17px"></div>

                    <div style="height:17px"></div>
                    {{--笔记内容--}}
                    {{--<div style="height:15px"></div>--}}
                    <div class="course_note_border">

                        {{--下拉框--}}
                        <div class="course_note_select">
                            <div class="course_note_select1">
                                <select name="" id="myWholeNotes">
                                    <option value="0">全部笔记</option>
                                    <option value="1">课前导学</option>
                                    <option value="2">课堂授课</option>
                                    <option value="3">课后指导</option>
                                </select>
                            </div>
                            <div class="course_note_select2">
                                <select name="" id="myWholeChapter">
                                    <option value="">全部笔记</option>
                                </select>
                            </div>
                        </div>
                        <div style="height:17px"></div>



                        <div class="modify_notes none">
                            <textarea name="" id="" width="720" id="modifyContent" ms-duplex="modifyContent"></textarea>
                            {{--发布按钮--}}
                            <div class="modify_notes_fabu">
                                <div ms-click="postCommentModify()">修改</div>
                            </div>
                        </div>
                        <div style="height:20px"></div>
                        <div class="course_note_content" ms-repeat="courseMyNote">
                            <div style="height:20px"></div>
                            <div class="course_note_content_top">
                                <div class="course_note_content_top_img">
                                    <img ms-attr-src="el.pic" alt="" width="36px" height="36px">
                                </div>
                                <div class="course_note_content_top_name" ms-html="el.username"></div>
                                {{--小工具--}}
                                <div class="course_note_content_top_locking" ms-if="el.public == 1" ms-click="privateNote(el.id)">
                                    <img src="{{asset('home/image/teacherCourse/suo.png')}}" alt="" width="20px" height="20px">
                                </div>
                                <div class="course_note_content_top_edit" ms-click="modifyNote(el.id,el.notecontent)">
                                    <img src="{{asset('home/image/teacherCourse/edit.png')}}" alt="" width="20px" height="20px">
                                </div>
                                <div class="course_note_content_top_del" ms-click="deleteNote(el.id)">
                                    <img src="{{asset('home/image/teacherCourse/delete.png')}}" alt="" width="20px" height="20px">
                                </div>
                                <div class="course_note_content_top_title_time">
                                    {{--标题--}}
                                    <div class="course_note_content_top_title" ms-html="el.parchaptertitle"></div>
                                    {{--时间--}}
                                    <div class="course_note_content_top_time"  ms-html=" formTime(el.notetime)  "></div>
                                </div>
                            </div>
                            <div style="clear: both"></div>
                            <div class="course_note_content_bottom" ms-html="el.notecontent">
                            </div>
                            <div style="height:20px"></div>
                        </div>
                        <div class="wodebiji" ms-if="wodebiji">
                            <div style="height:70px;"></div>
                            暂无随堂笔记内容
                        </div>
                    </div>
                    {{--共享笔记内容--}}
                    <div class="course_note_borders none">
                        {{--下拉框--}}
                        <div class="course_note_select">
                            <div class="course_note_select1">
                                <select name="" id="shareWholeNotes">
                                    <option value="0">全部笔记</option>
                                    <option value="1">课前导学</option>
                                    <option value="2">课堂授课</option>
                                    <option value="3">课后指导</option>
                                </select>
                            </div>
                            <div class="course_note_select2">
                                <select name="" id="shareWholeChapter">
                                    <option value="">全部笔记</option>
                                </select>
                            </div>
                        </div>
                        <div style="height:17px"></div>

                        <div class="course_note_content" ms-repeat="courseShareNote">
                            <div style="height:20px"></div>
                            <div class="course_note_content_top">
                                <div class="course_note_content_top_img">
                                    <img ms-attr-src="el.pic" alt="" width="36px" height="36px">
                                </div>
                                <div class="course_note_content_top_name" ms-html="el.username"></div>
                                <div class="course_note_content_top_title_time">
                                    {{--标题--}}
                                    <div class="course_note_content_top_title" ms-html="el.parchaptertitle"></div>
                                    {{--时间--}}
                                    <div class="course_note_content_top_time"  ms-html=" formTime(el.notetime)  "></div>
                                </div>
                            </div>
                            <div style="clear: both"></div>
                            <div class="course_note_content_bottom" ms-html="el.notecontent">

                            </div>
                            <div style="height:20px"></div>
                        </div>
                        <div class="gongxiangbiji" ms-if="gongxiangbiji">
                            <div style="height:160px;"></div>
                            暂无随堂笔记内容
                        </div>
                    </div>
                </div>
                {{--课程问答--}}
                <div class="content_right_coursequestion_tabs none" ms-visible="currentIndex=='question'">
                    <div class="course_question_title">
                        课程问答
                    </div>
                    <div class="hide_show" style="display: none">
                        <div class="course_question_area">
                            <textarea name="" id="" width="680px" id="commentContents" ms-duplex="commentContents"></textarea>
                        </div>
                        {{--发布按钮--}}
                        <div class="course_question_fabu">
                            <div ms-click="postComments()">发布</div>
                        </div>
                    </div>
                    <div style="height:22px"></div>
                    {{--内容--}}
                    <div class="course_question_content" ms-repeat="courseAskData">
                        {{--提问--}}
                        <div style="height:22px"></div>
                        <div class="course_question_stu">
                            <div class="course_question_stu_img">
                                <img ms-attr-src="el.pic" alt="" width="36px" height="36px">
                            </div>
                            <div class="course_question_stu_name" ms-html="el.username"></div>
                            @if(\Auth::user()->id == $data->teacherId )
                                <div class="course_question_tea_huifu" ms-if="!el.teaId" ms-click="postReply('{{$data->id}}', el.id )">回复</div>
                            @endif
                            <div class="course_question_stu_time" ms-html="el.asktime | truncate(12,' ')"></div>
                        </div>
                        <div style="clear: both"></div>
                        <div style="height:10px"></div>
                        <div class="course_question_stu_content" ms-html="el.content"></div>
                        <div style="height:12px"></div>
                        {{--回答--}}
                        <div ms-if="el.teaId">
                            <div class="course_question_stu">
                                <div class="course_question_stu_img">
                                    <img ms-attr-src="el.teaPic" alt="" width="36px" height="36px">
                                </div>
                                <div class="course_question_tea_name" ms-html="el.teaName"></div>
                                <div class="course_question_tea_huida">回答</div>
                                <div class="course_question_tea_time" ms-html="el.anstime | truncate(12,' ')"></div>
                            </div>
                            <div style="clear: both"></div>
                            <div style="height:10px"></div>
                            <div class="course_question_stu_content" ms-html="el.answer"></div>
                            <div style="height:15px"></div>
                        </div>
                    </div>
                    <div style="height:25px"></div>
                    {{--暂无问答--}}
                    <div class="zanwuwenda" ms-if="wodewendas">
                        <div style="height:160px;"></div>
                        暂无课堂问答内容
                    </div>
                </div>
                {{--学习情况--}}
                <div class="content_right_coursestudy_tabs none" ms-visible="currentIndex=='study'">
                    <div class="course_study_title">
                        <div class="statistic_condition">
                            <div class="class">
                                <div class="select" ms-click="changeModel('selectClass')"><span ms-text='condition.classSelectedText === "全部" ? condition.classSelectedText : condition.classSelectedText + "..."'></span><p>▲</p></div>
                                <div class="option" ms-if="condition.selectClass">
                                    <p ms-selectclass="['全部', '全部']">
                                        <span>全部</span>
                                        <span class="nike" style="display: none;">√</span>
                                    </p>
                                    <p ms-repeat="classList"  ms-selectclass="[parseInt(el.gradeId) + '-' + parseInt(el.classId), el.gradeName + el.className]">
                                        <span ms-text="el.gradeName + el.className"></span>
                                        <span class="nike" style="display: none;">√</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{--<div class="course_study_title_select">--}}
                            {{--<select name="" id="learningState">--}}
                                {{--<option value="0">全部</option>--}}
                                {{--<option value="1">1</option>--}}
                                {{--<option value="2">2</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    </div>
                    <div style="height:40px;"></div>
                    {{--学习情况--}}
                    <div class="study_status">
                        {{--完成学习--}}
                        <div class="study_finish">
                            <div class="study_finish_title" ms-html="'完成学习 ( ' + studyFinish.size() + ' )'"></div>
                            <div style="clear: both"></div>
                            <div style="height:22px;"></div>
                            <div class="study_finish_content">
                                <div class="study_finish_content_name" ms-repeat="studyFinish">
                                    <div ms-html="el.username"></div>
                                </div>
                                <div style="clear: both"></div>
                            </div>
                            <div style="height:25px;"></div>
                        </div>
                        {{--尚未学习--}}
                        <div class="study_no">
                            <div class="study_no_title" ms-html="'尚未学习 ( ' + studyNo.size() + ' )'"></div>
                            <div style="clear: both"></div>
                            <div style="height:22px;"></div>
                            <div class="study_no_content">
                                <div class="study_no_content_name" ms-repeat="studyNo">
                                    <div ms-html="el.username"></div>
                                </div>

                                <div style="clear: both"></div>
                            </div>
                            <div style="height:25px;"></div>
                        </div>
                        {{--正在学习--}}
                        <div class="study_conduct">
                            <div class="study_conduct_title" ms-html="'正在学习 ( ' + studySchedule.size() + ' )'"></div>
                            <div style="clear: both"></div>
                            <div style="height:22px;"></div>
                            <div class="study_conduct_content">

                                <div class="study_conduct_content_sum" ms-repeat="studySchedule">
                                    <div class="study_conduct_content_sum_name" ms-html="el.username"></div>
                                    <div class="study_conduct_content_sum_bar">
                                        <progress class="progress" ms-attr-value="el.learnNumber" ms-attr-max="el.sumNumber">
                                            <ie ms-css-width=" (el.learnNumber/el.sumNumber*100) + '%';"></ie>
                                        </progress>
                                    </div>
                                    <div class="study_conduct_content_sum_left"></div>
                                    <div class="study_conduct_content_sum_count" ms-html="el.learnNumber + '/' + el.sumNumber" ></div>
                                </div>

                                <div style="clear: both"></div>
                            </div>
                            <div style="height:25px;"></div>
                        </div>
                    </div>
                </div>


            </div>
            {{-- ============================= 课程同步练习（作业-已答）=========================== --}}
            <div class="student_paper hide">
                <div class="student_paper_top">
                    <div class="student_paper_top_title" ms-text="basicInfo.title"></div>
                    <div class="student_paper_top_type" ms-text="basicInfo.subjectName + ' ' + basicInfo.editionName + ' ' + basicInfo.gradeName + ' ' + basicInfo.bookName"></div>
                </div>
                <div class="student_paper_content">
                    <div class="student_paper_content_top">
                        <div class="student_paper_content_top_left" ms-text="paperAttr"></div>
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
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_cen">
                                    <div ms-repeat="a.choice">
                                        <div class="student_paper_content_repeat_cen_answer">
                                            <div class="one" ms-class-1="has_choose:isTrue($index, a.answer, 1)" ms-class-2="no_choose:isTrue($index, a.answer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span>
                                                <span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_bot">
                                    <div class="student_paper_content_repeat_bot_left right_color" ms-text="'正确答案 ：' + a.answer"></div>
                                </div>
                            </div>
                            {{-- 多选题2 --}}
                            <div class="student_paper_content_repeat_2" ms-if="a.type == 2">
                                <div class="student_paper_content_repeat_top">
                                    <div class="student_paper_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">多选</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_cen">
                                    <div ms-repeat="a.choice">
                                        <div class="student_paper_content_repeat_cen_answer">
                                            <div class="one" ms-class-1="has_choose:isTrue($index, a.answer, 1)" ms-class-2="no_choose:isTrue($index, a.answer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span>
                                                <span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_bot">
                                    <div class="student_paper_content_repeat_bot_left right_color" ms-text="'正确答案 ：' + a.answer"></div>
                                </div>
                            </div>
                            {{-- 填空题4 --}}
                            <div class="student_paper_content_repeat_3" ms-if="a.type == 4">
                                <div class="student_paper_content_repeat_top">
                                    <div class="student_paper_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">填空</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_bot">
                                    <div class="student_paper_content_repeat_bot_left right_color" ms-text="'正确答案 ：' + a.answer"></div>
                                </div>
                            </div>
                            {{-- 判断题3 --}}
                            <div class="student_paper_content_repeat_5" ms-if="a.type == 3">
                                <div class="student_paper_content_repeat_top">
                                    <div class="student_paper_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">判断</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_cen">
                                    <div class="student_paper_content_repeat_cen_answer">
                                        <div ms-if="a.answer == 1" class="one has_choose"></div>
                                        <div ms-if="a.answer == 0" class="one no_choose"></div>
                                        <div class="two">正确</div>
                                    </div>
                                    <div class="student_paper_content_repeat_cen_answer">
                                        <div ms-if="a.answer == 1" class="one no_choose"></div>
                                        <div ms-if="a.answer == 0" class="one has_choose"></div>
                                        <div class="two">错误</div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_bot">
                                    <div class="student_paper_content_repeat_bot_left right_color">
                                        正确答案 ：<span ms-text="a.answer == 1 ? '正确' : '错误'"></span>
                                    </div>
                                </div>
                            </div>
                            {{-- 解答题5 --}}
                            <div class="student_paper_content_repeat_4" ms-if="a.type == 5">
                                <div class="student_paper_content_repeat_top">
                                    <div class="student_paper_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">解答</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_cen">
                                    <div class="student_paper_content_repeat_cen_answer">
                                        {{--<div class="student_paper_content_repeat_cen_answer_top" ms-text="a.title + '（' + a.score + '）'"></div>--}}
                                        <div class="student_paper_content_repeat_cen_answer_bot">
                                            <span class="right_color" ms-html="'参考答案：' + a.answer"></span>
                                        </div>
                                    </div>
                                    {{--<div class="clear"></div>--}}
                                    {{--<div class="student_paper_content_repeat_cen_answer">--}}
                                    {{--<div class="student_paper_content_repeat_cen_answer_top" ms-text="a.title + '（' + a.score + '）'"></div>--}}
                                    {{--<div class="student_paper_content_repeat_cen_answer_bot">--}}
                                    {{--<span class="right_color"ms-text="'参考答案：' + a.answer"></span>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="imgZoom hide" ms-class="hide: !showImg">
                    <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
                    <img ms-attr-src="imgZoom">
                </div>
                <div class="notice hide">
                    <div class="top">提示</div>
                    <div class="cen">尚无成绩记录</div>
                    <div class="bot">知道了</div>
                </div>
                <div style="height: 50px;width: 100%"></div>
            </div>
            {{-- ============================= 课程同步练习（测试-已答）=========================== --}}
            <div class="student_answer hide">
                <div class="student_test_top">
                    <div class="student_test_top_title" ms-text="basicInfo.title"></div>
                    <div class="student_test_top_type" ms-text="basicInfo.subjectName + ' ' + basicInfo.editionName + ' ' + basicInfo.gradeName + ' ' + basicInfo.bookName"></div>
                </div>
                <div class="student_test_content">
                    <div class="student_test_content_top">
                        <div class="student_test_content_top_left" ms-text="paperAttr"></div>
                        <div class="student_test_content_top_right" ms-text="'作业提交截止时间为 : ' + basicInfo.submitTime"></div>
                    </div>
                    <div class="student_test_content_bot">
                        {{-- 单选题 --}}
                        <div class="student_test_content_repeat_one" ms-if="sChooseFlag">
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
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_cen">
                                    <div ms-repeat="a.choice">
                                        <div class="student_test_content_repeat_cen_answer">
                                            <div class="one" ms-class-1="has_choose:isTrue($index, a.answer, 1)" ms-class-2="no_choose:isTrue($index, a.answer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span>
                                                <span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left right_color" ms-text="'正确答案 ：' + a.answer"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        {{-- 多选题 --}}
                        <div class="student_test_content_repeat_two" ms-if="mChooseFlag">
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
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_cen">
                                    <div ms-repeat="a.choice">
                                        <div class="student_test_content_repeat_cen_answer">
                                            <div class="one" ms-class-1="has_choose:isMTrue($index, a.answer, 1)" ms-class-2="no_choose:isMTrue($index, a.answer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span>
                                                <span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left right_color" ms-html="'正确答案 ：' + a.answer"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        {{-- 判断题 --}}
                        <div class="student_test_content_repeat_five" ms-if="judgeFlag">
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
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_cen">
                                    <div class="student_test_content_repeat_cen_answer">
                                        <div ms-if="a.answer == 1" class="one has_choose"></div>
                                        <div ms-if="a.answer == 0" class="one no_choose"></div>
                                        <div class="two">正确</div>
                                    </div>
                                    <div class="student_test_content_repeat_cen_answer">
                                        <div ms-if="a.answer == 1" class="one no_choose"></div>
                                        <div ms-if="a.answer == 0" class="one has_choose"></div>
                                        <div class="two">错误</div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left right_color">
                                        <span>正确答案 ：</span>
                                        <span ms-text="a.answer == 1 ? '正确' : '错误'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        {{-- 填空题 --}}
                        <div class="student_test_content_repeat_three" ms-if="completionFlag">
                            <div class="student_test_content_repeat_three_top">
                                四、填空题
                            </div>
                            <div class="student_test_content_repeat_3" ms-repeat-a="testInfo.completion">
                                <div class="student_test_content_repeat_top">
                                    <div class="student_test_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">填空</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_bot" style="width:700px;height: 40px;background-color: #F5F5F5">
                                    <div class="student_test_content_repeat_bot_left right_color" ms-html="'正确答案 ：' + a.answer"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        {{-- 解答题 --}}
                        <div class="student_test_content_repeat_four" ms-if="subjectiveFlag">
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
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_cen">
                                    <div class="student_test_content_repeat_cen_answer">
                                        {{--<div class="student_test_content_repeat_cen_answer_top" ms-text="a.title"></div>--}}
                                        <div class="student_test_content_repeat_cen_answer_bot"
                                             style="width:670px;height: 40px;background-color: #F5F5F5;padding-left: 30px;margin-left: 40px;">
                                            <span class="right_color" ms-html="'正确答案：' + a.answer"></span>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div style="height: 50px;width: 100%"></div>
                <div class="imgZoom hide" ms-class="hide: !showImg">
                    <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
                    <img ms-attr-src="imgZoom">
                </div>
            </div>
        </div>
        <div style="clear: both"></div>

    </div>

    <div style="height:215px;background: #F5F5F5;"></div>


@endsection
@section('selectjs')
    <script type="text/javascript" src="{{asset('home/js/users/select2.min.js') }}"></script>
    <script  type="text/javascript">
        $("#myWholeNotes").select2({
            minimumResultsForSearch: Infinity,
        });
        $("#myWholeChapter").select2({
            minimumResultsForSearch: Infinity,
        });


        $("#shareWholeNotes").select2({
            minimumResultsForSearch: Infinity,
        });
        $("#shareWholeChapter").select2({
            minimumResultsForSearch: Infinity,
        });


        $("#learningState").select2({
            minimumResultsForSearch: Infinity,
        });

    </script>

@endsection
@section('js')
    <script type="text/javascript" src="{{asset('home/js/teacherCourse/detail.js')}}"></script>
    <script>
        require(['/teacherCourse/detail', '/teacherCourse/synchroTest'], function (model, synchroTest) {
            model.getNotes();
            model.getSelectNote();
            model.getShareNotes();
            model.getShareSelectNote();
            //筛选学习情况
//            model.getLearningState();
            if (window.location.hash) {
                model.tab = window.location.hash.split('#')[1];
                model.tabs(model.tab);
            }
            if (model.currentIndex == 'intro') {
                $('.courseintro').addClass('span_active').siblings().removeClass('span_active')
            } else if (model.currentIndex == 'list') {
                $('.courselist').addClass('span_active').siblings().removeClass('span_active')
            } else if (model.currentIndex == 'test') {
                $('.synchrotest').addClass('span_active').siblings().removeClass('span_active')
            } else if (model.currentIndex == 'note') {
                $('.coursenote').addClass('span_active').siblings().removeClass('span_active')
            } else if (model.currentIndex == 'question') {
                $('.coursequestion').addClass('span_active').siblings().removeClass('span_active')
            } else if (model.currentIndex == 'study') {
                $('.coursestudy').addClass('span_active').siblings().removeClass('span_active')
            }
            model.courseId = '{{$data->id}}' || null;

            model.getCourseAskData(model.courseId)
            //我的笔记
            model.getCourseMyNote(model.courseId)
            //共享笔记
            model.getCourseShareNote(model.courseId)
            synchroTest.getData('/teacherCourse/getLeadLearn/' + model.courseId, 'GET', '', 'leadLearnInfo');
            synchroTest.getData('/teacherCourse/getClassTeach/' + model.courseId, 'GET', '', 'classTeachInfo');
            synchroTest.getData('/teacherCourse/getAfterClass/' + model.courseId, 'GET', '', 'afterClassInfo');
            synchroTest.init();
            // 答案数字转字母
            avalon.filters.changeCode = function (value) {
                return String.fromCharCode(parseInt(value) + 65);
            };
            //获取课程目录
            model.getCourseChapter(model.courseId);
            //尚未学习课程
            model.getStudyNo(model.courseId);
            //学习完成课程
            model.getStudyFinish(model.courseId);
            //正在学习
            model.getStudySchedule(model.courseId);
            //选择下拉框
            model.getGradeClass(model.courseId);
            avalon.scan();
        });
    </script>

@endsection

