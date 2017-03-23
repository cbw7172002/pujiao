@extends('layouts.layoutHome')

@section('title', '首页')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/index/index.css')}}">
@endsection

@section('content')
    <div ms-controller="index" style="background: #ffffff">
        {{--登录banner--}}
        <div class="bar_con">
            <div class="bar">
                <div class="login_box">
                    <form method="POST" action="{{url('/auth/login')}}" onsubmit="return postcheck()">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div style="height: 74px">
                        <div style="height:25px;"></div>
                        <div class="login_box_msg errorMsg" style="line-height: 36px;color:red;"></div>
                    </div>
                    <div class="login_box_uinfo">
                        <div class="login_box_uinfo_con zhanghu">
                            <input type="text" class="txt uname" name="username" placeholder="请输入您的登录号"><div class="clearInput hide"></div>
                        </div>
                    </div>
                    <div class="login_box_uinfo">
                        <div class="login_box_uinfo_con mima">
                            <input type="password" class="txt psd" name="password" placeholder="请输入您的密码"><div class="clearInput hide"></div>
                        </div>
                    </div>
                    <div class="login_box_help">
                        <div class="login_box_help_l"><input type="checkbox" name="remember" checked>记住密码</div>
                        <div class="login_box_help_r"><a href="{{url('/index/retrievepsd')}}" style="color:rgb(153,153,153);">忘记密码？</a></div>
                    </div>
                    <button class="login_box_btn" type="submit">登录</button>
                    </form>
                </div>
            </div>
        </div>
        {{--创客资讯--}}
        <div class="courseInfo">
            {{--合作伙伴--}}
            <div class="courseInfo_top"></div>
            <div style="height:10px;"></div>
            <div class="courseInfo_con">
                {{--<img src="/home/image/index/vk.png" alt="" style="margin-top: 40px;margin-left: 40px;">--}}
                {{--<img src="/home/image/index/mk.png" alt="" style="position: relative;top:-20px;left:70px;">--}}
                {{--<img src="/home/image/index/db.png" alt="" style="position: relative;left:140px;">--}}
                {{--<img src="/home/image/index/yqzy.png" alt="" style="position: relative;left:180px;">--}}
                @foreach ($frids as $frid)
                    <a href="{{url('http://'.$frid->url)}}" target="_blank"><img src="{{asset($frid->path)}}" alt="{{$frid->title}}"></a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('home/js/index/index.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/index/login.js') }}"></script>
    <script>
//        require(['/index/index'], function () {
//            avalon.scan(document.body);
//        });
    </script>
@endsection