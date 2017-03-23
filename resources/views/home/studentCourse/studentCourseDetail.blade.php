@extends('layouts.layoutHome')

@section('title', '课程中心')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/studentCourse/detail.css') }}">
@endsection

@section('content')

    <div style="height:40px"></div>

    <div class="content" ms-controller="studentCourseDetail">
        <div class="shadow hide"></div>
        {{--左部分--}}
        <div class="content_left">

            <div class="content_left_top">
                <div class="content_left_top_img">
                    <img src="{{asset('home/image/teacherCourse/1.png')}}" alt="" width="221px" height="166px" >
                </div>
            </div>
            <span class="span_hover"></span><div class="courseintro course_back" ms-click="tabs('intro')">课程介绍</div>
            <span class="span_hover"></span><div class="courselist course_back" ms-click="tabs('list')">课程目录</div>
            <span class="span_hover"></span><div class="synchrotest course_back" ms-click="tabs('test')">同步测验</div>
            <span class="span_hover"></span><div class="coursenote course_back" ms-click="tabs('note')">随堂笔记</div>
            <span class="span_hover"></span><div class="coursequestion course_back" ms-click="tabs('question')">课程问答</div>
        </div>


        {{--右侧部分--}}
        <div class="content_right" ms-controller="synchroTest">
            <div class="content_right_courseintro">
                <div class="courseintro_top">
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
                <div class="content_right_courseintro_tabs hide"  ms-visible="currentIndex=='intro'">
                    <div class="course_intro_title">
                        课程介绍
                    </div>
                    <div style="height:26px"></div>
                    <div class="content_right_content">
                        {{--知识点--}}
                        <div class="content_right_content_point_title">知识点 :</div>
                        <div style="height:8px;"></div>
                        <div class="content_right_content_point">{{$data->courseIntro}}</div>
                        <div style="height:30px"></div>
                        {{--@if($data->type == 2)--}}
                            {{--<div class="content_right_content_information">教案信息 ： {!!$data->courseContent!!}</div>--}}
                        {{--@endif--}}
                    </div>
                </div>

                {{--课程目录--}}
                <div class="content_right_courselist_tabs hide" ms-visible="currentIndex=='list'">
                    <div class="content_right_synchrotest_top">课程目录</div>
                    <div class="content_right_synchrotest_one" ms-if="CourseChapter.duidance.size()">
                        <div class="content_right_synchrotest_one_top">
                            <div class="content_right_synchrotest_one_top_1"></div>
                            <div class="content_right_synchrotest_one_top_2">第一部分</div>
                            <div class="content_right_synchrotest_one_top_3">课前导学</div>
                        </div>
                        <div class="content_right_synchrotest_one_repeat" ms-if-loop="el.courseType == 0" ms-repeat="CourseChapter.duidance">
                            <div class="content_right_synchrocatalog_one_repeat_img">
                                <img ms-attr-src="el.selectImage" alt="" width="14px" height="14px">

                            </div>
                            <div class="content_right_synchrocatalog_one_repeat_left" ms-html="'1.' +( $index + 1)"></div>
                            <div class="content_right_synchrocatalog_one_repeat_center"><a ms-attr-href="'/studentCourse/stuCatalog/' + el.courseId + '/' + el.id" ms-html="el.title"></a></div>
                            <div class="content_right_synchrocatalog_one_repeat_right">
                                <a class="content_right_synchrocatalogtest_two_repeat_righta a" ms-class="b:item.icontype" ms-repeat-item="el.chapter"></a>
                            </div>
                        </div>

                        <div class="content_right_synchrotest_one_repeat" ms-repeat="leadLearnInfo">
                            <div class="content_right_synchrotest_one_repeat_left" ms-text="el.title"></div>
                            <div class="content_right_synchrotest_one_repeat_center" style="width: 360px;" ms-if="!el.submitTime && !el.completeTime" ms-text="'暂无截止日期与完成时限'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" style="width: 360px;" ms-if="el.submitTime && el.completeTime" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                            <div class="content_right_synchrotest_one_repeat_right" ms-click="showPop(el, 1);">进去测试</div>
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
                                <div class="content_right_synchrocatalog_one_repeat_left_img">
                                    <img ms-attr-src="chap.selectImageChapter" alt="" width="14px" height="14px">

                                </div>

                                <div class="content_right_synchrocatalogtest_two_repeat_left" ms-html="('2.' +( $outer.$index + 1)) + '.' + ($index + 1)"></div>
                                <div class="content_right_synchrocatalogtest_two_repeat_center"><a ms-attr-href="'/studentCourse/stuCatalog/' + el.courseId + '/' + chap.id" ms-html="chap.title"></a></div>
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
                            <div class="content_right_synchrotest_one_repeat_center" style="width: 360px;" ms-if="!el.submitTime && !el.completeTime" ms-text="'暂无截止日期与完成时限'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" style="width: 360px;" ms-if="el.submitTime && el.completeTime" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                            <div class="content_right_synchrotest_one_repeat_right" ms-click="showPop(el, 2);">进去测试</div>
                        </div>

                    </div>
                    <div class="content_right_synchrotest_three" ms-if="CourseChapter.guidance.size()">
                        <div class="content_right_synchrotest_three_top">
                            <div class="content_right_synchrotest_three_top_1"></div>
                            <div class="content_right_synchrotest_three_top_2">第三部分</div>
                            <div class="content_right_synchrotest_three_top_3">课后指导</div>
                        </div>
                        <div class="content_right_synchrotest_three_repeat" ms-if-loop="el.courseType == 2" ms-repeat="CourseChapter.guidance">
                            {{--<div class="content_right_synchrocatalog_one_repeat_img ratee"></div>--}}
                            <div class="content_right_synchrocatalog_one_repeat_img">
                                <img ms-attr-src="el.selectImage" alt="" width="14px" height="14px">

                            </div>
                            <div class="content_right_synchrocatalog_three_repeat_left" ms-html="'3.' +( $index + 1)"></div>
                            <div class="content_right_synchrocatalog_three_repeat_center"><a ms-attr-href="'/studentCourse/stuCatalog/' + el.courseId + '/' + el.id" ms-html="el.title"></a></div>
                            <div class="content_right_synchrocatalog_three_repeat_right">
                                <a class="content_right_synchrocatalogtest_two_repeat_righta a" ms-class="b:item.icontype" ms-repeat-item="el.chapter"></a>
                            </div>
                        </div>

                        <div class="content_right_synchrotest_one_repeat" ms-repeat="afterClassInfo">
                            <div class="content_right_synchrotest_one_repeat_left" ms-text="el.title"></div>
                            <div class="content_right_synchrotest_one_repeat_center" style="width: 360px;" ms-if="!el.submitTime && !el.completeTime" ms-text="'暂无截止日期与完成时限'"></div>
                            <div class="content_right_synchrotest_one_repeat_center" style="width: 360px;" ms-if="el.submitTime && el.completeTime" ms-text="'截止日期' + el.submitTime + ' / 完成时限 ' + el.completeTime + ' 分钟'"></div>
                            <div class="content_right_synchrotest_one_repeat_right" ms-click="showPop(el, 3);">进去测试</div>
                        </div>

                    </div>
                    {{-- ========================================= 开始测验弹窗 ========================================= --}}
                    <div class="start_test hide">
                        <div class="question_detail_title">
                            <div class="question_detail_title_right" ms-click="closePop()"></div>
                        </div>
                        <div class="question_detail_content">
                            <div class="question_detail_content_top" ms-text="popContent.title"></div>
                            <div class="question_detail_content_cen" ms-if="!popContent.submitTime" ms-text="'暂无提交截止日期'"></div>
                            <div class="question_detail_content_cen" ms-if="popContent.submitTime" ms-text="'提交截止日期：' + popContent.submitTime"></div>
                            <div class="question_detail_content_bot" ms-if="!popContent.completeTime" ms-text="'暂无完成时限'"></div>
                            <div class="question_detail_content_bot" ms-if="popContent.completeTime" ms-text="'完成时限 ：' + popContent.completeTime + '分钟'"></div>
                            <div class="start_btn" ms-click="getPaper(popContent.paperId, popContent.paperType, popContent.type)">开始测验</div>
                        </div>
                    </div>
                    {{-- ========================================= 开始测验弹窗 ========================================= --}}
                </div>

                {{--同步测验--}}
                <div class="content_right_synchrotest_tabs hide" ms-visible="currentIndex=='test'" >
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
                            <div class="content_right_synchrotest_one_repeat_right" ms-click="showPop(el, 1);">点击进入</div>
                        </div>
                        {{--<div class="content_right_synchrotest_one_repeat">--}}
                            {{--<div class="content_right_synchrotest_one_repeat_left">课前导学 测验</div>--}}
                            {{--<div class="content_right_synchrotest_one_repeat_center">截止日期2016-12-28 18:00 / 完成时限 60分钟</div>--}}
                            {{--<div class="content_right_synchrotest_one_repeat_right">点击进入</div>--}}
                        {{--</div>--}}
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
                            <div class="content_right_synchrotest_two_repeat_right" ms-click="showPop(el, 2);">点击进入</div>
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
                            <div class="content_right_synchrotest_three_repeat_right" ms-click="showPop(el, 3);">点击进入</div>
                        </div>
                    </div>
                    {{-- ========================================= 开始测验弹窗 ========================================= --}}
                    <div class="start_test hide">
                        <div class="question_detail_title">
                            <div class="question_detail_title_right" ms-click="closePop()"></div>
                        </div>
                        <div class="question_detail_content">
                            <div class="question_detail_content_top" ms-text="popContent.title"></div>
                            <div class="question_detail_content_cen" ms-if="!popContent.submitTime" ms-text="'暂无提交截止日期'"></div>
                            <div class="question_detail_content_cen" ms-if="popContent.submitTime" ms-text="'提交截止日期：' + popContent.submitTime"></div>
                            <div class="question_detail_content_bot" ms-if="!popContent.completeTime" ms-text="'暂无完成时限'"></div>
                            <div class="question_detail_content_bot" ms-if="popContent.completeTime" ms-text="'完成时限 ：' + popContent.completeTime + '分钟'"></div>
                            <div class="start_btn" ms-click="getPaper(popContent.paperId, popContent.paperType, popContent.type)">开始测验</div>
                        </div>
                    </div>
                    {{-- ========================================= 开始测验弹窗 ========================================= --}}

                </div>

                {{--随堂笔记--}}
                <div class="content_right_coursenote_tabs hide" ms-visible="currentIndex=='note'">
                    <div class="course_note_title">
                        {{--我的笔记--}}
                        <div class="course_note_my blue">我的笔记</div>
                        {{--共享笔记--}}
                        <div class="course_note_share">共享笔记</div>
                    </div>
                    <div style="height:17px"></div>

                    {{--笔记内容--}}
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
                            <textarea  name="" id="" width="720"  id="modifyContent" ms-duplex="modifyContent" ></textarea>
                            {{--发布按钮--}}
                            <div class="modify_notes_fabu">
                                <div ms-click="postCommentModify()">修改</div>
                            </div>
                        </div>


                        <div class="course_note_content hide" ms-class="hide: wodebiji" ms-repeat="courseMyNote">
                            <div style="height:20px"></div>
                            <div class="course_note_content_top">
                                <div class="course_note_content_top_img">
                                    <img ms-attr-src="el.pic" src="{{asset('home/image/teacherCourse/1.png')}}" alt="" width="36px" height="36px" >
                                </div>
                                <div class="course_note_content_top_name" ms-html="el.username"></div>
                                {{--小工具--}}
                                <div class="course_note_content_top_locking" ms-if="el.public == 1"  ms-click="privateNote(el.id)" >
                                    <img src="{{asset('home/image/teacherCourse/suo.png')}}" alt="" width="20px" height="20px" >
                                </div>
                                <div class="course_note_content_top_edit" ms-click="modifyNote(el.id,el.notecontent)">
                                    <img src="{{asset('home/image/teacherCourse/edit.png')}}" alt="" width="20px" height="20px" >
                                </div>
                                <div class="course_note_content_top_del" ms-click="deleteNote(el.id)">
                                    <img src="{{asset('home/image/teacherCourse/delete.png')}}" alt="" width="20px" height="20px" >
                                </div>
                                <div class="course_note_content_top_title_time">
                                    {{--标题--}}
                                    <div class="course_note_content_top_title" ms-html="el.parchaptertitle"></div>
                                    {{--时间--}}
                                    <div class="course_note_content_top_time" ms-if="el.courseTypes == false"  ms-html=" formTime(el.notetime)  "></div>
                                </div>
                            </div>
                            <div style="clear: both"></div>
                            <div class="course_note_content_bottom" ms-html="el.notecontent">

                            </div>
                            <div style="height:20px"></div>
                        </div>

                        <div class="wodebiji" ms-if="wodebiji">
                            <div style="height:160px;"></div>
                            暂无随堂笔记内容
                            <div style="clear: both"></div>
                        </div>


                    </div>



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
                                    <img ms-attr-src="el.pic" src="{{asset('home/image/teacherCourse/1.png')}}" alt="" width="36px" height="36px" >
                                </div>
                                <div class="course_note_content_top_name" ms-html="el.username"></div>
                                <div class="course_note_content_top_title_time">
                                    {{--标题--}}
                                    <div class="course_note_content_top_title" ms-html="el.parchaptertitle"></div>
                                    {{--时间--}}
                                    <div class="course_note_content_top_time" ms-if="el.courseTypes == false" ms-html=" formTime(el.notetime)  "></div>
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
                <div class="content_right_coursequestion_tabs hide" ms-visible="currentIndex=='question'">
                    <div class="course_question_title">
                        课程问答
                    </div>
                    <div class="course_question_area">
                        <textarea name="" id="" width="680px"  id="commentContent" ms-duplex="commentContent"></textarea>
                    </div>
                    {{--发布按钮--}}
                    <div class="course_question_fabu">
                        <div ms-click="postComment({{$data->id}})">发布</div>
                    </div>


                    {{--内容--}}
                    <div class="course_question_content" ms-repeat="courseAskData">
                        {{--提问--}}
                        <div style="height:22px"></div>
                        <div class="course_question_stu" >
                            <div class="course_question_stu_img">
                                <img ms-attr-src="el.pic" src="{{asset('home/image/teacherCourse/1.png')}}" alt="" width="36px" height="36px" >
                            </div>
                            <div class="course_question_stu_name" ms-html="el.username"></div>
                            <div class="course_question_stu_time" ms-html="el.asktime | truncate(12,' ')"></div>
                        </div>
                        <div style="clear: both"></div>

                        <div style="height:10px"></div>
                        <div class="course_question_stu_content" ms-html="el.content" ></div>

                        <div style="height:12px"></div>
                        {{--回答--}}
                        <div  ms-if="el.teaId">
                            <div class="course_question_stu">
                                <div class="course_question_stu_img">
                                    <img ms-attr-src="el.teaPic" alt="" width="36px" height="36px" >
                                </div>
                                <div class="course_question_tea_name" ms-html="el.teaName"></div>
                                <div class="course_question_tea_huida">回答</div>
                                <div class="course_question_tea_time" ms-html="el.anstime | truncate(12,' ')"></div>
                            </div>
                            <div style="clear: both"></div>
                            <div style="height:10px"></div>
                            <div class="course_question_stu_content" ms-html="el.answer" ></div>

                            <div style="height:15px"></div>
                        </div>
                    </div>

                    <div style="height:25px"></div>

                    {{--暂无问答--}}
                    <div class="zanwuwenda" ms-if="wodewendas">
                        <div style="height:70px;"></div>
                        暂无课堂问答内容
                    </div>

                </div>



            </div>

            {{-- ============================= 课程同步练习（作业-未答）============================ --}}
            <div class="student_homework hide">
                <div class="student_homework_top">
                    <div class="student_homework_top_title" ms-text="basicInfo.title"></div>
                    <div class="student_homework_top_type" ms-text="basicInfo.subjectName + ' ' + basicInfo.editionName + ' ' + basicInfo.gradeName + ' ' + basicInfo.bookName"></div>
                </div>
                <div class="student_homework_content">
                    <div class="student_homework_content_top">
                        <div class="student_homework_content_top_left" ms-text="paperAttr"></div>
                        <div class="student_homework_content_top_right" ms-text="'作业提交截止时间为 : ' + basicInfo.submitTime"></div>
                    </div>
                    <div class="student_homework_content_bot">
                        <div ms-repeat-a="paperInfo">
                            {{-- 单选题 --}}
                            <div class="student_homework_content_repeat_1" ms-if="a.type == 1">
                                <div class="student_homework_content_repeat_top">
                                    <div class="student_homework_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">单选</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_homework_content_repeat_cen">
                                    <div ms-repeat="a.choice">
                                        <div class="student_homework_content_repeat_cen_answer">
                                            <input type="radio" ms-click="singleChoose($index, a.sort, a.answer, a.score);" />
                                            <div class="one" ms-class-1="has_choose:isTrue($index, a.newAnswer, 1)" ms-class-2="no_choose:isTrue($index, a.newAnswer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span><span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- 多选题 --}}
                            <div class="student_homework_content_repeat_2" ms-if="a.type == 2">
                                <div class="student_homework_content_repeat_top">
                                    <div class="student_homework_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">多选</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_homework_content_repeat_cen">
                                    <div ms-repeat="a.choice">
                                        <div class="student_homework_content_repeat_cen_answer">
                                            <input type="checkbox" ms-click="manyChoose($index, a.sort, a.answer, a.score);" />
                                            <div class="one" ms-class-1="has_choose:isMTrue($index, a.newAnswer, 1)" ms-class-2="no_choose:isMTrue($index, a.newAnswer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span><span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- 填空题 --}}
                            <div class="student_homework_content_repeat_3" ms-if="a.type == 4">
                                <div class="student_homework_content_repeat_top">
                                    <div class="student_homework_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">填空</div>
                                        <div class="three">
                                            <span ms-html="changeTitle($index)"></span>
                                        </div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- 判断题 --}}
                            <div class="student_homework_content_repeat_5" ms-if="a.type == 3">
                                <div class="student_homework_content_repeat_top">
                                    <div class="student_homework_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">判断</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_homework_content_repeat_cen">
                                    <div class="student_homework_content_repeat_cen_answer">
                                        <input type="radio" ms-click="judgeTest(1, a.sort, a.answer, a.score);" />
                                        <div ms-if="a.newAnswer !== ''" class="one" ms-class-1="has_choose:a.newAnswer" ms-class-2="no_choose:!a.newAnswer"></div>
                                        <div ms-if="a.newAnswer === ''" class="one no_choose"></div>
                                        <div class="two">正确</div>
                                    </div>
                                    <div class="student_homework_content_repeat_cen_answer">
                                        <input type="radio" ms-click="judgeTest(0, a.sort, a.answer, a.score);" />
                                        <div ms-if="a.newAnswer !== ''" class="one" ms-class-1="has_choose:!a.newAnswer" ms-class-2="no_choose:a.newAnswer"></div>
                                        <div ms-if="a.newAnswer === ''" class="one no_choose"></div>
                                        <div class="two">错误</div>
                                    </div>
                                </div>
                            </div>
                            {{-- 解答题 --}}
                            <div class="student_homework_content_repeat_4" ms-if="a.type == 5">
                                <div class="student_homework_content_repeat_top">
                                    <div class="student_homework_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">解答</div>
                                        <div class="three" ms-html="a.title"></div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_homework_content_repeat_cen">
                                    <div class="student_homework_content_repeat_cen_answer">
                                        {{--<div class="student_homework_content_repeat_cen_answer_top" ms-text="a.title"></div>--}}
                                        <div class="student_homework_content_repeat_cen_answer_bot">
                                            <textarea ms-duplex-string="a.newAnswer" >答：</textarea>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="imgZoom hide" ms-class="hide: !showImg">
                    <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
                    <img ms-attr-src="imgZoom">
                </div>
                {{-- ======= 测试报告 start ======= --}}
                <div class="report hide">
                    <div class="report_top">
                        <div class="report_top_close">
                            <div ms-click="goToPaper(1, 2)">×</div>
                        </div>
                        <div class="report_top_title">作业报告</div>
                    </div>
                    <div class="clear"></div>
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
                    <div class="clear"></div>
                    <div class="report_tip">以上为客观题作答统计</div>
                    <div class="clear"></div>
                    <div class="report_count">
                        <div class="report_count_left" ms-text="'共作答：' + report.num + '题'"></div>
                        <div class="report_count_right" ms-text="'用时：' + report.time"></div>
                    </div>
                    <div class="clear"></div>
                    <div class="report_explain" ms-click="goToPaper(1, 1)">查看解析</div>
                    <div class="clear"></div>
                    <div class="report_bot_tip">
                        提示：如试卷中包含主观题，请等待教师批改后在查看相关题目成绩
                    </div>
                    <div class="clear"></div>
                </div>
                {{-- ======= 测试报告 end ======= --}}
                {{-- ========================================= 成绩提示窗口 ========================================= --}}
                <div class="notice hide">
                    <div class="top">提示</div>
                    <div class="cen">作业保存成功~</div>
                    <div class="bot" ms-click="seeScore(false)">知道了</div>
                </div>
                {{-- ========================================= 成绩提示窗口 ========================================= --}}
                <div style="height: 50px;width: 100%"></div>
                <div class="question_detail_score_submit">
                    <div class="question_detail_score_submit_save" ms-click="saveAnswer()">保存</div>
                    <div class="question_detail_score_submit_submit" ms-click="submitAnswer(1)">提交</div>
                </div>
                <div style="height: 50px;width: 100%"></div>
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
                            {{-- 单选题 --}}
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
                                            <div class="one" ms-class-1="has_choose:isTrue($index, a.userAnswer, 1)" ms-class-2="no_choose:isTrue($index, a.userAnswer, 2)"></div>
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
                                    <div class="student_paper_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                            </div>
                            {{-- 多选题 --}}
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
                                            <div class="one" ms-class-1="has_choose:isMTrue($index, a.userAnswer, 1)" ms-class-2="no_choose:isMTrue($index, a.userAnswer, 2)"></div>
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
                                    <div class="student_paper_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                            </div>
                            {{-- 填空题 --}}
                            <div class="student_paper_content_repeat_3" ms-if="a.type == 4">
                                <div class="student_paper_content_repeat_top">
                                    <div class="student_paper_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">填空</div>
                                        <div class="three">
                                            <div ms-html="showTitle($index)"></div>
                                        </div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_bot">
                                    <div class="student_paper_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                            </div>
                            {{-- 判断题 --}}
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
                                <div class="clear"></div>
                                <div class="student_paper_content_repeat_bot">
                                    <div class="student_paper_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight">
                                        <span>正确答案 ：</span>
                                        <span ms-text="a.answer == 1 ? '正确' : '错误'"></span>
                                    </div>
                                    <div class="student_paper_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                            </div>
                            {{-- 解答题 --}}
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
                                        <div class="student_paper_content_repeat_cen_answer_cen" ms-html="'答：' + a.userAnswer"></div>
                                        <div class="student_paper_content_repeat_cen_answer_bot">
                                            <span class="right_color" ms-html="'参考答案：' + a.answer"></span>
                                        </div>
                                    </div>
                                    <div class="question_detail_score" ms-if="a.getScore">
                                        <div class="question_detail_score_top">
                                            评分：<input type="text" ms-attr-value="a.getScore" disabled /> 分
                                        </div>
                                        <div class="question_detail_score_bot">
                                            <span>评语：</span><textarea ms-attr-value="a.comment" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="imgZoom hide" ms-class="hide: !showImg">
                    <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
                    <img ms-attr-src="imgZoom">
                </div>
                <div style="height: 50px;width: 100%"></div>
            </div>

            {{-- ============================= 课程同步练习（测试-未答）============================ --}}
            <div class="student_test hide">
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
                        <div class="student_test_content_top_left" ms-text="paperAttr"></div>
                        <div class="student_test_content_top_right" ms-text="'作业提交截止时间为 : ' + basicInfo.submitTime"></div>
                    </div>
                    <div class="student_test_content_bot">
                        {{-- 单选题 --}}
                        <div class="student_test_content_repeat_one" ms-if="testInfo.sChoose.size()">
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
                                            <input type="radio" ms-click="tSingleChoose($index, $outer.$index, a.answer, a.score);" />
                                            <div class="one" ms-class-1="has_choose:isTrue($index, a.newAnswer, 1)" ms-class-2="no_choose:isTrue($index, a.newAnswer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span><span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        {{-- 多选题 --}}
                        <div class="student_test_content_repeat_two" ms-if="testInfo.mChoose.size()">
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
                                            <input type="checkbox" ms-click="tManyChoose($index, $outer.$index, a.answer, a.score);" />
                                            <div class="one" ms-class-1="has_choose:isMTrue($index, a.newAnswer, 1)" ms-class-2="no_choose:isMTrue($index, a.newAnswer, 2)"></div>
                                            <div class="two">
                                                <span ms-text="$index|changeCode"></span><span ms-html="'、' + el"></span>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        {{-- 判断题 --}}
                        <div class="student_test_content_repeat_five" ms-if="testInfo.judge.size()">
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
                                <div class="clear"></div>
                            </div>
                        </div>
                        {{-- 填空题 --}}
                        <div class="student_test_content_repeat_three" ms-if="testInfo.completion.size()">
                            <div class="student_test_content_repeat_three_top">
                                四、填空题
                            </div>
                            <div class="student_test_content_repeat_3" ms-repeat-a="testInfo.completion">
                                <div class="student_test_content_repeat_top">
                                    <div class="student_test_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">填空</div>
                                        <div class="three">
                                            <span ms-html="changeTestTitle($index)"></span>
                                        </div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- 解答题 --}}
                        <div class="student_test_content_repeat_four" ms-if="testInfo.subjective.size()">
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
                                        <div class="student_test_content_repeat_cen_answer_bot">
                                            <textarea ms-duplex-string="a.newAnswer" >答：</textarea>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="imgZoom hide" ms-class="hide: !showImg">
                    <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
                    <img ms-attr-src="imgZoom">
                </div>
                {{-- ======= 测试报告 start ======= --}}
                <div class="report hide">
                    <div class="report_top">
                        <div class="report_top_close">
                            <div ms-click="goToPaper(2, 1)">×</div>
                        </div>
                        <div class="report_top_title">作业报告</div>
                    </div>
                    <div class="clear"></div>
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
                    <div class="clear"></div>
                    <div class="report_tip">以上为客观题作答统计</div>
                    <div class="clear"></div>
                    <div class="report_count">
                        <div class="report_count_left" ms-text="'共作答：' + report.num + '题'"></div>
                        <div class="report_count_right" ms-text="'用时：' + report.time"></div>
                    </div>
                    <div class="clear"></div>
                    <div class="report_explain" ms-click="goToPaper(2, 1)">查看解析</div>
                    <div class="clear"></div>
                    <div class="report_bot_tip">
                        提示：如试卷中包含主观题，请等待教师批改后在查看相关题目成绩
                    </div>
                    <div class="clear"></div>
                </div>
                {{-- ======= 测试报告 end ======= --}}
                {{-- ========================================= 成绩提示窗口 ========================================= --}}
                <div class="notice hide">
                    <div class="top">提示</div>
                    <div class="cen">答题时间已结束，系统自动提交</div>
                    <div class="bot" ms-click="seeExplain()">查看解析</div>
                </div>
                {{-- ========================================= 成绩提示窗口 ========================================= --}}
                <div style="height: 50px;width: 100%"></div>
                <div class="question_detail_score_submit" ms-click="submitAnswer(2)">提交</div>
                <div style="height: 50px;width: 100%"></div>
            </div>
            {{-- ============================= 课程同步练习（测试-已答）============================ --}}
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
                        <div class="student_test_content_repeat_one" ms-if="testInfo.sChoose.size()">
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
                                            <div class="one" ms-class-1="has_choose:isTrue($index, a.userAnswer, 1)" ms-class-2="no_choose:isTrue($index, a.userAnswer, 2)"></div>
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
                                    <div class="student_test_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_test_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                            </div>
                        </div>
                        {{-- 多选题 --}}
                        <div class="student_test_content_repeat_two" ms-if="testInfo.mChoose.size()">
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
                                            <div class="one" ms-class-1="has_choose:isMTrue($index, a.userAnswer, 1)" ms-class-2="no_choose:isMTrue($index, a.userAnswer, 2)"></div>
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
                                    <div class="student_test_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_test_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                            </div>
                        </div>
                        {{-- 判断题 --}}
                        <div class="student_test_content_repeat_five" ms-if="testInfo.judge.size()">
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
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight">
                                        <span>正确答案 ：</span>
                                        <span ms-text="a.answer == 1 ? '正确' : '错误'"></span>
                                    </div>
                                    <div class="student_test_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                            </div>
                        </div>
                        {{-- 填空题 --}}
                        <div class="student_test_content_repeat_three" ms-if="testInfo.completion.size()">
                            <div class="student_test_content_repeat_three_top">
                                四、填空题
                            </div>
                            <div class="student_test_content_repeat_3" ms-repeat-a="testInfo.completion">
                                <div class="student_test_content_repeat_top">
                                    <div class="student_test_content_repeat_top_question">
                                        <div class="one" ms-text="a.sort + '.'"></div>
                                        <div class="two">填空</div>
                                        <div class="three">
                                            <div ms-html="showTestTitle($index)"></div>
                                        </div>
                                        <div class="four" ms-html="'（' + a.score + '分）'"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="student_test_content_repeat_bot">
                                    <div class="student_test_content_repeat_bot_left" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="'正确答案 ：' + a.answer"></div>
                                    <div class="student_test_content_repeat_bot_right" ms-class-1="right_color:a.isRight" ms-class-2="false_color:!a.isRight" ms-text="a.isRight ? '恭喜你回答正确！' : '回答错误'"></div>
                                </div>
                            </div>
                        </div>
                        {{-- 解答题 --}}
                        <div class="student_test_content_repeat_four" ms-if="testInfo.subjective.size()">
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
                                        {{--<div class="student_test_content_repeat_cen_answer_top" ms-html="a.title"></div>--}}
                                        <div class="student_test_content_repeat_cen_answer_cen" ms-html="'答：' + a.userAnswer"></div>
                                        <div class="student_test_content_repeat_cen_answer_bot">
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
    <div style="height:215px"></div>
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
    </script>

