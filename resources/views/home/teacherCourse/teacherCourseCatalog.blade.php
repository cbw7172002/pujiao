@extends('layouts.layoutHome')

@section('title', '课程中心')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/teacherCourse/catalog.css') }}">
@endsection

@section('content')
    <div style="height:10px"></div>
    <div class="crumbs">
        <div class="crumbs_title">
            <a href="/teacherCourse/teaDetail/{{$data->id}}#list"><div class="crumbs_course">课程主页</div></a>
            <div class="crumbs_course">></div>
            <a href=""><div class="crumbs_course">课程详情</div></a>
        </div>
    </div>
    <div style="clear: both"></div>




    {{--主体内容--}}
    <div class="main" ms-controller="teacherCourseCatalog">

        {{--ms-repeat="courseVideoData"--}}
        {{--ms-if="el.countChapter == 1"--}}
        {{--左部分--}}
        <div class="main_left" >
            <div class="main_left_top_select " ms-repeat-el="autoCourseVideoData">
                {{--视频--文档选项  (1种情况) --}}
                <div class="main_left_top hide" ms-if="el.countChapter==1" ms-class="hide:el.countChapter!=1">
                    <div class="top_video1 gray_bac"  ms-repeat-item="el.chapter">
                        <div class="top_document_img " ms-if="item.icontype == 0" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/shipin.png')}}" alt="" width="25px" height="25px" >
                        </div>
                        <div class="top_document_img" ms-if="item.icontype == 1" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/wenjian.png')}}" alt="" width="25px" height="25px" >
                        </div>
                    </div>
                </div>
                {{--视频--文档选项  (2种情况) --}}
                <div class="main_left_top hide" ms-if="el.countChapter==2" ms-class="hide:el.countChapter!=2">
                    <div class="top_video2 gray_bac"  ms-repeat-item="el.chapter">
                        {{--<a ms-attr-href="item.id">--}}
                        <div class="top_document_img " ms-if="item.icontype == 0" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/shipin.png')}}" alt="" width="25px" height="25px" >
                        </div>
                        {{--</a>--}}

                        {{--<a ms-attr-href="item.id">--}}
                        <div class="top_document_img " ms-if="item.icontype == 1" ms-click="showCourseVideo(item.id)">
                            <img src="{{asset('home/image/teacherCourse/detail/wenjian.png')}}" alt="" width="25px" height="25px" >
                        </div>
                        {{--</a>--}}

                    </div>
                </div>
                {{--视频--文档选项  (3种情况) --}}
                <div class="main_left_top hide" ms-if="el.countChapter==3" ms-class="hide:el.countChapter!=3">
                    <div class="top_video3 gray_bac"  ms-repeat-item="el.chapter">
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
            <div class="video_document">


                {{--视频--}}
                <div class="video_browse none" ms-class="none: !showVideo " ms-if="showVideo">
                    <div id="mediaplayer"  alt="" width="800px" height="450px"></div>
                </div>

                {{--知识点贴士弹出框--}}
                <div class="tips_popup none">
                    {{--顶端--}}
                    <div class="tips_popup_up">
                        温馨提示
                        <div class="guanbi">
                            <img src="{{asset('home/image/teacherCourse/detail/guanbi.png')}}" alt="" width="20px" height="20px" >
                        </div>
                    </div>
                    {{--内容--}}
                    <div class="tips_popup_down">
                        <div style="height:30px;"></div>
                        <div class="tips_popup_content">
                            <div class="textarea_div">
                                在视频课件中加入贴士内容，帮助学生实时巩固知识重点， 同时有助于准确有效掌握学生实际学习情况.
                            </div>
                        </div>
                        {{--知道了按钮--}}
                        <div class="tips_popup_button">
                            <div class="tips_popup_button_button">
                                知道了
                            </div>
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
                <div class="right_top_catalog" ms-click="tabs('catalog')">
                    <div class="catalog_img">

                    </div>
                    <div class="catalog_name">目录</div>
                </div>
                <div class="right_top_note" ms-click="tabs('note')">
                    <div class="note_img">

                    </div>
                    <div class="note_name">贴士</div>
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
                        <div class="every_title" ms-html="'课前导学'">
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    {{--链接标题--}}
                    <div ms-repeat-el="CourseChapter.duidance">
                        <div class="bianse">
                            <div class="catalog_content_every_name" ms-class="gray : (el.id == chapterId-1)">
                                <div class="every_name_sum" >
                                    <div class="subscript" ms-html="'1.' +( $index + 1)"></div>
                                    <div class="every_name_sum_name"  ms-html="el.title"    ms-click="chapterData(el.id,'{{$data->id}}')">
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
                        <div class="every_title" ms-html="'课堂授课'">
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    {{--链接标题--}}
                    <div ms-repeat-el="CourseChapter.teaching">
                        <div class="bianse">
                            <div class="catalog_content_every_name" >
                                <div class="every_name_sum" >
                                    <div class="subscript" ms-html="'2.' +( $index + 1)"></div>
                                    <div class="every_name_sum_name"   ms-html="el.title" >
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
                                <div class="catalog_content_every_name" ms-class="gray : (item.id == chapterId-1)">
                                    <div class="every_name_sum" >
                                        <div class="subscript" ms-html="('2.' +( $outer.$index + 1)) + '.' + ($index + 1)"></div>
                                        <div class="every_name_sum_name"  ms-html="item.title" ms-click="chapterData(item.id,'{{$data->id}}')">
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
                        <div class="every_title" ms-html="'课后指导'">
                        </div>
                    </div>
                    <div style="clear: both"></div>

                    {{--链接标题--}}
                    <div ms-repeat-el="CourseChapter.guidance">
                        <div class="bianse">
                            <div class="catalog_content_every_name" ms-class="gray : (el.id == chapterId-1)" >
                                <div class="every_name_sum" >
                                    <div class="subscript" ms-html="'3.' +( $index + 1)"></div>
                                    <div class="every_name_sum_name"  ms-html=" el.title "  ms-click="chapterData(el.id,'{{$data->id}}')" >

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



            {{--贴士--}}
            <div class="note_content none"   ms-visible="currentIndex=='note'">
                @if($teacherId == \Auth::user()->id)
                <div ms-if="documentOrVideo == 'video'">
                    <div style="height:20px;"></div>
                    <div class="note_area">
                        <textarea name="" id="promptContent" ms-duplex="promptContent" maxlength="50"></textarea>
                    </div>
                    <div class="my_note_content ">
                        {{--提示  保存--}}
                        <div class="private_confirm">

                            <div class="prompt" ms-click="popUpSwitch()">
                                什么是 "贴士" ?
                            </div>
                            <div class="confirm" ms-click="submitNote('{{$data->id}}')">保存</div>
                        </div>
                        <div style="height:15px;"></div>

                        <div class="min_height">
                            <div class="note_comment_content" ms-repeat="courseTips">
                                <div class="note_comment_content_sum" >
                                    <div class="content_sum_img_tieshi">
                                        <img src="{{asset('home/image/teacherCourse/detail/tieshi1.png')}}"  alt="" width="25px" height="25px" >
                                    </div>
                                    <div class="content_sum_name" ms-html="'贴士' + ($index+1)"></div>
                                    <div class="content_sum_time_note" ms-html="formTime(el.tipstime)">17:51</div>
                                </div>
                                <div style="height:10px"></div>
                                <div class="note_areass1">
                                    <div class="note_comment_content_detail" ms-html="el.tipscontent"></div>
                                    <div class="edit_delete">
                                        <div class="edit_edit" ms-click="pop_edit(el.id,el.tipscontent)">
                                            <img src="{{asset('home/image/teacherCourse/detail/bianji.png')}}" alt="" width="20px" height="20px">
                                        </div>
                                        <div class="delete_delete" ms-click="deleteTips(el.id,$index)">
                                            <img src="{{asset('home/image/teacherCourse/detail/shanchu.png')}}" alt="" width="20px" height="20px">
                                        </div>
                                    </div>
                                </div>
                                <div class="note_areass2 none">
                                    {{--修改框--}}
                                    <div class="note_areas">
                                        <textarea name="" id="promptEditContent" ms-duplex="promptEditContent"></textarea>
                                    </div>
                                    {{--<div style="clear: both"></div>--}}
                                    <div class="edit_deletes" ms-click="modifyTips(el.id,el.tipscontent,$index)">
                                        修改
                                    </div>
                                </div>
                                <div style="clear: both"></div>
                            </div>
                            <div style="clear: both"></div>
                            <div style="height:30px"></div>
                            {{--<div class="wodetieshi" ms-if="wodetieshi">暂无贴士内容</div>--}}
                        </div>
                        {{--留言内容--}}

                    </div>
                </div>

                <div class="note_content_novideo" ms-if="documentOrVideo != 'video'">
                    非视频格式课件暂不支持添加贴士
                </div>
                @endif
                @if($teacherId !== \Auth::user()->id)
                <div class="note_content_teacher" >
                    非教师本人课程不允许添加视频贴士
                </div>
                @endif
            </div>


            {{--问答内容--}}
            <div class="answer_content none" ms-visible="currentIndex=='answers'">
                <div style="height:20px"></div>
                <div class="hide_show"  style="display: none">
                    <div class="note_area" >
                        <textarea name=""   id="commentContents" ms-duplex="commentContents"></textarea>
                    </div>

                    <div class="private_confirm">
                        <div class="confirms" ms-click="postComments()">保存</div>
                    </div>
                </div>



                <div class="question_answer" ms-repeat="courseAskData">
                    <div style="height:8px"></div>
                    <div class="note_comment_content">
                        <div class="note_comment_content_sum">
                            <div class="content_sum_img">
                                <img ms-attr-src="el.pic"  alt="" width="36px" height="36px" >
                            </div>
                            <div class="content_sum_name" ms-html="el.username"></div>
                            @if(\Auth::user()->id == $data->teacherId )
                            <div class="course_question_tea_huifu" ms-if="!el.teaId" ms-click="postReply('{{$data->id}}', el.id )">回复</div>
                            @endif
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
                            <div class="content_sum_answer">回答</div>
                            <div class="content_sum_time" ms-html="el.anstime | truncate(12,' ')"></div>
                        </div>
                        <div style="height:10px"></div>
                        <div class="answer_comment" ms-html="el.answer">

                        </div>
                    </div>
                    <div style="width:300px;height:15px;border-bottom: 1px solid #ccc;margin-left: 30px"></div>
                </div>
                <div style="height:20px"></div>
                <div style="height:40px;"></div>
                <div class="wodewenda" ms-if="wodewenda">暂无问答内容</div>
                {{--<a ms-attr-href="'/teacherCourse/teaDetail/'+el.id+'#note' "><div class="gengduo">查看更多</div></a>--}}
                <a href="/teacherCourse/teaDetail/{{$data->id}}#question"><div class="gengduo" ms-if="!wodewenda">查看更多</div></a>


            </div>

        </div>




    </div>



    <div style="clear: both"></div>
    <div style="height:160px">

    </div>





@endsection

@section('js')


    <script type="text/javascript" src="{{asset('home/js/teacherCourse/catalog.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>



    <script>
        require(['/teacherCourse/catalog'], function (model) {

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

            //当前登录用户id
            model.userId = '{{ \Auth::user()->id}}';

            model.courseId = '{{$data->id}}' || null;
            //章节Id
            model.chapterId = '{{$chapterId}}' || null;
            //章节目录
            model.getCourseChapter(model.courseId)
            model.getCourseAskData(model.courseId)
            //我的笔记
            model.getCourseMyNote(model.courseId)
            //共享笔记
            model.getCourseShareNote(model.courseId)
            //获取贴士
//            model.getCourseTips(model.courseId)

            model.getChapterData(model.chapterId)

            //默认播放
            model.getDefaultChapter(model.chapterId,model.courseId)




            avalon.scan();
        });
    </script>

@endsection


