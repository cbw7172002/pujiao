<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>启创教育云管理后台</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="启创教育云管理后台,启创,教育,教育平台,启创教育云"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- basic styles -->
    <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('admin/assets/css/font-awesome.min.css')}}"/>

    <!--[if IE 7]>
    <link rel="stylesheet" href="{{asset('admin/assets/css/font-awesome-ie7.min.css')}}"/>
    <![endif]-->

    <!-- page specific plugin styles -->

    <!-- fonts -->

    {{--<link rel="stylesheet" href="{{asset('admin/assets/font/fonts.css')}}" />--}}

            <!-- ace styles -->

    <link rel="stylesheet" href="{{asset('admin/assets/css/ace.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('admin/assets/css/ace-rtl.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('admin/assets/css/ace-skins.min.css')}}"/>

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="{{asset('admin/assets/css/ace-ie.min.css')}}"/>
    <![endif]-->
    @yield('css')

            <!-- inline styles related to this page -->

    <!-- ace settings handler -->

    <script src="{{asset('admin/assets/js/ace-extra.min.js')}}"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="{{asset('admin/assets/js/html5shiv.js')}}"></script>
    <script src="{{asset('admin/assets/js/respond.min.js')}}"></script>
    <![endif]-->

    <script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('avalon/avalon.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/avalonConfig.js')}}"></script>
</head>

<body>
<div class="navbar navbar-default" id="navbar">
    <script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="#" class="navbar-brand">
                <small>
                    <i class="icon-leaf"></i>
                    启创教育云管理后台
                </small>
            </a><!-- /.brand -->
        </div><!-- /.navbar-header -->

        <div class="navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">

                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="{{\Auth::user() -> pic}}"/>
								<span class="user-info">
									<small>欢迎光临,</small>
                                    {{\Auth::user()->username}}
								</span>

                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        {{--<li>--}}
                        {{--<a href="#">--}}
                        {{--<i class="icon-cog"></i>--}}
                        {{--设置--}}
                        {{--</a>--}}
                        {{--</li>--}}

                        {{--<li>--}}
                        {{--<a href="#">--}}
                        {{--<i class="icon-user"></i>--}}
                        {{--个人资料--}}
                        {{--</a>--}}
                        {{--</li>--}}

                        <li class="divider"></li>

                        <li>
                            <a href="{{url('auth/logout')}}">
                                <i class="icon-off"></i>
                                退出
                            </a>
                        </li>
                    </ul>
                </li>
            </ul><!-- /.ace-nav -->
        </div><!-- /.navbar-header -->
    </div><!-- /.container -->
</div>

