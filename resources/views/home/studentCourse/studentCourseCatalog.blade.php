@extends('layouts.layoutHome')

@section('title', '课程中心')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/studentCourse/catalog.css') }}">
@endsection

@section('content')
    <div style="height:10px"></div>
    <div class="crumbs">
        <div class="crumbs_title">
            <a href="/studentCourse/stuDetail/{{$data->id}}#list"><div class="crumbs_course">课程主页</div></a>
            <div class="crumbs_course">></div>
            <a href=""><div class="crumbs_course">课程详情</div></a>
        </div>
    </div>
    <div style="clear: both"></div>


    {{--主体内容--}}
    <div class="main" ms-controller="studentCourseCatalog" >
        {{--ms-repeat="courseVideoData"--}}
        {{--ms-if="el.countChapter == 1"--}}
        {{--左部分--}}
        <div class="main_left" >
            <div class="main_left_top_select " ms-repeat-el="autoCourseVideoData">
                {{--视频--文档选项  (1种情况) --}}
                <div class="main_left_top hide " ms-if="el.countChapter==1" ms-class="hide:el.countChapter!=1">
                    <div class="top_video1 " ms-repeat-item="el.chapter">
                        {{--mp4、flv、avi、rmvb、wmv、mkv--}}
                        <div class="top_document_img"  ms-if="item.icontype == 0"  ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/shipin.png')}}" alt="" width="25px" height="25px" >
                        </div>
                        <div class="top_document_img"  ms-if="item.icontype == 1" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/wenjian.png')}}" alt="" width="25px" height="25px" >
                        </div>
                    </div>
                </div>
                {{--视频--文档选项  (2种情况) --}}
                <div class="main_left_top hide " ms-if="el.countChapter==2" ms-class="hide:el.countChapter!=3">
                    <div class="top_video2 " ms-repeat-item="el.chapter">
                        {{--<a ms-attr-href="item.id">--}}
                        <div class="top_document_img" ms-if="item.icontype == 0" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/shipin.png')}}" alt="" width="25px" height="25px" >
                        </div>
                        {{--</a>--}}

                        <div class="top_document_img" ms-if="item.icontype == 1" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/wenjian.png')}}" alt="" width="25px" height="25px" >
                        </div>

                    </div>
                </div>
                {{--视频--文档选项  (3种情况) --}}
                <div class="main_left_top hide" ms-if="el.countChapter==3" ms-class="hide:el.countChapter!=3">
                    <div class="top_video3 " ms-repeat-item="el.chapter">
                        <div class="top_document_img" ms-if="item.icontype == 0" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/shipin.png')}}" alt="" width="25px" height="25px" >
                        </div>

                        <div class="top_document_img" ms-if="item.icontype == 1" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/wenjian.png')}}" alt="" width="25px" height="25px" >
                        </div>
                    </div>
                </div>
            </div>


            <div style="clear: both;"></div>
            <div class="video_document" >
                {{--视频--}}
                <div class="video_browse none"  ms-class="none: !showVideo " ms-if="showVideo">
                    <div id="mediaplayer"  alt="" width="800px" height="450px"></div>

                    <div class="video_msg_con hide" ms-class="hide:!showtipcon">
                        <div style="height:115px;"></div>
                        <div class="video_msg">
                            <div class="video_msg_top">
                                知识点贴士
                                <div>

                                </div>
                            </div>
                            <div style="height:20px;"></div>
                            <div class="video_msg_msg" ms-html="tipcon">贴士是英语tip的音译词，用作名词是指供参考的资料</div>
                            <div style="height:15px;"></div>
                            <div class="video_msg_btn" ms-click="goOnStudy()">继续学习</div>
                            <div class="video_msg_seconde" ms-html=" tipsecond + 's' ">18s</div>
                        </div>
                    </div>


                </div>
                {{--文档--}}
                <div class="document_browse none"  ms-class="none: !pdfShow " ms-if="pdfShow">
                    <iframe ms-if="!showVideo" ms-attr-src="info.coursePath" width="100%" height="100%" ></iframe>
                </div>
                <div id="onlinePlay" ms-if="isOnline" ms-html="onlineifram"></div>
            </div>


        </div>



        {{--右部分--}}
        <div class="main_right">
            {{--目录 笔记 问答--}}
            <div class="main_right_top">
                <div class="right_top_catalog " ms-click="tabs('catalog')">
                    <div class="catalog_img ">

                    </div>
                    <div class="catalog_name ">目录</div>
                </div>
                <div class="right_top_note" ms-click="tabs('note')">
                    <div class="note_img">

                    </div>
                    <div class="note_name">笔记</div>
                </div>
                <div class="right_top_answers" ms-click="tabs('answers')">
                    <div class="answer_img">

                    </div>
                    <div class="answer_name">问答</div>
                </div>
            </div>

            <div style="clear: both"></div>


            {{--目录内容--}}
            <div class="catalog_content " ms-visible="currentIndex=='catalog'" >
                <div style="height:10px"></div>
                <div class="catalog_content_every" ms-if="CourseChapter.duidance.size()">
                    {{--标题--}}
                    <div class="catalog_content_every_title">
                        <div class="every_number none"  ms-class="none:!CourseChapter.duidance.size()"   ms-html="'1'"></div>
                        <div class="every_title" ms-html="'课前导学'"></div>
                    </div>
                    <div style="clear: both"></div>

                    {{--链接标题--}}
                    <div ms-repeat-el="CourseChapter.duidance">
                        <div class="bianse">
                            <div class="circle_schedule none" ms-class="none:!el.selectImage">
                                <img ms-attr-src="el.selectImage" alt="" width="14px" height="14px">
                            </div>
                            <div class="catalog_content_every_name" ms-class="gray : el.id == chapterId-1">
                                <div class="every_name_sum" >
                                    <div class="subscript" ms-html="'1.' +( $index + 1)"></div>
                                    <div class="every_name_sum_name"  ms-html="el.title"    ms-click="chapterData(el.id)">
                                    </div>
                                    <div style="width: 24px;height: 24px;float: right"></div>
                                    <div class="every_name_sum_right" ms-repeat-item="el.chapter">
                                        <a ms-attr-href="item.id"><div class="every_name_sum_shipin none"  ms-class="none:item.icontype != 0"  ms-if="item.icontype == 0"></div></a>
                                        <a ms-attr-href="item.id"><div class="every_name_sum_wenjian none" ms-class="none:item.icontype != 1" ms-if="item.icontype == 1"></div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="height:10px;" ms-if="el.courseType == 0"></div>
                    </div>
                </div>

                <div style="height:10px"></div>
                <div class="catalog_content_every" ms-if="CourseChapter.teaching.size()">
                    {{--标题--}}
                    <div class="catalog_content_every_title">
                        <div class="every_number none" ms-class="none:!CourseChapter.teaching.size()" ms-html="'2'"></div>
                        <div class="every_title" ms-html="'课堂授课'"></div>
                    </div>
                    <div style="clear: both"></div>
                    {{--链接标题--}}
                    <div ms-repeat-el="CourseChapter.teaching">
                        <div class="bianse">

                            <div class="catalog_content_every_name" >
                                <div class="every_name_sum" >
                                    <div class="subscript" ms-html="'2.' +( $index + 1)"></div>
                                    <div class="every_name_sum_name"  ms-html="el.title" >
                                    </div>
                                    <div style="width: 24px;height: 24px;float: right"></div>
                                    <div class="every_name_sum_right" ms-repeat-item="el.chapter">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div style="height:10px;" ms-if="el.courseType == 1"></div>--}}
                        <div style="height:10px;"></div>
                        {{--小节--}}
                        <div ms-repeat-item="el.chapter" ms-if="el.courseType == 1">
                            <div class="bianse">
                                <div class="circle_schedule none" ms-class="none:!item.selectImageChapter ">
                                    <img ms-attr-src="item.selectImageChapter" alt="" width="14px" height="14px">
                                </div>
                                <div class="catalog_content_every_name" ms-class="gray : item.id == chapterId-1">
                                    <div class="every_name_sum" >
                                        <div class="subscript" ms-html="('2.' +( $outer.$index + 1)) + '.' + ($index + 1)"></div>
                                        <div class="every_name_sum_name"  ms-html="item.title" ms-click="chapterData(item.id)">
                                        </div>
                                        <div style="width: 24px;height: 24px;float: right"></div>
                                        {{--视频or文档--}}
                                        <div class="every_name_sum_right" ms-repeat-items="item.node">
                                            <a ms-attr-href="items.id"><div class="every_name_sum_shipin none" ms-class="none:items.icontype != 0" ms-if="items.icontype == 0" ></div></a>
                                            <a ms-attr-href="items.id"><div class="every_name_sum_wenjian none" ms-class="none:items.icontype != 1" ms-if="items.icontype == 1"></div></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="height:10px;"></div>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                </div>
                {{--课后指导--}}
                <div style="height:10px"></div>
                <div class="catalog_content_every" ms-if="CourseChapter.guidance.size()">
                    {{--标题--}}
                    <div class="catalog_content_every_title">
                        <div class="every_number none"  ms-class="none:!CourseChapter.guidance.size()"  ms-html="'3'"></div>
                        <div class="every_title" ms-html="'课后指导'"></div>
                    </div>
                    <div style="clear: both"></div>
                    {{--链接标题--}}
                    <div ms-repeat-el="CourseChapter.guidance">
                        <div class="bianse">
                            <div class="circle_schedule none" ms-class="none:!el.selectImage ">
                                <img ms-attr-src="el.selectImage" alt="" width="14px" height="14px">
                            </div>
                            <div class="catalog_content_every_name" ms-class="gray : el.id == chapterId-1" >
                                <div class="every_name_sum" >
                                    <div class="subscript" ms-html="'3.' +( $index + 1)"></div>
                                    <div class="every_name_sum_name"  ms-html=" el.title "  ms-click="chapterData(el.id)" >
                                    </div>
                                    <div style="width: 24px;height: 24px;float: right"></div>
                                    <div class="every_name_sum_right" ms-repeat-item="el.chapter">
                                        <a ms-attr-href="item.id"><div class="every_name_sum_shipin none" ms-class="none:item.icontype != 0" ms-if="item.icontype == 0"></div></a>
                                        <a ms-attr-href="item.id"><div class="every_name_sum_wenjian none" ms-class="none:item.icontype != 1" ms-if="item.icontype == 1"></div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="height:10px;" ms-if="el.courseType == 2"></div>
                    </div>
                </div>
            </div>



            {{--笔记内容--}}
            <div class="note_content none"  ms-visible="currentIndex=='note'">
                {{--我的笔记--共享笔记--}}
                <div class="note_sum">
                    <div class="my_note blue">
                        我的笔记
                    </div>
                    <div class="share_note">
                        共享笔记
                    </div>
                </div>

                <div class="my_note_content">
                    <div class="note_area">
                        <textarea name="" id="noteContent" ms-duplex="noteContent" ></textarea>
                    </div>
                    {{--私密  保存--}}
                    <div class="private_confirm">
                        <div class="private">
                            <div class="private_checkbox">
                                <input type="checkbox" class="checkbox">
                            </div>
                            <div class="private_name">
                                私密
                            </div>
                        </div>
                        <div class="confirm" ms-click="submitNote('{{$data->id}}')">保存</div>
                    </div>

                    {{--留言内容--}}
                    <div class="note_comment_content" ms-repeat="courseMyNote">
                        <div class="note_comment_content_sum" >
                            <div class="content_sum_img">
                                <img ms-attr-src="el.pic"  alt="" width="36px" height="36px" >
                            </div>
                            <div class="content_sum_name" ms-html="el.username"></div>

                            <div class="content_sum_time_note" ms-if="el.courseTypes == false"  ms-html="formTime(el.notetime)"></div>

                        </div>
                        <div style="height:10px"></div>
                        <div class="note_comment_content_detail" ms-html="el.notecontent"></div>
                        <div style="height:20px"></div>
                    </div>
                    <div style="height:30px"></div>
                    <div style="height:30px"></div>
                    <div class="wodebiji" ms-if="wodebiji">暂无笔记内容</div>
                    <a href="/studentCourse/stuDetail/{{$data->id}}#note">
                    <div class="check_more" ms-if="!wodebiji">
                            查看更多
                    </div>
                    </a>
                </div>


                {{--共享笔记--}}
                <div class="share_note_content none" >
                    {{--留言内容--}}
                    <div class="note_comment_content" ms-repeat="courseShareNote">
                        <div class="note_comment_content_sum">
                            <div class="content_sum_img">
                                <img ms-attr-src="el.pic"  alt="" width="36px" height="36px" >
                            </div>
                            <div class="content_sum_name" ms-html="el.username"></div>
                            <div class="content_sum_time_note" ms-if="el.courseTypes == false" ms-html="formTime(el.notetime)"></div>
                        </div>
                        <div class="note_comment_content_detail" ms-html="el.notecontent"></div>
                        <div style="height:20px"></div>
                    </div>
                    <div style="height:30px"></div>
                    <div style="height:90px"></div>
                    <div class="gongxiangbiji" ms-if="gongxiangbiji">暂无笔记内容</div>

                    <a href="/studentCourse/stuDetail/{{$data->id}}#note">
                        <div class="check_more" ms-if="!gongxiangbiji">
                            查看更多
                        </div>
                    </a>
                </div>


            </div>

            {{--问答内容--}}
            <div class="answer_content none" ms-visible="currentIndex == 'answers'">
                <div style="height:20px"></div>
                <div class="note_area">
                    <textarea name="" id="commentContent" ms-duplex="commentContent"></textarea>
                </div>

                <div class="private_confirm">
                    <div class="confirms" ms-click="postComment('{{$data->id}}')">保存</div>
                </div>
                {{--内容--}}
                <div class="question_answer" ms-repeat="courseAskData">
                    <div style="height:8px"></div>
                    <div class="note_comment_content">
                        <div class="note_comment_content_sum">
                            <div class="content_sum_img">
                                <img ms-attr-src="el.pic"  alt="" width="36px" height="36px" >
                            </div>
                            <div class="content_sum_name" ms-html="el.username"></div>
                            <div class="content_sum_time" ms-html="el.asktime | truncate(12,' ')"></div>
                        </div>
                        <div style="height:10px"></div>
                        <div class="answer_comment" ms-html="el.content">
                        </div>
                    </div>
                    <div style="height:8px"></div>
                    {{--回答--}}
                    <div class="note_comment_content" ms-if="el.teaId">
                        <div class="note_comment_content_sum">
                            <div class="content_sum_img">
                                <img ms-attr-src="el.teaPic" alt="" width="36px" height="36px" >
                            </div>
                            <div class="content_sum_name" ms-html="el.teaName"></div>
                            <div class="content_sum_answer" ms-html="'回答'" ></div>
                            <div class="content_sum_time" ms-html="el.anstime | truncate(12,' ')">2017-1-11</div>
                        </div>
                        <div style="height:10px"></div>
                        <div class="answer_comment" ms-html="el.answer">
                        </div>
                    </div>
                    <div style="width:300px;height:15px;border-bottom: 1px solid #ccc;margin-left: 30px"></div>
                </div>
                <div style="height:20px"></div>
                <div style="height:100px;"></div>
                <div class="wodewenda" ms-if="wodewenda">暂无问答内容</div>

                <a href="/studentCourse/stuDetail/{{$data->id}}#question"><div class="gengduo" ms-if="!wodewenda">查看更多</div></a>
            </div>


        </div>


    </div>



    <div style="clear: both"></div>
    <div style="height:160px">

    </div>





