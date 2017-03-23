@extends('layouts.layoutHome')

@section('title', '社区首页')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/community/community.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/community/pagination.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">

    <style>


        .newstudent .paginationjs-prev{
            position: relative;
            top:-110px;
            left:-20px;
            opacity: 0;
            filter:alpha(opacity=0);
        }


        .newstudent .paginationjs-next{
            width:80px;
            float: left;
            position: relative;
            top:-105px;
            left:1090px;
            opacity: 0;
            filter:alpha(opacity=0);
            z-index:2;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b{
            border-top-color: #363F4E;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b{
            border-bottom-color: #363F4E;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #363F4E;
            color: white;
        }
    </style>


@endsection

@section('content')



    <div class="background" ms-controller="community">

        <div style="height:21px"></div>

        <div class="main_img">
            <img src="{{url('home/image/community/zaixianshequ.png')}}" alt=""/>
        </div>
        <div style="height:40px"></div>

        <!-- 名师 新闻 模块   -->
        <div class="teacher_new">

            <!-- 问答主页 -->
            <div class="teacher">
                <!-- title more -->
                <div class="title_more">
                    <div class="teacher_title">
                        <span class="bottom_line" ms-click="getquestions('views',false)">热门</span>
                        <span class="" ms-click="getquestions('anstime',false)">最新</span>
                        <span class="dengdai" ms-click="getquestions('anstime',true)">等待回答</span>
                    </div>
                    <div class="teacher_more">
                        <div style="font-size:16px;width:60px;height:45px;font-weight: 500;float:left;line-height:28px;">分类</div>
                        <select name="type"  class="js-example-basic-single  objtype" style="width:200px;height:50px;border: none;">
                            <option value="0">-全部-</option>
                        </select>
                    </div>
                </div>

                <div class="clear"></div>

                <!-- 问答内容列表 -->
                <div class="teacher_content">
                    <div style="height:20px;"></div>
                    {{--<div class="question_msg" ms-if="question_msg">--}}
                    <div class="question_msg hide" ms-class="hide:!question_msg">
                        <div class="question_msg_top">暂无相关提问</div>
                        {{--<div class="question_msg_fot" ms-if="!iswaitans">为您推荐以下内容</div>--}}
                        <div class="question_msg_fot">为您推荐以下内容</div>
                    </div>
                    {{--repeat start--}}
                    <div class="teacher_content_detail" ms-repeat="questions">
                        <div class="teacher_content_img">
                            <a><img ms-attr-src="el.stupic" alt="" width="60" height="60"/></a>
                        </div>
                        <div class="content_detail_right">
                            @if(Auth::check())
                                <a ms-attr-href=" 'community/askDetail/'+el.id ">
                                    @else
                                        <a ms-attr-href=" '/index/login' ">
                                            @endif
                                            <div class="from_type">
                                                <span class="from">来自</span>
                                                <span class="type" ms-html="el.subjectName">绘画</span>
                                            </div>
                                            <div class="content_detail_question">
                                                <span ms-html="el.qestitle">如何快速提升素描能力?</span>
                                            </div>
                                            <div class="content_detail_author hide" ms-class="hide:iswaitans">
                                                <span class="author" ms-html="el.teaname">超现代毕加索</span>
                                                <span class="answer">回答:</span>
                                            </div>
                                            <div class="content_detail_answer hide" ms-class="hide:iswaitans">
                                                <span ms-html="el.answer"></span>
                                            </div>
                                            <div class="content_detail_info hide" ms-class="hide:iswaitans">
                                                <div class="scan">
                                                    <span ms-html="el.views">123</span>
                                                    <span>浏览</span>
                                                </div>
                                                <div class="zan">
                                                    <span ms-html="el.thumb">152</span>
                                                    <span>回答</span>
                                                </div>
                                            </div>
                                            <div class="makeanscon hide" ms-class="hide:!iswaitans">
                                                <div class="makeans_btn">撰写答案</div>
                                            </div>
                                            @if(Auth::check())
                                        </a>
                                        @else
                                </a>
                            @endif
                        </div>
                    </div>
                    {{--repeat over--}}

                </div>
                <div style="height:90px;"></div>
                <div class="pagecon" style="width:740px;height:35px;margin:0 auto;text-align:center">
                    <div style="display: inline-block"><div id="page_qes"></div></div>
                </div>

            </div>



            <!-- 右部分 -->
            <div class="new">

                {{--我要提问--}}
                {{--<a href="/community/question/{{$userId}}">--}}
                <div class="woyaotiwen" >
                    @if(Auth::check())
                        <a href="/community/question">
                            @else
                                <a href="/index/login">
                                    @endif
                                    <img src="{{asset('/home/image/community/woyaotiwenslan.png')}}" width="360" alt="">
                                </a>
                        {{--<a ms-attr-href="'/community/question/'+el.id"><div class="ask_question">提问</div></a>--}}
                </div>
                {{--</a>--}}
                <div style="height:25px"></div>


                <!-- title more -->
                <div class="title_more2 more2_diff">
                    <div class="teacher_title">
                        <span>校园资讯</span>
                    </div>
                    <div class="teacher_more">
                        <a href="{{asset('community/newlist')}}"><span>更多></span></a>
                    </div>
                </div>
                <div style="height:20px"></div>

                <!-- 循环 -->
                <div class="new_content" ms-repeat="newlist">

                    <!-- 图片 -->
                    <div class="new_content_top"  >
                        <div class="new_content_back" ms-html=" $index+1 "  ms-class="deep_blue : $index < 3"  ms-shuzi>

                        </div>

                    </div>
                    <!-- 文字 -->
                    <div class="new_content_down">
                        <a ms-attr-href=" theteacherlisturl + el.id ">
                            <div ms-html="el.description" ms-newyincang></div>
                        </a>
                    </div>

                    {{--<!-- 图片 -->--}}
                    {{--<div class="new_content_img"  >--}}
                    {{--<div ms-html=" $index+1 "> </div>--}}
                    {{--</div>--}}
                    {{--<!-- 文字 -->--}}
                    {{--<div class="new_content_font">--}}
                    {{--<a ms-attr-href=" theteacherlisturl + el.id ">--}}
                    {{--<div ms-html="el.description" ms-newyincang></div>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                </div>


                <div style="height:60px"></div>

                <div class="online_teacher">
                    <div class="title_more2">
                        <div class="teacher_title">
                            <span>社区回答榜</span>
                        </div>
                        <div class="teacher_more">
                            {{--<a href="{{asset('community/theteacher')}}"><span>更多></span></a>--}}
                        </div>
                    </div>

                    <div class="clear"></div>

                    <div class="online_teacher_content">
                        <div class="teacher_content_item">
                            {{--repeat start--}}
                            <div class="content_item" ms-repeat="teachers">
                                <div class="avatar_realname_left">
                                    <div class="avatar_left" ms-if="el.type == 1">
                                        <a ms-attr-href="'/member/studentHomePagePublic/'+el.id"><img ms-attr-src="el.pic" alt="" width="60" height="60"/></a>
                                    </div>
                                    <div class="avatar_left" ms-if="el.type == 2" >
                                        <a ms-attr-href="'/member/teacherHomePagePublic/'+el.id"><img ms-attr-src="el.pic" alt="" width="60" height="60"/></a>
                                    </div>

                                    <div class="realname_middle" ms-if="el.type == 1">
                                        <div class="realname_middle_name" >
                                            <a ms-attr-href="'/member/studentHomePagePublic/'+el.id"><span ms-html="el.realname">张强</span></a>
                                        </div>
                                    </div>

                                    <div class="realname_middle" ms-if="el.type == 2">
                                        <div class="realname_middle_name" >
                                            <a ms-attr-href="'/member/teacherHomePagePublic/'+el.id"><span ms-html="el.realname">张强</span></a>
                                        </div>
                                    </div>

                                </div>

                                <div class="ask_question_right">

                                    <div class="ask_number">
                                        <span ms-html="el.count">21</span>次回答
                                    </div>
                                </div>
                            </div>
                            {{--repeat over--}}
                        </div>
                    </div>
                </div>




            </div>

        </div>

        <div style="height:50px;"></div>
        <div style="clear: both"></div>

        <!-- 最新学员 -->


            {{--<div id="demo" ms-fenye ></div>--}}




        <!-- 最热视频 -->
        <div class="hotvideo" ms-if-loop="Yes">
            <!-- 标题 -->
            <div class="newstudent_title">
                <div>
                    <span>最热视频</span>
                </div>
            </div>
            <div style="height:30px"></div>

            <!-- 图片 -->
            {{--<div class="first_child">--}}
            {{--<!-- 循环 -->--}}
            {{--<div class="newstudent_video" ms-repeat="hotvideo">--}}
            {{--<div style="overflow: hidden;position: relative;width: 390px;height: 260px;">--}}
            {{--@if (Auth::check() && \Auth::user()->type != 3)--}}
            {{--<a ms-attr-href="hotvideourl + el.id"><img class="big_img" ms-bigImg ms-attr-src="el.cover" alt="" width="390" height="260"/></a>--}}
            {{--@else--}}
            {{--<a ms-attr-href="'/index/login'"><img class="big_img" ms-bigImg ms-attr-src="el.cover" alt="" width="390" height="260"/></a>--}}
            {{--@endif--}}
            {{--</div>--}}
            {{--<!-- 遮罩层 -->--}}
            {{--<div class="zhezhao">--}}
            {{--<span ms-html="el.title"></span>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}

            <div class="newstudent_content">
                <div class="newstudent_videos" ms-repeat="hotvideo">
                    <div style="overflow: hidden;position: relative;width: 285px;height: 172px;">
                        @if (Auth::check() && \Auth::user()->type != 3)
                            <a ms-attr-href="hotvideourl + el.id"><img class="big_img" ms-bigImg ms-attr-src="el.cover" alt="" width="285" height="172"/></a>
                        @else
                            <a ms-attr-href="'/index/login'"><img class="big_img" ms-bigImg ms-attr-src="el.cover" alt="" width="285" height="172"/></a>
                        @endif
                    </div>
                    {{--<!-- 遮罩层 -->--}}
                    <div class="zhezhaos">
                        <span ms-html="el.title"></span>
                    </div>
                </div>

            </div>
            <div style="clear: both"></div>
        </div>


        <div style="height:20px" id="kongbai" >

        </div>


    </div>

    <div style="clear: both"></div>










@endsection
@section('selectjs')
    <script type="text/javascript" src="{{asset('home/js/users/select2.min.js') }}"></script>
@endsection
@section('js')

    <script type="text/javascript" src="{{asset('home/js/community/community.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/community/pagination.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/community/pagination2.js')}}"></script>
    <!-- // <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script> -->


    <script>
        require(['/community/community.js'], function (model) {

            model.getquestions('views',false);
            model.getteachers();
            model.selsub();

            model.getnewData();
            model.gethotData();
            // model.getteacher();
//            model.getstudent();

            $('.newstudent_left').bind("selectstart", function () { return false; });
            $('.newstudent_right').bind("selectstart", function () { return false; });
            $('.newstudent_detail').bind("selectstart", function () { return false; });

            // var selsub = function(subid){
            //     alert(subid);
            // }

            avalon.scan();
        });

    </script>
@endsection