<div class="main-container" id="main-container">
    {{--<script type="text/javascript">--}}
    {{--try{ace.settings.check('main-container' , 'fixed')}catch(e){}--}}
    {{--</script>--}}

    <div class="main-container-inner">
        <a class="menu-toggler" id="menu-toggler" href="#">
            <span class="menu-text"></span>
        </a>

        <div class="sidebar" id="sidebar">
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'fixed')
                } catch (e) {
                }
            </script>

            <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                    <button class="btn btn-success">
                        <i class="icon-signal"></i>
                    </button>

                    <button class="btn btn-info">
                        <i class="icon-pencil"></i>
                    </button>

                    <button class="btn btn-warning">
                        <i class="icon-group"></i>
                    </button>

                    <button class="btn btn-danger">
                        <i class="icon-cogs"></i>
                    </button>
                </div>

                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                    <span class="btn btn-success"></span>

                    <span class="btn btn-info"></span>

                    <span class="btn btn-warning"></span>

                    <span class="btn btn-danger"></span>
                </div>
            </div><!-- #sidebar-shortcuts -->

            <ul class="nav nav-list">
                <li class="active">
                    <a href="{{url('/admin/index')}}">
                        <i class="icon-dashboard"></i>
                        <span class="menu-text"> 控制台 </span>
                    </a>
                </li>


                @permission('check.role')
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-text-width"></i>
                        <span class="menu-text"> 权限管理 </span>
                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul class="submenu">
                        <li class="authrole">
                            <a href="{{ url('admin/auth/roleList') }}">
                                <i class="icon-double-angle-right"></i>
                                角色管理
                            </a>
                        </li>

                        @level(1)
                        <li class="authpermission">
                            <a href="{{ url('admin/auth/permissionList') }}">
                                <i class="icon-double-angle-right"></i>
                                操作权限
                            </a>
                        </li>
                        @endlevel
                    </ul>
                </li>
                @endpermission


                        <!--用户管理-->
                @permission('user.list')
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-user"></i>
                        <span class="menu-text"> 用户管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="usersteacher">
                            <a href="{{url('admin/users/teacherList')}}">
                                <i class="icon-double-angle-right"></i>
                                教师列表
                            </a>
                        </li>
                        <li class="usersinstudent">
                            <a href="{{url('admin/users/inStudentList')}}">
                                <i class="icon-double-angle-right"></i>
                                在校学生列表
                            </a>
                        </li>
                        <li class="usersoutstudent">
                            <a href="{{url('admin/users/outStudentList')}}">
                                <i class="icon-double-angle-right"></i>
                                离校学生列表
                            </a>
                        </li>

                        @permission('add.user')
                        <li class="usersadd">
                            <a href="{{url('admin/users/addUser')}}">
                                <i class="icon-double-angle-right"></i>
                                添加用户
                            </a>
                        </li>
                        @endpermission

                        {{--<li class="userspersondetail">--}}
                            {{--<a href="{{url('admin/users/personDetail/'. \Auth::user()->id ) }}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--管理员信息--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    </ul>
                </li>
                @endpermission


                {{--@permission('check.course')--}}
                        <!--专题课程管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text">  基础信息管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="baseinfograde">
                            <a href="{{url('/admin/baseInfo/gradeList')}}">
                                <i class="icon-double-angle-right"></i>
                                年级列表
                            </a>
                        </li>

                        <li class="baseinfoclass">
                            <a href="{{url('/admin/baseInfo/classList')}}">
                                <i class="icon-double-angle-right"></i>
                                班级列表
                            </a>
                        </li>

                        <li class="baseinfosubject">
                            <a href="{{url('/admin/baseInfo/subjectList')}}">
                                <i class="icon-double-angle-right"></i>
                                科目列表
                            </a>
                        </li>

                        <li class="baseinfoedition">
                            <a href="{{url('/admin/baseInfo/editionList')}}">
                                <i class="icon-double-angle-right"></i>
                                版本列表
                            </a>
                        </li>

                        <li class="baseinfobook">
                            <a href="{{url('/admin/baseInfo/bookList')}}">
                                <i class="icon-double-angle-right"></i>
                                册别列表
                            </a>
                        </li>


                    </ul>
                </li>
                {{--@endpermission--}}



                {{--@permission('check.course')--}}
                        <!--专题课程管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text">  知识点管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="chapterchapter">
                            <a href="{{url('/admin/chapter/chapterList')}}">
                                <i class="icon-double-angle-right"></i>
                                知识点列表
                            </a>
                        </li>
                    </ul>
                </li>
                {{--@endpermission--}}

                @permission('check.resource')
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text">  资源管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="resourceresource">
                            <a href="{{url('/admin/resource/resourceList')}}">
                                <i class="icon-double-angle-right"></i>
                                资源列表
                            </a>
                        </li>

                        <li class="resourceresourcetype">
                            <a href="{{url('/admin/resource/resourceTypeList')}}">
                                <i class="icon-double-angle-right"></i>
                                资源类型
                            </a>
                        </li>


                    </ul>
                </li>
                @endpermission


                @permission('check.course')
                        <!--专题课程管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text">  课程管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="specialcoursespecialcourse">
                            <a href="{{url('/admin/specialCourse/specialCourseList')}}">
                                <i class="icon-double-angle-right"></i>
                                课程列表
                            </a>
                        </li>

                        {{--<li class="specialcoursespecialtype">--}}
                        {{--<a href="{{url('/admin/specialCourse/specialTypeList')}}">--}}
                        {{--<i class="icon-double-angle-right"></i>--}}
                        {{--课程类型列表--}}
                        {{--</a>--}}
                        {{--</li>--}}

                        <li class="specialcourserecommendspecialcourse">
                            <a href="{{url('/admin/specialCourse/recommendSpecialCourseList')}}">
                                <i class="icon-double-angle-right"></i>
                                课程推荐
                            </a>
                        </li>

                        {{--<li class="specialcoursespecialfeedbacklist0">--}}
                            {{--<a href="{{url('/admin/specialCourse/specialFeedbackList/0')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--意见反馈列表--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    </ul>
                </li>
                @endpermission


                {{--@permission('logs.list')--}}
                        <!--后台试卷管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 试卷管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">

                        <li class="examexam">
                            <a href="{{url('/admin/exam/examList')}}">
                                <i class="icon-double-angle-right"></i>
                                试卷列表
                            </a>
                        </li>

                    </ul>
                </li>
                {{--@endpermission--}}


                {{--@permission('check.question')--}}
                        <!--问答管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text">  问答管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="questionquestion">
                            <a href="{{url('/admin/question/questionList')}}">
                                <i class="icon-double-angle-right"></i>
                                问答管理列表
                            </a>
                        </li>

                    </ul>
                </li>
                {{--@endpermission--}}



                {{--@permission('check.subject')--}}
                        {{--<!--问答管理-->--}}
                {{--<li>--}}
                    {{--<a href="#" class="dropdown-toggle">--}}
                        {{--<i class="icon-desktop"></i>--}}
                        {{--<span class="menu-text">  科目管理 </span>--}}

                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}

                    {{--<ul class="submenu">--}}
                        {{--<li class="subjectsubject">--}}
                            {{--<a href="{{url('/admin/subject/subjectList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--科目管理列表--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                {{--</li>--}}
                {{--@endpermission--}}


                {{--@permission('check.commentcourse')--}}
                {{--<!--点评管理-->--}}
                {{--<li>--}}
                {{--<a href="#" class="dropdown-toggle">--}}
                {{--<i class="icon-desktop"></i>--}}
                {{--<span class="menu-text"> 辅导课程 </span>--}}

                {{--<b class="arrow icon-angle-down"></b>--}}
                {{--</a>--}}

                {{--<ul class="submenu">--}}
                {{--<li class="commentcoursecommentcourse">--}}
                {{--<a href="{{url('/admin/commentCourse/commentCourseList')}}">--}}
                {{--<i class="icon-double-angle-right"></i>--}}
                {{--申请辅导列表--}}
                {{--</a>--}}
                {{--</li>--}}

                {{--<li class="commentcourseteachercourse">--}}
                {{--<a href="{{url('/admin/commentCourse/teacherCourseList')}}">--}}
                {{--<i class="icon-double-angle-right"></i>--}}
                {{--辅导课程列表--}}
                {{--</a>--}}
                {{--</li>--}}

                {{--<li class="commentcourserecommendcourse">--}}
                {{--<a href="{{url('/admin/commentCourse/recommendCourseList')}}">--}}
                {{--<i class="icon-double-angle-right"></i>--}}
                {{--辅导课程推荐--}}
                {{--</a>--}}
                {{--</li>--}}

                {{--<li class="specialcoursespecialfeedbacklist1">--}}
                {{--<a href="{{url('/admin/specialCourse/specialFeedbackList/1')}}">--}}
                {{--<i class="icon-double-angle-right"></i>--}}
                {{--意见反馈列表--}}
                {{--</a>--}}
                {{--</li>--}}

                {{--</ul>--}}
                {{--</li>--}}
                {{--@endpermission--}}

                {{--@permission('check.order')--}}
                        {{--<!--订单管理-->--}}
                {{--<li>--}}
                    {{--<a href="#" class="dropdown-toggle">--}}
                        {{--<i class="icon-desktop"></i>--}}
                        {{--<span class="menu-text"> 订单管理 </span>--}}

                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}

                    {{--<ul class="submenu">--}}
                        {{--<li class="orderorderlist8">--}}
                            {{--<a href="{{url('/admin/order/orderList/8')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--全部订单--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="orderorderlist5">--}}
                            {{--<a href="{{url('/admin/order/orderList/5')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--未付款订单--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="orderorderlist0">--}}
                            {{--<a href="{{url('/admin/order/orderList/0')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--已付款订单--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="orderorderlist1">--}}
                            {{--<a href="{{url('/admin/order/orderList/1')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--待点评订单--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="orderorderlist2">--}}
                            {{--<a href="{{url('/admin/order/orderList/2')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--已完成订单--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="orderorderlist3">--}}
                            {{--<a href="{{url('/admin/order/orderList/3')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--退款中订单--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="orderorderlist4">--}}
                            {{--<a href="{{url('/admin/order/orderList/4')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--已退款订单--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                {{--</li>--}}
                {{--@endpermission--}}

                @permission('list.notice')
                        <!--通知管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 通知管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul class="submenu">
                        <li class="noticenotice">
                            <a href="{{url('/admin/notice/noticeList')}}">
                                <i class="icon-double-angle-right"></i>
                                通知列表
                            </a>
                        </li>
                        @permission('list.noticeTem')
                        <li class="noticenoticetem">
                            <a href="{{url('/admin/notice/noticeTemList')}}">
                                <i class="icon-double-angle-right"></i>
                                通知模板
                            </a>
                        </li>
                        @endpermission
                    </ul>
                </li>
                @endpermission

                {{--@permission('commentReply.list')--}}
                {{--评论回复管理--}}
                {{--<li>--}}
                    {{--<a href="#" class="dropdown-toggle">--}}
                        {{--<i class="icon-desktop"></i>--}}
                        {{--<span class="menu-text"> 评论回复管理 </span>--}}

                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}

                    {{--<ul class="submenu">--}}
                        {{--<li class="commentreplyapplycomment">--}}
                            {{--<a href="{{url('/admin/commentReply/applyCommentList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--问答评论列表--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="commentreplyquestioncomment">--}}
                            {{--<a href="{{url('/admin/commentReply/questionCommentList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--问答评论列表--}}
                            {{--</a>--}}
                        {{--</li>--}}


                        {{--<li class="commentreplycoursecomment">--}}
                            {{--<a href="{{url('/admin/commentReply/courseCommentList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--创课课程评论列表--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                {{--</li>--}}
                {{--@endpermission--}}


                {{--@permission('collection.list')--}}
                {{--评论回复管理--}}
                {{--<li>--}}
                    {{--<a href="#" class="dropdown-toggle">--}}
                        {{--<i class="icon-desktop"></i>--}}
                        {{--<span class="menu-text"> 用户收藏管理 </span>--}}

                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}

                    {{--<ul class="submenu">--}}
                        {{--<li class="collectioncollection">--}}
                            {{--<a href="{{url('/admin/collection/collectionList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--创客课程收藏列表--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="collectionquestionfav">--}}
                            {{--<a href="{{url('/admin/collection/questionfavList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--问答收藏列表--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                {{--</li>--}}
                {{--@endpermission--}}




                {{--@permission('activity.list')--}}
                {{--赛事管理--}}
                {{--<li>--}}
                    {{--<a href="#" class="dropdown-toggle">--}}
                        {{--<i class="icon-desktop"></i>--}}
                        {{--<span class="menu-text"> 赛事管理 </span>--}}

                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}

                    {{--<ul class="submenu">--}}
                        {{--<li class="activityactivity">--}}
                            {{--<a href="{{url('/admin/activity/activityList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--赛事管理列表--}}
                            {{--</a>--}}
                        {{--</li>--}}


                        {{--<li class="activityaddactivity">--}}
                        {{--<a href="{{url('/admin/activity/addactivity')}}">--}}
                        {{--<i class="icon-double-angle-right"></i>--}}
                        {{--添加赛事--}}
                        {{--</a>--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                {{--</li>--}}
                {{--@endpermission--}}


                @permission('contentManager.list')
                {{--内容管理--}}
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 内容管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="contentmanagerbanner">
                            <a href="{{url('/admin/contentManager/bannerList')}}">
                                <i class="icon-double-angle-right"></i>
                                banner列表
                            </a>
                        </li>

                        <li class="contentmanagerpartner">
                            <a href="{{url('/admin/contentManager/partnerList')}}">
                                <i class="icon-double-angle-right"></i>
                                合作伙伴列表
                            </a>
                        </li>

                        <li class="contentmanagerhotvideo">
                            <a href="{{url('/admin/contentManager/hotvideoList')}}">
                                <i class="icon-double-angle-right"></i>
                                热门视频列表
                            </a>
                        </li>


                        {{--<li class="usersrecommendfamous">--}}
                            {{--<a href="{{url('/admin/contentManager/recteacherList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--社区名师推荐--}}
                            {{--</a>--}}
                        {{--</li>--}}


                        <li class="contentmanagernews">
                            <a href="{{url('/admin/contentManager/newsList')}}">
                                <i class="icon-double-angle-right"></i>
                                新闻资讯列表
                            </a>
                        </li>

                        {{--<li class="loginvideologinvideo">--}}
                            {{--<a href="{{url('/admin/loginVideo/loginVideoList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--登录视频推荐列表--}}
                            {{--</a>--}}
                        {{--</li>--}}


                    </ul>
                </li>
                @endpermission


                @permission('aboutus.list')
                        <!--关于我们-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 关于我们 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="aboutusfirmintro">
                            <a href="{{url('/admin/aboutUs/firmInt
									roList')}}">
                                <i class="icon-double-angle-right"></i>
                                公司介绍
                            </a>
                        </li>

                        <li class="aboutusfriendlink">
                            <a href="{{url('/admin/aboutUs/friendlinkList')}}">
                                <i class="icon-double-angle-right"></i>
                                友情链接
                            </a>
                        </li>

                    </ul>
                </li>
                @endpermission


                @permission('companyUser.list')
                        <!--公司后台用户管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 系统管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">

                        <li class="departmentpostdepartment">
                            <a href="{{url('/admin/departmentPost/departmentList')}}">
                                <i class="icon-double-angle-right"></i>
                                部门列表
                            </a>
                        </li>


                        <li class="departmentpostpost">
                            <a href="{{url('/admin/departmentPost/postList')}}">
                                <i class="icon-double-angle-right"></i>
                                岗位列表
                            </a>
                        </li>

                        <li class="companyusercompanyuser">
                            <a href="{{url('/admin/companyUser/companyUserList')}}">
                                <i class="icon-double-angle-right"></i>
                                后台用户列表
                            </a>
                        </li>

                        <li class="logslog">
                            <a href="{{url('/admin/logs/logList')}}">
                                <i class="icon-double-angle-right"></i>
                                日志列表
                            </a>
                        </li>
                        <li class="companyusersystem">
                            <a href="{{url('/admin/companyUser/systemList')}}">
                                <i class="icon-double-angle-right"></i>
                                系统设置
                            </a>
                        </li>


                    </ul>

                </li>
                @endpermission


                {{--@permission('departmentpost.list')--}}
                        {{--<!--部门岗位管理-->--}}
                {{--<li>--}}
                    {{--<a href="#" class="dropdown-toggle">--}}
                        {{--<i class="icon-desktop"></i>--}}
                        {{--<span class="menu-text"> 部门岗位管理 </span>--}}

                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}

                    {{--<ul class="submenu">--}}


                        {{--<li class="departmentpostdepartment">--}}
                            {{--<a href="{{url('/admin/departmentPost/departmentList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--部门列表--}}
                            {{--</a>--}}
                        {{--</li>--}}


                        {{--<li class="departmentpostpost">--}}
                            {{--<a href="{{url('/admin/departmentPost/postList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--岗位列表--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    {{--</ul>--}}

                {{--</li>--}}
                {{--@endpermission--}}



                <!--意见反馈管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 意见反馈 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">

                        <li class="complaintcomplaint">
                            <a href="{{url('/admin/complaint/complaintList')}}">
                                <i class="icon-double-angle-right"></i>
                                意见反馈列表
                            </a>
                        </li>

                    </ul>

                </li>
                <!--数据统计管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 数据统计 </span>
                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">

                        <li class="datacountresourcecount">
                            <a href="{{url('/admin/datacount/resourcecountList')}}">
                                <i class="icon-double-angle-right"></i>
                                资源历史统计列表
                            </a>
                        </li>

                        <li class="datacountcoursecount">
                            <a href="{{url('/admin/datacount/coursecountList')}}">
                                <i class="icon-double-angle-right"></i>
                                课程历史统计列表
                            </a>
                        </li>

                        <li class="datacounttresourcerank">
                            <a href="{{url('/admin/datacount/tresourcerankList')}}">
                                <i class="icon-double-angle-right"></i>
                                教师资源发布量排名列表
                            </a>
                        </li>

                        <li class="datacounttcourserank">
                            <a href="{{url('/admin/datacount/tcourserankList')}}">
                                <i class="icon-double-angle-right"></i>
                                教师课程发布量排名列表
                            </a>
                        </li>

                    </ul>

                </li>




                {{--@permission('logs.list')--}}
                        {{--<!--后台日志管理-->--}}
                {{--<li>--}}
                    {{--<a href="#" class="dropdown-toggle">--}}
                        {{--<i class="icon-align-justify"></i>--}}
                        {{--<span class="menu-text"> 后台日志管理 </span>--}}

                        {{--<b class="arrow icon-angle-down"></b>--}}
                    {{--</a>--}}

                    {{--<ul class="submenu">--}}

                        {{--<li class="logslog">--}}
                            {{--<a href="{{url('/admin/logs/logList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--日志列表--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                {{--</li>--}}
                {{--@endpermission--}}

                {{--@permission('check.count')--}}

                <!--敏感词库管理-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 违禁词库 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">

                        <li class="sensitivesensitive">
                            <a href="{{url('/admin/sensitive/sensitiveList')}}">
                                <i class="icon-double-angle-right"></i>
                                词库列表
                            </a>
                        </li>
                        <li class="sensitiveaddsensitive">
                            <a href="{{url('/admin/sensitive/addSensitive')}}">
                                <i class="icon-double-angle-right"></i>
                                批量新建
                            </a>
                        </li>

                    </ul>

                </li>


                @permission('check.recycle')
                        <!--回收站-->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-trash"></i>
                        <span class="menu-text"> 回收站 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">

                        <li class="recyclerecyclecourse">
                            <a href="{{url('/admin/recycle/recycleCourseList')}}">
                                <i class="icon-double-angle-right"></i>
                                在线课程
                            </a>
                        </li>

                        <li class="recyclerecycleresource">
                            <a href="{{url('/admin/recycle/recycleResourceList')}}">
                                <i class="icon-double-angle-right"></i>
                                资源列表
                            </a>
                        </li>

                        <li class="recyclerecyclequestion">
                            <a href="{{url('/admin/recycle/recycleQuestionList')}}">
                                <i class="icon-double-angle-right"></i>
                                问答管理
                            </a>
                        </li>

                        {{--<li class="recyclerecyclecommentcourse">--}}
                            {{--<a href="{{url('/admin/recycle/recycleCommentCourseList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--申请辅导视频--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="recyclerecycleteachercourse">--}}
                            {{--<a href="{{url('/admin/recycle/recycleTeacherCourseList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--辅导课程视频--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="recyclerecycleorder">--}}
                            {{--<a href="{{url('/admin/recycle/recycleOrderList')}}">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--订单--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    </ul>
                </li>
                @endpermission


            </ul><!-- /.nav-list -->

            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon-double-angle-left" data-icon1="icon-double-angle-left"
                   data-icon2="icon-double-angle-right"></i>
            </div>

            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'collapsed')
                } catch (e) {
                }
            </script>
        </div>

        @yield('content')

        <div class="ace-settings-container" id="ace-settings-container">
            <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                <i class="icon-cog bigger-150"></i>
            </div>

            <div class="ace-settings-box" id="ace-settings-box">
                <div>
                    <div class="pull-left">
                        <select id="skin-colorpicker" class="hide">
                            <option data-skin="default" value="#438EB9">#438EB9</option>
                            <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                            <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                            <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                        </select>
                    </div>
                    <span>&nbsp; 选择皮肤</span>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar"/>
                    <label class="lbl" for="ace-settings-navbar"> 固定导航条</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar"/>
                    <label class="lbl" for="ace-settings-sidebar"> 固定滑动条</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs"/>
                    <label class="lbl" for="ace-settings-breadcrumbs">固定面包屑</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl"/>
                    <label class="lbl" for="ace-settings-rtl">切换到左边</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container"/>
                    <label class="lbl" for="ace-settings-add-container">
                        切换窄屏
                        <b></b>
                    </label>
                </div>
            </div>
        </div><!-- /#ace-settings-container -->
    </div><!-- /.main-container-inner -->

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="icon-double-angle-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->

<!--<script src="assets/js/jquery-2.0.3.min.js"></script>-->
{{--<script src="{{asset('admin/assets/js/jquery-2.0.3.min.js')}}"></script>--}}


        <!-- <![endif]-->

<script>
    var url = window.location.href;
    var param = url.split('admin/');
    var route = (param[param.length - 1]).toLowerCase();
    var first = route.split('/')[(route.split('/').length - 2)]; //取admin/后一个

    var second = route.split('/')[(route.split('/').length - 3)];
    console.log(route);
    if (route.match(/\//g)) {
        if (route.match(/\//g).length == '1') { // 一个‘/’
            route = route.split('/')[route.split('/').length - 1];
//            console.log('+++');
            console.log(route);
            if (route.indexOf('?') > 0) { // 一个‘/’ 一个‘？’
                var real = route.split('?')[route.split('?').length - 2];
                $('.' + first + real.slice(0, -4)).parent().parent().addClass('open');
                $('.' + first + real.slice(0, -4)).parent().css('display', 'block');
                $('.' + first + real.slice(0, -4)).addClass('active');
                $('.' + first + real.slice(4)).parent().parent().addClass('open');
                $('.' + first + real.slice(4)).parent().css('display', 'block');
                $('.' + first + real.slice(4)).addClass('active');
            } else { // 仅一个‘/’
                console.log('+++==');
                $('.' + first + route.slice(0, -4)).parent().parent().addClass('open');
                $('.' + first + route.slice(0, -4)).parent().css('display', 'block');
                $('.' + first + route.slice(0, -4)).addClass('active');
                $('.' + first + route.slice(4)).parent().parent().addClass('open');
                $('.' + first + route.slice(4)).parent().css('display', 'block');
                $('.' + first + route.slice(4)).addClass('active');
                $('.' + first + route.slice(3)).parent().parent().addClass('open');
                $('.' + first + route.slice(3)).parent().css('display', 'block');
                $('.' + first + route.slice(3)).addClass('active');

                //add
               if(route == 'addrecommendhomepage' || route == 'addrecommendcommunity'){
                   $('.' + first + route.slice(3)).parent().parent().addClass('open');
                   $('.' + first + route.slice(3)).parent().css('display', 'block');
                   $('.' + first + route.slice(3)).addClass('active');
               }

            }
        } else if (route.match(/\//g).length == '2') { // 2个‘/’
            orderroute = route.split('/')[0] + route.split('/')[1] + route.split('/')[2];
            orderroute = orderroute.split('?')[0];
            console.log('====');

            route = route.split('/')[route.split('/').length - 2];
            console.log(route);
            if(second == 'chapter'){ //知识点管理
                if(route == 'seechapter' || route == 'editsee' || route == 'addsee'){
                    $('.chapterchapter').parent().parent().addClass('open');
                    $('.chapterchapter').parent().css('display', 'block');
                    $('.chapterchapter').addClass('active');
                }
            }

            if(second == 'resource'){ //资源管理
                if(route == 'getcommentlist'){
                    $('.resourceresource').parent().parent().addClass('open');
                    $('.resourceresource').parent().css('display', 'block');
                    $('.resourceresource').addClass('active');
                }
            }
            if (second == 'auth') {  //权限管理
                if (route == 'checkroleuser' || route == 'addroleuser' || route == 'checkrolepermission' || route == 'addrolepermission') {
                    $('.authrole').parent().parent().addClass('open');
                    $('.authrole').parent().css('display', 'block');
                    $('.authrole').addClass('active');
                }
            }

            if (second == 'companyuser') { //后台用户列表的查看密码
                if (route == 'resetpassword') {
                    $('.companyusercompanyuser').parent().parent().addClass('open');
                    $('.companyusercompanyuser').parent().css('display', 'block');
                    $('.companyusercompanyuser').addClass('active');
                }
            }

            if (second == 'users') { //后台用户列表的查看密码
                if (route == 'editrecommendhomepage') {
                    $('.usersrecommendhomepage').parent().parent().addClass('open');
                    $('.usersrecommendhomepage').parent().css('display', 'block');
                    $('.usersrecommendhomepage').addClass('active');
                }

                if (route == 'editrecommendcommunity') {
                    $('.usersrecommendcommunity').parent().parent().addClass('open');
                    $('.usersrecommendcommunity').parent().css('display', 'block');
                    $('.usersrecommendcommunity').addClass('active');
                }

                if (route == 'persondetail') {
                    $('.userspersondetail').parent().parent().addClass('open');
                    $('.userspersondetail').parent().css('display', 'block');
                    $('.userspersondetail').addClass('active');
                }
            }


            if (second == 'specialcourse') { //课程管理
                if (route == 'specialchapterlist' || route == 'datalist' || route == 'adddata' || route == 'editdata' || route == 'addspecialchapter' || route == 'editspecialcourse' || route == 'questionlist' || route == 'detailquestion' || route == 'noteslist' || route == 'detailnotes') {
                    $('.specialcoursespecialcourse').parent().parent().addClass('open');
                    $('.specialcoursespecialcourse').parent().css('display', 'block');
                    $('.specialcoursespecialcourse').addClass('active');
                }
                if (route == 'editrecommendspecialcourse') {
                    $('.specialcourserecommendspecialcourse ').parent().parent().addClass('open');
                    $('.specialcourserecommendspecialcourse ').parent().css('display', 'block');
                    $('.specialcourserecommendspecialcourse ').addClass('active');
                }
            } else if (route == 'show' || route == 'resetpass') {  //用户管理
                $('.usersuser').parent().parent().addClass('open');
                $('.usersuser').parent().css('display', 'block');
                $('.usersuser').addClass('active');

            } else {
                $('.' + second + route.slice(0, -4)).parent().parent().addClass('open');

                $('.' + second + route.slice(0, -4)).parent().css('display', 'block');
                $('.' + second + route.slice(0, -4)).addClass('active');
                $('.' + second + route.slice(4)).parent().parent().addClass('open');
                $('.' + second + route.slice(4)).parent().css('display', 'block');
                $('.' + second + route.slice(4)).addClass('active');
            }
        } else if (route.match(/\//g).length == '4') {
            var auth = route.split('/')[route.split('/').length - 5];
            route = route.split('/')[route.split('/').length - 1];
            $('.' + auth + route.slice(0, -4)).parent().parent().addClass('open');
            $('.' + auth + route.slice(0, -4)).parent().css('display', 'block');
            $('.' + auth + route.slice(0, -4)).addClass('active');
        } else if (route.match(/\//g).length == '3') {
            remarks = route.split('/')[0] + route.split('/')[1] + route.split('/')[3];
            var stat =  (route.split('/')[0] + route.split('/')[2]).toLowerCase(); //用户编辑 usersteacher usersinstudent usersoutstudent

            console.log('====');
            console.log(remarks);
            status = route.split('/')[3];
            //用户管理
            if (stat == 'usersteacher' || stat == 'usersinstudent' || stat == 'usersoutstudent') {
                $('.' + stat).parent().parent().addClass('open');
                $('.' + stat).parent().css('display', 'block');
                $('.' + stat).addClass('active');
            }

            if (remarks == 'orderremarklist' + status || remarks == 'ordereditretiredmoney' + status || remarks == 'ordereditrefundmoney' + status || remarks == 'orderrefundlist' + status) {
                $('.orderorderlist' + status).parent().parent().addClass('open');
                $('.orderorderlist' + status).parent().css('display', 'block');
                $('.orderorderlist' + status).addClass('active');
            }
            if (remarks == 'orderremarklist' + status || remarks == 'orderrefundlist' + status) {
                $('.orderorderlist' + status).parent().parent().addClass('open');
                $('.orderorderlist4' + status).parent().css('display', 'block');
                $('.orderorderlist4' + status).addClass('active');
            }
            if (remarks == 'ordereditpaymoney' + status || remarks == 'ordereditpaymoney' + status) {
                $('.orderorderlist' + status).parent().parent().addClass('open');
                $('.orderorderlist' + status).parent().css('display', 'block');
                $('.orderorderlist' + status).addClass('active');
            }

        }
    }

</script>

<!--[if IE]>
<script src="{{asset('admin/assets/js/jquery-1.10.2.min.js')}}"></script>
<![endif]-->

<!--[if !IE]> -->

<script type="text/javascript">
    window.jQuery || document.write("<script src='{{asset("admin/assets/js/jquery-2.0.3.min.js")}}'>" + "<" + "script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='" + {
    {
        asset("assets/js/jquery-1.10.2.min.js")
    }
    }
    +"'>" + "<" + "script>"
    )
    ;
</script>
<![endif]-->

<script type="text/javascript">
    if ("ontouchend" in document) document.write("<script src='{{asset('admin/assets/js/jquery.mobile.custom.min.js')}}'>" + "<" + "script>");
</script>
<script src="{{asset('admin/assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('admin/assets/js/typeahead-bs2.min.js')}}"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="{{asset('assets/js/excanvas.min.js')}}"></script>
<![endif]-->

<script src="{{asset('admin/assets/js/jquery-ui-1.10.3.custom.min.js')}}"></script>
<script src="{{asset('admin/assets/js/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{asset('admin/assets/js/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('admin/assets/js/jquery.easy-pie-chart.min.js')}}"></script>
<script src="{{asset('admin/assets/js/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('admin/assets/js/flot/jquery.flot.min.js')}}"></script>
<script src="{{asset('admin/assets/js/flot/jquery.flot.pie.min.js')}}"></script>
<script src="{{asset('admin/assets/js/flot/jquery.flot.resize.min.js')}}"></script>

<!-- ace scripts -->

<script src="{{asset('admin/assets/js/ace-elements.min.js')}}"></script>
<script src="{{asset('admin/assets/js/ace.min.js')}}"></script>
@yield('js')

        <!-- inline scripts related to this page -->

<script type="text/javascript">
    jQuery(function ($) {
        $('.easy-pie-chart.percentage').each(function () {
            var $box = $(this).closest('.infobox');
            var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
            var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
            var size = parseInt($(this).data('size')) || 50;
            $(this).easyPieChart({
                barColor: barColor,
                trackColor: trackColor,
                scaleColor: false,
                lineCap: 'butt',
                lineWidth: parseInt(size / 10),
                animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
                size: size
            });
        })

        $('.sparkline').each(function () {
            var $box = $(this).closest('.infobox');
            var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
            $(this).sparkline('html', {
                tagValuesAttribute: 'data-values',
                type: 'bar',
                barColor: barColor,
                chartRangeMin: $(this).data('min') || 0
            });
        });


        var placeholder = $('#piechart-placeholder').css({'width': '90%', 'min-height': '150px'});
        var data = [
            {label: "social networks", data: 38.7, color: "#68BC31"},
            {label: "search engines", data: 24.5, color: "#2091CF"},
            {label: "ad campaigns", data: 8.2, color: "#AF4E96"},
            {label: "direct traffic", data: 18.6, color: "#DA5430"},
            {label: "other", data: 10, color: "#FEE074"}
        ]

        function drawPieChart(placeholder, data, position) {
            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        tilt: 0.8,
                        highlight: {
                            opacity: 0.25
                        },
                        stroke: {
                            color: '#fff',
                            width: 2
                        },
                        startAngle: 2
                    }
                },
                legend: {
                    show: true,
                    position: position || "ne",
                    labelBoxBorderColor: null,
                    margin: [-30, 15]
                }
                ,
                grid: {
                    hoverable: true,
                    clickable: true
                }
            })
        }

        drawPieChart(placeholder, data);

        /**
         we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
         so that's not needed actually.
         */
        placeholder.data('chart', data);
        placeholder.data('draw', drawPieChart);


        var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
        var previousPoint = null;

        placeholder.on('plothover', function (event, pos, item) {
            if (item) {
                if (previousPoint != item.seriesIndex) {
                    previousPoint = item.seriesIndex;
                    var tip = item.series['label'] + " : " + item.series['percent'] + '%';
                    $tooltip.show().children(0).text(tip);
                }
                $tooltip.css({top: pos.pageY + 10, left: pos.pageX + 10});
            } else {
                $tooltip.hide();
                previousPoint = null;
            }

        });


        var d1 = [];
        for (var i = 0; i < Math.PI * 2; i += 0.5) {
            d1.push([i, Math.sin(i)]);
        }

        var d2 = [];
        for (var i = 0; i < Math.PI * 2; i += 0.5) {
            d2.push([i, Math.cos(i)]);
        }

        var d3 = [];
        for (var i = 0; i < Math.PI * 2; i += 0.2) {
            d3.push([i, Math.tan(i)]);
        }


        var sales_charts = $('#sales-charts').css({'width': '100%', 'height': '220px'});
        $.plot("#sales-charts", [
            {label: "Domains", data: d1},
            {label: "Hosting", data: d2},
            {label: "Services", data: d3}
        ], {
            hoverable: true,
            shadowSize: 0,
            series: {
                lines: {show: true},
                points: {show: true}
            },
            xaxis: {
                tickLength: 0
            },
            yaxis: {
                ticks: 10,
                min: -2,
                max: 2,
                tickDecimals: 3
            },
            grid: {
                backgroundColor: {colors: ["#fff", "#fff"]},
                borderWidth: 1,
                borderColor: '#555'
            }
        });


        $('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
        function tooltip_placement(context, source) {
            var $source = $(source);
            var $parent = $source.closest('.tab-content')
            var off1 = $parent.offset();
            var w1 = $parent.width();

            var off2 = $source.offset();
            var w2 = $source.width();

            if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
            return 'left';
        }


        $('.dialogs,.comments').slimScroll({
            height: '300px'
        });


        //Android's default browser somehow is confused when tapping on label which will lead to dragging the task
        //so disable dragging when clicking on label
        var agent = navigator.userAgent.toLowerCase();
        if ("ontouchstart" in document && /applewebkit/.test(agent) && /android/.test(agent))
            $('#tasks').on('touchstart', function (e) {
                var li = $(e.target).closest('#tasks li');
                if (li.length == 0)return;
                var label = li.find('label.inline').get(0);
                if (label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation();
            });

        $('#tasks').sortable({
                    opacity: 0.8,
                    revert: true,
                    forceHelperSize: true,
                    placeholder: 'draggable-placeholder',
                    forcePlaceholderSize: true,
                    tolerance: 'pointer',
                    stop: function (event, ui) {//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
                        $(ui.item).css('z-index', 'auto');
                    }
                }
        );
        $('#tasks').disableSelection();
        $('#tasks input:checkbox').removeAttr('checked').on('click', function () {
            if (this.checked) $(this).closest('li').addClass('selected');
            else $(this).closest('li').removeClass('selected');
        });


    })
</script>
<script type="text/javascript">
    if ($('.alert').css('display') == 'block') {
        setTimeout(function () {
            $('.alert').slideUp(500);
        }, 3000);
    }
    ;
</script>
<div style="display:none">
    <script src="{{asset('admin/assets/font/stat.js')}}" language='JavaScript' charset='gb2312'></script>
</div>
</body>
</html>