@endsection
@section('js')
    <script type="text/javascript" src="{{asset('home/js/studentCourse/detail.js')}}"></script>
    <script>
        require(['/studentCourse/detail', '/studentCourse/synchroTest'], function (model, synchroTest) {
            model.getNotes();
            model.getSelectNote();
            model.getShareNotes();
            model.getShareSelectNote();
            model.courseId = '{{$data->id}}' || null;
            synchroTest.userId = '{{$userId}}' || null;
            if(window.location.hash){
                model.tab = window.location.hash.split('#')[1];
                model.tabs(model.tab);
            }
            if(model.currentIndex == 'intro'){
                $('.courseintro').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'list'){
                $('.courselist').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'test'){
                $('.synchrotest').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'note'){
                $('.coursenote').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'question'){
                $('.coursequestion').addClass('span_active').siblings().removeClass('span_active')
            }
            //问答接口
            model.getCourseAskData(model.courseId);
            //我的笔记
            model.getCourseMyNote(model.courseId);
            //共享笔记
            model.getCourseShareNote(model.courseId);
            synchroTest.getData('/studentCourse/getLeadLearn/' + model.courseId, 'GET', '', 'leadLearnInfo');
            synchroTest.getData('/studentCourse/getClassTeach/' + model.courseId, 'GET', '', 'classTeachInfo');
            synchroTest.getData('/studentCourse/getAfterClass/' + model.courseId, 'GET', '', 'afterClassInfo');
            synchroTest.init();
            // 答案数字转字母
            avalon.filters.changeCode = function(value){
                return String.fromCharCode(parseInt(value) + 65);
            };
            //获取课程目录
            model.getCourseChapter(model.courseId);
            avalon.scan();
        });
    </script>

@endsection