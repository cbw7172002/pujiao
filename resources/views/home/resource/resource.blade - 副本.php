@extends('layouts.layoutHome')

@section('title', '资源列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/resource/resource.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/games/pagination.css')}}">

@endsection

@section('content')
    <div class="contain_resource" ms-controller="rescontroller">
        <div class="shadow hide"></div>
        <div style="height: 40px;width: 100%;"></div>
        {{--bar--}}
        <div class="contain_lesson_top">
            <div class="contain_lesson_top_bk">
                <div style="height: 50px;"></div>
                <div class="title">
                    <i></i> 资源搜索
                </div>
                <div class="search">
                    <input type="text" name="search" ms-duplex="selTitle"/><span  style="cursor: pointer;text-indent: 10px;" ms-click="titsearch()">搜索</span>
                </div>
                @if (Auth::check() && \Auth::user()->type == 2)
                <div class="upres_con">
                    <a href="/resource/uploadRes"><div class="upres_con_btn">上传资源</div></a>
                    <div class="upres_con_msg">欢迎上传并分享您的教学资源</div>
                </div>
                @endif
            </div>
        </div>
        {{--主体--}}
        <div class="contain_resource_main">
            {{--主体左--}}
            <div class="contain_resource_main_left">
                <div class="contain_resource_main_top">资源目录</div>
                <div class="contain_resource_main_con">
                    <div style="height: 15px;"></div>
                    {{--年级-li--}}
                    <div class="contain_resource_main_con_li" ms-repeat-grade="sideSels" ms-if-loop="selGradeId==0 || grade.id == selGradeId">
                        <div class="contain_resource_main_con_li_tit" ms-clicksel="{type:1,id:grade.id}">
                            <div class="contain_resource_main_con_li_tit_pic down1" ms-class-1="down1: grade.id != selGradeId" ms-class-2="up1: grade.id == selGradeId"></div>
                            <div class="contain_resource_main_con_li_tit_name" ms-html="grade.gradeName">一年级</div>
                        </div>
                        <div class="contain_resource_main_con_li_con hide" ms-class-1="dottedbor:$index+1 != sideSels.length"  ms-class-2="hide: grade.id != selGradeId">
                            {{--科目-li--}}
                            <div class="contain_resource_main_con_li" ms-repeat-subject="grade.subjects" ms-if-loop="selSubjectId==0 || subject.id == selSubjectId">
                                <div class="contain_resource_main_con_li_tit"  ms-clicksel="{type:2,id:subject.id}">
                                    <div class="contain_resource_main_con_li_tit_pic down1" ms-class-1="down1: subject.id != selSubjectId" ms-class-2="up1: subject.id == selSubjectId"></div>
                                    <div class="contain_resource_main_con_li_tit_name" ms-html="subject.subjectName">语文</div>
                                </div>
                                <div class="contain_resource_main_con_li_con hide dottedbor" ms-class-1="hide: subject.id != selSubjectId">
                                    {{--版本-li--}}
                                    <div class="contain_resource_main_con_li" ms-repeat-edition="subject.editions" ms-if-loop="selEditionId==0 || edition.id == selEditionId">
                                        <div class="contain_resource_main_con_li_tit"  ms-clicksel="{type:3,id:edition.id}">
                                            <div class="contain_resource_main_con_li_tit_pic down1" ms-class-1="down1: edition.id != selEditionId" ms-class-2="up1: edition.id == selEditionId"></div>
                                            <div class="contain_resource_main_con_li_tit_name" ms-html="edition.editionName">人教版</div>
                                        </div>
                                        <div class="contain_resource_main_con_li_con hide" ms-class-1="dottedbor:$index+1 != subject.editions.length" ms-class-2="hide: edition.id != selEditionId">
                                            {{--册别-li--}}
                                            <div class="contain_resource_main_con_li" ms-repeat-book="edition.books" ms-if-loop="selBookId==0 || book.id == selBookId">
                                                <div class="contain_resource_main_con_li_tit"  ms-clicksel="{type:4,id:book.id}">
                                                    <div class="contain_resource_main_con_li_tit_pic down1" ms-class-1="down1: book.id != selBookId" ms-class-2="up1: book.id == selBookId"></div>
                                                    <div class="contain_resource_main_con_li_tit_name" ms-html="book.bookName">下册</div>
                                                </div>
                                                <div class="contain_resource_main_con_li_con hide" ms-class-1="dottedbor:$index+1 != edition.books.length" ms-class-2="hide: book.id != selBookId">
                                                    {{--章节单元--}}
                                                    {{--<div class="contain_resource_main_con_li_con_unit" title="">Uint1</div>--}}
                                                    <div class="contain_resource_main_con_li_con_unit" ms-repeat-chapter="book.chapters" ms-attr-title="chapter.chapterName" ms-html="chapter.chapterName" ms-clicksel="{type:6,id:chapter.id}">Uint1</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--拓展资源--}}
                            <div class="contain_resource_main_con_li">
                                <div class="contain_resource_main_con_li_tit" ms-clicksel="{type:5,id:0}">
                                    <div class="contain_resource_main_con_li_tit_pic down1"></div>
                                    <div class="contain_resource_main_con_li_tit_name">拓展资源</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="height: 30px;"></div>
                </div>
            </div>
            {{--主体右--}}
            <div class="contain_resource_main_right">
                {{--筛选条件--}}
                <div class="contain_resource_main_right_top">
                    <div style="height: 15px;"></div>
                    {{--年级--}}
                    <div class="contain_resource_main_right_top_sel_li">
                        <div class="contain_resource_main_right_top_sel_li_type">年级</div>
                        <div class="contain_resource_main_right_top_sel_li_selcon">
                            <div class="contain_resource_main_right_top_sel_li_selcon_li sel" ms-class="sel:selGradeId == 0" ms-click="selecting(1,0)" ms-sel>全部</div>
                            {{--<div class="contain_resource_main_right_top_sel_li_selcon_li">一年级</div>--}}
                            <div class="contain_resource_main_right_top_sel_li_selcon_li" ms-repeat="grades" ms-class="sel: el.id == selGradeId" ms-if-loop="$index < gradesShowNum" ms-html="el.gradeName" ms-click="selecting(1,el.id)" ms-sel></div>
                        </div>
                        <div class="contain_resource_main_right_top_sel_li_more down2" ms-if="grades.length > 8" ms-click="showMoreSel('a')"></div>
                    </div>
                    <div style="clear: both"></div>
                    {{--科目--}}
                    <div class="contain_resource_main_right_top_sel_li">
                        <div class="contain_resource_main_right_top_sel_li_type">科目</div>
                        <div class="contain_resource_main_right_top_sel_li_selcon">
                            <div class="contain_resource_main_right_top_sel_li_selcon_li sel" ms-class="sel:selSubjectId == 0" ms-click="selecting(2,0)" ms-sel>全部</div>
                            {{--<div class="contain_resource_main_right_top_sel_li_selcon_li">语文</div>--}}
                            <div class="contain_resource_main_right_top_sel_li_selcon_li" ms-repeat="subjects" ms-class="sel: el.id == selSubjectId" ms-if-loop="$index < subjectsShowNum" ms-html="el.subjectName" ms-click="selecting(2,el.id)" ms-sel></div>
                            <div class="contain_resource_main_right_top_sel_li_selcon_li hide" ms-class-1="hide:!grades.length" ms-class-2="sel:isExpand" ms-click="selecting(5)" ms-sel>拓展资源</div>
                        </div>
                        <div class="contain_resource_main_right_top_sel_li_more down2" ms-if="subjects.length > 7" ms-click="showMoreSel('b')"></div>
                    </div>
                    <div style="clear: both"></div>
                    {{--版本--}}
                    <div class="contain_resource_main_right_top_sel_li">
                        <div class="contain_resource_main_right_top_sel_li_type">版本</div>
                        <div class="contain_resource_main_right_top_sel_li_selcon">
                            <div class="contain_resource_main_right_top_sel_li_selcon_li sel" ms-class="sel:selEditionId == 0" ms-click="selecting(3,0)" ms-sel>全部</div>
                            {{--<div class="contain_resource_main_right_top_sel_li_selcon_li">人教版</div>--}}
                            <div class="contain_resource_main_right_top_sel_li_selcon_li" ms-repeat="editions" ms-class="sel: el.id == selEditionId" ms-if-loop="$index < editionsShowNum" ms-html="el.editionName" ms-click="selecting(3,el.id)" ms-sel></div>

                        </div>
                        <div class="contain_resource_main_right_top_sel_li_more down2" ms-if="editions.length > 8" ms-click="showMoreSel('c')"></div>
                    </div>
                    <div style="clear: both"></div>
                    {{--册别--}}
                    <div class="contain_resource_main_right_top_sel_li contain_resource_main_right_top_sel_li_book hide">
                        <div class="contain_resource_main_right_top_sel_li_type">册别</div>
                        <div class="contain_resource_main_right_top_sel_li_selcon">
                            <div class="contain_resource_main_right_top_sel_li_selcon_li sel" ms-class="sel:selBookId == 0" ms-click="selecting(4,0)" ms-sel>全部</div>
                            {{--<div class="contain_resource_main_right_top_sel_li_selcon_li">上册</div>--}}
                            <div class="contain_resource_main_right_top_sel_li_selcon_li" ms-repeat="books" ms-class="sel: el.id == selBookId" ms-if-loop="$index < booksShowNum" ms-html="el.bookName" ms-click="selecting(4,el.id)" ms-sel></div>
                        </div>
                        <div class="contain_resource_main_right_top_sel_li_more down2" ms-if="books.length > 8" ms-click="showMoreSel('d')"></div>
                    </div>
                    <div style="clear: both"></div>
                    <div class="contain_resource_main_right_top_sel_li" style="border-top: 1px solid #F5F5F5">
                        <div class="contain_resource_main_right_top_sel_more down3">更多选项</div>
                    </div>
                </div>
                <div style="height:20px;"></div>
                {{--筛选结果--}}
                <div class="contain_resource_main_right_con">
                    <div class="contain_resource_main_right_con_top">
                        <div class="contain_resource_main_right_con_top_li" style="width:0px;"></div>
                        <div class="contain_resource_main_right_con_top_li sel" ms-sel ms-click="selecting(7,0)">全部</div>
                        {{--<div class="contain_resource_main_right_con_top_li" ms-sel>课件</div>--}}
                        <div class="contain_resource_main_right_con_top_li" ms-repeat="types" ms-html="el.text" ms-sel ms-click="selecting(7,el.id)"></div>

                        <div class="contain_resource_main_right_con_top_right">
                            <span class="contain_resource_main_right_con_top_right_ord sel" ms-sel ms-click="selecting(8,true)">热门</span>-<span class="contain_resource_main_right_con_top_right_ord" ms-sel ms-click="selecting(8,false)">最新</span>
                        </div>
                    </div>
                    <div class="contain_resource_main_right_con_con">
                        {{--资源循环列表--}}
                        <div class="contain_resource_main_right_con_con_li" ms-repeat="resources">
                            <div style="height:17px;"></div>
                            <div class="contain_resource_main_right_con_con_li_con">
                                {{--exl pdf photo ppt video word--}}
                                {{--<div class="contain_resource_main_right_con_con_li_con_img exl"></div>--}}
                                <div class="contain_resource_main_right_con_con_li_con_img">
                                    <a ms-attr-href="'/resource/resDetail/'+el.id" target="_blank"><img ms-attr-src="el.resourcePic" alt=""></a>
                                </div>
                                <div class="contain_resource_main_right_con_con_li_con_con">
                                    <a ms-attr-href="'/resource/resDetail/'+el.id" target="_blank"><div class="contain_resource_main_right_con_con_li_con_con_tit" ms-html="el.resourceTitle">单词与句子</div></a>
                                    <div class="contain_resource_main_right_con_con_li_con_con_des" ms-if="el.isexpand == 1" ms-html="el.resourceEdition + el.resourceGrade + el.resourceSubject + el.resourceBook">人教版 六年级英语上册</div>
                                    <div class="contain_resource_main_right_con_con_li_con_con_des" ms-if="el.isexpand == 2" ms-html="el.resourceGrade">六年级</div>
                                    <div style="height: 10px;"></div>
                                    <div class="contain_resource_main_right_con_con_li_con_con_msg">
                                        <div class="contain_resource_main_right_con_con_li_con_con_msg_user" ms-html="'上传者 : ' + el.userId">上传者 : 王大明</div>
                                        <div class="contain_resource_main_right_con_con_li_con_con_msg_time" ms-html="'上传时间 : ' + el.created_at">上传时间 : 2016-12-24</div>
                                        <div class="contain_resource_main_right_con_con_li_con_con_msg_view" ms-html="'浏览 : ' + el.resourceView">浏览 : 99</div>
                                        <div class="contain_resource_main_right_con_con_li_con_con_msg_view" ms-html="'下载 : ' + el.resourceDownload">下载 : 99</div>
                                        <div class="contain_resource_main_right_con_con_li_con_con_msg_view" ms-html="'收藏 : ' + el.resourceFav">收藏 : 99</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--资源循环列表--}}
                        <div class="noresource hide" ms-class="hide:resources.length">努力搜索中 ...</div>
                    </div>
                </div>
                {{--分页--}}
                <div style="height: 30px;"></div>
                <div class="pagecon">
                    <div style="display: inline-block"><div id="page"></div></div>
                </div>
        </div>
    </div>
    <div class="clear" style="height: 80px;"></div>
