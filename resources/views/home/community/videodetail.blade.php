@extends('layouts.layoutHome')

@section('title', '热门视频详情')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/community/videodetail.css') }}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/commentDetail/index.css')}}">--}}

@endsection

@section('content')



    <div class="background" ms-controller="hotvideo" >

        <div style="height:15px"></div>

        <!-- 主体 -->
        <div class="main">
            <!-- 面包屑导航 -->
            <div class="crumbs">
                <a href="{{asset('/community')}}">社区</a><span>></span><a href="#">热门视频</a>
            </div>
            <div style="height:10px"></div>

            <!-- 视频以及详情介绍 -->
            <div class="videodetail">

                <!-- 视频 -->
                <div class="video">
                    {{--<img src="{{url('home/image/community/hotvideo.png') }}" alt="" />--}}
                    <div id="mediaplayer"></div>
                </div>

                <!-- 详情介绍 -->
                <div class="detail" ms-repeat="hotvideos" >
                    <!-- 标题 -->
                    <div class="detail_title"  >
                        <div >
                            <span ms-html="el.title"></span>
                        </div>
                    </div>
                    <div style="height:34px"></div>
                    <!-- 内容 -->
                    <div class="content">
                        <span ms-html="el.videoIntro">  </span>
                    </div>
                </div>

            </div>

        </div>

        <div style="height:218px"></div>



    </div>
    <div class="screen1200"></div>




@endsection

@section('js')
    <script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>
    <script>
        require(['/community/videodetail.js'], function ($) {

            avalon.scan();
        });
    </script>


@endsection
