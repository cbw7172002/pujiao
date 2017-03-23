@extends('layouts.layoutHome')

@section('title', '学生个人主页')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/studentHomePage.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">
@endsection

@section('content')

    <div class="h40"></div>
    <div class="container" ms-controller="studentHomePage">
        <!--主体上方个人介绍-->
        <div class="teacherHomepage_introduce">
            <div class="introduce_left">
                <div class="introduce_left_top">
                    <div class="introduce_left_top_left">
                        <img ms-attr-src="userInfo.pic" alt="" onerror="javascript:this.src='/home/image/layout/default.png';">
                    </div>

                    <div class="introduce_left_top_right">
                        <div class="introduce_left_top_right_left">
                            <div class="introduce_username" ms-html="userInfo.realname || userInfo.username"></div>
                            <div class="introduce_teacher" ms-html="'学生'"></div>
                        </div>
                    </div>
                </div>

                <div class="introduce_left_bottom">
                    <div class="introduce_left_bottom_sex" ms-html="userInfo.sex == 1 ? '男' : '女'"></div>
                    <div class="introduce_left_bottom_location" ms-html="userInfo.school"></div>
                    <div class="introduce_left_bottom_timer" ms-html="(userInfo.gradeId + userInfo.classId) || '暂无'"></div>
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
            <div class="account_common blue_common" name="wholeNotice" ms-click="changeTab('wholeNotice')">全部通知</div>
            <span class="span_hover"></span>
            <div class="account_common" name="commentAnswer" ms-click="changeTab('commentAnswer')">评论回复</div>

            <div class="account_manager">问答管理</div>
            <span class="span_hover"></span>
            <div class="account_common" name="studentCourseQa" ms-click="changeTab('studentCourseQa')">课程问答</div>
            <span class="span_hover"></span>
            <div class="account_common" name="myAuditing" ms-click="changeTab('myAuditing')">社区问答</div>

            <div class="account_manager">我的收藏</div>
            <span class="span_hover"></span>
            <div class="account_common" name="resourceStore" ms-click="changeTab('resourceStore')">资源收藏</div>
            <span class="span_hover"></span>
            <div class="account_common" name="courseStore" ms-click="changeTab('courseStore')">课程收藏</div>
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
            <div class="right_account_manager">全部通知</div>
            <div class="notice_repeat" ms-if="noticeInfo.size() > 0 && !loading">
                <!--======== 我的通知循环开始 ========-->
                <div ms-repeat="noticeInfo">
                    <div class="notice_repeat_item">
                        <div class="notice_item_top">
                            <div class="icon"></div>
                            <a href="#" ms-class-1="no_read: (el.isRead === '0')" ms-class-2="has_read: (el.isRead === '1')" ms-text="el.content" ms-click="jumpTo(el)"></a>
                        </div>
                        <div class="notice_item_bottom">
                            <div class="notice_item_bottom_timer" ms-text="el.created_at"></div>
                            <div class="notice_item_bottom_delete" ms-click="popUpSwitch('deleteNotice',el.id)">删 除</div>
                        </div>
                    </div>
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
            <div class="right_account_manager">评论回复</div>
            <div class="notice_repeat" ms-if="commentInfo.size() > 0 && !loading">
                <!--======== 我的通知循环开始 ========-->
                <div ms-repeat="commentInfo">
                    <div class="notice_repeat_item">
                        <div class="notice_item_top">
                            <div class="icon"></div>
                            <a href="#" ms-class-1="no_read: (el.isRead === '0')" ms-class-2="has_read: (el.isRead === '1')" ms-text="el.content" ms-click="jumpTo(el)"></a>
                        </div>
                        <div class="notice_item_bottom">
                            <div class="notice_item_bottom_timer" ms-text="el.created_at"></div>
                            <div class="notice_item_bottom_delete" ms-click="popUpSwitch('deleteComment',el.id)">删 除</div>
                        </div>
                    </div>
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
            <div ms-visible="answerMsg" class="warning_msg">暂无通知消息...</div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_comment"></div>
                </div>
            </div>
        </div>
        <!--主体右边   评论回复结束-->


        <!--主体右边   课程管理-->
        <div class="center_right hide " ms-visible="tabStatus === 'studentCourseQa'" ms-controller="studentCourseQaController" >
            <div class="right_wait_answer">
                <div class="answer_title">课程回答</div>
                <div class="my_question question_blue" ms-click="getDate(1)">等待回答</div>
                <div class="my_answer " ms-click="getDate(2)">已回答</div>
            </div>
            {{--内容--}}
            <div class="right_wait_answer_content" ms-if="studentCourseQaInfo.size() > 0 && !loading">
                {{--循环内容--}}
                <div class="content_every" ms-repeat="studentCourseQaInfo">
                    <div class="content_every_detail">
                        <a ms-attr-href="'/studentCourse/stuDetail/'+el.id+'#question' ">
                            <div class="content_every_detail_img">
                                <img ms-attr-src="el.pic"  alt="" width="60" height="60">
                            </div>
                        </a>
                        <div class="content_every_detail_sum">
                            <div class="content_every_detail_top" ms-html="'来自 '+el.courseTitle">来自 &nbsp; 绘画</div>
                            <div class="content_every_detail_bottom">
                                <a ms-attr-href="'/studentCourse/stuDetail/'+el.id"><div ms-html="el.content"></div></a>
                            </div>
                        </div>
                        <div class="content_every_detail_time">
                            <div ms-html="el.asktime | truncate(12,' ')">2017-1-12</div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="clear"></div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="questionMsg" class="warning_msg">暂无课程问答...</div>
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
                        <a ms-attr-href="'/community/askDetail/'+el.id">
                            <div class="content_every_detail_img">
                                <img ms-attr-src="el.pic"  alt="" width="60" height="60">
                            </div>
                        </a>
                        <div class="content_every_detail_sum">
                            <div class="content_every_detail_top" ms-html="'来自 '+el.type">来自 &nbsp; 绘画</div>
                            <div class="content_every_detail_bottom">
                                <a ms-attr-href="'/community/askDetail/'+el.id"><div ms-html="el.qestitle"></div></a>
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

        <!--主体右边   资源收藏开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'resourceStore'" ms-controller="resourceStore" >
            <div class="right_resource_top">
                <ul>
                    <li ms-click="getCollectionInfo(0)" class="resource_font_diff" ms-if="resourceTypeList.size() > 0" ms-html="'全部'"></li>
                    <li ms-repeat="resourceTypeList" ms-click="getCollectionInfo(el.id)" ms-html="el.name"></li>
                </ul>
            </div>

            <div class="resource_content" ms-if="collectionInfo.size() > 0 && !loading">
                <!--repeat开始-->
                <div class="resource_content_repeat" ms-repeat="collectionInfo">
                    <a ms-attr-href="'/resource/resDetail/' + el.id" ms-if="el.id" target="_blank" >
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
                                    <span ms-html="'浏览'+' '+ (el.resourceView || 0)"></span>
                                    <span ms-html="'下载'+' '+ (el.resourceDownload || 0)"></span>
                                    <span ms-html="'收藏'+' '+ (el.resourceFav || 0)"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a ms-if="!el.id" ms-click="popUpSwitch('resourceMessage', 1, el.storeId)" style="cursor: pointer;">
                        <div class="resource_repeat_left">
                            <img ms-attr-src="el.resourcePic" alt="" width="120" height="90" onerror="javascript:this.src='/home/image/resource/word.png';">
                        </div>

                        <div class="resource_repeat_right">
                            <div class="resource_repeat_right_first" ms-html="el.resourcetitle"></div>
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
                                    <span ms-html="'浏览'+' '+ (el.resourceView || 0)"></span>
                                    <span ms-html="'下载'+' '+ (el.resourceDownload || 0)"></span>
                                    <span ms-html="'收藏'+' '+ (el.resourceFav || 0)"></span>
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
        <div class="center_right hide" ms-visible="tabStatus === 'courseStore'" ms-controller="courseStore" >
            <div class="right_resource_top">
               <span ms-html="'课程收藏'"></span>
            </div>

            <div class="resource_content" ms-if="courseStoreInfo.size() > 0 && !loading">
                <!--repeat开始-->
                <div class="resource_content_repeat" ms-repeat="courseStoreInfo">
                    <a ms-attr-href="'/teacherCourse/teaDetail/' + el.courseId" ms-if="el.courseId" target="_blank">
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
                                    <span ms-html="'学习'+' '+ (el.courseStudyNum || 0)"></span>
                                    <span ms-html="'收藏'+' '+ (el.courseFav || 0)"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a ms-if="!el.courseId" ms-click="popUpSwitch('resourceMessage', 2, el.storeId)" style="cursor: pointer;" >
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
                                    <span ms-html="'收藏'+' '+ (el.courseFav || 0)"></span>
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

        <!--主体右边   问答收藏开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'auditingStore'" ms-controller="answerStore">
            <div class="right_account_manager" ms-html="'问答收藏'"></div>
            <div class="right_answer" ms-if="answerStoreInfo.size() > 0 && !loading">
                <!-- 循环开始 -->
                <div class="answer_repeat" ms-repeat="answerStoreInfo">
                    <a ms-attr-href="'/community/askDetail/' + el.questionId">
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
            <div class="center_right_top">
                <div class="center_right_information" ms-html="'我的关注'"></div>
                <div class="center_right_count">共<span ms-html="' ' + total + ' ' "></span>个关注</div>
            </div>
            <div class="h20"></div>

            {{--//我的关注--}}
            <div class="center_right_focus" ms-if="myFocusList.size() > 0 && !loading">
                {{--===============================我的关注循环开始====================================--}}
                <div class="right_focus_repeat" ms-repeat="myFocusList">
                    <!-- 教师 -->
                    <a ms-if="el.type == 1" ms-attr-href="'/member/studentHomePagePublic/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                    <!-- 学生 -->
                    <a ms-if="el.type == 2" ms-attr-href="'/member/teacherHomePagePublic/'+el.id">
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
            <div class="center_right_top">
                <div class="center_right_information" ms-html="'我的好友'"></div>
                <div class="center_right_count">共<span ms-html="' ' + total + ' '"></span>个好友</div>
            </div>
            <div class="h20"></div>

            {{--//我的关注--}}
            <div class="center_right_focus" ms-if="myFansList.size() > 0 && !loading">
                {{--===============================我的好友循环开始====================================--}}
                <div class="right_focus_repeat" ms-repeat="myFansList">
                    <!-- 教师 -->
                    <a ms-if="el.type == 1" ms-attr-href="'/member/studentHomePagePublic/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                    <!-- 学生 -->
                    <a ms-if="el.type == 2" ms-attr-href="'/member/teacherHomePagePublic/'+el.id">
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

    </div>
    <div class="clear"></div>

    <div class="h5"></div>
    <div class="h5"></div>
    <div class="h20"></div>

@endsection
@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script type="text/javascript">
        require(['/personCenter/directive.js', '/personCenter/studentHomePage.js'], function (directive, studentHomePage) {
            studentHomePage.mineId = '{{$id}}' || null;
            studentHomePage.mineUsername = '{{$mineUsername}}' || null;
            if (window.location.hash) {
                studentHomePage.tabStatus = window.location.hash.split('#')[1];
                studentHomePage.changeTab(studentHomePage.tabStatus, studentHomePage.mineId );
            } else {
                studentHomePage.tabStatus = 'wholeNotice';
                studentHomePage.changeTab(studentHomePage.tabStatus, studentHomePage.mineId );
            }
            studentHomePage.getData('/member/getResourceType', 'resourceTypeList', {table: 'resourcetype'}, 'POST');
            studentHomePage.getData('/member/getUserInfo/' + studentHomePage.mineId, 'userInfo');

            //日期过滤器
            avalon.filters.sliceTime = function(str,type){
                return type == 'year' ? str.slice(0,10) : str.slice(11,19);
            }

//        sideBar.findHaveNotice();
            avalon.scan();
        });
    </script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/teacherHomePage.js')}}"></script>
@endsection




