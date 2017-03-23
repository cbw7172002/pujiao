<!DOCTYPE html>
<html>
<head>
    <title>找回密码-验证账户</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/retrievepsd.css')}}">
</head>
<body>
    <div class="center">
        <div class="teacher_con_top">
            <div class="teacher_con_top_l" ><div style="height:60px;"></div><hr></div>
            <div class="teacher_con_top_t"><a href="/">启创教育云</a></div>
            <div class="teacher_con_top_r" ><div style="height:60px;"></div><hr></div>
        </div>
        <div class="ts">
            找回密码
        </div>
        <div style="height:50px;"></div>
        <div class="center_con">
            {{--手机号--}}
            <div class="center_con_bar">
                <div class="center_con_bar_txt"><input class="txt uphone" type="text"  placeholder="请输入绑定手机号" style="font-size: 12px;"></div>
                <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
                <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
            </div>
            <div class="message" style="height:32px;"></div>
            {{--验证码--}}
            <div class="center_con_bar">
                <div class="center_con_bar_txt">
                    <input class="txtt" type="text" placeholder="验证码" style="font-size: 12px;">
                    <button type="button" class="getyzm" onclick="getMsg()">获取验证码</button>
                </div>
                <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
                <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
            </div>
            <div class="message" style="height:32px;"></div>
        </div>

        <div class="btntj">
            <button class="wc" onclick="nextCheck()">下一步</button>
        </div>
    </div>
</body>
<script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/retrievepsd.js') }}"></script>
</html>