@endsection

@section('js')
    <scrip>
        <script type="text/javascript" src="{{asset('home/js/resource/resource.js')}}"></script>
        <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    </scrip>
    <script>
        require(['/resource/resource'], function (vm) {
            vm.getRessels(1); vm.getRessels(2); vm.getRessels(3); vm.getRessels(4); vm.getRessels(5); vm.getSidesels();
            vm.getResource();
            avalon.directive("sel", {
                init: function (binding) {
                    $('.contain_resource_main_right_top_sel_li_selcon_li').click(function () { $(this).addClass('sel').siblings().removeClass('sel') });
                    $('.contain_resource_main_right_con_top_li').click(function () { $(this).addClass('sel').siblings().removeClass('sel') })
                    $('.contain_resource_main_right_con_top_right_ord').click(function () { $(this).addClass('sel').siblings().removeClass('sel') })
                }
            })

            avalon.directive("clicksel", {
                init: function (binding) {
                    avalon(binding.element).bind("click",function () {
                        vm.selecting(binding.oldValue.type,binding.oldValue.id)
                        if(binding.oldValue.type == 6) return false
                        //if($(this).children('.contain_resource_main_con_li_tit_pic').hasClass('down1')) $(this).children('.contain_resource_main_con_li_tit_pic').removeClass('down1').addClass('up1').parent().next().removeClass('hide')
                        //else $(this).children('.contain_resource_main_con_li_tit_pic').addClass('down1').removeClass('up1').parent().next().addClass('hide')
                        if($(this).next().hasClass('hide')) $(this).children('.contain_resource_main_con_li_tit_pic').removeClass('down1').addClass('up1').parent().next().removeClass('hide')
                        else $(this).children('.contain_resource_main_con_li_tit_pic').addClass('down1').removeClass('up1').parent().next().addClass('hide')
                    })
                }
            })

            avalon.scan(document.body);
        })
    </script>
@endsection