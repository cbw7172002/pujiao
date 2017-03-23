<!DOCTYPE html>
<html>
<head>
    <title>用户登录</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/login.css')}}">
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
            {{--登陆框--}}
            <div class="center_con_r">
                <form method="POST" action="{{url('/auth/login')}}" onsubmit="return postcheck()">
                {!! csrf_field() !!}
                <div style="height:34px;"></div>
                {{--错误提示框--}}
                <div class="center_con_r_bar">
                    <div class="center_con_r_bar_l"></div>
                    <div class="center_con_r_bar_m errorMsg" style="line-height: 36px;color:red;"></div>
                </div>
                {{--用户名--}}
                <div style="height:10px;"></div>
                <div class="center_con_r_bar">
                    <div class="center_con_r_bar_l"><div class="clearInput hide"></div></div>
                    <div class="center_con_r_bar_m"><input class="txt uname" type="text" name="username" style="font-size: 12px;" placeholder="请输入用户名或手机号"></div>
                    <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
                </div>
                {{--密码--}}
                <div style="height:40px;"></div>
                <div class="center_con_r_bar">
                    <div class="center_con_r_bar_l"><div class="clearInput hide"></div></div>
                    <div class="center_con_r_bar_m"><input class="txt psd" type="password" name="password" style="font-size: 12px;" placeholder="请输入密码"></div>
                    <div class="cklogo cuo hide"><img src="{{url('home/image/register/rog.png')}}" alt=""></div>
                </div>

                {{--记住用户--}}
                <div style="height:5px;"></div>
                <div class="center_con_r_bar">
                    <div class="center_con_r_bar_l"></div>
                    <div class="center_con_r_bar_m">
                        <div class="center_con_r_bar_m_l"><input type="checkbox" name="remember">记住密码</div>
                        <div class="center_con_r_bar_m_r"><a href="{{url('/index/retrievepsd')}}" style="color:#000000">忘记密码？</a></div>
                    </div>
                </div>
                {{--登陆--}}
                <div style="height:10px;"></div>
                <div class="center_con_r_bar">
                    <div class="center_con_r_bar_l"></div>
                    <div class="center_con_r_bar_m">
                        <button class="tij" type="submit">登录</button>
                    </div>
                </div>
                {{--没有账号--}}
                <div style="height:10px;"></div>
                <div class="center_con_r_bar">
                    <div class="center_con_r_bar_l"></div>
                    <div class="center_con_r_bar_m">
                        <div class="center_con_r_bar_m_l"></div>
                        <div class="center_con_r_bar_m_r">没有账号，<a href="{{url('/index/register')}}"><span style="color:rgb(215,12,24)">立即注册</span></a></div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>
<script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/login.js') }}"></script>
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

//    jwplayer('myplayer').setup({
//        file: '/uploads/uploads/video/introduce/default.mp4',
//        image:'/home/image/index/vdo.png',
//        width: '700',
//        height: '400',
//        levels:[
//            {label: "标清",file:'/uploads/uploads/video/introduce/default.mp4',},
//            {label: "高清",file:'/uploads/uploads/video/introduce/default.mp4',},
//            {label: "超清",file:'/uploads/uploads/video/introduce/default.mp4',}
//        ]
//    });
</script>
</html>