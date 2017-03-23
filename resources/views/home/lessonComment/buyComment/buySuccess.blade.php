@extends('layouts.layoutHome')

@section('title', '在线支付')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/buyComment/index.css')}}">
@endsection

@section('content')
	<div class="puy_success">
		<div class="crumbs">
			<a href="/">首页</a> >
			<a href="/lessonSubject/list/1">课程</a> >
			<a href="/community">名师主页</a> >
			<a>在线支付</a>
		</div>
	
		<div class="pay_content buySuccess">
			<div style="clear: both; height: 50px;"></div>

			<div class="pay_content_title">
				<img src="{{asset('/home/image/lessonComment/commentDetail/success.png')}}">支付成功
			</div>
			<div class="pay_content_des">订单已处理完成，感谢你的支持。</div>
			<div class="pay_content_des">如有疑问，请联系客服获取帮助。</div>
			<a href='/lessonComment/buy/upload/{{$orderID}}' class="pay_button" style="margin: 250px 0px 0px 70px;">上传视频</a>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>
		</div>
		
		<div style="claer: both; height: 60px;"></div>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		require(['lessonComment/buyComment/index'], function (comment) {
			comment.orderID = {{$orderID}} || null;
            avalon.scan();
		});
	</script>
@endsection