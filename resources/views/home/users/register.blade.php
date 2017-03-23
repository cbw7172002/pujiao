<!DOCTYPE html>
<html>
<head>
    <title>用户注册</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/register.css')}}">
</head>
<body>
    <div class="center">
        <div class="teacher_con_top">
            <div class="teacher_con_top_l" ><div style="height:60px;"></div><hr></div>
            <div class="teacher_con_top_t"><a href="/">创课在线</a></div>
            <div class="teacher_con_top_r" ><div style="height:60px;"></div><hr></div>
        </div>
        <div style="height:20px;"></div>
        <div class="center_con">
            {{--视频介绍--}}
            <div class="center_con_l">
                <img src="{{asset('/home/image/personCenter/loginpic.png')}}" style="width:100%;height:100%" alt="">
                {{--<div id="myplayer" ></div>--}}
            </div>
            {{--注册框--}}
            <div class="center_con_r">
                <form method="POST" action="{{url('/auth/register')}}" onsubmit="return postcheck()">
                    {!! csrf_field() !!}
                    {{--用户名--}}
                    <div style="height:20px;"></div>
                    <div class="center_con_r_bar">
                        <div class="center_con_r_bar_ll"><div class="clearInputs"><div class="clearInput hide"></div></div>用户名</div>
                        <div class="center_con_r_bar_m"><input class="txt cancleartxt uname" type="text" name="username" style="font-size: 12px;" placeholder="2-8个字符，字母/中文/数字/下划线"></div>
                        <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
                        <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
                    </div>
                    <div class="message"></div>

                    {{--手机号--}}
                    <div class="center_con_r_bar">
                        <div class="center_con_r_bar_ll"><div class="clearInputs"><div class="clearInput hide"></div></div>手机号</div>
                        <div class="center_con_r_bar_m"><input class="txt cancleartxt uphone" type="text" name="phone" style="font-size: 12px;" placeholder="请输入手机号"></div>
                        <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
                        <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
                    </div>
                    <div class="message"></div>

                    {{--验证码--}}
                    <div class="center_con_r_bar">
                        <div class="center_con_r_bar_ll"><div class="clearInputs"></div>验证码</div>
                        <div class="center_con_r_bar_m" style="width:307px;">
                            <input class="txtt" type="text">
                            <button type="button" class="getyzm" onclick="getMsg()">获取验证码</button>
                        </div>
                        <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
                        <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
                    </div>
                    <div class="message"></div>

                    {{--密码--}}
                    <div class="center_con_r_bar">
                        <div class="center_con_r_bar_ll"><div class="clearInputs"><div class="clearInput hide"></div></div>设置密码</div>
                        <div class="center_con_r_bar_m"><input class="txt cancleartxt upsd" type="password" name="password" style="font-size: 12px;" placeholder="支持数字字母组合，6-12位字符"></div>
                        <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
                        <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
                    </div>
                    <div class="message"></div>

                    {{--确认密码--}}
                    <div class="center_con_r_bar">
                        <div class="center_con_r_bar_ll"><div class="clearInputs"><div class="clearInput hide"></div></div>确认密码</div>
                        <div class="center_con_r_bar_m"><input class="txt cancleartxt repsd" type="password" style="font-size: 12px;" placeholder="支持数字字母组合，6-12位字符"></div>
                        <div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>
                        <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
                    </div>
                    <div class="message"></div>

                    {{--邀请码--}}
                    {{--<div class="center_con_r_bar">--}}
                        {{--<div class="center_con_r_bar_ll"><div class="clearInputs"><div class="clearInput hide"></div></div>邀请码</div>--}}
                        {{--<div class="center_con_r_bar_m"><input class="txt cancleartxt incode" type="text" name="fromyaoqingma" style="font-size: 12px;" placeholder="请输入您的邀请码（选填）"></div>--}}
                        {{--<div class="cklogo dui hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>--}}
                        {{--<div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>--}}
                    {{--</div>--}}
                    {{--<div class="message"></div>--}}

                    {{--身份--}}
                    {{--<div class="center_con_r_bar">--}}
                        {{--<div class="center_con_r_bar_ll"><div class="clearInputs"></div>身份</div>--}}
                        {{--<div class="center_con_r_bar_m">&nbsp;&nbsp;&nbsp;&nbsp;--}}
                            {{--<input  type="radio" name="type" value="1" checked>老师 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
                            {{--<input  type="radio" name="type" value="0">学生--}}
                        {{--</div>--}}
                        {{--<div class="cklogo hide"><img src="{{url('home/image/register/rht.png')}}" alt=""></div>--}}
                        {{--<div class="cklogo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>--}}
                    {{--</div>--}}
                    {{--<div class="message"></div>--}}
                    <input  type="hidden" name="type" value="0">

                    {{--注册按钮--}}
                    <div style="height:10px;"></div>
                    <div class="center_con_r_bar">
                        <div class="center_con_r_bar_l"></div>
                        <div class="center_con_r_bar_m">
                            <input class="deal" type="checkbox" >已阅读并同意<a href="/aboutUs/firmintro/4" target="_blank"><span
                                        style="color:rgb(215,12,24)">《创课在线账号使用协议》</span></a>
                        </div>
                    </div>

                    {{--注册按钮--}}
                    <div style="height:10px;"></div>
                    <div class="center_con_r_bar">
                        <div class="center_con_r_bar_l"></div>
                        <div class="center_con_r_bar_m">
                            <button class="tij unclick" type="submit" disabled>注册</button>
                        </div>
                    </div>
                    {{--已有账号--}}
                    <div style="height:10px;"></div>
                    <div class="center_con_r_bar">
                        <div class="center_con_r_bar_l"></div>
                        <div class="center_con_r_bar_m">
                            <div class="center_con_r_bar_m_l"></div>
                            <div class="center_con_r_bar_m_r">已有账号，<a href="{{url('/index/login')}}"><span style="color:rgb(215,12,24)">马上登录</span></a></div>
                        </div>
                    </div>
                    <div style="height:40px;"></div>
                </form>
            </div>
        </div>
        <div class="clear"></div>
        <div style="width:100%;height:40px;"></div>
    </div>
</body>
<script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>
<script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/register.js') }}"></script>
<script>
    $.ajax({
        type: "get",
        url: "/index/introVdo",
        async:false,
        success: function(data){
            jwplayer('myplayer').setup({
                file: data.video,
                image:data.pic,
                width: '700',
                height: '400'
            });
        }
    });
</script>
</html>