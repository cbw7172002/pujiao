@extends('layouts.layoutHome')

@section('title', '课程中心')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/studentCourse/list.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">

@endsection

@section('content')

    <div style="height:40px"></div>

    {{--顶端图--}}
    {{--<div class="top_img">--}}
        {{----}}
    {{--</div>--}}
    <div ms-controller="studentCourse">



    <div class="contain_lesson_top">
        <div class="contain_lesson_top_bk">
            <div style="height: 50px;"></div>
            <div class="title">
                <i></i> 课程搜索
            </div>
            <div class="search">
                <input type="text" ms-duplex-string="condition" name="search"/><span ms-click="conditionSearch(condition)">搜索</span>
            </div>
        </div>
    </div>
    <div style="height:40px"></div>


    <div class="content" >

        <div class="content_left">
            <span class="span_hover"></span><div class="mycourse course_back " ms-click="tabs('my');">我的课程</div>
            <span class="span_hover"></span><div class="allcourse course_back" ms-click="tabs('all');">全部课程</div>
        </div>

        {{--我的课程--}}
        <div class="content_right_mycourse" ms-visible="currentIndex=='my'">
            {{--选项--}}
            <div class="mycourse_select">
                <div style="width:42px;height:42px;float: left"></div>
                <div class="release hot_new_blue" id="release1" >
                    等待学习
                </div>
                <div class="release" id="release2">
                    正在学习
                </div>
                <div class="release" id="release3">
                    学习完成
                </div>
            </div>
            {{--内容--}}
            <div class="mycourse_select_content1" ms-if="!noticeMsg1" ms-if="myCourseWait.size() > 0 && !loading1">
                <div class="mycourse_select_content_every" ms-repeat="myCourseWait">
                    <div class="every_details">
                        {{--图片--}}
                        <a ms-attr-href="courseDetailUrl+el.id" target="_blank">
                        <div class="every_details_img">
                            <img  ms-attr-src="el.coursePic"  width="120px" height="90px" alt="">
                        </div>
                        </a>
                        {{--右侧内容--}}
                        <div class="every_details_content">
                            <div style="height:10px"></div>
                            <a ms-attr-href="'/studentCourse/stuDetail/' + el.id "><div class="every_details_content_name" ms-html="el.courseTitle"></div></a>
                            <div style="height:13px;"></div>
                            <a  ms-attr-href="'/studentCourse/stuDetail/' + el.id "></a><div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div></a>
                            <div style="height:22px"></div>
                            <div class="every_details_content_time">
                                <div class="every_details_content_time_author"  ms-html=" '上传者 :&nbsp;&nbsp;' + el.username"></div>
                                <div class="every_details_content_time_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>
                        </div>
                        {{--开始学习--}}
                        <div class="every_details_button">
                            <a ms-attr-href="'/studentCourse/stuDetail/' + el.id ">
                                <div class="every_details_button_img_learn">
                                    开始学习
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
                {{--分页--}}
                <div class="spinner" style="margin: 200px auto;" ms-if="loading1">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div ms-if="page" class="pagecon_parent" style="margin-top:40px;">
                    <div class="pagecon">
                        <div id="page_question"></div>
                    </div>
                </div>
            </div>
            <div class="noticeMsg1 " ms-if="noticeMsg1" >
                暂无待学习的课程
            </div>





            {{--内容--}}
            <div class="mycourse_select_content2 none" ms-if="!noticeMsg2" ms-if="myCourseUnderway.size() > 0 && !loading2">
                <div class="mycourse_select_content_every" ms-repeat="myCourseUnderway">
                    <div class="every_details">
                        {{--图片--}}
                        <a ms-attr-href="courseDetailUrl+el.id" target="_blank">
                            <div class="every_details_img">
                                <img  ms-attr-src="el.coursePic"  width="120px" height="90px" alt="">
                            </div>
                        </a>
                        {{--右侧内容--}}
                        <div class="every_details_content">
                            <div style="height:10px"></div>
                            <a ms-attr-href="'/studentCourse/stuDetail/' + el.id "><div class="every_details_content_name" ms-html="el.courseTitle"></div></a>
                            <div style="height:13px;"></div>
                            <a  ms-attr-href="'/studentCourse/stuDetail/' + el.id"></a><div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div></a>
                            <div style="height:22px"></div>
                            <div class="every_details_content_time">
                                <div class="every_details_content_time_author"  ms-html=" '上传者 :&nbsp;&nbsp;' + el.username"></div>
                                <div class="every_details_content_time_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>
                        </div>

                        <div class="every_details_button">
                            <a ms-attr-href="'/studentCourse/stuDetail/' + el.id" target="_blank">
                                <div class="every_details_button_img_learn">
                                    继续学习
                                </div>
                            </a>
                            <div style="height:5px"></div>
                            {{--<div class="every_details_button_img_bar"  >--}}
                                {{--<div class="every_details_button_img_progress"  >--}}

                                {{--</div>--}}
                            {{--</div>--}}
                            <progress class="progress" ms-attr-value="el.learnNumber" ms-attr-max="el.sumNumber"><ie ms-css-width=" (el.learnNumber/el.sumNumber*100) + '%';"></ie></progress>
                            <div class="every_details_button_img_time"  ms-html="'已完成' + el.learnNumber + '/' + el.sumNumber + '课时'  " >
                                已完成3/10课时
                            </div>
                        </div>


                    </div>
                </div>
                {{--分页--}}
                <div class="spinner" style="margin: 200px auto;" ms-if="loading2">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div ms-if="page2" class="pagecon_parent" style="margin-top:40px;">
                    <div class="pagecon">
                        <div id="page_question2"></div>
                    </div>
                </div>
            </div>
            <div class="noticeMsg2 none" ms-if="noticeMsg2" >
                暂无正在学习的课程
            </div>





            {{--内容--}}
            <div class="mycourse_select_content3 none" ms-if="!noticeMsg3" ms-if="myCourseFinish.size() > 0 && !loading3">
                <div class="mycourse_select_content_every" ms-repeat="myCourseFinish">
                    <div class="every_details">
                        {{--图片--}}
                        <a ms-attr-href="courseDetailUrl+el.id" target="_blank">
                            <div class="every_details_img">
                                <img  ms-attr-src="el.coursePic"    width="120px" height="90px" alt="">
                            </div>
                        </a>
                        {{--右侧内容--}}
                        <div class="every_details_content">
                            <div style="height:10px"></div>
                            <a ms-attr-href="courseDetailUrl+el.id"><div class="every_details_content_name" ms-html="el.courseTitle"></div></a>
                            <div style="height:13px;"></div>
                            <a  ms-attr-href="courseDetailUrl+ el.id"></a><div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div></a>
                            <div style="height:22px"></div>
                            <div class="every_details_content_time">
                                <div class="every_details_content_time_author"  ms-html=" '上传者 :&nbsp;&nbsp;' + el.username"></div>
                                <div class="every_details_content_time_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>
                        </div>

                    </div>
                </div>

                {{--分页--}}
                <div class="spinner" style="margin: 200px auto;" ms-if="loading3">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div ms-if="page3" class="pagecon_parent" style="margin-top:40px;">
                    <div class="pagecon">
                        <div id="page_question3"></div>
                    </div>
                </div>
            </div>
            <div class="noticeMsg3 none" ms-if="noticeMsg3" >
                暂无学习完成的课程
            </div>



        </div>






        {{--所有课程--}}
        <div class="content_right_allcourse" ms-visible="currentIndex=='all'">
            {{--筛选--}}
            <div class="mycourse_screen">
                <div style="height:15px"></div>

                <div class="mycourse_screen_sum">
                    <div class="mycourse_screen_sum_title">年级</div>
                    <div class="mycourse_screen_sum_sel">
                        <div class="all_grade hot_new_blue" ms-click="selectAll('gradeId');">全部</div>
                        <div class="mycourse_screen_every1 hot_new_gray" ms-repeat="gradeCourse" ms-html="el.gradeName"  ms-attr-vid="el.id" key="gradeId"
                             ms-addcolor></div>
                    </div>
                    <div class="mycourse_screen_sum_flag"></div>
                </div>
                <div style="clear: both"></div>
                <div class="mycourse_screen_sum">
                    <div class="mycourse_screen_sum_title">科目</div>
                    <div class="mycourse_screen_sum_sel ">
                        <div class="all_subject hot_new_blue " ms-click="selectAll('subjectId');">全部</div>
                        <div class="mycourse_screen_every2 hot_new_gray" ms-repeat="subjectCourse" ms-html="el.subjectName" ms-attr-vid="el.id" key="subjectId"
                             ms-addcolor></div>
                    </div>
                    <div class="mycourse_screen_sum_flag"></div>
                </div>
                <div style="clear: both"></div>

                <div class="mycourse_screen_sum">
                    <div class="mycourse_screen_sum_title">册别</div>
                    <div class="mycourse_screen_sum_sel">
                        <div class="all_book hot_new_blue " ms-click="selectAll('bookId');">全部</div>
                        <div class="mycourse_screen_every3 hot_new_gray" ms-repeat="bookCourse" ms-html="el.bookName" ms-attr-vid="el.id" key="bookId"
                             ms-addcolor></div>
                    </div>
                    <div class="mycourse_screen_sum_flag"></div>
                </div>
                <div style="clear: both"></div>

                <div class="mycourse_screen_sum version" style="display: none">
                    <div class="mycourse_screen_sum_title">版本</div>
                    <div class="mycourse_screen_sum_sel">
                        <div class="all_edition hot_new_blue " ms-click="selectAll('editionId');">全部</div>
                        <div class="mycourse_screen_every4 hot_new_gray" ms-repeat="editionCourse" ms-html="el.editionName" ms-attr-vid="el.id" key="editionId"
                             ms-addcolor></div>
                    </div>
                    <div class="mycourse_screen_sum_flag"></div>
                </div>

                {{--更多选项--}}
                <div class="more_option" >
                    更多选项
                </div>
                <div class="stop_option none">
                    收起
                </div>
            </div>
            <div style="clear: both"></div>
            <div style="height:20px;background: #F5F5F5"></div>
            {{--选项--}}
            <div class="mycourse_select">
                <div style="width:42px;height:42px;float: left"></div>
                <div class="hot_new">
                    <div class="hot_new_hot blue" ms-click="selectData(1)">
                        热门
                    </div>
                    <div class="huaxian">
                        -
                    </div>
                    <div class="hot_new_new" ms-click="selectData(2)">
                        最新
                    </div>
                </div>
            </div>
            {{--内容--}}
            <div class="mycourse_select_content" ms-if="allCourse.size() > 0 && !loading4">
                <div class="mycourse_select_content_every" ms-repeat="allCourse">
                    <div class="every_details">
                        {{--图片--}}
                        <a  ms-attr-href="courseDetailUrl+ el.id" target="_blank">
                            <div class="every_details_img">
                                <img  ms-attr-src="el.coursePic" width="120px" height="90px" alt="">
                            </div>
                        </a>
                        {{--右侧内容--}}
                        <div class="every_details_content">
                            <div style="height:10px"></div>
                            <a ms-attr-href="courseDetailUrl+el.id"><div class="every_details_content_name" ms-html="el.courseTitle"></div></a>
                            <div style="height:13px;"></div>
                            <a  ms-attr-href="courseDetailUrl+ el.id"></a><div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div></a>
                            <div style="height:22px"></div>
                            <div class="every_details_content_time">
                                <div class="every_details_content_time_author" ms-html=" '上传者 :&nbsp;&nbsp;' + el.username"></div>
                                <div class="every_details_content_time_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>
                        </div>
                        {{--<div class="every_details_button">--}}
                        {{--<div class="every_details_button_img">--}}

                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                    <div class="every_details_learn_collection">
                        <div class="every_details_browse " ms-html="'学习 :&nbsp;&nbsp;' + el.courseStudyNum" >学习  99</div>
                        <div class="every_details_collection"  ms-html="'收藏 :&nbsp;&nbsp;' + el.courseFav"></div>
                    </div>
                </div>
                <div class="noticeMsgAll " ms-if="noticeMsgAll" >
                    暂无课程
                </div>

            </div>

            <div style="clear: both"></div>

            {{--<div style="height:25px;background: #ffffff"></div>--}}
            {{--分页--}}
            <div class="spinner" style="margin: 200px auto;" ms-if="loading4">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-if="pageAll" class="pagecon_parent" style="margin-top:40px;">
                <div class="pagecon">
                    <div id="page_question_all"></div>
                </div>
            </div>

        </div>




        <div style="clear: both"></div>

    </div>

    <div style="height:215px"></div>

    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/studentCourse/list.js')}}"></script>


    <script>
        require(['/studentCourse/list'], function (model) {

            if(window.location.hash){
                model.tab = window.location.hash.split('#')[1];
                model.tabs(model.tab);
            }



            if(model.currentIndex == 'my'){
                $('.mycourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'all'){
                $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
            }

            avalon.directive("addcolor", {
                init: function (binding) {
                    var elem = binding.element;
                    avalon(elem).bind("click",function () {
                        var key = $(this).attr('key');
                        var value = $(this).attr('vid');
                        if($(this).hasClass('hot_new_blue')){
                            if(model[key].length <= 1) return false;
                            $(this).removeClass('hot_new_blue').addClass('hot_new_gray');
                            model.delNum(key, value);
                        }else{
                            $(this).addClass('hot_new_blue').removeClass('hot_new_gray');
                            $(this).siblings().first('children').removeClass('hot_new_blue')
                            model.addNum(key, value);
                        }
                    })
                }
            });



            model.get1();
            model.get2();
            model.get3();
            model.get4();


            avalon.scan();
        });
    </script>

@endsection