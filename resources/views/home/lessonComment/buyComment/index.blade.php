@extends('layouts.layoutHome')

@section('title', '在线支付')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/buyComment/index.css')}}">
@endsection

@section('content')
	<div class="buyController ms-controller" ms-controller="buyController">
		<div class="crumbs">
			<a href="/">首页</a> >
			<a href="/community">名师介绍</a> >
			<a href="/lessonComment/teacher/{{$teacherID}}">名师主页</a> >
			<a>在线支付</a>
		</div>
	
		<div class="buy_top">
			<div class="buy_title">名师邀请</div>
			<img ms-attr-src="teacherInfo.pic">
			<div class="buy_top_name" ms-html="teacherInfo.username"></div>
			<div class="buy_top_company" ms-html="teacherInfo.company"></div>
			<div class="buy_top_detail">近期[--teacherInfo.name--]老师尚有<span style="color: red;">{{$stock}}</span>个辅导名额在售，有问题可要抓紧哦。</div>
		</div>

		<div class="buy_middle">
			<div class="buy_title">辅导类型</div>
			<span ms-html="commentType"></span>
		</div>

		<div class="buy_bottom">
			<div class="buy_title">订单支付</div>
			<div class="buy_bottom_money">
				<div class="buy_bottom_left">订单金额</div>
				<span class="hide" ms-visible="teacherInfo.price" ms-html="'￥' + Math.ceil(teacherInfo.price / 100) + '元'"></span>
			</div>
			<div class="buy_bottom_buyway">
				<div class="buy_bottom_left">支付类型</div>
				<input type="radio" ms-duplex-number='payType' value='0'>
				<img src="{{asset('/home/image/lessonComment/commentDetail/zhifubao.png')}}" ms-click="selectPayType(0)">
				<input type="radio" ms-duplex-number='payType' value='1'>
				<img src="{{asset('/home/image/lessonComment/commentDetail/weixin.png')}}" style="margin-top: 10px;" ms-click="selectPayType(1)">
				<div style="color: red; margin-top: 5px; line-height: 55px; font-size: 14px; display: none;" ms-visible="payWarning">请选择支付方式</div>
			</div>
			<div ms-click="submit()" class="pay_button">在线支付</div>
		</div>
		
		<div style="claer: both; height: 60px;"></div>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		require(['lessonComment/buyComment/index'], function (comment) {
			comment.teacherID = {{$teacherID}} || null;
			comment.mineName = '{{\Auth::user() -> username}}' || null;
			comment.mineID = {{\Auth::user() -> id}} || null;
			//	获取用户信息
			comment.getData('/lessonComment/getTeacherInfo/' + comment.teacherID,   'teacherInfo');
            avalon.scan();
		});
	</script>
@endsection