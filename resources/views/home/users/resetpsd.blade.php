<!DOCTYPE html>
<html>
<head>
    <title>找回密码-设置新密码</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/resetpsd.css')}}">
</head>
<body>
<div class="center">
    <div class="teacher_con_top">
        <div class="teacher_con_top_l" ><div style="height:60px;"></div><hr></div>
        <div class="teacher_con_top_t"><a href="/">启创教育云</a></div>
        <div class="teacher_con_top_r" ><div style="height:60px;"></div><hr></div>
    </div>
    <div class="ts">
        请重新设置密码
    </div>
    <div style="height:50px;"></div>
    <div class="center_con">
        {{--密码--}}
        <div class="center_con_bar">
            <div class="center_con_bar_txt"><input class="txt upsd" type="password" name="password" placeholder="请输入新密码" style="font-size: 12px;"></div>
            <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
            <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
        </div>
        <div class="message" style="height:32px;"></div>
        {{--确认密码--}}
        <div class="center_con_bar">
            <div class="center_con_bar_txt"><input class="txt repsd" type="password" placeholder="请确认新密码" style="font-size: 12px;"></div>
            <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
            <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
        </div>
        <div class="message" style="height:32px;"></div>
    </div>

    <div class="btntj">
        <button class="wc" onclick="submitInfo()">完成</button>
    </div>

    <!-- 删除评论弹出层 -->
    <div class="delete_comment hide" ms-visible="'deleteComment' == popUp">
        <div class="top">
            <span>密码修改成功！</span>
        </div>
        {{--<div class="bot">--}}
            {{--<span class="quit" ms-click="popUpSwitch(false)">取消</span>--}}
            {{--<span class="sure" ms-click="popUpSwitch(commentId);">确定</span>--}}
        {{--</div>--}}
    </div>
</div>
</body>
<script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/resetpsd.js') }}"></script>
</html>