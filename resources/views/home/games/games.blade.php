@extends('layouts.layoutHome')

@section('title', '活动赛事')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/games/games.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/games/pagination.css')}}">
@endsection

@section('content')
    <div class="cont" ms-controller="games">
        <div class="dh">首页 > 活动赛事</div>
        <div class="cont_con">
            <div class="cont_con_con">
                <div class="cont_con_con_t">
                    <div class="cont_con_con_t_li sel" ms-cont="1" ms-click="getdata(1)">进行中</div>
                    <div style="float: left;width:26px;height: 100%;"></div>
                    <div class="cont_con_con_t_li" ms-cont="2" ms-click="getdata(2)">未开始</div>
                    <div style="float: left;width:26px;height: 100%;"></div>
                    <div class="cont_con_con_t_li" ms-cont="3" ms-click="getdata(3)">已结束</div>
                </div>
                <div class="cont_con_con_con ">
                    <div ms-repeat="datas" ms-if-loop="Con">
                        <div style="height:30px;"></div>
                        <a ms-attr-href="[--el.url--]" target="_blank">
                        <div class="cont_con_con_con_li">
                            <div class="cont_con_con_con_li_l"><img ms-attr-src="{{asset('[--el.img--]')}}" alt=""></div>
                            <div class="cont_con_con_con_li_r">
                                <div class="cont_con_con_con_li_r_t" ms-text="el.title"></div>
                                <div class="cont_con_con_con_li_r_time" ms-text="el.starttime+' - '+el.endtime"></div>
                                <div class="cont_con_con_con_li_r_from" ms-text="el.org"></div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="Msg" ms-if="Msg">没有相关赛事...</div>
                </div>
            </div>
        </div>
        <div style="height:50px;width:100%;min-width: 1200px;"></div>
        <div class="pagecon">
            <div style="display: inline-block"><div id="page" ms-if="page"></div></div>
        </div>
        <div style="height:100px;width:100%;min-width: 1200px;">
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/games.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script>
        require(['/games/games'], function () {
            avalon.scan(document.body);
        });
    </script>
@endsection