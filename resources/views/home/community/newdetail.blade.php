@extends('layouts.layoutHome')

@section('title', '新闻资讯列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/community/newdetail.css') }}">
@endsection

@section('content')


    <div class="background" ms-controller="newdetail">

        <div style="height:18px"></div>

        <!-- 主体 -->
        <div class="main">
            <!-- 面包屑导航 -->
            <div class="crumbs">
                <a href="{{asset('/community')}}">社区</a><span>></span><a href="{{asset('/community/newlist')}}">新闻资讯</a><span>></span><a href="#">新闻详情</a>
            </div>
            <div style="height:10px"></div>
            <!-- 内容 -->
            <div class="main_content" ms-repeat="newdetails" >
                <!-- 标题 -->
                <div class="main_title" ms-html="el.title">
                    
                </div>
                <!-- 详细内容 -->
                <div class="main_detail" ms-html="el.content" >
                    
                </div>
                <!-- 日期 -->
                <div class="main_time" ms-html="el.time">
                    
                </div>
            </div>

            <div class="screen1200"></div>
        </div>


        <div style="height:175px"></div>




    </div>





@endsection

@section('js')
    <script>
        require(['/community/newdetail.js'], function () {

            avalon.scan();
        });
    </script>
@endsection