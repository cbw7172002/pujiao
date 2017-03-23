@extends('layouts.layoutHome')

@section('title', '上传视频')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/commentDetail/uploadComment.css')}}">
@endsection

@section('content')
	<div class="uploadComment ms-controller" ms-controller="uploadController">
		<div class="crumbs">
			<a href="/">首页</a> >
			<a href="/lessonComment/teacher/{{$info -> teacherId}}">名师主页</a> >
			<a href="">上传视频</a>
		</div>

		<div class="uploadComment_content" style="min-height: 650px; height: auto;">
			<div style="clear: both; height: 50px;"></div>

			<div id="fileDiv" class="fileButton"></div>
			<input type="text" value="" class="fileButton" id="md5container">

            <div class="upload_screen"></div>
					
			<div class="add_video">
				<div class="add_video_top">
					<div>添加视频</div>
					<div ms-slectfile='file'>本地上传</div>
					<div>请上传不超过8分钟的视频文件</div>
				</div>
				<div class="add_video_tip" style="display: none;" ms-visible="uploadStatus == 1">(支持mp4、flv、avi、rmvb、wmv、mkv格式上传)</div>
				<div class="add_video_loading" style="display: none;" ms-visible="uploadStatus == 2">
					<div class="progress_bar">
						<div ms-css-width="[--progressBar--]%"></div>
					</div>
					<div class="progress_tip">视频上传中，请勿关闭页面...</div>
					<div class="progress_close" ms-click="endUpload()">取消上传</div>
				</div>
				<div class="add_video_success" style="display: none;" ms-visible="uploadStatus == 3" ms-html='uploadTip'></div>
			</div>

			<div style="clear: both; height: 20px;"></div>

            <div class="upload_screen"></div>

			<div class="works_title">
				<div class="suit_level_title" style="margin-left: -6px;"><span>*</span>作品名称</div>
				<input type="text" ms-duplex='uploadInfo.courseTitle' placeholder="请输入作品名称">
				<div class="works_title_number"><span style="color: gray;" ms-html='titleLength'></span>/20</div>
				<div class='uploadWarning hide' ms-visible='warning.title'>请输入20字以内名称</div>
			</div>

            <div class="upload_screen"></div>

			<div class="works_des">
				<div class="suit_level_title" style="width: 78px;">留言说明</div>
				<textarea ms-duplex='uploadInfo.message' placeholder="说出你的困惑，或是希望老师特别辅导的地方"></textarea>
				<div class="works_des_number"><span ms-html='messageLength'></span>/80</div>
				<div class='uploadWarning hide' ms-visible='warning.message' style='margin-left: 55px;'>请输入80字以内留言</div>
			</div>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>

			<div class="content_bottom" style="margin-top: 40px;">
				<div class="content_bottom_provision">
					<input type="checkbox" ms-duplex-checked='submitDisable' value='true'>我已阅读并同意<a href="/aboutUs/firmintro/1#4" target="_blank">作品上传服务条款</a>
				</div>
				<div class="content_bottom_button" ms-css-cursor='submitDisable ? "pointer" : "not-allowed"' ms-css-background="submitDisable ? '#1A9FEB' : 'silver'" ms-click='submit(false)'>
					完成并发布
				</div>
			</div>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>
		</div>

		<div class="bottom_tip">温馨提示：支付并上传作品成功后，老师将于10个工作日内完成课程，请耐心等待，如果有问题请联系客服。</div>
    </div>
@endsection

@section('js')
	<script type="text/javascript">
		require(['lessonComment/directive', 'lessonComment/buyComment/upload'], function (directive, upload) {
			upload.orderID = {{$info -> id}} || null;
			upload.uploadInfo.orderSn = '{{$info -> orderSn}}' || null;
			upload.uploadInfo.teacherId = '{{$info -> teacherId}}' || null;
			upload.mineID = {{$mineID}} || null;
			upload.mineType = '{{\Auth::user() -> type}}' || null;

			upload.$watch('uploadInfo.courseTitle', function(value, oldValue) {
                if (value.length > 20) {
                    upload.uploadInfo.courseTitle = oldValue;
                }
                upload.titleLength = upload.uploadInfo.courseTitle.length;
                upload.warning.title = false;
            });

            upload.$watch('uploadInfo.message', function(value, oldValue) {
                if (value.length > 80) {
                    upload.uploadInfo.message = oldValue;
                }
                upload.messageLength = upload.uploadInfo.message.length;
            });

            avalon.scan();
		});
	</script>
@endsection