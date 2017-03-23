@extends('layouts.layoutHome')

@section('title', $orderInfo -> orderTitle)

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/buyComment/index.css')}}">
@endsection

@section('content')
	<div class="puy_success ms-controller"  ms-controller="buyController">
		<div style="clear: both; height: 60px;"></div>

		<div class="weixin_scan">
            <div class="weixin_scan_box"></div>
			<div style="clear: both; height: 100px;"></div>
			<div class="scan_price">实付金额：<span>{{ceil($orderInfo -> orderPrice / 100)}}元</span></div>
			<div class="scan_code">
				{!! \QrCode::encoding('UTF-8') -> size(200) -> generate($url) !!}
			</div>
			<div class="scan_tip">
				<img src="{{asset('/home/image/lessonComment/commentDetail/scan.png')}}">使用微信扫一扫支付
			</div>
		</div>
		
		<div style="claer: both; height: 60px;"></div>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		require(['lessonComment/buyComment/index'], function (comment) {
			comment.orderID = {{$orderID}} || null;
			comment.getData('/lessonComment/orderStatus/'+comment.orderID, 'orderStatus');
            avalon.scan();
		});
	</script>
@endsection