@extends('layouts.layoutHome')

@section('title', '课程中心')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/teacherCourse/list.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/uploadify.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/teacherCourse/editPaper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('calendar/datedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/teacherCourse/create.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('calendar/timedropper.min.css')}}">
@endsection

@section('content')

    <div style="height:40px"></div>

    {{--顶端图--}}
    {{--<div class="top_img">--}}
    {{--</div>--}}

    <div class="shadow hide"></div>

    <div ms-controller="teacherCourse">




    <div class="contain_lesson_top_bk">
        <div style="height: 50px;"></div>
        <div class="title">
            <i></i> 课程搜索
        </div>
        <div class="search">
            <input type="text" ms-duplex-string="condition" name="search"/><span ms-click="conditionSearch(condition)">搜索</span>
        </div>
    </div>

    <div style="height:40px"></div>


    <div class="content" >

        <div class="content_left">
            <span class="span_hover"></span><div class="mycourse course_back" ms-click="tabs('my');">我的课程</div>
            <span class="span_hover"></span><div class="reccourse course_back" ms-click="tabs('rec');">推荐课程</div>
            <span class="span_hover"></span><div class="allcourse course_back" ms-click="tabs('all');">全部课程</div>
            <span class="span_hover"></span><div class="newcourse course_back" ms-click="tabsb('new');">新建课程</div>
        </div>

        {{--我的课程--}}
        <div class="content_right_mycourse" ms-visible="currentIndex=='my' || currentIndex == 'shenhe' ">
            {{--选项--}}
            <div class="mycourse_select">
                <div style="width:42px;height:42px;float: left"></div>
                {{--@if(isset($minSubjectId))--}}
                    <div class="release color_blue" ms-click="select(0)">
                        已发布
                    </div>
                    <div class="release color_gray"  ms-click="select(3)">
                        待发布
                    </div>
                    @if($default == 1)
                    <div class="release shenhe color_gray"  ms-click="select(1)">
                        审核中
                    </div>
                    <div class="release color_gray"  ms-click="select(2)">
                        未通过
                    </div>
                    @endif
                {{--@endif--}}
            </div>

            {{--内容--}}
            {{--<div class="mycourse_select_content" ms-if="!noticeMsgMy">--}}
            <div class="mycourse_select_content hide" ms-class="hide:noticeMsgMy" ms-if="myCourse.size() > 0 && !loading1">
                <div class="mycourse_select_content_every" ms-repeat="myCourse">
                    <div class="every_details" >
                        @if($default == 1)
                            {{--图片--}}
                            <div ms-if="el.courseStatus == 0">
                                <a  ms-attr-href="courseDetailUrl+el.id"  target="_blank">
                                    <div class="every_details_img">
                                        <img  ms-attr-src="el.coursePic"    width="120px" height="90px" alt="">
                                    </div>
                                </a>
                            </div>

                            <div ms-if="el.courseStatus != 0" target="_blank">
                                <div class="every_details_img">
                                    <img  ms-attr-src="el.coursePic"    width="120px" height="90px" alt="">
                                </div>
                            </div>

                            {{--右侧内容--}}
                            {{--已发布课程--}}

                            <div class="every_details_content" ms-if="el.courseStatus == 0">
                                <div style="height:10px"></div>
                                <a  ms-attr-href="courseDetailUrl+ el.id" target="_blank">
                                    <div class="every_details_content_name" ms-html="el.courseTitle"></div>
                                </a>
                                <div style="height:13px;"></div>
                                <a  ms-attr-href="courseDetailUrl+ el.id" target="_blank"><div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div></a>
                                <div style="height:22px"></div>
                                <div class="every_details_content_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>
                            {{--其他课程--}}
                            <div class="every_details_content" ms-if="el.courseStatus != 0">
                                <div style="height:10px"></div>
                                <div class="every_details_content_name" ms-html="el.courseTitle"></div>
                                <div style="height:13px;"></div>
                                <div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div>
                                <div style="height:22px"></div>
                                <div class="every_details_content_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>

                        @else
                            {{--图片--}}{{-- 后台审核关闭 $default == 0 --}}
                            <div ms-if="el.courseStatus == 0 || el.courseStatus == 7">
                                <a  ms-attr-href="courseDetailUrl+el.id"  target="_blank">
                                    <div class="every_details_img">
                                        <img  ms-attr-src="el.coursePic"    width="120px" height="90px" alt="">
                                    </div>
                                </a>
                            </div>

                            <div ms-if="el.courseStatus == 3 || el.courseStatus == 4 || el.courseStatus == 6">
                                <div class="every_details_img">
                                    <img  ms-attr-src="el.coursePic"    width="120px" height="90px" alt="">
                                </div>
                            </div>

                            {{--右侧内容--}}
                            {{--已发布课程--}}

                            <div class="every_details_content" ms-if="el.courseStatus == 0">
                                <div style="height:10px"></div>
                                <a  ms-attr-href="courseDetailUrl+ el.id" target="_blank">
                                    <div class="every_details_content_name" ms-html="el.courseTitle"></div>
                                </a>
                                <div style="height:13px;"></div>
                                <a  ms-attr-href="courseDetailUrl+ el.id" target="_blank"><div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div></a>
                                <div style="height:22px"></div>
                                <div class="every_details_content_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>

                            <div class="every_details_content" ms-if="el.courseStatus == 7">
                                <div style="height:10px"></div>
                                <div class="every_details_content_name" ms-click="popUpSwitch('resourcePass')">
                                    <div class="content_name_first" ms-html="el.courseTitle"></div>
                                    <div class="content_name_second" ms-if="el.courseStatus == 7" ms-html="'转码中...'"></div>
                                </div>
                                <div style="height:13px;"></div>
                                <div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div>
                                <div style="height:22px"></div>
                                <div class="every_details_content_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>
                            {{--其他课程--}}
                            <div class="every_details_content" ms-if="el.courseStatus == 3 || el.courseStatus == 4 || el.courseStatus == 6">
                                <div style="height:10px"></div>
                                <div class="every_details_content_name">
                                    <div class="content_name_first" ms-html="el.courseTitle"></div>
                                    <div class="content_name_second" ms-if="el.courseStatus == 6" ms-html="'转码失败'"></div>
                                </div>
                                <div style="height:13px;"></div>
                                <div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div>
                                <div style="height:22px"></div>
                                <div class="every_details_content_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>
                        @endif
                        <div class="every_details_button">
                            <div class="every_details_button_img_del " ms-if="el.courseStatus == '0' || el.courseStatus=='2' || el.courseStatus=='3' || el.courseStatus == '4' " ms-click="deleteCourse(el.id)"  >
                                删除课程
                            </div>
                            <div class="every_details_button_img_edit "  ms-click="editCourse(el.id)"  ms-if="el.courseStatus=='2' || el.courseStatus =='3' || el.courseStatus == '4' ">
                                编辑课程
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div style="clear: both"></div>

            {{--<div style="height:25px;background: #ffffff"></div>--}}
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
            <div class="noticeMsgMy " ms-if="noticeMsgMy" >
            {{--<div class="noticeMsgMy hide" ms-class="hide:noticeMsgMy" >--}}
            暂无课程
            </div>

        </div>



        {{--推荐课程--}}
        <div class="content_right_reccourse hide" ms-visible="currentIndex=='rec'" >
            {{--最小科目id--}}
            @if(isset($minSubjectId))
                <input  type="hidden" class="minSubjectId" value="{{$minSubjectId}}"  >
            @endif
            {{--选项--}}
            <div class="mycourse_select" >
                <div style="width:42px;height:42px;float: left"></div>
                {{--<div class="release color_gray" ms-click="selectSubjectAll('all')">全部</div>--}}
                @foreach($subject as $s)
                    <div class="release " ms-click="selectSubject('{{$s->id}}')">
                        {{$s->subjectName}}
                    </div>
                @endforeach
            </div>
            {{--内容--}}
            <div class="mycourse_select_content"  ms-if="recCourse.size() > 0 && !loading2">
                <div class="mycourse_select_content_every" ms-repeat="recCourse">
                    <div class="every_details">
                        {{--图片--}}
                        <a  ms-attr-href="courseDetailUrl+ el.id" target="_blank">
                            <div class="every_details_img">
                                <img  ms-attr-src="el.coursePic"    width="120px" height="90px" alt="">
                            </div>
                        </a>
                        {{--右侧内容--}}
                        <div class="every_details_content">
                            <div style="height:10px"></div>
                            <a ms-attr-href="courseDetailUrl+ el.id"><div class="every_details_content_name" ms-html="el.courseTitle"></div></a>
                            <div style="height:13px;"></div>
                            <a  ms-attr-href="courseDetailUrl+ el.id"></a><div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div></a>
                            <div style="height:22px"></div>
                            <div class="every_details_content_time_author" ms-html=" '发布者 :&nbsp;&nbsp;' + el.username"></div>
                            <div class="every_details_content_time_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                        </div>
                        {{--<div class="every_details_button">--}}
                            {{--<div class="every_details_button_img">--}}

                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                    <div class="every_details_learn_collection">
                        <div class="every_details_learn" ms-html=" '学习 &nbsp;' +  el.courseStudyNum"></div>
                        <div class="every_details_collection" ms-html=" '收藏 &nbsp;' + el.courseFav " ></div>
                    </div>
                </div>
            </div>

            <div style="clear: both"></div>

            {{--<div style="height:25px;background: #ffffff"></div>--}}
            {{--分页--}}
            <div class="spinner" style="margin: 200px auto;" ms-if="loading2">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div ms-if="pageRec" class="pagecon_parent" style="margin-top:40px;">
                <div class="pagecon">
                    <div id="page_question_rec"></div>
                </div>
            </div>
            <div class="noticeMsgRec hide" ms-class="hide: !noticeMsgRec" ms-if="noticeMsgRec" >
                暂无课程
            </div>

        </div>



        {{--所有课程--}}
        <div class="content_right_allcourse hide" ms-visible="currentIndex=='all'" >
            {{--筛选--}}
            <div class="mycourse_screen">

                <div style="height:15px"></div>

                <div class="mycourse_screen_sum">
                    <div class="mycourse_screen_sum_title">年级</div>
                    <div class="mycourse_screen_sum_sel">
                        <div class="all_grade hot_new_blue" ms-click="selectAll('gradeId');">全部</div>
                        <div class="mycourse_screen_every1" ms-repeat="gradeCourse" ms-html="el.gradeName"  ms-attr-vid="el.id" key="gradeId"
                             ms-addcolor></div>
                    </div>
                    <div class="mycourse_screen_sum_flag"></div>
                </div>
                <div style="clear: both"></div>
                <div class="mycourse_screen_sum">
                    <div class="mycourse_screen_sum_title">科目</div>
                    <div class="mycourse_screen_sum_sel ">
                        <div class="all_subject hot_new_blue " ms-click="selectAll('subjectId');">全部</div>
                        <div class="mycourse_screen_every2" ms-repeat="subjectCourse" ms-html="el.subjectName" ms-attr-vid="el.id" key="subjectId" ms-addcolor></div>
                    </div>
                    <div class="mycourse_screen_sum_flag"></div>
                </div>
                <div style="clear: both"></div>

                <div class="mycourse_screen_sum">
                    <div class="mycourse_screen_sum_title">册别</div>
                    <div class="mycourse_screen_sum_sel">
                        <div class="all_book hot_new_blue " ms-click="selectAll('bookId');">全部</div>
                        <div class="mycourse_screen_every3" ms-repeat="bookCourse" ms-html="el.bookName" ms-attr-vid="el.id" key="bookId" ms-addcolor></div>
                    </div>
                    <div class="mycourse_screen_sum_flag"></div>
                </div>
                <div style="clear: both"></div>

                <div class="mycourse_screen_sum version" style="display: none">
                    <div class="mycourse_screen_sum_title">版本</div>
                    <div class="mycourse_screen_sum_sel">
                        <div class="all_edition hot_new_blue " ms-click="selectAll('editionId');">全部</div>
                        <div class="mycourse_screen_every4" ms-repeat="editionCourse" ms-html="el.editionName" ms-attr-vid="el.id" key="editionId" ms-addcolor></div>
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
            <div class="mycourse_selects">
                <div style="width:42px;height:42px;float: left"></div>
                <div class="hot_new">
                    <div class="hot_new_hot hot_new_blue" ms-click="selectData(1)" >
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
            <div class="mycourse_select_content" ms-if="allCourse.size() > 0 && !loading2">
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
                            <a ms-attr-href="courseDetailUrl+ el.id"><div class="every_details_content_name" ms-html="el.courseTitle"></div></a>
                            <div style="height:13px;"></div>
                            <a  ms-attr-href="courseDetailUrl+ el.id"><div class="every_details_content_title" ms-html="el.editionName + ' &nbsp;&nbsp;&nbsp;' + el.gradeName+ el.subjectName + el.bookname"></div></a>
                            <div style="height:22px"></div>
                            <div class="every_details_content_time">
                                <div class="every_details_content_time_author" ms-html=" '发布者 :&nbsp;&nbsp;' + el.username"></div>
                                <div class="every_details_content_time_time" ms-html=" '发布时间 :&nbsp;&nbsp;' + el.created_at | truncate(19,' ')"></div>
                            </div>
                        </div>
                    </div>

                    <div class="every_details_learn_collection">
                        <div class="every_details_learn" ms-html="'学习 :&nbsp;&nbsp;' + el.courseStudyNum"></div>
                        <div class="every_details_collection"  ms-html="'收藏 :&nbsp;&nbsp;' + el.courseFav"></div>
                    </div>
                </div>
                <div class="noticeMsgAll " ms-if="noticeMsgAll" >
                    暂无课程
                </div>
            </div>

            <div style="clear: both"></div>
            <div class="spinner" style="margin: 200px auto;" ms-if="loading3">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            {{--<div style="height:25px;background: #ffffff"></div>--}}
            <div ms-if="pageAll" class="pagecon_parent" style="margin-top:40px;">
                <div class="pagecon">
                    <div id="page_question_all"></div>
                </div>
            </div>

        </div>



        {{--新建课程--}}
        <div class="content_right_newcourse hide" ms-controller="createCourse" ms-visible="currentIndex=='new'" >
            <div class="content_right_newcourse_top">新建课程</div>
            {{--进度--}}
            <div class="content_right_newcourse_bar" ms-class="[--pageClass--]:true">
                <div class="content_right_newcourse_bar_a" ms-click="changePage(1)"></div>
                <div class="content_right_newcourse_bar_b" ms-click="changePage(2)"></div>
                <div class="content_right_newcourse_bar_c" ms-click="changePage(3)"></div>
            </div>
            {{--教学设计--}}
            <div class="content_right_newcourse_design hide" ms-class="hide:!pageNuma">
                <div class="content_right_newcourse_design_bara">
                    <div class="content_right_newcourse_design_bara_l">课程归属</div>
                    <div class="content_right_newcourse_design_bara_r" style="border: none;">
                        <select name=""  class="js-example-basic-single subject" style="width: 501px;">
                            <option selected="selected" value="">年级 - 学科 - 版本 - 册别</option>
                        </select>
                    </div>
                </div>

                <div class="content_right_newcourse_design_bara">
                    <div class="content_right_newcourse_design_bara_l">所属章节</div>
                    <div class="content_right_newcourse_design_bara_r" style="border: none;">
                        <select name=""  class="js-example-basic-single chapter" style="width: 501px;">
                            <option selected="selected" value="">请选择章节</option>
                        </select>
                    </div>
                </div>

                <div class="content_right_newcourse_design_bara">
                    <div class="content_right_newcourse_design_bara_l">课程名称</div>
                    <div class="content_right_newcourse_design_bara_r" style="overflow: hidden">
                        <input type="text" ms-duplex="postInfo.baseInfo.courseTitle">
                    </div>
                </div>

                <div class="content_right_newcourse_design_barb">
                    <div class="content_right_newcourse_design_bara_l">课程封面</div>
                    <div class="content_right_newcourse_design_barb_r" style="border: none">
                        <div class="content_right_newcourse_design_barb_r_img">
                            <img ms-attr-src="postInfo.baseInfo.coursePic" class="center_right_comment_fabu_a_con_pic_r_l_pic" width="120" height="90" alt="">
                        </div>
                        <div class="content_right_newcourse_design_barb_r_r">
                            <div class="content_right_newcourse_design_barb_r_r_li">图片尺寸：120*90</div>
                            <div class="content_right_newcourse_design_barb_r_r_li">图片大小不超过5M</div>
                            <div class="content_right_newcourse_design_barb_r_r_btn">上传封面</div>
                        </div>
                        <div class="content_right_newcourse_design_barb_r_upbtn">
                            <input id="file_upload_coursepic" name="file_upload_coursepic" type="file" multiple="false" value=""/>
                        </div>
                    </div>
                </div>

                <div class="content_right_newcourse_design_barb">
                    <div class="content_right_newcourse_design_bara_l">知识点</div>
                    <div class="content_right_newcourse_design_barb_r">
                        <div class="content_right_newcourse_design_barb_r_top">
                            <textarea ms-duplex="postInfo.baseInfo.courseIntro" maxlength="80"></textarea>
                        </div>
                        <div class="content_right_newcourse_design_barb_r_fot" ms-html="(postInfo.baseInfo.courseIntro).length"></div>
                    </div>
                </div>

                <div style="height:5px;"></div>
                <div class="content_right_newcourse_design_barc">
                    <div class="content_right_newcourse_design_bara_l">编辑教案</div>
                    <div class="content_right_newcourse_design_barc_r">
                        {{--<div class="content_right_newcourse_design_barc_r_t"><span>模板</span> | <span>导入</span></div>--}}
                        <div class="content_right_newcourse_design_barc_r_r">
                            <script id="container" name="courseContent" type="text/plain"></script>
                        </div>
                    </div>
                </div>

                <div class="content_right_newcourse_design_next">
                    <div style="height:20px;"></div>
                    <div class="btn" ms-click="changePage(2)">下一步</div>
                </div>
            </div>
            {{--创建课程--}}
            <div class="content_right_newcourse_create hide" ms-class="hide:!pageNumb">
                <div class="content_right_newcourse_create_con">
                    {{--第一部分 课前导学--}}
                    <div class="content_right_newcourse_create_con_list">
                        <div class="content_right_newcourse_create_con_bar" ms-click="stacks(1,1)">
                            {{-- 上下箭头 up down--}}
                            <div class="content_right_newcourse_create_con_bar_img" ms-class-1="up:showparta" ms-class-2="down:!showparta"></div>
                            <div class="content_right_newcourse_create_con_bar_t">第一部分</div>
                            <div class="content_right_newcourse_create_con_bar_t">课前导学</div>
                        </div>
                        {{-- 导学 列表--}}
                        <div ms-if="showparta">
                            <div class="content_right_newcourse_create_con_node_li" ms-repeat="postInfo.prelearnInfo">
                                <div class="content_right_newcourse_create_con_li">
                                    <div class="content_right_newcourse_create_con_li_num" ms-html="'1.'+($index+1)">1.1</div>
                                    <div class="content_right_newcourse_create_con_li_tit"><input type="text" ms-duplex="el.title"></div>
                                    <div class="content_right_newcourse_create_con_li_btncon">
                                        <div class="content_right_newcourse_create_con_li_btncon_btn upload" ms-attr-type="1" ms-attr-chapter="$index" ms-attr-node="0" ms-slectfile></div>
                                        <div class="content_right_newcourse_create_con_li_btncon_btn selfile" ms-click="selresource(el)"></div>
                                        <div class="content_right_newcourse_create_con_li_btncon_btn del" ms-click="delchapter(1,el,0,0,$index)"></div>
                                        {{--上下箭头 up2 down2 --}}
                                        <div class="content_right_newcourse_create_con_li_btncon_mor" ms-if="el.dataInfo.length" ms-hide>收起</div>
                                    </div>
                                    {{--文件域--}}
                                    <div>
                                        {{--<input class="fileresource" name="fileresource" type="file" class="files" multiple style="display: none">--}}
                                    </div>
                                </div>
                                {{--上传导学资料 列表--}}
                                <div>
                                    <div class="content_right_newcourse_create_con_li_li_con" ms-repeat="el.dataInfo">
                                        <div class="content_right_newcourse_create_con_li_num"></div>
                                        <div class="content_right_newcourse_create_con_li_tit" style="border: none">
                                            <div class="content_right_newcourse_create_con_li_tit_li_name_con">
                                                <div class="content_right_newcourse_create_con_li_tit_li_name_con_name" ms-html="el.title">一去二三里.doc</div>
                                                <div class="content_right_newcourse_create_con_li_tit_li_name_con_status" ms-if=el.fileID><img src="/home/image/teacherCourse/newok.png"  alt=""></div>
                                            </div>
                                            {{--进度条--}}
                                            <div class="content_right_newcourse_create_con_li_li_con_jdbarcon" ms-if="el.showjdbar">
                                                <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_a">
                                                    <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_b" ms-css-width="[--el.progressBara--]%">
                                                        <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_c" ms-css-width="[--el.progressBarb--]%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="content_right_newcourse_create_con_li_li_con_jdnum hide" style="text-indent:10px" ms-class="hide:el.jdmsg">读取中..</div>
                                                <div class="content_right_newcourse_create_con_li_li_con_jdnum hide" ms-class="hide:!el.jdmsg"   ms-html="el.progressBarb+'%'">75%</div>
                                            </div>
                                        </div>
                                        <div class="content_right_newcourse_create_con_li_btncon_btn del" ms-click="dostopupload(1,el,0,$outer.$index,$index)"></div>
                                        {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipatime" ms-class-2="haveaddtip:el.tipatime" ms-click="showaddtip(el,1)" ></div>--}}
                                        {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipbtime" ms-class-2="haveaddtip:el.tipbtime" ms-if="el.tipatime || el.tipbtime" ms-click="showaddtip(el,2)" ></div>--}}
                                        {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipctime" ms-class-2="haveaddtip:el.tipctime" ms-if="el.tipbtime || el.tipctime" ms-click="showaddtip(el,3)" ></div>--}}
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                            {{--添加练习展示--}}
                            <div class="content_right_newcourse_create_con_node_li hide" ms-class="hide:!testIda">
                                <div class="content_right_newcourse_create_con_li">
                                    <div class="content_right_newcourse_create_con_li_num" ms-html=" '1.' + (postInfo.prelearnInfo.length+1)"></div>
                                    <div class="content_right_newcourse_create_con_li_tit"><input type="text" ms-duplex="testTitlea" readOnly></div>
                                    <div class="content_right_newcourse_create_con_li_btncon">
                                        <div class="content_right_newcourse_create_con_li_btncon_btn del" ms-click="deltest(1)"></div>
                                    </div>
                                </div>
                            </div>
                            {{--添加导学 添加练习 按钮--}}
                            <div style="height: 20px;;"></div>
                            <div class="content_right_newcourse_create_con_node_li_addbtn" style="border:none">
                                <div class="content_right_newcourse_create_con_node_li_addbtn_l" ms-click="addchapter(1)">+添加导学</div>
                                <div class="content_right_newcourse_create_con_node_li_addbtn_r" ms-if="postInfo.prelearnInfo.length > 0" ms-click="addtest(1)">+添加同步练习</div>
                            </div>
                        </div>
                        <div style="height: 40px;clear: both;"></div>
                    </div>
                    {{--第二部分 课堂授课--}}
                    <div class="content_right_newcourse_create_con_list">
                        <div class="content_right_newcourse_create_con_bar" ms-click="stacks(1,2)">
                            {{-- 上下箭头 up down--}}
                            <div class="content_right_newcourse_create_con_bar_img" ms-class-1="up:showpartb" ms-class-2="down:!showpartb"></div>
                            <div class="content_right_newcourse_create_con_bar_t">第二部分</div>
                            <div class="content_right_newcourse_create_con_bar_t">课堂授课</div>
                        </div>
                        {{-- 章 列表--}}
                        <div ms-if="showpartb">
                            <div class="content_right_newcourse_create_con_node_li" ms-repeat-outel="postInfo.chaprerInfo">
                                {{--章名称--}}
                                <div class="content_right_newcourse_create_con_li">
                                    <div class="content_right_newcourse_create_con_li_num" ms-html="'2.'+($index+1)">2.1</div>
                                    <div class="content_right_newcourse_create_con_li_tit"><input type="text" ms-duplex="outel.title"></div>
                                </div>
                                {{--节列表--}}
                                <div class="content_right_newcourse_create_con_node_li_li" ms-repeat-inner="outel.nodeInfo">
                                    <div class="content_right_newcourse_create_con_li">
                                        <div class="content_right_newcourse_create_con_li_num" ms-html="'2.'+($outer.$index+1)+'.'+($index+1)">2.1.1</div>
                                        <div class="content_right_newcourse_create_con_li_tit"><input type="text" ms-duplex="inner.title"></div>
                                        <div class="content_right_newcourse_create_con_li_btncon">
                                            <div class="content_right_newcourse_create_con_li_btncon_btn upload" ms-attr-type="2" ms-attr-chapter="$outer.$index" ms-attr-node="$index" ms-slectfile></div>
                                            <div class="content_right_newcourse_create_con_li_btncon_btn selfile" ms-click="selresource(inner)"></div>
                                            <div class="content_right_newcourse_create_con_li_btncon_btn del"  ms-click="delchapter(2,inner,outel,$outer.$index,$index)"></div>
                                            {{--上下箭头 up2 down2 --}}
                                            <div class="content_right_newcourse_create_con_li_btncon_mor" ms-if="inner.dataInfo.length" ms-hide>收起</div>
                                        </div>
                                        {{--文件域--}}
                                        <div>
                                            {{--<input class="fileresource" name="fileresource" type="file" class="files" multiple style="display: none">--}}
                                        </div>
                                    </div>
                                    {{--上传节文件 列表--}}
                                    <div>
                                        <div class="content_right_newcourse_create_con_li_li_con" ms-repeat-el="inner.dataInfo">
                                            <div class="content_right_newcourse_create_con_li_num"></div>
                                            <div class="content_right_newcourse_create_con_li_tit" style="border: none">
                                                <div class="content_right_newcourse_create_con_li_tit_li_name_con">
                                                    <div class="content_right_newcourse_create_con_li_tit_li_name_con_name" ms-html="el.title">一去二三里.doc</div>
                                                    {{--<div class="content_right_newcourse_create_con_li_tit_li_name_con_status hide" ms-class="hide:el.showjdbar"><img src="/home/image/teacherCourse/newok.png"  alt=""></div>--}}
                                                    <div class="content_right_newcourse_create_con_li_tit_li_name_con_status" ms-if="el.fileID"><img src="/home/image/teacherCourse/newok.png"  alt=""></div>
                                                </div>
                                                {{--进度条--}}
                                                {{--<div class="content_right_newcourse_create_con_li_li_con_jdbarcon hide" ms-class="hide:el.showjdbar">--}}
                                                <div class="content_right_newcourse_create_con_li_li_con_jdbarcon" ms-if="el.showjdbar">
                                                    <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_a">
                                                        <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_b" ms-css-width="[--el.progressBara--]%">
                                                            <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_c" ms-css-width="[--el.progressBarb--]%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{--<div class="content_right_newcourse_create_con_li_li_con_jdnum hide" ms-class="hide:el.jdmsg"  ms-html="el.progressBara+'%'">75%</div>--}}
                                                    <div class="content_right_newcourse_create_con_li_li_con_jdnum hide" style="text-indent:10px" ms-class="hide:el.jdmsg">读取中..</div>
                                                    <div class="content_right_newcourse_create_con_li_li_con_jdnum hide" ms-class="hide:!el.jdmsg"   ms-html="el.progressBarb+'%'">75%</div>
                                                </div>
                                            </div>
                                            <div class="content_right_newcourse_create_con_li_btncon_btn del" ms-click="dostopupload(2,el,$outer.$outer.$index,$outer.$index,$index)"></div>
                                            {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipatime" ms-class-2="haveaddtip:el.tipatime" ms-click="showaddtip(el,1)" ></div>--}}
                                            {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipbtime" ms-class-2="haveaddtip:el.tipbtime" ms-if="el.tipatime || el.tipbtime" ms-click="showaddtip(el,2)" ></div>--}}
                                            {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipctime" ms-class-2="haveaddtip:el.tipctime" ms-if="el.tipbtime || el.tipctime" ms-click="showaddtip(el,3)" ></div>--}}
                                            <div style="clear: both"></div>
                                        </div>
                                    </div>
                                </div>
                                <div style="height: 20px;"></div>
                                <div class="content_right_newcourse_create_con_node_li_addbtn" style="border:none">
                                    <div class="content_right_newcourse_create_con_node_li_addbtn_l" ms-click="addchapter(2,$index)">+新增小节</div>
                                    <div class="content_right_newcourse_create_con_node_li_addbtn_r" ms-if="($index+1) == postInfo.chaprerInfo.length" ms-click="addtest(2)">+添加同步练习</div>
                                </div>
                                {{--<div style="height: 20px;"></div>--}}
                            </div>
                            {{--添加练习展示--}}
                            <div class="content_right_newcourse_create_con_node_li hide" ms-class="hide:!testIdb">
                                <div class="content_right_newcourse_create_con_li">
                                    <div class="content_right_newcourse_create_con_li_num" ms-html=" '2.' + (postInfo.chaprerInfo.length+1)"></div>
                                    <div class="content_right_newcourse_create_con_li_tit"><input type="text" ms-duplex="testTitleb" readOnly></div>
                                    <div class="content_right_newcourse_create_con_li_btncon">
                                        <div class="content_right_newcourse_create_con_li_btncon_btn del" ms-click="deltest(2)"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="content_right_newcourse_create_con_node_li_addbtn" style="cursor: pointer;margin-top: 20px;" ms-click="addchapter(3)">添加章</div>
                        </div>
                        <div style="height: 50px;"></div>
                    </div>
                    {{--第三部分 课堂指导--}}
                    <div class="content_right_newcourse_create_con_list">
                        <div class="content_right_newcourse_create_con_bar" ms-click="stacks(1,3)">
                            {{-- 上下箭头 up down--}}
                            <div class="content_right_newcourse_create_con_bar_img" ms-class-1="up:showpartc" ms-class-2="down:!showpartc"></div>
                            <div class="content_right_newcourse_create_con_bar_t">第三部分</div>
                            <div class="content_right_newcourse_create_con_bar_t">课后指导</div>
                        </div>
                        {{-- 指导 列表--}}
                        <div ms-if="showpartc">
                            <div class="content_right_newcourse_create_con_node_li" ms-repeat="postInfo.guideInfo">
                                <div class="content_right_newcourse_create_con_li">
                                    <div class="content_right_newcourse_create_con_li_num" ms-html="'3.'+($index+1)">3.1</div>
                                    <div class="content_right_newcourse_create_con_li_tit"><input type="text" ms-duplex="el.title"></div>
                                    <div class="content_right_newcourse_create_con_li_btncon">
                                        <div class="content_right_newcourse_create_con_li_btncon_btn upload" ms-attr-type="3" ms-attr-chapter="$index" ms-attr-node="0" ms-slectfile></div>
                                        <div class="content_right_newcourse_create_con_li_btncon_btn selfile" ms-click="selresource(el)"></div>
                                        <div class="content_right_newcourse_create_con_li_btncon_btn del" ms-click="delchapter(3,el,0,0,$index)"></div>
                                        {{--上下箭头 up2 down2 --}}
                                        <div class="content_right_newcourse_create_con_li_btncon_mor" ms-if="el.dataInfo.length" ms-hide>收起</div>
                                    </div>
                                    {{--文件域--}}
                                    <div>
                                        {{--<input class="fileresource" name="fileresource" type="file" class="files" multiple style="display: none">--}}
                                    </div>
                                </div>
                                {{--上传指导文件 列表--}}
                                <div>
                                    <div class="content_right_newcourse_create_con_li_li_con" ms-repeat="el.dataInfo">
                                        <div class="content_right_newcourse_create_con_li_num"></div>
                                        <div class="content_right_newcourse_create_con_li_tit" style="border: none">
                                            <div class="content_right_newcourse_create_con_li_tit_li_name_con">
                                                <div class="content_right_newcourse_create_con_li_tit_li_name_con_name" ms-html="el.title">一去二三里.doc</div>
                                                <div class="content_right_newcourse_create_con_li_tit_li_name_con_status" ms-if="el.fileID"><img src="/home/image/teacherCourse/newok.png"  alt=""></div>
                                            </div>
                                            {{--进度条--}}
                                            <div class="content_right_newcourse_create_con_li_li_con_jdbarcon" ms-if="el.showjdbar">
                                                <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_a">
                                                    <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_b" ms-css-width="[--el.progressBara--]%">
                                                        <div class="content_right_newcourse_create_con_li_li_con_jdbarcon_bar_c" ms-css-width="[--el.progressBarb--]%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="content_right_newcourse_create_con_li_li_con_jdnum hide" style="text-indent:10px" ms-class="hide:el.jdmsg">读取中..</div>
                                                <div class="content_right_newcourse_create_con_li_li_con_jdnum hide" ms-class="hide:!el.jdmsg"   ms-html="el.progressBarb+'%'">75%</div>
                                            </div>
                                        </div>
                                        <div class="content_right_newcourse_create_con_li_btncon_btn del" ms-click="dostopupload(3,el,0,$outer.$index,$index)"></div>
                                        {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipatime" ms-class-2="haveaddtip:el.tipatime" ms-click="showaddtip(el,1)" ></div>--}}
                                        {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipbtime" ms-class-2="haveaddtip:el.tipbtime" ms-if="el.tipatime || el.tipbtime" ms-click="showaddtip(el,2)" ></div>--}}
                                        {{--<div class="content_right_newcourse_create_con_li_btncon_btn" ms-class-1="addtip:!el.tipctime" ms-class-2="haveaddtip:el.tipctime" ms-if="el.tipbtime || el.tipctime" ms-click="showaddtip(el,3)" ></div>--}}
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                            {{--添加练习展示--}}
                            <div class="content_right_newcourse_create_con_node_li hide" ms-class="hide:!testIdc">
                                <div class="content_right_newcourse_create_con_li">
                                    <div class="content_right_newcourse_create_con_li_num" ms-html=" '3.' + (postInfo.guideInfo.length+1)"></div>
                                    <div class="content_right_newcourse_create_con_li_tit"><input type="text" ms-duplex="testTitlec" readOnly></div>
                                    <div class="content_right_newcourse_create_con_li_btncon">
                                        <div class="content_right_newcourse_create_con_li_btncon_btn del" ms-click="deltest(3)"></div>
                                    </div>
                                </div>
                            </div>
                            {{--添加指导 添加练习 按钮--}}
                            <div style="height: 20px;;"></div>
                            <div class="content_right_newcourse_create_con_node_li_addbtn" style="border:none;">
                                <div class="content_right_newcourse_create_con_node_li_addbtn_l" ms-click="addchapter(4)">+添加指导</div>
                                <div class="content_right_newcourse_create_con_node_li_addbtn_r" ms-if="postInfo.guideInfo.length > 0" ms-click="addtest(3)">+添加同步练习</div>
                            </div>
                        </div>
                        <div style="height: 40px;clear: both;"></div>
                    </div>
                    <input type="text" value="" class="md5container" id="md5container" style="display: none">{{--MD5容器--}}

                    <div class="content_right_newcourse_create_con_jdbtncon">
                        <div class="content_right_newcourse_create_con_jdbtncon_btn" style="margin-left: 100px;" ms-click="changePage(1)">上一步</div>
                        <div class="content_right_newcourse_create_con_jdbtncon_btn" style="margin-left: 200px;" ms-click="changePage(3)">保存并发布</div>
                    </div>

                </div>
            </div>
            {{--发布课程--}}
            <div class="content_right_newcourse_commit hide" ms-class="hide:!pageNumc">
                <div class="content_right_newcourse_commit_con">
                    <div class="content_right_newcourse_commit_con_top">选择下发班级</div>
                    {{--<div class="content_right_newcourse_commit_class_li selclass">一年级二班</div>--}}
                    <div class="content_right_newcourse_commit_class_li" ms-repeat="classes" ms-class="selclass:!el.issel" ms-html="el.gradeName+el.className" ms-click="clickclass(el)">二年级二班</div>
                </div>
                <div class="content_right_newcourse_commit_sure" ms-click="commitAll()">确认下发</div>
            </div>

            {{-- 试题布置弹窗 --}}
            <div class="choose_paper_pop hide">
                <div class="choose_paper_pop_top">
                    <div class="choose_paper_pop_top_title">
                        <div class="option_checked" ms-class="option_checked:papertype" ms-click="changepaper(1)">平台题库</div>
                        <div ms-class="option_checked:!papertype" ms-click="changepaper(2)">我的收藏</div>
                    </div>
                    <div class="choose_paper_pop_top_close" ms-click="closetest()"></div>
                </div>
                <div class="choose_paper_pop_center">
                    <div class="choose_paper_pop_center_repeat" ms-repeat="paperInfo">
                        <div class="choose_paper_pop_center_repeat_title" ms-text="el.title">单词与句子</div>
                        <div class="choose_paper_pop_center_repeat_author" ms-text="'发布者：' + el.username"></div>
                        <div class="choose_paper_pop_center_repeat_time" ms-text="'上传时间：' + el.created_at"></div>
                        <a>
                            <div class="choose_paper_pop_center_repeat_icon" ms-attr-id="el.id" ms-click="selpaper(addtestType,el.id,el.title)"></div>
                        </a>
                    </div>
                    <div class="choose_paper_pop_center_nodata choose_paper_pop_center_nodata_paper" ms-if="!paperInfo.length">正在查找..</div>
                </div>
                <div class="choose_paper_pop_bot">
                    <a ms-attr-href="'/evaluateManageTea/editPaper/' + lessonTemp + '/0'">
                        <div ms-click="createPaper(addtestType)">+新建试卷</div>
                    </a>
                </div>
            </div>

            {{-- 选择资源弹窗 --}}
            <div class="choose_paper_pop_b hide">
                <div class="choose_paper_pop_top">
                    <div class="choose_paper_pop_top_title">
                        <div class="option_checked" ms-class="option_checked:resourcelisttype" ms-click="changereslist(1)">资源中心</div>
                        <div ms-class="option_checked:!resourcelisttype" ms-click="changereslist(2)">个人收藏</div>
                    </div>
                    <div class="choose_paper_pop_top_close" ms-click="closetestres()"></div>
                </div>
                <div class="choose_paper_pop_center">
                    <div class="choose_paper_pop_center_repeat choose_paper_pop_center_repeat_t" ms-html="selgradecontent">年级 科目 版本 测别</div>
                    <div class="choose_paper_pop_center_repeat" ms-repeat="resourcelist">
                        <div class="choose_paper_pop_center_repeat_title" ms-title="el.resourceTitle" ms-text="el.resourceTitle">单词与句子</div>
                        <div class="choose_paper_pop_center_repeat_author" ms-title="el.username" ms-text="'发布者：' + el.username"></div>
                        <div class="choose_paper_pop_center_repeat_time">
                            <span ms-html="'上传时间：'"></span>
                            <span ms-html="el.created_at|sliceTime('year')"></span>
                        </div>
                        <a>
                            <div class="choose_paper_pop_center_repeat_icon" ms-attr-id="el.id" ms-click="sellistres(nownodeobj,el)"></div>
                        </a>
                    </div>
                    <div class="choose_paper_pop_center_nodata choose_paper_pop_center_nodata_res" ms-if="!resourcelist.length">正在查找..</div>
                </div>
                {{--<div class="choose_paper_pop_bot">--}}
                    {{--<a ms-attr-href="'/evaluateManageTea/editPaper/' + lessonTemp + '/0'">--}}
                        {{--<div ms-click="createPaper(addtestType)">+新建试卷</div>--}}
                    {{--</a>--}}
                {{--</div>--}}
            </div>

            {{-- 新增贴士弹窗 --}}
            <div class="choose_paper_pop_c hide">
                <div class="choose_paper_pop_top">
                    <div class="choose_paper_pop_top_title title">新增知识点</div>
                    {{--<div class="choose_paper_pop_top_close" ms-click="closetestres()"></div>--}}
                </div>
                <div class="choose_paper_pop_center">
                    <div class="choose_paper_pop_center_tit">出现时间</div>
                    <div class="choose_paper_pop_center_timebarcon">
                        <div style="height:20px;"></div>
                        <div class="choose_paper_pop_center_timebarcon_bar_con">
                            <div class="timepoint" id="timepoint">
                                <div class="timesecond timemid" ms-html="formTime(nowpoint)">00:30:50</div>
                            </div>
                            <div class="choose_paper_pop_center_timebarcon_bar">
                                <div class="timesecond timetart">00:00:00</div>
                                <div class="timesecond timeend" ms-html="formTime(courseDuration)">01:30:50</div>
                            </div>
                        </div>
                    </div>
                    <div class="choose_paper_pop_center_tit">贴士内容</div>
                    <div class="choose_paper_pop_center_concon">
                        <div class="choose_paper_pop_center_con">
                            <div class="choose_paper_pop_center_con_top">
                                <textarea name="" id="" cols="30" rows="10" ms-duplex="nowtipcon" maxlength="50"></textarea>
                            </div>
                            <div class="choose_paper_pop_center_con_fot" ms-html=" nowtipcon.length+'/50' ">0/50</div>
                        </div>
                    </div>
                </div>
                <div style="height:15px;"></div>
                <div class="choose_paper_pop_center_btncon">
                    {{--<div class="choose_paper_pop_center_btn center_btn_a">保存</div>--}}
                    <div class="choose_paper_pop_center_btn center_btn_b" ms-if="isEdittip"  ms-click="delTheTip(nowtipobj,tipnum)">删除</div>
                    <div class="choose_paper_pop_center_btn center_btn_b" ms-if="!isEdittip" ms-click="cancelAddTip()">取消</div>
                    <div class="choose_paper_pop_center_btn center_btn_b" ms-click="addtip(nowtipobj,tipnum)">确认</div>
                </div>
            </div>
        </div>

        <div class="content_right_papercourse hide" >
            <div class="addPaper" ms-controller="addPaper">

                <div id="fileDiv" class="fileButton"></div>
                <input type="text" value="" class="fileButton" id="md5container">

                <div class="addPaper_main" ms-if="!importId && !showImg">

                    <div class="addPaper_main_title">
                        <h1 ms-class="addPaper_main_titleActive: quesStyle === 1" ms-click="changeModel('quesStyle', 1)">作业样式</h1>
                        <h1 ms-class="addPaper_main_titleActive: quesStyle === 2" ms-click="changeModel('quesStyle', 2)">测验样式</h1>
                    </div>

                    <div class="addPaper_main_choice">
                        <h2 ms-click="changeModel('addQues', 1)">单选题</h2>
                        <h2 ms-click="changeModel('addQues', 2)">多选题</h2>
                        <h2 ms-click="changeModel('addQues', 3)">判断题</h2>
                        <h2 ms-click="changeModel('addQues', 4)">填空题</h2>
                        <h2 ms-click="changeModel('addQues', 5)">解答题</h2>
                        <div ms-click="changeModel('importQues', 1)">引入试题</div>
                    </div>

                    <h1 class="addPaper_main_paperTitle" ms-html='title'></h1>

                    <div class="addPaper_main_ques" ms-class="editing: editing === el.index" ms-repeat='taskQues' ms-mouseover="changeModel('editShow', el.index)" ms-mouseout="changeModel('editShow')" ms-attr-id="'testQues' + el.index">

                        <p class="paper_type" ms-if="quesStyle === 2 && el.type === 1" ms-visible="paperType[el.type] === el.index" ms-papertype="[1, el.index]">单选题</p>
                        <p class="paper_type" ms-if="quesStyle === 2 && el.type === 2" ms-visible="paperType[el.type] === el.index" ms-papertype="[2, el.index]">多选题</p>
                        <p class="paper_type" ms-if="quesStyle === 2 && el.type === 3" ms-visible="paperType[el.type] === el.index" ms-papertype="[3, el.index]">判断题</p>
                        <p class="paper_type" ms-if="quesStyle === 2 && el.type === 4" ms-visible="paperType[el.type] === el.index" ms-papertype="[4, el.index]">填空题</p>
                        <p class="paper_type" ms-if="quesStyle === 2 && el.type === 5" ms-visible="paperType[el.type] === el.index" ms-papertype="[5, el.index]">解答题</p>

                        <div class="addPaper_main_ques_choice" ms-if="editing !== el.index">
                            <span ms-visible="editShow === el.index && editing === undefined">
                                <div ms-hover='ques_choice_hover' ms-click="changeModel('replace', el.type, $index)">替换</div>
                                <div ms-hover='ques_choice_hover' ms-click="moveQues($index, 'down')">↓下移</div>
                                <div ms-hover='ques_choice_hover' ms-click="moveQues($index, 'up')">↑上移</div>
                                <div ms-hover='ques_choice_hover' ms-click="changeModel('removeQues', $index)">删除</div>
                                <div ms-hover='ques_choice_hover' ms-click="changeModel('editing', el.index)">编辑</div>
                            </span>
                        </div>

                        <div class="addPaper_main_ques_content" ms-class="ques_content_active: editShow === el.index && editing === undefined">

                            <div class='single_question' ms-click="changeModel('showAnswer', el.index)" ms-visible="editing === undefined || editing !== el.index">
                                <div class="single_title">
                                    <div class='single_title_type'>
                                        <span ms-html="el.index + '.'"></span>
                                        <span ms-css-background="'#1F9EEA'" ms-if="el.type === 1" ms-html="'单选'"></span>
                                        <span ms-css-background="'#85CCC8'" ms-if="el.type === 2" ms-html="'多选'"></span>
                                        <span ms-css-background="'#FC7E8B'" ms-if="el.type === 3" ms-html="'判断'"></span>
                                        <span ms-css-background="'#c477d5'" ms-if="el.type === 4" ms-html="'填空'"></span>
                                        <span ms-css-background="'#F7BC5D'" ms-if="el.type === 5" ms-html="'解答'"></span>
                                    </div>
                                    <div class='single_title_content' ms-html='el.title'></div>
                                    <div class='single_title_number' ms-html="'( ' + el.score + '分 )'"></div>
                                    <div class="clear"></div>
                                </div>

                                <div class="single_choice" ms-if="el.type < 3">
                                    <p ms-repeat-choice="el.choice">
                                        <span ms-questionindex="$index"></span>
                                        <span ms-html="choice"></span>
                                    </p>
                                </div>

                                <div style="height: 15px; clear: both;"></div>

                                <div class="single_answer" ms-visible="showAnswer === el.index">
                                    <p>
                                        <span class="analysisBlock">答案</span>
                                        <span ms-if="el.type !== 2 && el.type !== 4" ms-html="el.type === 3 ? (el.answer ? '正确' : '错误') : el.answer"></span>
                                        <span ms-if="el.type === 2 || el.type === 4" ms-fillmutlanswer="[el.answer, 1]"></span>
                                    </p>

                                    <div style="clear: both; height: 1px;"></div>

                                    <p>
                                        <span class="analysisBlock">解析</span>
                                        <span ms-html='el.analysis || "无"'></span>
                                    </p>
                                </div>

                                <div style="clear: both; height: 1px;"></div>
                            </div>

                            <div class="single_question_edit" ms-visible="editing === el.index">
                                <div class="single_title_edit" ms-attr-id="'#titleEdit' + $index">
                                    <p ms-html="el.index + '. 题干'"></p>

                                    <div ms-attr-id="'title' + $index"
                                         class="editable"
                                         contenteditable="true"
                                         ms-if="el.type !== 6"
                                         ms-html="el.title"
                                         ms-editable="[$index, 'title']"
                                         ms-selection="el.type"
                                         ms-click="changeModel('editWindow', 'title')">
                                    </div>

                                    <p>难易度 <span ms-questiondifficult="$index"></span></p>

                                    <div class="edit_window window_title" ms-class="window_title_juge: el.type === 4" ms-if="window === 'title'">
                                        <a ms-attr-href="'#titleEdit' + $index" class="edit_window_span" ms-exec="[1, $index, 'title']" ms-if="el.type !== 4">B</a>
                                        <a ms-attr-href="'#titleEdit' + $index" class="edit_window_span" ms-exec="[2, $index, 'title']" ms-if="el.type !== 4">I</a>
                                        <a ms-attr-href="'#titleEdit' + $index" class="edit_window_span" ms-exec="[3, $index, 'title']" ms-if="el.type !== 4">U</a>
                                        <a class="edit_window_span" ms-selectfile="[$index, 'title']">
                                            <img src="/home/image/evaluateManageTea/editPaper/edit_img.png">
                                        </a>
                                        <a class="edit_window_span fill_window_span" ms-fill="[$index, 'answer']" ms-if="el.type === 4">
                                            <img src="/home/image/evaluateManageTea/editPaper/test.png">
                                        </a>
                                        <a class="edit_window_span " ms-click="changeModel('reset', $index)" ms-if="el.type === 4">
                                            <img src="/home/image/evaluateManageTea/editPaper/reset.png">
                                        </a>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div ms-if="el.choice" class="single_choice_edit" ms-repeat-cho="el.choice" ms-attr-id="'#choiceEdit' + $index">
                                    <p ms-questionindex="$index"></p>

                                    <div ms-attr-id="'choice' + $outer.$index + '-' + $index"
                                         class="editable"
                                         contenteditable="true"
                                         ms-html="cho"
                                         ms-editable="[$outer.$index + '-' + $index, 'choice']"
                                         ms-click="changeModel('editWindow', $outer.$index + '-' + $index)">
                                    </div>

                                    <p ms-if="el.type === 1" ms-singlechoice="[el.index, $index]"></p>
                                    <p ms-if="el.type === 2" class="multi_choice" ms-multichoice="[el.index, $index]"></p>

                                    <p ms-if="$index > 1" ms-click="changeModel('removeChoice', $index, el.index)">×</p>


                                    <div class="edit_window window_choice" ms-if="window === $outer.$index + '-' + $index">
                                        <a ms-attr-href="'#choiceEdit' + $index" class="edit_window_span" ms-exec="[1, $outer.$index + '-' + $index, 'choice']">B</a>
                                        <a ms-attr-href="'#choiceEdit' + $index" class="edit_window_span" ms-exec="[2, $outer.$index + '-' + $index, 'choice']">I</a>
                                        <a ms-attr-href="'#choiceEdit' + $index" class="edit_window_span" ms-exec="[3, $outer.$index + '-' + $index, 'choice']">U</a>
                                        <a class="edit_window_span" ms-selectfile="[$outer.$index + '-' + $index, 'choice']">
                                            <img src="/home/image/evaluateManageTea/editPaper/edit_img.png">
                                        </a>
                                    </div>

                                    <div style="clear: both;"></div>
                                </div>

                                <div class="add_single_question" ms-if="el.choice">
                                    <p ms-click="changeModel('addChoice', $index)">+ 添加选项</p>
                                </div>

                                <div class="judge_choice_edit" ms-if="el.type === 3">
                                    <p>正确</p>
                                    <p ms-click="changeModel('judge', $index, 1)"><span ms-if="el.answer === 1"></span></p>
                                    <p>错误</p>
                                    <p ms-click="changeModel('judge', $index, 0)"><span ms-if="el.answer === 0"></span></p>
                                </div>

                                <div style="clear: both; height: 30px;" ms-if="el.type > 3"></div>

                                <div class="single_number_edit" ms-if="el.type !== 4">
                                    <p>分数</p>
                                    <input type="text" ms-duplex-number="el.score">
                                    <p>分</p>
                                </div>

                                <div class="single_number_edit" ms-if="el.type === 4">
                                    <p>每题</p>
                                    <input type="text" ms-fillscore="$index">
                                    <p>分</p>
                                    <p>总分数 <span ms-text="el.score"></span></p>
                                </div>

                                <div class="single_number_edit" ms-if="el.type !== 5">
                                    <p>答案</p>
                                    <input type="text" ms-if="el.type === 1 || el.type === 3" ms-attr-value="el.type === 3 ? (el.answer ? '正确' : '错误') : el.answer" readonly ms-css-width="'200px'">
                                    <input type="text" ms-if="el.type === 4" ms-fillanswer="[el.answer, $index]" readonly ms-css-width="'510px'">
                                    <input type="text" ms-if="el.type === 2" ms-fillmutlanswer="[el.answer, 0]" readonly ms-css-width="'510px'">
                                </div>

                                <div class="single_explain_edit" ms-if="el.type === 5" ms-attr-id="'#answerEdit' + $index">
                                    <p>答案</p>
                                    <div ms-attr-id="'answer' + $index"
                                         class="editable"
                                         contenteditable="true"
                                         ms-html="el.answer"
                                         ms-editable="[$index, 'answer']"
                                         ms-click="changeModel('editWindow', 'answer')">
                                    </div>

                                    <div class="edit_window window_answer"  ms-if="window === 'answer'">
                                        <a ms-attr-href="'#answerEdit' + $index" class="edit_window_span" ms-exec="[1, $index, 'answer']">B</a>
                                        <a ms-attr-href="'#answerEdit' + $index" class="edit_window_span" ms-exec="[2, $index, 'answer']">I</a>
                                        <a ms-attr-href="'#answerEdit' + $index" class="edit_window_span" ms-exec="[3, $index, 'answer']">U</a>
                                        <a class="edit_window_span" ms-selectfile="[$index, 'answer']">
                                            <img src="/home/image/evaluateManageTea/editPaper/edit_img.png">
                                        </a>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div class="single_explain_edit" ms-attr-id="'#analysisEdit' + $index">
                                    <p>解析</p>
                                    <div placeholder="(选填)"
                                         ms-attr-id="'analysis' + $index"
                                         class="editable"
                                         contenteditable="true"
                                         ms-html="el.analysis"
                                         ms-editable="[$index, 'analysis']"
                                         ms-click="changeModel('editWindow', 'analysis')">
                                    </div>

                                    <div class="edit_window window_explain" ms-if="window === 'analysis'">
                                        <a ms-attr-href="'#analysisEdit' + $index" class="edit_window_span" ms-exec="[1, $index, 'analysis']">B</a>
                                        <a ms-attr-href="'#analysisEdit' + $index" class="edit_window_span" ms-exec="[2, $index, 'analysis']">I</a>
                                        <a ms-attr-href="'#analysisEdit' + $index" class="edit_window_span" ms-exec="[3, $index, 'analysis']">U</a>
                                        <a class="edit_window_span" ms-selectfile="[$index, 'analysis']">
                                            <img src="/home/image/evaluateManageTea/editPaper/edit_img.png">
                                        </a>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div class="single_edit_complete">
                                    <button ms-click="changeModel('editing', 'cancel', $index)">取消</button>
                                    <button ms-click="changeModel('editing', 'complete', $index)">完成</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div name='addQues' id='addQues' style="height: 30px;"></div>

                    <div class="addPaper_main_publish" ms-visible="taskQues.size() > 0 && !importing && !editing">
                        <button id="testButton" ms-html="'发布'" ms-click="publish"></button>
                    </div>

                    <div class="import_ques hide" ms-class="hide: !importWindow">
                        <div class="import_ques_content" ms-if="importWindow < 3">
                            <p class="import_ques_content_title">　　　替换试题<span class="import_ques_content_close" ms-click="changeModel('importQues', null)">×</span></p>

                            <div style="overflow: auto; height: 360px;">

                                <div class="import_ques_content_condition">
                                    <div class="import_ques_content_condition_difficult" style="margin-right: 20px;">
                                        难易度
                                        <select ms-duplex-number="importDifficult" ms-attr-disabled="importing">
                                            <option ms-attr-value="3">困难</option>
                                            <option ms-attr-value="2">一般</option>
                                            <option ms-attr-value="1">简单</option>
                                        </select>
                                    </div>
                                    <div class="import_ques_content_condition_difficult" ms-visible="importWindow === 1">
                                        题型
                                        <select  ms-duplex-number="importType" ms-attr-disabled="importing">
                                            <option ms-attr-value="1">单选题</option>
                                            <option ms-attr-value="2">多选题</option>
                                            <option ms-attr-value="3">判断题</option>
                                            <option ms-attr-value="4">填空题</option>
                                            <option ms-attr-value="5">解答题</option>
                                        </select>
                                    </div>
                                    <div class="import_ques_content_condition_confirm" ms-attr-disabled="importing" ms-click="changeModel('replaceQues')">确认替换</div>
                                </div>

                                <div class="import_ques_content_page" ms-importpage="importQuesCount"></div>

                                <div class="import_ques_content_ques" ms-visible="!importing && importQues">
                                    <div class="description">
                                        <p ms-html="'题号：' + importQues.id"></p>
                                        <p ms-questiontype="importQues.type"></p>
                                        <p ms-html="'日期：' + importQues.created_at"></p>
                                        <p ms-html="'难易度：' + ((importQues.difficult === 1) ? '简单' : ((importQues.difficult === 2) ? '一般' : '困难'))"></p>
                                    </div>

                                    <div class="title" ms-html="importQues.title"></div>

                                    <div class="choice" ms-visible="importQues.type < 3" ms-repeat="importQues.choice">
                                        <p>
                                            <span ms-questionindex="$index"></span>
                                            <span ms-html="el"></span>
                                        </p>
                                    </div>

                                    <div class="split"></div>

                                    <div class="answer">
                                        <p>
                                            <span class="analysisBlock">答案</span>
                                            <span ms-html="((typeof importQues.answer === 'number') ? (importQues.answer === 1 ? '正确' : '错误') : importQues.answer)"></span>
                                        </p>
                                    </div>

                                    <div class="answer">
                                        <p>
                                            <span class="analysisBlock">解析</span>
                                            <span ms-html="importQues.analysis"></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="spinner" ms-if="importing">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>

                                <p class="import_ques_content_tip" ms-if="!importing && !importQues">暂无数据</p>

                            </div>
                        </div>

                        <div class="import_ques_content publish_window" ms-if="importWindow === 3">

                            <p class="import_ques_content_title" id="paperTitle">　　　发布试卷<span class="import_ques_content_close" ms-click="changeModel('importQues', null)">×</span></p>

                            <div style="clear: both; height: 20px;"></div>

                            <div ms-if="!importing">
                                <div class="publish_block">
                                    <span class="title" ms-if="!paperTitileWanring">试卷标题</span>
                                    <span class="title" ms-if="paperTitileWanring" style="color: red;">请输入试卷标题</span>
                                    <div>
                                        <input type="text" ms-duplex="title">
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div class="publish_block">
                                    <span class="title">试卷内容</span>
                                    <div>
                                        <p ms-if="typeCount['1'].count > 0"><span ms-html="'单选题' + typeCount['1'].count + '道'"></span> <span ms-html="'共' + typeCount['1'].score + '分'"></span></p>
                                        <p ms-if="typeCount['2'].count > 0"><span ms-html="'多选题' + typeCount['2'].count + '道'"></span> <span ms-html="'共' + typeCount['2'].score + '分'"></span></p>
                                        <p ms-if="typeCount['3'].count > 0"><span ms-html="'判断题' + typeCount['3'].count + '道'"></span> <span ms-html="'共' + typeCount['3'].score + '分'"></span></p>
                                        <p ms-if="typeCount['4'].count > 0"><span ms-html="'填空题' + typeCount['4'].count + '道'"></span> <span ms-html="'共' + typeCount['4'].score + '分'"></span></p>
                                        <p ms-if="typeCount['5'].count > 0"><span ms-html="'解答题' + typeCount['5'].count + '道'"></span> <span ms-html="'共' + typeCount['5'].score + '分'"></span></p>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div class="publish_block">
                                    <span class="title">总分值</span>
                                    <div ms-html="scoreCount + '分'"></div>
                                </div>

                                <div style="clear: both;"></div>

                                <div class="publish_block">
                                    <span class="title">提交时限</span>
                                    <div>
                                        <input type="text" id="pickdate" ms-duplex="pickdate" style="width: 100px;">
                                        <input type="text" id="picktime" ms-duplex="picktime" style="width: 80px;">
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div class="publish_block" ms-if="quesStyle === 2">
                                    <span class="title">完成时间</span>
                                    <div>
                                        <select ms-duplex-number="completeTime">
                                            <option ms-attr-value="20">20分钟</option>
                                            <option ms-attr-value="30">30分钟</option>
                                            <option ms-attr-value="40">40分钟</option>
                                            <option ms-attr-value="50">50分钟</option>
                                            <option ms-attr-value="60">60分钟</option>
                                        </select>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div class="publish_submit" ms-if="!sumitFail" ms-click="submitPaper">确认提交</div>
                                <div class="publish_submit" ms-if="sumitFail" style="background: white; color: red; width: 300px;">试卷发布失败，请重试</div>
                            </div>

                            <div class="spinner" ms-if="importing" style="margin: 150px auto;">
                                <div class="rect1"></div>
                                <div class="rect2"></div>
                                <div class="rect3"></div>
                                <div class="rect4"></div>
                                <div class="rect5"></div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="imgZoom hide" ms-class="hide: !showImg">
                    <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
                    <img ms-attr-src="imgZoom">
                </div>

                <div class="spinner hide" ms-class="hide: !importId">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
            </div>
        </div>


        <div style="clear: both"></div>

    </div>
    <div style="height:215px"></div>
    <!-- 遮罩层 -->
    <div class="hide" id="shadow" ms-popup="popUp" value="close"></div>
    <!--资源转码中提示弹窗-->
    <div class="warning_resource hide" ms-popup="popUp" value="resourceMessage">
        <div class="top_title">
            <div>温馨提示</div>
            <span ms-click="popUpSwitch(false)"></span>
        </div>
        <div class="middle_content" ms-html="'课程格式转换中,暂不可查看,请稍后刷新页面重试。'"></div>
        <div class="bot_button">
            <span class="sure" ms-html="'知道了'" ms-click="popUpSwitch(false)"></span>
        </div>
    </div>
    </div>

@endsection

@section('selectjs')
    <script type="text/javascript" src="{{asset('home/js/users/select2.min.js') }}"></script>
    <script>
        $('.chapter').select2(
                {minimumResultsForSearch:Infinity}
        )
    </script>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('admin/ueditor/ueditor.config3.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin/ueditor/ueditor.all.js') }}"></script>
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/jquery.uploadify.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/teacherCourse/list.js')}}"></script>
	<script type="text/javascript" src="{{asset('/calendar/datedropper.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('/calendar/timedropper.min.js')}}"></script>
    <script>
//        var ue = UE.getEditor('container',{
//            initialFrameHeight:260,
//        });
    </script>
    <script>
        require(['/teacherCourse/list', '/teacherCourse/create', '/teacherCourse/editPaper'], function (modela, modelb, editPaper) {
            modelb.randomPic();
            modelb.createUeditor('<p style="white-space: normal;">【教学目标】</p><p style="white-space: normal;"><br/></p><p style="white-space: normal;">【教学重点】</p><p style="white-space: normal;"><br/></p><p style="white-space: normal;">【教学难点】</p><p style="white-space: normal;"><br/></p><p style="white-space: normal;">【教学过程】</p><p><br/></p>');
            modelb.getType('subject',1);
            if(window.location.hash){
                modela.tab = window.location.hash.split('#')[1];

                modela.tabs(modela.tab);
            }
            if(modela.currentIndex != 'shenhe') modela.getDataMy(0);

            if(modela.currentIndex == 'my'){
                $('.mycourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(modela.currentIndex == 'rec'){
                $('.reccourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(modela.currentIndex == 'all'){
                $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(modela.currentIndex == 'new'){
                $('.newcourse').addClass('span_active').siblings().removeClass('span_active')
//                location.reload()
                window.location.href = '/teacherCourse/list#new';
            }

            modela.tabs = function(index){
                 window.location.hash = index;
                 modela.currentIndex = index;
                 if(modela.currentIndex == 'my'){
                     modelb.reloadData();
                     $('.mycourse').addClass('span_active').siblings().removeClass('span_active')
                 }else if(modela.currentIndex == 'rec'){
                     $('.reccourse').addClass('span_active').siblings().removeClass('span_active')
                 }else if(modela.currentIndex == 'all'){
                     $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
                 }else if(modela.currentIndex == 'new'){
                     window.location.href = '/teacherCourse/list#new/1';
                 }
            };

            modela.tabsb = function (index){
                window.location.hash = index;
                modela.currentIndex = index;
                modelb.reloadData();
                modelb.randomPic();

                $('.newcourse').addClass('span_active').siblings().removeClass('span_active');
            };

            avalon.directive('popup', {
                update: function(value) {
                    var element = this.element, popUpType = avalon(element).attr('value');
                    if (!value) {
                        avalon(element).css('display', 'none');
                        return;
                    }
                    if (value == popUpType || popUpType == 'close') {
                        avalon(element).css('display', 'block');
                    } else {
                        avalon(element).css('display', 'none');
                    }
                }
            });

            avalon.directive("addcolor", {
                init: function (binding) {
                    var elem = binding.element;
                    avalon(elem).bind("click",function () {
                        var key = $(this).attr('key');
                        var value = $(this).attr('vid');
                        if($(this).hasClass('hot_new_blue')){
                            if(modela[key].length <= 1) return false;
                            $(this).removeClass('hot_new_blue');
                            modela.delNum(key, value);
                        }else{
                            $(this).addClass('hot_new_blue');
                            $(this).siblings().first('children').removeClass('hot_new_blue');
                            modela.addNum(key, value);
                        }
                    })
                }
            });

            //上传视频  -----  选择文件
            avalon.directive("slectfile", {
                init: function (binding) {
                    var elem = binding.element
                    avalon(elem).bind("click",function () {
                        //$(this).parent().next(); //当前inputfile
                        var type = $(this).attr('type');
                        //console.log(type);
                        //return false;
                        var chapterIndex = $(this).attr('chapter');  //当前章索引
                        var nodeIndex = $(this).attr('node');        //当前节索引
                        var file;                                    //文件对象
                        $(this).parent().next().html('<input class="fileresource" name="fileresource" type="file" class="files" multiple style="display: none">');
                        var inputf = $(this).parent().next().find('.fileresource');        //input 选择文件框
                        inputf.bind('change',function(e){
                            file = inputf[0].files;                  //获取文件对象
                            //清空文件域
                            inputf.after(inputf.clone().val(""));
                            inputf.remove();
                            //document.getElementById("ziliaosource").outerHTML = document.getElementById("ziliaosource").outerHTML; //清空input存储文件对象

                            //console.log(file);

                            var filelength = file.length;
                            if(type == 1) var typearr = modelb.postInfo.prelearnInfo[chapterIndex].dataInfo
                            if(type == 2) var typearr = modelb.postInfo.chaprerInfo[chapterIndex].nodeInfo[nodeIndex].dataInfo
                            if(type == 3) var typearr = modelb.postInfo.guideInfo[chapterIndex].dataInfo
                            var perarrlength = typearr.length
                            if(filelength + perarrlength > 3){ alert('资料数不可超过三个');return false }
                            for(var i=0;i<filelength;i++){//格式验证
                                var suffix = file[i].name.substring(file[i].name.lastIndexOf('.') + 1);
                                if(!suffix.match(/(xls|xlsx|doc|docx|pdf|ppt|mov|mp4|flv|avi|rmvb|wmv|mkv|swf)/i)){
                                    alert('文件格式错误');return false
                                }
                                if(!modelb.countsize(file[i].name,file[i].size)){
                                    alert(file[i].name+'文件大小超过1G');return false;
                                }
                            }

                            modelb.uploadziliao(file,filelength,chapterIndex,nodeIndex,typearr); //执行上传

                        });

                        inputf.click();
                    })
                }
            });

            avalon.directive("hide", {
                init: function (binding) {
                    avalon(binding.element).bind("click",function () {
                        if($(this).html() == '收起'){ $(this).html('展开');$(this).parent().parent().next().addClass('hide') }
                        else{ $(this).html('收起');$(this).parent().parent().next().removeClass('hide') }
                    })
                }
            });

            //创建练习
            modelb.createPaper = function(type){
                $('.shadow').hide();
                $('.choose_paper_pop').hide();
                $('.content_right_newcourse').hide();
                $('.content_right_papercourse').show();
                editPaper.lessonInfo = modelb.postInfo.baseInfo.gradeId+'-'+modelb.postInfo.baseInfo.subjectId+'-'+modelb.postInfo.baseInfo.editionId+'-'+modelb.postInfo.baseInfo.bookId+'-'+modelb.postInfo.baseInfo.chapterId;
                editPaper.init(type);
            };

            //选择练习
            modelb.selpaper = function(type, id, title){
                $('.shadow').hide();
                $('.choose_paper_pop').hide();
                $('.content_right_newcourse').hide();
                $('.content_right_papercourse').show();
                editPaper.importId = id;
                editPaper.lessonInfo = modelb.postInfo.baseInfo.gradeId+'-'+modelb.postInfo.baseInfo.subjectId+'-'+modelb.postInfo.baseInfo.editionId+'-'+modelb.postInfo.baseInfo.bookId+'-'+modelb.postInfo.baseInfo.chapterId;
                editPaper.init(type);
            };

            modela.get1();
            modela.get2();
            modela.get3();
            modela.get4();

            editPaper.userId = {{\Auth::user() -> id}} || null;

            //编辑课程
            modela.editCourse = function(courseId){
                //alert(courseId)
                modelb.isEdit = true;
                modelb.courseId = courseId;
                modelb.geteditCourseInfo(1,courseId);


                $('.content_right_mycourse').hide()
                $('.content_right_newcourse').show();
            }
            //日期过滤器
            avalon.filters.sliceTime = function(str,type){
                return type == 'year' ? str.slice(0,10) : str.slice(11,19);
            }

            $('#pickdate').dateDropper({
                animate: false,
                format: 'Y-m-d',
                maxYear: new Date().getFullYear(),
                minYear: new Date().getFullYear()
            });

            $("#picktime").timeDropper({
                meridians: false,
                format: 'HH:mm'
            });

            avalon.scan();
        });
    </script>

@endsection