@endsection

@section('js')

    <script type="text/javascript" src="{{asset('home/js/studentCourse/catalog.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>


    <script>
        require(['/studentCourse/catalog'], function (model) {

            if(window.location.hash){
                model.tab = window.location.hash.split('#')[1];
                model.tabs(model.tab);
            }


            if(model.currentIndex == 'catalog'){
                $('.right_top_catalog').addClass('div_back').siblings().removeClass('div_back');$('.catalog_img').addClass('catalog_img_bai');$('.catalog_name').addClass('catalog_name_bai');
                $('.note_img').removeClass('note_img_bai');$('.note_name').removeClass('note_name_bai');
                $('.answer_img').removeClass('answer_img_bai');$('.answer_name').removeClass('answer_name_bai');
            }else if(model.currentIndex == 'note'){$('.right_top_note').addClass('div_back').siblings().removeClass('div_back');$('.note_img').addClass('note_img_bai');$('.note_name').addClass('note_name_bai');
                $('.catalog_img').removeClass('catalog_img_bai');$('.catalog_name').removeClass('catalog_name_bai');
                $('.answer_img').removeClass('answer_img_bai');$('.answer_name').removeClass('answer_name_bai');
            }else if(model.currentIndex == 'answers'){
                $('.right_top_answers').addClass('div_back').siblings().removeClass('div_back');$('.answer_img').addClass('answer_img_bai');$('.answer_name').addClass('answer_name_bai');
                $('.note_img').removeClass('note_img_bai');$('.note_name').removeClass('note_name_bai');
                $('.catalog_img').removeClass('catalog_img_bai');$('.catalog_name').removeClass('catalog_name_bai');
            }


            model.courseId = '{{$data->id}}' || null;
            //章节Id
            model.chapterId = '{{$chapterId}}' || null;


            //章节目录
            model.getCourseChapter(model.courseId)

            //问答接口
            model.getCourseAskData(model.courseId)
            //我的笔记
            model.getCourseMyNote(model.courseId)
            //共享笔记
            model.getCourseShareNote(model.courseId)

            model.getChapterData(model.chapterId)

            //默认播放
            model.getDefaultChapter(model.chapterId)

            //取贴士个数
//            model.getCountTipData(model.chapterId)

            avalon.scan();
        });
    </script>

@endsection





