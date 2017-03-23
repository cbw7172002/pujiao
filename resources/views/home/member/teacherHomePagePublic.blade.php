@extends('layouts.layoutHome')

@section('title', '教师个人主页--公开')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/teacherHomePagePublic.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">
@endsection

@section('content')

    <div class="h40"></div>
    <div class="container" ms-controller="teacherHomePagePublic">
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
                        @if(Auth::check())
                            @if($mineId != $hisId)
                                <div class="introduce_left_top_right_right" id="follow" ms-follow="isFollow"
                                     ms-click="followUser()"></div>
                            @else
                                <div id="follow" class="introduce_left_top_right_right isFollow"
                                     style="background: none !important;cursor: default;"></div>
                            @endif
                        @else
                            <a href="/index/login" id="follow" class="introduce_left_top_right_right"
                               ms-follow="isFollow"></a>
                        @endif
                    </div>
                </div>

                <div class="introduce_left_bottom">
                    <div class="introduce_left_bottom_sex" ms-html="userInfo.sex == 1 ? '男' : '女'"></div>
                    <div class="introduce_left_bottom_location" ms-attr-title="userInfo.school" ms-html="userInfo.school || '暂无'"></div>
                    <div class="introduce_left_bottom_timer" ms-attr-title="userInfo.subjectNames" ms-html=" userInfo.subjectName || '' "></div>
                </div>

            </div>


            <div class="introduce_right">
                <div class="introduce_right_video">
                    <div class="introduce_right_video_img"></div>
                    <div class="introduce_right_video_text" ms-html=" '课程' "></div>
                    <div class="introduce_right_video_number" ms-html=" courseCount "></div>
                </div>
                <div class="introduce_right_video introduce_right_fans">
                    <div class="introduce_right_video_img"></div>
                    <div class="introduce_right_video_text" ms-html="'资源'"></div>
                    <div class="introduce_right_video_number" ms-html=" resourceCount "></div>
                </div>
            </div>
        </div>
        <!--主体左边-->
        <div class="h40"></div>
        <div class="clear"></div>

        <div class="center_left">
            <span class="span_hover"></span>
            <div class="account_common" name="teacherCourse" ms-click="changeTab('teacherCourse')">教师课程</div>
            <span class="span_hover"></span>
            <div class="account_common" name="teacherAnswer" ms-click="changeTab('teacherAnswer')">教师问答</div>
            <span class="span_hover"></span>
            <div class="account_common" name="HisFriends" ms-click="changeTab('HisFriends', {{$hisId}})">他的好友</div>
            <span class="span_hover"></span>
            <div class="account_common" name="HisFocus" ms-click="changeTab('HisFocus', {{$hisId}})">他的关注</div>
        </div>


        <!--主体右边   教师课程开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'teacherCourse'"  ms-controller="teacherCourseController">
            <div class="right_course_list">
                <div class="course_list">课程列表</div>
            </div>
            <div class="resource_content">
                <!--repeat开始-->
                <div class="resource_content_repeat" ms-repeat="teacherCourseInfo">
                    <a ms-attr-href="'/teacherCourse/teaDetail/'+el.id">
                    <div class="resource_repeat_left">
                        <img ms-attr-src="el.coursePic" alt="" width="120" height="90">
                    </div>
                    </a>
                    <div class="resource_repeat_right">
                        <a ms-attr-href="'/teacherCourse/teaDetail/'+el.id"><div class="resource_repeat_right_first" ms-html="el.courseTitle">单词和句子</div></a>
                        <div class="resource_repeat_right_second">
                            <span ms-html="el.editionName">人教版</span>
                            <span ms-html="el.gradeName+ el.subjectName + el.bookname">六年级英语上册</span>
                        </div>
                        <div class="resource_repeat_right_third">
                            <span ms-html=" '上传者 :&nbsp;&nbsp;' + el.username">上传者 : 王大明</span>
                            <span ms-html=" '上传时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')">上传时间 : 2016-12-20</span>
                        </div>
                        <div class="resource_repeat_right_last">
                            <div class="resource_repeat_right_last_left"></div>
                            <div class="resource_repeat_right_last_right">
                                <span ms-html=" '浏览 &nbsp;' + el.courseView ">浏览 99 </span>
                                {{--<span >下载 100</span>--}}
                                <span ms-html=" '收藏 &nbsp;' + el.courseFav ">收藏 99 </span>
                            </div>

                        </div>

                    </div>
                </div>
                <!--repeat结束-->
                <div style="clear: both"></div>

            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="teaCourseMsg" class="warningMsg">暂无课程...</div>
            <div ms-if="page" class="pagecon_parent" style="margin-top:40px;">
                <div class="pagecon">
                    <div id="page_question"></div>
                </div>
            </div>

        </div>
        <!--主体右边   教师课程结束-->

        <!--主体右边   教师问答开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'teacherAnswer'" ms-controller="teacherAnswerController">
            <div class="right_course_list">
                <div class="course_list">问答列表</div>
            </div>
            {{--内容--}}
            <div class="right_wait_answer_content" ms-if="!teaAnswerMsg">
                {{--循环内容--}}
                <div class="content_every" ms-repeat="teacherAnswerInfo">
                    <div class="content_every_detail">
                        <a ms-attr-href="'/community/askDetail/'+el.id">
                        <div class="content_every_detail_img">
                            <img  ms-attr-src="el.pic" alt="" width="60" height="60">
                        </div>
                        </a>
                        <div class="content_every_detail_sum">
                            <div class="content_every_detail_top" ms-html="'来自 '+el.type">来自 &nbsp; 绘画</div>
                            <a ms-attr-href="'/community/askDetail/'+el.id">
                                <div class="content_every_detail_bottom">
                                    <div ms-html="el.qestitle">如何快速提升素描造型能力?</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div style="height:20px"></div>
                    <div class="content_every_detail_times">
                        <div class="content_every_detail_times_answer" ms-if="el.status == 1">未解答</div>
                        <div class="content_every_detail_times_answer" ms-if="el.status == 2">已解答</div>
                        <div class="content_every_detail_times_time" ms-html="el.asktime | truncate(12,' ')">2017-1-12</div>
                        <div class="content_every_detail_times_title">发布时间</div>
                    </div>
                </div>
                <div style="clear: both"></div>
            </div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-visible="teaAnswerMsg" class="warningMsg">暂无问答...</div>
            <div ms-if="page" class="pagecon_parent" style="margin-top:40px;">
                <div class="pagecon">
                    <div id="page_question"></div>
                </div>
            </div>
        </div>
        <!--主体右边   教师问答结束-->

        <!--主体右边   他的好友开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'HisFriends'" ms-controller="myFansTeacher">
            <div class="center_right_top">
                <div class="center_right_information" ms-html="'他的好友'"></div>
                <div class="center_right_count">共<span ms-html="' ' + total + ' '"></span>个好友</div>
            </div>
            <div class="h20"></div>

            {{--//我的关注--}}
            <div class="center_right_focus" ms-if="myFansList">
                {{--===============================我的好友循环开始====================================--}}
                <div class="right_focus_repeat" ms-repeat="myFansList">
                    <!-- 学生 不是自己-->
                    <a ms-if="el.type == 1 && el.id != mineId" ms-attr-href="'/member/studentHomePagePublic/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                    <a ms-if="el.type == 1 && el.id == mineId" ms-attr-href="'/member/studentHomePage/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>

                    <!-- 教师 不是自己-->
                    <a ms-if="el.type == 2 && el.id != mineId" ms-attr-href="'/member/teacherHomePagePublic/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                    <a ms-if="el.type == 2 && el.id == mineId" ms-attr-href="'/member/teacherHomePage/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                </div>
                {{--===============================我的好友循环结束====================================--}}
                <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div ms-visible="myFans" class="warningMsg && !loading" style="height: 290px;line-height: 290px;">暂无好友...</div>
            </div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_friend"></div>
                </div>
            </div>
        </div>
        <!--主体右边   他的好友结束-->

        <!--主体右边   他的关注开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'HisFocus'" ms-controller="myFocusTeacher">
            <div class="center_right_top">
                <div class="center_right_information" ms-html="'他的关注'"></div>
                <div class="center_right_count">共<span ms-html="' ' + total + ' ' "></span>个关注</div>
            </div>
            <div class="h20"></div>

            {{--//我的关注--}}
            <div class="center_right_focus" ms-if="myFocusList">
                {{--===============================我的关注循环开始====================================--}}
                <div class="right_focus_repeat" ms-repeat="myFocusList">
                    <!-- 学生 不是自己-->
                    <a ms-if="el.type == 1 && el.id != mineId" ms-attr-href="'/member/studentHomePagePublic/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                    <a ms-if="el.type == 1 && el.id == mineId" ms-attr-href="'/member/studentHomePage/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>

                    <!-- 教师 不是自己-->
                    <a ms-if="el.type == 2 && el.id != mineId" ms-attr-href="'/member/teacherHomePagePublic/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                    <a ms-if="el.type == 2 && el.id == mineId" ms-attr-href="'/member/teacherHomePage/'+el.id">
                        <img ms-attr-src="el.pic" alt="" width="84" height="84">
                        <div class="focus_repeat_name" ms-attr-title="el.username" ms-text="el.username"></div>
                    </a>
                </div>
                {{--===============================我的关注循环结束====================================--}}
                <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div ms-visible="myFocus" class="warningMsg && !loading" style="height: 290px;line-height: 290px;">暂无关注...</div>
            </div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_focus"></div>
                </div>
            </div>
        </div>
        <!--主体右边   他的关注结束-->

        <!-- 遮罩层 -->
        <div class="shadow hide" id="shadow" ms-popup="popUp" value="close"></div>
        <!-- 删除评论弹出层 -->
        <div class="delete_follow hide" ms-popup="popUp" value="unFollow">
            <div class="top">
                <span>确认取消关注？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch(false, true)">确定</span>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script type="text/javascript">
        require(['/personCenter/directive.js', '/personCenter/teacherHomePagePublic.js'], function (directive, teacherHomePagePublic) {
            teacherHomePagePublic.hisId = '{{$hisId}}' || null;
            teacherHomePagePublic.mineId = '{{$mineId}}' || null;

            if (window.location.hash) {
                teacherHomePagePublic.tab = window.location.hash.split('#')[1];
            } else {
                teacherHomePagePublic.tab = 'teacherCourse';
            }

            if (teacherHomePagePublic.tab) {
                teacherHomePagePublic.tabStatus = teacherHomePagePublic.tab;
                teacherHomePagePublic.changeTab(teacherHomePagePublic.tabStatus, teacherHomePagePublic.hisId);
            }

            teacherHomePagePublic.getData('/member/getUserInfo/' + teacherHomePagePublic.hisId, 'userInfo');
            //是否关注
            teacherHomePagePublic.mineId && teacherHomePagePublic.getData('/member/followUser', 'isFollow', {
                table: 'friends',
                action: 1,
                data: {fromUserId: teacherHomePagePublic.mineId, toUserId: teacherHomePagePublic.hisId}
            }, 'POST');
            teacherHomePagePublic.getData('/member/getCount', 'courseCount', {table: 'course', action: 1, data: {teacherId: teacherHomePagePublic.hisId, courseIsDel: 0}}, 'POST');
            teacherHomePagePublic.getData('/member/getCount', 'resourceCount', {table: 'resource', action: 1, data: {userId: teacherHomePagePublic.hisId, resourceIsDel: 0}}, 'POST');

            //日期过滤器
            avalon.filters.sliceTime = function (str, type) {
                return type == 'year' ? str.slice(0, 10) : str.slice(11, 19);
            };

            avalon.scan();
        });
    </script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/studentHomePagePublic.js')}}"></script>
@endsection




