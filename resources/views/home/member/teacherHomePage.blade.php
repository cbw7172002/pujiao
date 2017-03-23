@extends('layouts.layoutHome')

@section('title', '教师个人主页')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/teacherHomePage.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">
@endsection

@section('content')

    <div class="h40"></div>
    <div class="container" ms-controller="teacherHomePage">
        <!--主体上方个人介绍-->
        <div class="teacherHomepage_introduce">
            <div class="introduce_left">
                <div class="introduce_left_top">
                    <div class="introduce_left_top_left">
                        <img ms-attr-src=" userInfo.pic " alt="" onerror="javascript:this.src='/home/image/layout/default.png';">
                    </div>

                    <div class="introduce_left_top_right">
                        <div class="introduce_left_top_right_left">
                            <div class="introduce_username" ms-html="userInfo.realname || userInfo.username"></div>
                            <div class="introduce_teacher" ms-html="'教师'"></div>
                        </div>
                    </div>
                </div>

                <div class="introduce_left_bottom">
                    <div class="introduce_left_bottom_sex" ms-html="userInfo.sex == 1 ? '男' : '女'"></div>
                    <div class="introduce_left_bottom_location" ms-attr-title="userInfo.school" ms-html="userInfo.school"></div>
                    <div class="introduce_left_bottom_timer"  ms-attr-title="userInfo.subjectNames" ms-html=" userInfo.subjectName"></div>
                </div>

            </div>


            <div class="introduce_right">
                <div class="introduce_right_video">
                    <div class="introduce_right_video_img"></div>
                    <div class="introduce_right_video_text" ms-html=" '课程' "></div>
                    <div class="introduce_right_video_number" ms-html="courseCount"></div>
                </div>
                <div class="introduce_right_video introduce_right_fans">
                    <div class="introduce_right_video_img"></div>
                    <div class="introduce_right_video_text" ms-html="'资源'"></div>
                    <div class="introduce_right_video_number" ms-html="resourceCount"></div>
                </div>
            </div>
        </div>
        <!--主体左边-->
        <div class="h40"></div>
        <div class="clear"></div>

        <div class="center_left">
            <div class="h10"></div>
            <div class="h5"></div>
            <div class="account_manager">我的通知</div>
            <span class="span_hover"></span>
            <div class="account_common " name='wholeNotice' ms-click="changeTab('wholeNotice')">全部通知</div>
            <span class="span_hover"></span>
            <div class="account_common" name='commentAnswer' ms-click="changeTab('commentAnswer')">评论回复</div>

            <div class="account_manager">问答管理</div>
            <span class="span_hover"></span>
            <div class="account_common" name="teacherCourseQa" ms-click="changeTab('teacherCourseQa')">课程问答</div>
            <span class="span_hover"></span>
            <div class="account_common" name="myAuditing" ms-click="changeTab('myAuditing')">社区问答</div>

            <div class="account_manager">资源管理</div>
            <span class="span_hover"></span>
            <div class="account_common" name="myResource" ms-click="changeTab('myResource')">我的资源</div>

            <div class="account_manager">我的收藏</div>
            <span class="span_hover"></span>
            <div class="account_common" name="resourceStore" ms-click="changeTab('resourceStore')">资源收藏</div>
            <span class="span_hover"></span>
            <div class="account_common" name="courseStore" ms-click="changeTab('courseStore')">课程收藏</div>
            <span class="span_hover"></span>
            <div class="account_common" name="examStore" ms-click="changeTab('examStore')">试题收藏</div>
            <span class="span_hover"></span>
            <div class="account_common" name="paperStore" ms-click="changeTab('paperStore')">试卷收藏</div>
            <span class="span_hover"></span>
            <div class="account_common" name="auditingStore" ms-click="changeTab('auditingStore')">问答收藏</div>

            <div class="account_manager">关注与好友</div>
            <span class="span_hover"></span>
            <div class="account_common" name="myFocus" ms-click="changeTab('myFocus', mineId)">我的关注</div>
            <span class="span_hover"></span>
            <div class="account_common" name="myFriends" ms-click="changeTab('myFriends', mineId)">我的好友</div>

            <div class="h20"></div>
        </div>

        <!--主体右边   全部通知开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'wholeNotice'" ms-controller="noticeController">
            {{--<div class="right_account_manager">全部通知</div>--}}
            <div class="right_resource_top">
                <span ms-html="'全部通知'"></span>
                <span ms-html=" noRead + '条未读'"></span>
            </div>
            <div class="notice_repeat" ms-if="noticeInfo.size() > 0 && !loading">
                <!--======== 我的通知循环开始 ========-->
                <div ms-repeat="noticeInfo">
                    <div class="notice_repeat_item">
                        <div class="notice_item_top">
                            <div class="icon"></div>
                            <a href="#wholeNotice" ms-class-1="no_read: (el.isRead === '0')" ms-class-2="has_read: (el.isRead === '1')" ms-text="el.content" ms-click="jumpTo(el)" target="_blank"></a>
                        </div>
                        <div class="notice_item_bottom">
                            <div class="notice_item_bottom_timer" ms-text="el.created_at"></div>
                            <div class="notice_item_bottom_delete" ms-click="popUpSwitch('deleteNotice',el.id)">删 除</div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <!--======== 我的通知循环结束 ========-->
            </div>
            <div class="clear"></div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="noticeMsg" class="warning_msg">暂无通知消息...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_notice"></div>
                </div>
            </div>
        </div>
        <!--主体右边   全部通知结束-->

        <!--主体右边   评论回复开始-->
        <div class="center_right hide " ms-visible="tabStatus === 'commentAnswer'" ms-controller="commentController">
            {{--<div class="right_account_manager">评论回复</div>--}}
            <div class="right_resource_top">
                <span ms-html="'评论回复'"></span>
                <span ms-html=" noRead + '条未读'"></span>
            </div>
            <div class="notice_repeat">
                <!--======== 我的通知循环开始 ========-->
                <div ms-repeat="commentInfo">
                    <div class="notice_repeat_item">
                        <div class="notice_item_top">
                            <div class="icon"></div>
                            <a href="#commentAnswer" ms-class-1="no_read: (el.isRead === '0')" ms-class-2="has_read: (el.isRead === '1')" ms-text="el.content" ms-click="jumpTo(el)" target="_blank"></a>
                        </div>
                        <div class="notice_item_bottom">
                            <div class="notice_item_bottom_timer" ms-text="el.created_at"></div>
                            <div class="notice_item_bottom_delete" ms-click="popUpSwitch('deleteComment',el.id)">删 除</div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <!--======== 我的通知循环结束 ========-->
                <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div ms-visible="answerMsg" class="warning_msg">暂无通知消息...</div>
            </div>
            <div class="clear"></div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_comment"></div>
                </div>
            </div>
        </div>
        <!--主体右边   评论回复结束-->


        <!--主体右边   课程管理-->
        <div class="center_right hide " ms-visible="tabStatus === 'teacherCourseQa'" ms-controller="teacherCourseQaController" >
            <div class="right_wait_answer">
                <div class="answer_title">课程回答</div>
                <div class="my_question question_blue" ms-click="getDate(1)">等待回答</div>
                <div class="my_answer " ms-click="getDate(2)">我的回答</div>
            </div>
            {{--内容--}}
            <div class="right_wait_answer_content" ms-if="teacherCourseQaInfo.size() > 0 && !loading">
                {{--循环内容--}}
                <div class="content_every" ms-repeat="teacherCourseQaInfo">
                    <div class="content_every_detail">
                        <a ms-attr-href="'/teacherCourse/teaDetail/'+el.id+'#question' " target="_blank">
                            <div class="content_every_detail_img">
                                <img ms-attr-src="el.pic"  alt="" width="60" height="60">
                            </div>
                        </a>
                        <div class="content_every_detail_sum">
                            <div class="content_every_detail_top" ms-html="'来自 '+el.courseTitle">来自 &nbsp; 绘画</div>
                            <div class="content_every_detail_bottom">
                                <a ms-attr-href="'/teacherCourse/teaDetail/'+el.id"><div ms-html="el.content"></div></a>
                            </div>
                        </div>
                        <div class="content_every_detail_time">
                            <div class="content_every_detail_time_time" ms-html="el.asktime | truncate(12,' ')">2017-1-12</div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="teaAnswerMsg" class="warning_msg">暂无课程问答...</div>
            <div class="clear"></div>
            <div ms-if="page" class="pagecon_parent" style="margin-top:40px;">
                <div class="pagecon">
                    <div id="page_questionQa"></div>
                </div>
            </div>
        </div>
        <!--主体右边   课程管理-->


        <!--主体右边   问答管理--我的回答-->
        <div class="center_right hide " ms-visible="tabStatus === 'myAuditing'"  ms-controller="questionController" >

            <div class="right_wait_answer">
                <div class="answer_title">社区问答</div>
                <div class="my_question question_blue" ms-click="getDate(1)">我的提问</div>
                <div class="my_answer " ms-click="getDate(2)">我的回答</div>
            </div>
            {{--内容--}}
            <div class="right_wait_answer_content" ms-if="!questionMsg" ms-if="questionInfo.size() > 0 && !loading">
                {{--循环内容--}}
                <div class="content_every" ms-repeat="questionInfo">
                    <div class="content_every_detail">
                        <a ms-attr-href="'/community/askDetail/'+el.id" target="_blank">
                        <div class="content_every_detail_img">
                            <img ms-attr-src="el.pic"  alt="" width="60" height="60">
                        </div>
                        </a>

                        <div class="content_every_detail_sum">
                            <div class="content_every_detail_top" ms-html="'来自 '+el.type">来自 &nbsp; 绘画</div>
                            <div class="content_every_detail_bottom">
                                <a ms-attr-href="'/community/askDetail/'+el.id" target="_blank"><div ms-html="el.qestitle">如何快速提升素描造型能力?</div></a>
                            </div>
                        </div>
                        <div class="content_every_detail_time" >
                            <div class="content_every_detail_time_type" ms-if="el.status == 1 && el.qa == 1">未解答</div>
                            <div class="content_every_detail_time_type" ms-if="el.status == 2 && el.qa == 1">已解答</div>
                            <div  class="content_every_detail_time_time" ms-html="el.asktime | truncate(12,' ')">2017-1-12</div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="questionMsg" class="warning_msg">暂无社区问答...</div>
            <div class="clear"></div>
            <div ms-if="page" class="pagecon_parent" style="margin-top:40px;">
                <div class="pagecon">
                    <div id="page_question"></div>
                </div>
            </div>
        </div>
        <!--主体右边   问答管理--我的回答结束-->

        <!--主体右边   我的资源开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'myResource'" ms-controller="myResource">

            <div class="right_resource_different">
                <div class="right_resource_type_list">
                    <ul>
                        <li ms-click="getMyResourceInfo(0)" class="resource_font_diff" ms-if="resourceTypeList.size() > 0" ms-html="'全部'"></li>
                        <li ms-repeat="resourceTypeList" ms-click="getMyResourceInfo(el.id)" ms-html="el.name"></li>
                    </ul>
                </div>
                <div class="right_resource_item_sum" ms-if="resourceTypeList.size() > 0">
                    <span ms-html="'共' + total + '个结果'"></span>
                </div>
            </div>
            <div class="clear"></div>
            <div style="width: 840px; height: 1px; border-bottom: 1px solid #f5f5f5"></div>

            <div class="resource_content" ms-if="myResourceInfo.size() > 0 && !loading">
                <!--repeat开始-->
                <div class="resource_content_repeat" ms-repeat="myResourceInfo">
                    <a ms-attr-href="'/resource/resDetail/' + el.id" ms-if="el.passCode == 2" target="_blank">
                        <div class="resource_repeat_left">
                            <img ms-attr-src="el.resourcePic" alt="" width="120" height="90">
                        </div>
                        <div class="resource_repeat_right">
                            <div class="resource_repeat_right_first" ms-html="el.resourceTitle" style="width: 80%;"></div>

                            <div class="resource_repeat_right_second">
                                <span ms-html="el.resourceEdition"></span>
                                <span ms-html="el.resourceGrade + el.resourceSubject + el.resourceBook"></span>
                            </div>
                            <div class="resource_repeat_right_third">
                                <span ms-html="'上传者 : ' + (el.realname || el.username )"></span>
                                <span ms-html="'上传时间 : ' + (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></span>
                            </div>
                            <div class="resource_repeat_right_last">
                                <div class="resource_repeat_right_last_left"></div>
                                <div class="resource_repeat_right_last_right">
                                    <span ms-html="'浏览 : '+' '+ el.resourceView"></span>
                                    <span ms-html="'下载 : '+' '+ el.resourceDownload"></span>
                                    <span ms-html="'收藏 : '+' '+ el.resourceFav"></span>
                                </div>

                            </div>

                        </div>
                    </a>
                    <div class="resource_item_button" ms-click="deleteMyResource(el.id)">删除资源</div>

                    <a ms-if="el.passCode != 2" ms-click="popUpSwitch('resourceMessage', 1)" style="cursor: pointer;">
                        <div class="resource_repeat_left">
                            <img ms-attr-src="el.resourcePic" alt="" width="120" height="90">
                        </div>

                        <div class="resource_repeat_right">
                            <div class="resource_repeat_right_first" style="width: 80%;">
                                <div class="right_first_code" ms-html="el.resourceTitle"></div>
                                <div class="right_second_code" ms-html="el.passCode == 1 ? '转码中...' : '转码失败...'" ms-if="el.passCode != 2"></div>
                            </div>
                            <div class="resource_repeat_right_second">
                                <span ms-html="el.resourceEdition"></span>
                                <span ms-html="el.resourceGrade + el.resourceSubject + el.resourceBook"></span>
                            </div>
                            <div class="resource_repeat_right_third">
                                <span ms-html="'上传者 : ' + (el.realname || el.username )"></span>
                                <span ms-html="'上传时间 : ' + (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></span>
                            </div>
                            <div class="resource_repeat_right_last">
                                <div class="resource_repeat_right_last_left"></div>
                                <div class="resource_repeat_right_last_right">
                                    <span ms-html="'浏览 : '+' '+ el.resourceView"></span>
                                    <span ms-html="'下载 : '+' '+ el.resourceDownload"></span>
                                    <span ms-html="'收藏 : '+' '+ el.resourceFav"></span>
                                </div>

                            </div>

                        </div>
                    </a>
                </div>
                <!--repeat结束-->
            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="myResourceMsg" class="warning_msg">暂无我的资源...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_myResource"></div>
                </div>
            </div>

        </div>
        <!--主体右边   wode资源结束-->

        <!--主体右边   资源收藏开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'resourceStore'" ms-controller="resourceStore">

            {{--<div class="right_resource_top">--}}
                {{--<ul>--}}
                    {{--<li ms-click="getCollectionInfo(0)" class="resource_font_diff" ms-if="resourceTypeList.size() > 0" ms-html="'全部'"></li>--}}
                    {{--<li ms-repeat="resourceTypeList" ms-click="getCollectionInfo(el.id)" ms-html="el.name"></li>--}}
                {{--</ul>--}}
            {{--</div>--}}

            <div class="right_resource_different">
                <div class="right_resource_type_list">
                    <ul>
                        <li ms-click="getCollectionInfo(0)" class="resource_font_diff" ms-if="resourceTypeList.size() > 0" ms-html="'全部'"></li>
                        <li ms-repeat="resourceTypeList" ms-click="getCollectionInfo(el.id)" ms-html="el.name"></li>
                    </ul>
                </div>
                <div class="right_resource_item_sum" ms-if="resourceTypeList.size() > 0">
                    <span ms-html="'共' + total + '个结果'"></span>
                </div>
            </div>
            <div class="clear"></div>
            <div style="width: 840px; height: 1px; border-bottom: 1px solid #f5f5f5"></div>

            <div class="resource_content" ms-if="collectionInfo.size() > 0 && !loading">
                <!--repeat开始-->
                <div class="resource_content_repeat" ms-repeat="collectionInfo">
                    <a ms-attr-href="'/resource/resDetail/' + el.id" ms-if="el.id" target="_blank">
                        <div class="resource_repeat_left">
                            <img ms-attr-src="el.resourcePic" alt="" width="120" height="90">
                        </div>

                        <div class="resource_repeat_right">
                            <div class="resource_repeat_right_first" ms-html="el.resourceTitle"></div>
                            <div class="resource_repeat_right_second">
                                <span ms-html="el.resourceEdition"></span>
                                <span ms-html="el.resourceGrade + el.resourceSubject + el.resourceBook"></span>
                            </div>
                            <div class="resource_repeat_right_third">
                                <span ms-html="'上传者 : ' + (el.realname || el.username )"></span>
                                <span ms-html="'上传时间 : ' + (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></span>
                            </div>
                            <div class="resource_repeat_right_last">
                                <div class="resource_repeat_right_last_left"></div>
                                <div class="resource_repeat_right_last_right">
                                    <span ms-html="'浏览 : '+' '+ el.resourceView"></span>
                                    <span ms-html="'下载 : '+' '+ el.resourceDownload"></span>
                                    <span ms-html="'收藏 : '+' '+ el.resourceFav"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a ms-if="!el.id" ms-click="popUpSwitch('resourceMessage', 2, el.storeId)" style="cursor: pointer;">
                        <div class="resource_repeat_left">
                            <img ms-attr-src="el.resourcePic" alt="" width="120" height="90" onerror="javascript:this.src='/home/image/resource/word.png';">
                        </div>

                        <div class="resource_repeat_right">
                            <div class="resource_repeat_right_first" ms-html="el.resourcetitle || '暂无'"></div>
                            <div class="resource_repeat_right_second">
                                <span ms-html="el.resourceEdition"></span>
                                <span ms-html="el.resourceGrade + el.resourceSubject + el.resourceBook"></span>
                            </div>
                            <div class="resource_repeat_right_third">
                                <span ms-html="'上传者 : ' + (el.realname || el.username )"></span>
                                <span ms-html="'上传时间 : ' + (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></span>
                            </div>
                            <div class="resource_repeat_right_last">
                                <div class="resource_repeat_right_last_left"></div>
                                <div class="resource_repeat_right_last_right">
                                    <span ms-html="'浏览 : '+' '+ (el.resourceView || 0)"></span>
                                    <span ms-html="'下载 : '+' '+ (el.resourceDownload || 0)"></span>
                                    <span ms-html="'收藏 : '+' '+ (el.resourceFav || 0)"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!--repeat结束-->
            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="collectionMsg" class="warning_msg">暂无资源收藏...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_resourceStore"></div>
                </div>
            </div>

        </div>
        <!--主体右边   资源收藏结束-->

        <!--主体右边   课程收藏开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'courseStore'" ms-controller="courseStore">
            <div class="right_resource_top">
                <span ms-html="'课程收藏'"></span>
                <span ms-html=" '共' + total + '个结果'" style="width: 31%;"></span>
            </div>

            <div class="resource_content" ms-if="courseStoreInfo.size() > 0 && !loading">
                <!--repeat开始-->
                <div class="resource_content_repeat" ms-repeat="courseStoreInfo">
                    <a ms-attr-href="'/teacherCourse/teaDetail/' + el.courseId" ms-if="el.courseId" target="_blank" >
                        <div class="resource_repeat_left">
                            <img ms-attr-src="el.coursePic" alt="" width="120" height="90">
                        </div>

                        <div class="resource_repeat_right">
                            <div class="resource_repeat_right_first" ms-html="el.courseTitle"></div>
                            <div class="resource_repeat_right_second">
                                <span ms-html="el.editionId"></span>
                                <span ms-html="el.gradeId + el.subjectId + el.bookId"></span>
                            </div>
                            <div class="resource_repeat_right_third">
                                <span ms-html="'发布者 : ' + (el.realname || el.username)"></span>
                                <span ms-html="'发布时间 : ' + (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></span>
                            </div>
                            <div class="resource_repeat_right_last">
                                <div class="resource_repeat_right_last_left"></div>
                                <div class="course_repeat_right_last_right">
                                    <span ms-html="'学习'+' '+ el.courseStudyNum"></span>
                                    <span ms-html="'收藏'+' '+ el.courseFav"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a ms-if="!el.courseId" ms-click="popUpSwitch('resourceMessage', 3, el.storeId)" style="cursor: pointer;">
                        <div class="resource_repeat_left">
                            <img ms-attr-src="el.coursePic || '/home/image/teacherCourse/cover/6.png'" alt="" width="120" height="90">
                        </div>

                        <div class="resource_repeat_right">
                            <div class="resource_repeat_right_first" ms-html="el.resourcetitle"></div>
                            <div class="resource_repeat_right_second">
                                <span ms-html="el.editionId"></span>
                                <span ms-html="el.gradeId + el.subjectId + el.bookId"></span>
                            </div>
                            <div class="resource_repeat_right_third">
                                <span ms-html="'发布者 : ' + (el.realname || el.username)"></span>
                                <span ms-html="'发布时间 : ' + (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></span>
                            </div>
                            <div class="resource_repeat_right_last">
                                <div class="resource_repeat_right_last_left"></div>
                                <div class="course_repeat_right_last_right">
                                    <span ms-html="'学习'+' '+ (el.courseStudyNum || 0)"></span>
                                    <span ms-html="'收藏'+' '+ (el.courseFav || 0 )"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!--repeat结束-->
            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="courseStoreMsg" class="warning_msg">暂无课程收藏...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_courseStore"></div>
                </div>
            </div>

        </div>
        <!--主体右边   课程收藏结束-->

        <!--主体右边   试题收藏开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'examStore'" ms-controller="examStore">
            <div class="right_resource_top">
                <span ms-html="'试题收藏'"></span>
                <span ms-html=" '共' + total + '个结果'" style="width: 30.5%;"></span>
            </div>
            <div class="h20"></div>
            <div ms-if="count">
                <div class="right_subject">
                    <div class="left_subject">科目</div>
                    <div class="middle_subject">
                        <div class="middle_subject_item subject_font_diff" ms-class="subject_font_diff: 0 == selSubjectId" ms-click="selecting(1, 0)" ms-html="'全部'" ms-sel></div>
                        <div class="middle_subject_item" ms-repeat="subjectList" ms-class="subject_font_diff: el.id == selSubjectId" ms-if-loop="$index < gradesShowNum" ms-html="el.subjectName" ms-click="selecting(1, el.id)" ms-sel></div>
                    </div>
                    <div class="right_subject_flag down" ms-if="subjectList.length > 9" ms-click="selMoreShow()"></div>
                </div>
                <div class="clear"></div>
                <div class="h10"></div>
                <div class="right_subject">
                    <div class="left_subject">题型</div>
                    <div class="middle_subject">
                        <div class="middle_subject_item subject_font_diff" ms-class="subject_font_diff: 0 == selTypeId" ms-html="'全部'" ms-click="selecting(2, 0)" ms-sel></div>
                        <div class="middle_subject_item" ms-class="subject_font_diff: 1 == selTypeId" ms-html="'单选'" ms-click="selecting(2, 1)" ms-sel></div>
                        <div class="middle_subject_item" ms-class="subject_font_diff: 2 == selTypeId" ms-html="'多选'" ms-click="selecting(2, 2)" ms-sel></div>
                        <div class="middle_subject_item" ms-class="subject_font_diff: 3 == selTypeId" ms-html="'判断'" ms-click="selecting(2, 3)" ms-sel></div>
                        <div class="middle_subject_item" ms-class="subject_font_diff: 4 == selTypeId" ms-html="'填空'" ms-click="selecting(2, 4)" ms-sel></div>
                        <div class="middle_subject_item" ms-class="subject_font_diff: 5 == selTypeId" ms-html="'问答'" ms-click="selecting(2, 5)" ms-sel></div>
                    </div>
                </div>
            </div>

            <div class="clear"></div>
            <div ms-if="examStoreInfo.size() > 0 && !loading">
                <!--repeat start-->
                <div ms-repeat="examStoreInfo">
                    <div class="question_content" ms-if="el.examType == 1 && !!el.examId">
                        <div class="question_border">
                            <div class="question_border_top">
                                <div class="question_border_top_number" ms-html="'题号 : ' + '&nbsp;' + el.examId"></div>
                                <div class="question_border_top_number" ms-html="'题型: 单选题'"></div>
                                <div class="question_border_top_number" ms-html="'日期: '+ (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></div>
                                <div class="question_border_top_number" ms-html="'难易度: ' + (el.difficult < 3 ? '一般' : '困难')"></div>
                                <div class="question_border_top_number question_border_top_img_red" ms-click="collection(el.id)">
                                    取消收藏
                                </div>
                            </div>
                            <div ms-shoudetail>
                                <div class="question_border_title" ms-html="el.title"></div>
                                <div class=" font_diff" ms-repeat-choice="el.choice">
                                    <span ms-html="$index|changeCode"></span>
                                    <span ms-html="'、' + choice"></span>
                                </div>
                            </div>

                        </div>
                        <div class="clear"></div>
                        <div class="question_answer hide">
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'答案'"></div>
                                <div class="question_answer_top_right" ms-html="el.answer || '略'"></div>
                            </div>
                            <div class="clear"></div>
                            <div class="h10"></div>
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'解析'"></div>
                                <div class="question_answer_top_right" ms-html="el.analysis || '略'"></div>
                            </div>
                        </div>
                    </div>
                    <div class="question_content" ms-if="el.examType == 2 && !!el.examId">
                        <div class="question_border">
                            <div class="question_border_top">
                                <div class="question_border_top_number" ms-html="'题号 : ' + '&nbsp;' + el.examId"></div>
                                <div class="question_border_top_number" ms-html="'题型: 多选题'"></div>
                                <div class="question_border_top_number" ms-html="'日期: '+ (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></div>
                                <div class="question_border_top_number" ms-html="'难易度: ' + (el.difficult < 3 ? '一般' : '困难')"></div>
                                <div class="question_border_top_number question_border_top_img_red" ms-click="collection(el.id)">
                                    取消收藏
                                </div>
                            </div>
                            <div ms-shoudetail>
                                <div class="question_border_title" ms-html="el.title"></div>
                                <div class=" font_diff" ms-repeat-choice="el.choice">
                                    <span ms-text="$index|changeCode"></span>
                                    <span ms-html="'、' + choice"></span>
                                </div>
                            </div>

                        </div>
                        <div class="clear"></div>
                        <div class="question_answer hide">
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'答案'"></div>
                                <div class="question_answer_top_right" ms-html="el.answer|strReplace"></div>
                            </div>
                            <div class="clear"></div>
                            <div class="h10"></div>
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'解析'"></div>
                                <div class="question_answer_top_right" ms-html="el.analysis|strReplace"></div>
                            </div>
                        </div>
                    </div>
                    <div class="question_content" ms-if="el.examType == 3 && !!el.examId">
                        <div class="question_border">
                            <div class="question_border_top">
                                <div class="question_border_top_number" ms-html="'题号 : ' + '&nbsp;' + el.examId"></div>
                                <div class="question_border_top_number" ms-html="'题型: 判断题'"></div>
                                <div class="question_border_top_number" ms-html="'日期: '+ (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></div>
                                <div class="question_border_top_number" ms-html="'难易度: ' + (el.difficult < 3 ? '一般' : '困难')"></div>
                                <div class="question_border_top_number question_border_top_img_red" ms-click="collection(el.id)">
                                    取消收藏
                                </div>
                            </div>
                            <div ms-shoudetail>
                                <div class="question_border_title" ms-html="el.title"></div>
                            </div>

                        </div>
                        <div class="clear"></div>
                        <div class="question_answer hide">
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'答案'"></div>
                                <div class="question_answer_top_right" ms-if="el.answer == 1" ms-html="'正确'"></div>
                                <div class="question_answer_top_right" ms-if="el.answer == 0" ms-html="'错误'"></div>
                            </div>
                            <div class="clear"></div>
                            <div class="h10"></div>
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'解析'"></div>
                                <div class="question_answer_top_right" ms-html="el.analysis || '略'"></div>
                            </div>
                        </div>
                    </div>
                    <div class="question_content" ms-if="el.examType == 4 && !!el.examId">
                        <div class="question_border">
                            <div class="question_border_top">
                                <div class="question_border_top_number" ms-html="'题号 : ' + '&nbsp;' + el.examId"></div>
                                <div class="question_border_top_number" ms-html="'题型: 填空题'"></div>
                                <div class="question_border_top_number" ms-html="'日期: '+ (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></div>
                                <div class="question_border_top_number" ms-html="'难易度: ' + (el.difficult < 3 ? '一般' : '困难')"></div>
                                <div class="question_border_top_number question_border_top_img_red" ms-click="collection(el.id)">
                                    取消收藏
                                </div>
                            </div>
                            <div ms-shoudetail>
                                <div class="question_border_title" ms-html="el.title"></div>
                            </div>

                        </div>
                        <div class="clear"></div>
                        <div class="question_answer hide">
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'答案'"></div>
                                <div class="question_answer_top_right" ms-html="el.answer"></div>
                            </div>
                            <div class="clear"></div>
                            <div class="h10"></div>
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'解析'"></div>
                                <div class="question_answer_top_right" ms-html="el.analysis || '略'"></div>
                            </div>
                        </div>
                    </div>
                    <div class="question_content" ms-if="el.examType == 5 && !!el.examId">
                        <div class="question_border">
                            <div class="question_border_top">
                                <div class="question_border_top_number" ms-html="'题号 : ' + '&nbsp;' + el.examId"></div>
                                <div class="question_border_top_number" ms-html="'题型: 解答题'"></div>
                                <div class="question_border_top_number" ms-html="'日期: '+ (el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无')"></div>
                                <div class="question_border_top_number" ms-html="'难易度: ' + (el.difficult < 3 ? '一般' : '困难')"></div>
                                <div class="question_border_top_number question_border_top_img_red" ms-click="collection(el.id)">
                                    取消收藏
                                </div>
                            </div>
                            <div ms-shoudetail>
                                <div class="question_border_title" ms-html="el.title"></div>
                            </div>

                        </div>
                        <div class="clear"></div>
                        <div class="question_answer hide">
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'答案'"></div>
                                <div class="question_answer_top_right" ms-html="el.answer"></div>
                            </div>
                            <div class="clear"></div>
                            <div class="h10"></div>
                            <div class="question_answer_top">
                                <div class="question_answer_top_left" ms-html="'解析'"></div>
                                <div class="question_answer_top_right" ms-html="el.analysis || '略'"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--repeat over-->
            </div>
            <div class="clear"></div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-if="examStoreMsg" class="warning_msg" style="height: 340px;line-height: 340px;">暂无试题收藏...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_examStore"></div>
                </div>
            </div>
        </div>
        <!--主体右边   试题收藏结束-->

        <!--主体右边   试卷收藏开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'paperStore'" ms-controller="paperStore">
            {{--<div class="right_account_manager">试卷收藏</div>--}}
            <div class="right_resource_top">
                <span ms-html="'试题收藏'"></span>
                <span ms-html=" '共' + total + '个结果'" style="width: 29%;"></span>
            </div>
            <div class="h20"></div>
            <div ms-if="count">
                <div class="right_subject">
                    <div class="left_subject" ms-html="'科目'"></div>
                    <div class="middle_subject">
                        <div class="middle_subject_item subject_font_diff" ms-class="subject_font_diff: 0 == selSubjectId" ms-click="selecting(1, 0)" ms-html="'全部'" ms-sel></div>
                        <div class="middle_subject_item" ms-repeat="subjectLists" ms-class="subject_font_diff: el.id == selSubjectId" ms-if-loop="$index < gradesShowNum" ms-html="el.subjectName" ms-click="selecting(1, el.id)" ms-sel></div>
                    </div>
                    <div class="right_subject_flag down" ms-if="subjectLists.length > 9" ms-click="selMoreShow('a')"></div>
                </div>
                <div class="clear"></div>
                <div class="h10"></div>
                <div class="right_subject">
                    <div class="left_subject" ms-html="'类型'"></div>
                    <div class="middle_subject">
                        <div class="middle_subject_item subject_font_diff" ms-class="subject_font_diff: 1000 == selTypeId" ms-click="selecting(2, 1000)" ms-html="'全部'" ms-sel></div>
                        <div class="middle_subject_item" ms-class="subject_font_diff: 1 == selTypeId" ms-click="selecting(2, 1)" ms-html="'测验'" ms-sel></div>
                        <div class="middle_subject_item" ms-class="subject_font_diff: 0 == selTypeId" ms-click="selecting(2, 0)" ms-html="'作业'" ms-sel></div>
                    </div>
                </div>
            </div>

            <div class="clear"></div>

            <div class="right_paper" ms-if="paperStoreInfo.size() > 0 && !loading">
                <div class="paper_top" ms-if="count">
                    <div class="paper_top_paper_number">序号</div>
                    <div class="paper_top_paper_title">试卷标题</div>
                    <div class="paper_top_paper_attr">试卷属性</div>
                </div>

                <!--试卷收藏  循环开始-->
                <div class="paper_repeat" ms-repeat="paperStoreInfo">
                    <div class="paper_repeat_number" ms-html="el.id"></div>
                    <a ms-attr-href="'/evaluateManageTea/testPaperTea/' + el.id" target="_blank"><div class="paper_repeat_title" ms-html="el.title"></div></a>
                    <div class="paper_repeat_attr">
                        <span ms-html="el.gradeId"></span>
                        <span ms-html="el.subjectId"></span>
                        <span ms-html="el.editionId"></span>
                        <span ms-html="el.bookId"></span>
                    </div>
                </div>
                <!--试卷收藏  循环结束-->
            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-if="paperStoreMsg" class="warning_msg" style="height: 400px;line-height: 400px;">暂无试卷收藏...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_paperStore"></div>
                </div>
            </div>
        </div>
        <!--主体右边   试卷收藏结束-->


        <!--主体右边   问答收藏开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'auditingStore'"  ms-controller="answerStore">
            {{--<div class="right_account_manager" ms-html="'问答收藏'"></div>--}}
            <div class="right_resource_top">
                <span ms-html="'试题收藏'"></span>
                <span ms-html=" '共' + total + '个结果'" style="width: 23%;"></span>
            </div>
            <div class="right_answer" ms-if="answerStoreInfo.size() > 0 && !loading">
                <!-- 循环开始 -->
                <div class="answer_repeat" ms-repeat="answerStoreInfo">
                    <a ms-attr-href="'/community/askDetail/' + el.questionId" target="_blank">
                        <div class="answer_repeat_item">
                            <div class="answer_repeat_item_img">
                                <img ms-attr-src="el.pic" alt="" width="60" height="60">
                            </div>

                            <div class="answer_repeat_item_right">
                                <div class="answer_repeat_item_right_from">
                                    <span ms-html="'来自'"></span>
                                    <span ms-html="el.subjectName ? el.subjectName : ''"></span>
                                </div>

                                <div class="answer_repeat_item_right_body">
                                    <div class="right_body_question" ms-html="el.qestitle"></div>
                                    <div class="right_body_timer" ms-html="el.created_at ? avalon.filters.sliceTime(el.created_at, 'year') : '暂无'"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- 循环结束 -->
            </div>
            <div class="clear"></div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="answerStoreMsg" class="warning_msg">暂无问答收藏...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_answerStore"></div>
                </div>
            </div>

        </div>
        <!--主体右边   问答收藏结束-->

        <!--主体右边   我的关注开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'myFocus'" ms-controller="myFocusTeacher">
            <div class="right_resource_top">
                <span ms-html="'我的关注'"></span>
                <span ms-html=" '共' + total + '个结果'" style="width: 23.2%;"></span>
            </div>
            <div class="h20"></div>

            {{--//我的关注--}}
            <div class="center_right_focus" ms-if="myFocusList.size() > 0 && !loading">
                {{--===============================我的关注循环开始====================================--}}
                <div class="right_focus_repeat" ms-repeat="myFocusList">
                    <!-- 教师 -->
                    <a ms-if="el.type == 1" ms-attr-href="'/member/studentHomePagePublic/'+el.id" target="_blank">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                    <!-- 学生 -->
                    <a ms-if="el.type == 2" ms-attr-href="'/member/teacherHomePagePublic/'+el.id" target="_blank">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                </div>
                {{--===============================我的关注循环结束====================================--}}

            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="myFocus" class="warning_msg" style="height: 280px;line-height: 280px;">暂无关注...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_focus"></div>
                </div>
            </div>
        </div>
        <!--主体右边   我的关注结束-->

        <!--主体右边   我的好友开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'myFriends'" ms-controller="myFansTeacher">
            {{--<div class="center_right_top">--}}
                {{--<div class="center_right_information" ms-html="'我的好友'"></div>--}}
                {{--<div class="center_right_count">共<span ms-html="' ' + total + ' '"></span>个好友</div>--}}
            {{--</div>--}}
            <div class="right_resource_top">
                <span ms-html="'我的关注'"></span>
                <span ms-html=" '共' + total + '个结果'" style="width: 23.2%;"></span>
            </div>
            <div class="h20"></div>

            {{--//我的关注--}}
            <div class="center_right_focus" ms-if="myFansList.size() > 0 && !loading">
                {{--===============================我的好友循环开始====================================--}}
                <div class="right_focus_repeat" ms-repeat="myFansList">
                    <!-- 教师 -->
                    <a ms-if="el.type == 1" ms-attr-href="'/member/studentHomePagePublic/'+el.id" target="_blank">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                    <!-- 学生 -->
                    <a ms-if="el.type == 2" ms-attr-href="'/member/teacherHomePagePublic/'+el.id" target="_blank">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                </div>
            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="myFans" class="warning_msg" style="height: 280px;line-height: 280px;">暂无好友...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_friend"></div>
                </div>
            </div>
        </div>
        <!--主体右边   我的好友结束-->

        <!-- 遮罩层 -->
        <div class="shadow hide" id="shadow" ms-popup="popUp" value="close"></div>
        <!-- 删除通知弹出层 -->
        <div class="delete_comment hide" ms-popup="popUp" value="deleteNotice">
            <div class="top">
                <span>确认删除该通知？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch('sureNotice')">确定</span>
            </div>
        </div>
        <!-- 删除评论弹出层 -->
        <div class="delete_comment hide" ms-popup="popUp" value="deleteComment">
            <div class="top">
                <span>确认删除该通知？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch('sureComment')">确定</span>
            </div>
        </div>
        <!--资源转码中提示弹窗-->
        <div class="warning_resource hide" ms-popup="popUp" value="resourceMessage">
            <div class="top_title">
                <div>温馨提示</div>
                <span ms-click="popUpSwitch(false)"></span>
            </div>
            <div class="middle_content" ms-html="resourceMessage"></div>
            <div class="bot_button">
                <span class="sure" ms-html="resourceBtn" ms-click="deleteStore(storeType, storeId)"></span>
            </div>
        </div>

        <div class="imgZoom hide" ms-class="hide: !showImg">
            <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
            <img ms-attr-src="imgZoom">
        </div>

    </div>
    <div class="clear"></div>

    <div class="h5"></div>
    <div class="h5"></div>
    <div class="h20"></div>

@endsection
@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script type="text/javascript">
        require(['/personCenter/directive.js', '/personCenter/teacherHomePage.js'], function (directive, teacherHomePage) {
            teacherHomePage.mineId = '{{$id}}' || null;
            teacherHomePage.mineUsername = '{{$mineUsername}}' || null;
            teacherHomePage.init();
            if (window.location.hash) {
                teacherHomePage.tabStatus = window.location.hash.split('#')[1];
                teacherHomePage.changeTab(teacherHomePage.tabStatus, teacherHomePage.mineId );
            } else {
                teacherHomePage.tabStatus = 'wholeNotice';
                teacherHomePage.changeTab(teacherHomePage.tabStatus, teacherHomePage.mineId );
            }
            teacherHomePage.getData('/member/getSubjects', 'subjectList',  { data: {userId: teacherHomePage.mineId, type: 2}}, 'POST') ;
            teacherHomePage.getData('/member/getSubjects', 'subjectLists', { data: {userId: teacherHomePage.mineId, type: 3}}, 'POST') ;
            teacherHomePage.getData('/member/getResourceType', 'resourceTypeList', {table: 'resourcetype'}, 'POST');
            teacherHomePage.getData('/member/getUserInfo/' + teacherHomePage.mineId, 'userInfo');
            teacherHomePage.getData('/member/getCount', 'courseCount', {table: 'course', action: 1, data: {teacherId: teacherHomePage.mineId, courseIsDel: 0}}, 'POST');
            teacherHomePage.getData('/member/getCount', 'resourceCount', {table: 'resource', action: 1, data: {userId: teacherHomePage.mineId, resourceIsDel: 0}}, 'POST');


        //日期过滤器
        avalon.filters.sliceTime = function(str,type){
            return type == 'year' ? str.slice(0,10) : str.slice(11,19);
        };
        // 答案数字转字母
        avalon.filters.changeCode = function(value){
            return String.fromCharCode(parseInt(value) + 65);
        };
        // 答案特殊字符替换
        avalon.filters.strReplace = function(value){
            return value.replace(/┼┼/g, "、");
        };
        // 答案特殊字符替换
        avalon.filters.difficult = function(value){
            var difficult = '';
            if(value == 1) difficult = '极易';
            if(value == 2) difficult = '简单';
            if(value == 3) difficult = '一般';
            if(value == 4) difficult = '困难';
            if(value == 5) difficult = '极难';
            return difficult;
        };

        avalon.scan();
        });
    </script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/teacherHomePage.js')}}"></script>
@endsection




