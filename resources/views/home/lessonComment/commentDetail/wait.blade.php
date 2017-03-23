@extends('layouts.layoutHome')

@section('title', '我的辅导')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/commentDetail/index.css')}}">
@endsection

@section('content')
	<div class="commentDetail ms-controller" ms-controller="waitCommentController">
		<div class="crumbs">
			@if(\Auth::user() -> type == 2)
				<a href="/member/student/{{\Auth::user() -> id}}">个人中心</a> >
                <a href="/member/famousTeacher#waitComment">我的辅导</a>
			@else
				<a href="/member/famousTeacher">个人中心</a> >
                <a href="/member/student/{{\Auth::user() -> id}}#lessonComment">我的辅导</a>
			@endif
		</div>

		<div class="current_video">
			<div class="video_block">
				<div class="videobox" id="mediaplayer"></div>
			</div>

			<div class="video_bar">
				<div class="video_bar_title">创课视频</div>
			</div>
		</div>

		@if (\Auth::user() -> type == 2)
			<div class="switch_video" style="height: 110px;">
	            <a href='/lessonComment/upload/{{$orderSn}}/{{$messageID}}' class="upload">上传辅导视频</a>
				<div class="tip">该视频已上传<span style="color: red;" ms-html='studentInfo.time'></span>天</div>
			</div>
		@endif

		<div class="palyinfo">
			<div class="title">创课信息</div>
			<div class="palyinfo_detail">
				<a ms-attr-href="/lessonComment/student/[--studentInfo.userId--]" class="palyinfo_detail_img"><img ms-attr-src="studentInfo.pic" width="100%" height="100%"></a>
				<a ms-attr-href="/lessonComment/student/[--studentInfo.userId--]" class="palyinfo_detail_text" ms-html="studentInfo.username"></a>
				<div class="palyinfo_detail_time" ms-html="studentInfo.created_at"></div>
			</div>
			<div class="palyinfo_detail_title" ms-html="'曲目：' + studentInfo.extra"></div>
			<div class="palyinfo_detail_description" ms-visible="studentInfo.message" ms-html="'留言：' + studentInfo.message"></div>
            <div style="clear: both; height: 50px;"></div>
		</div>

        <div class="upload_screen"></div>

        <div style="clear: both; height: 100px;"></div>

	</div>
@endsection

@section('js')
	<script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>
	<script type="text/javascript">
		require(['lessonComment/commentDetail/wait'], function (comment) {
			comment.commentID = {{$commentID}} || null;
			comment.orderSn = '{{$orderSn}}' || null;
			comment.messageID = '{{$messageID}}' || null;
			console.log(comment.messageID);
			console.log('{{$messageID}}');

			//  获取辅导信息
			comment.getData('/lessonComment/getDetailInfo/'+ comment.orderSn +'/0', 'studentInfo');

            avalon.scan();
		});
	</script>
@endsection