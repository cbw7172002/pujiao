<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" type="text/css" href="{{asset('home/css/layout/layout.css')}}">
	@yield('css')
</head>
<body>
    <!-- 头部开始 -->
    <div class="head">
    	<div class="head_con">
    		<div class="head_con_logo">

				<a href="{{url('/')}}"><img src="{{asset('home/image/layout/logoup.png')}}"></a>
				{{--<img src="{{asset('home/image/layout/logoup.png')}}">--}}
			</div>
			@if (Auth::check() && Auth::user()->type != 3)
    		<div>
				@if (\Auth::user()->type == 2)
					<a href="{{url('/member/teacherHomePage/'.\Auth::user()->id)}}"><div class="head_con_li" ><div class="head_con_li_con member" >个人主页</div></div></a>
					<a href="{{url('/resource')}}"><div class="head_con_li" ><div class="head_con_li_con resource" >资源中心</div></div></a>
					<a href="{{url('/teacherCourse/list')}}"><div class="head_con_li" ><div class="head_con_li_con teacherCourse" >课程中心</div></div></a>
					<a href="{{url('/evaluateManageTea/index')}}"><div class="head_con_li" ><div class="head_con_li_con evaluateManageTea" >测评管理</div></div></a>
				@else
					<a href="{{url('/member/studentHomePage/'.\Auth::user()->id)}}"><div class="head_con_li" ><div class="head_con_li_con member" >个人主页</div></div></a>
					<a href="{{url('/resource')}}"><div class="head_con_li" ><div class="head_con_li_con resource" >资源中心</div></div></a>
					<a href="{{url('/studentCourse/list')}}"><div class="head_con_li" ><div class="head_con_li_con studentCourse" >我的课程</div></div></a>
					<a href="{{url('/evaluateManageStu/index')}}"><div class="head_con_li" ><div class="head_con_li_con evaluateManageStu" >我的测验</div></div></a>
                @endif
				<a href="{{url('/community')}}"><div class="head_con_li" ><div class="head_con_li_con community">问答社区</div></div></a>
    		</div>
			@endif
    		<div class="head_con_login showperspace">
    		    <!-- 登陆前 -->
				@if (!Auth::check() || \Auth::user()->type == 3)
					{{--<div class="head_con_login_con "><a href="{{url('/index/login')}}"><span>登录</span></a>&nbsp;|&nbsp;<a href="{{url('/index/register')}}"><span>注册</span></a></div>--}}
					{{--<a href="/aboutUs/firmintro/1"><div class="head_con_login_con ">关于我们</div></a>--}}
    		    <!-- 登陆后 -->
				@else
					<img  class="touxiang " src="{{asset(\Auth::user()->pic)}}" onerror="javascript:this.src='/home/image/layout/default.png';">
					{{--@if ($Msg)<div class="haveMsg">·</div>@else<div class="haveMsg"></div> @endif--}}

					<div class="clear"></div>
					<div  class="persapce hide showperspace">
						{{--<div class="persapce_li">--}}
							{{--<div class="persapce_li_con_per">--}}
								{{--@if(\Auth::user()->type == 1)--}}
									{{--<a href="{{url('/member/studentHomePage/'.\Auth::user()->id.'/#myAuditing')}}">个人中心</a>--}}
								{{--@elseif(\Auth::user()->type == 2)--}}
									{{--<a href="{{url('/member/teacherHomePage/'.\Auth::user()->id.'/#wholeNotice')}}">个人中心</a>--}}
								{{--@endif--}}
							{{--</div>--}}
						{{--</div>--}}
						{{--@if( \Auth::user()->type != 2 )--}}
							{{--<div class="persapce_li">--}}
								{{--<a href="{{asset('/member/studentHomePage/'.\Auth::user()->id.'/#wholeNotice')}}">--}}
									{{--@if ($Msg)--}}
									{{--<div class="persapce_li_con_msg" style="background: url('/home/image/layout/haveMsg.png') no-repeat 15px 5px;">消息通知</div>--}}
									{{--@else--}}
									{{--<div class="persapce_li_con_msg">消息通知</div>--}}
									{{--@endif--}}
								{{--</a>--}}
							{{--</div>--}}
						{{--@else--}}
							{{--<div class="persapce_li">--}}
								{{--<a href="{{asset('/member/teacherHomePage'.\Auth::user()->id.'/#wholeNotice')}}">--}}
									{{--@if ($Msg)--}}
										{{--<div class="persapce_li_con_msg" style="background: url('/home/image/layout/haveMsg.png') no-repeat 15px 5px;">消息通知</div>--}}
									{{--@else--}}
										{{--<div class="persapce_li_con_msg">消息通知</div>--}}
									{{--@endif--}}
								{{--</a>--}}
							{{--</div>--}}
						{{--@endif--}}
						{{--<div class="persapce_li" style="border-bottom:1px solid #F5F5F5"><a href="{{url('/index/switchs')}}"><div class="persapce_li_con_cha">切换账号</div></a></div>--}}
						@if(\Auth::user()->type == 1)
							<div class="persapce_li" style="border-bottom:1px solid #F5F5F5"><a href="{{url('/member/accountManagerStudent/'.\Auth::user()->id .'/#infoUphold')}}"><div class="persapce_li_con_cha">账号管理</div></a></div>
						@elseif(\Auth::user()->type == 2)
							<div class="persapce_li" style="border-bottom:1px solid #F5F5F5"><a href="{{url('/member/accountManagerTeacher/'.\Auth::user()->id .'/#infoUphold')}}"><div class="persapce_li_con_cha">账号管理</div></a></div>
						@endif
						<div class="persapce_li"><a href="{{url('/auth/logout')}}"><div class="persapce_li_con_lout">退出登录</div></a></div>
					</div>
				@endif
			</div>
			{{--搜索--}}
    		{{--<div class="head_con_search">--}}
				{{--<div class="head_con_search_con">--}}
					{{--<form method="post" target= "_blank" action="{{url('/index/search')}}">--}}
						{{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
						{{--<input type="hidden" name="type" value="all">--}}
						{{--<input type="text" name="search" placeholder="请输入您要查找的课程名称" class="sear" style="font-size: 12px;">--}}
						{{--<input type="image" src="{{asset('home/image/layout/search.png')}}" class="sbtn">--}}
					{{--</form>--}}
				{{--</div>--}}
			{{--</div>--}}
    	</div>
    </div>
    <div class="head_head"></div>
    <!-- 联系客服 -->
	<div class="onlines">
		<div style="background: #ffffff;">
			<div class="onlines_wx">
				<div class="onlines_wx_con hide">
					<div class="onlines_wx_con_top"><img  width="55px" height="55px" src="{{asset('home/image/layout/qczyerweima.jpg')}}" style="margin-top: 12px;margin-left: 12px;"></div>
					<div class="onlines_wx_con_bot">扫一扫<br>关注公众号</div>
				</div>
			</div>
		</div>
		<div style="height:17.5px;"></div>
		@if (Auth::check())
		<div style="background: #ffffff;">
			<div class="onlines_fk">
				<a href="{{url('community/feedback')}}">
				<div class="onlines_fk_con hide">
					<div class="onlines_fk_con_l"></div>
					<div class="onlines_fk_con_r">意见反馈</div>
				</div>
				</a>
			</div>
		</div>
		<div style="height:17.5px;"></div>
		@endif
		<div style="background: #ffffff;">
			<div class="onlines_db">
				<a href="javascript:scroll(0,0)">
				<div class="onlines_db_con hide">
					<div class="onlines_db_con_l"></div>
					<div class="onlines_db_con_r">返回顶部</div>
				</div>
				</a>
			</div>
		</div>
	</div>
    <!-- 头部结束 -->

    <!-- body体 -->
    <div class="body">
		@yield('content')

		{{--<div style="width:1200px;height:1000px;margin:0 auto;background:#dddddd;"></div>--}}
    </div>
    <!-- body体结束 -->

	<div class="foot">
		<div class="foot_con">
			<div class="foot_con_l">
				<div style="height:20px;"></div>
				<div class="foot_con_l_a"><img src="{{asset('home/image/layout/logodown.png')}}" width="180" height="40" ></div>
				<div class="foot_con_l_b">启创教育云校园教学资源应用平台</div>
				<div class="foot_con_l_c">Copyright © 2017 primecloud.cn lnc.All Rights Reserved</div>
				<div class="foot_con_l_d">启创卓越 版权所有</div>
			</div>
			<div class="foot_con_m">
				<div style="height:40px;"></div>
				<div class="foot_con_m_a">关于我们</div>
				<div style="height:20px;"></div>
				<div class="foot_con_m_b"><a href="/aboutUs/firmintro/1" title="公司介绍" target="_blank">公司介绍</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="{{url($weibo)}}" title="官方微博"
																											   target="_blank">官方微博</a></div>
				<div class="foot_con_m_b"><a href="/aboutUs/firmintro/2" title="联系我们" target="_blank">联系我们</a> &nbsp;&nbsp;&nbsp;&nbsp; <a
							href="/aboutUs/firmintro/4">用户协议</a></div>
				<div class="foot_con_m_b"><a href="/aboutUs/firmintro/3" title="常见问题" target="_blank">常见问题</a> &nbsp;&nbsp;&nbsp;&nbsp; <a
							href="/aboutUs/firmintro/5" title="友情链接" target="_blank">友情链接</a></div>
			</div>
			<div class="foot_con_r">
				{{--<div class="foot_con_r_l">--}}
					{{--<div style="height:40px;"></div>--}}
					{{--<div class="foot_con_r_l_a">官方微信</div>--}}
					{{--<div style="height:30px;"></div>--}}
					{{--<div class="weima">{!! $weixin !!}</div>--}}
				{{--</div>--}}
				<div class="foot_con_r_r">
					<div style="height:40px;"></div>
					{{--<div class="foot_con_r_l_a">APP下载</div>--}}
					<div class="foot_con_r_l_a">官方微信</div>
					<div style="height:30px;"></div>
					<img class="weima" src="{{asset('home/image/layout/qczyerweima.jpg')}}" alt="">
				</div>
			</div>
		</div>

		{{--<div class="foot_con2">--}}
			{{--版权所有：全国教育信息技术研究“十二五”规划重点课题“慕课助推西部地区中小学教育均衡发展的策略研究”总课题组<br/>--}}
			{{--研发支持：重庆浪尖创课科技有限公司“创课在线”项目组<br/>--}}
			{{--网络支持：重庆市沙坪坝区教育管理信息中心<br/>--}}
		{{--</div>--}}
	</div>
	<!-- 底部结束 -->

</body>
<script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
@yield('selectjs')
<script type="text/javascript" src="{{asset('home/js/layout/main.js')}}"></script>
@yield('js')
</html>