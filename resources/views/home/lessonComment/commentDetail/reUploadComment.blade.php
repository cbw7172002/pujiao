@extends('layouts.layoutHome')

@section('title', '上传视频')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/commentDetail/uploadComment.css')}}">
@endsection

@section('content')
	<div class="uploadComment ms-controller" ms-controller="uploadController">
		<div class="crumbs">
			<a href="/">首页</a> >
			<a href="/community">名师主页</a> >
			<a>上传视频</a>
		</div>

		<div class="uploadComment_content">
			<div style="clear: both; height: 75px;"></div>

			<div id="fileDiv" class="fileButton"></div>
			<input type="text" value="" class="fileButton" id="md5container">

            <div class="upload_screen"></div>

			<div class="add_video">
				<div class="add_video_top">
					<div>添加视频</div>
					<div ms-slectfile='file'>本地上传</div>
					<div>请上传不超过1GB大小的视频文件</div>
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

            <div class="upload_screen"></div>

			<div class="score">
				<div class="tip">名师评分</div>
				<div class="bot" name="a">
					<div class="left">创课想法</div>
					<span class="star no-light" ms-class="light : uploadInfo.aNum >= 1" ms-click="addScore('a',1);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.aNum >= 2" ms-click="addScore('a',2);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.aNum >= 3" ms-click="addScore('a',3);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.aNum >= 4" ms-click="addScore('a',4);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.aNum >= 5" ms-click="addScore('a',5);" onclick="turnLight(this);"></span>
					<div class="right" ms-text="scoreStyle[uploadInfo.aNum - 1]"></div>
				</div>
				<div class="bot" name="b">
					<div class="left">内容设计</div>
					<span class="star no-light" ms-class="light : uploadInfo.bNum >= 1" ms-click="addScore('b',1);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.bNum >= 2" ms-click="addScore('b',2);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.bNum >= 3" ms-click="addScore('b',3);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.bNum >= 4" ms-click="addScore('b',4);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.bNum >= 5" ms-click="addScore('b',5);" onclick="turnLight(this);"></span>
					<div class="right" ms-text="scoreStyle[uploadInfo.bNum - 1]"></div>
				</div>
				<div class="bot" name="c">
					<div class="left">学习方式</div>
					<span class="star no-light" ms-class="light : uploadInfo.cNum >= 1" ms-click="addScore('c',1);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.cNum >= 2" ms-click="addScore('c',2);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.cNum >= 3" ms-click="addScore('c',3);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.cNum >= 4" ms-click="addScore('c',4);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.cNum >= 5" ms-click="addScore('c',5);" onclick="turnLight(this);"></span>
					<div class="right" ms-text="scoreStyle[uploadInfo.cNum - 1]"></div>
				</div>
				<div class="bot" name="d">
					<div class="left">配套器具</div>
					<span class="star no-light" ms-class="light : uploadInfo.dNum >= 1" ms-click="addScore('d',1);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.dNum >= 2" ms-click="addScore('d',2);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.dNum >= 3" ms-click="addScore('d',3);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.dNum >= 4" ms-click="addScore('d',4);" onclick="turnLight(this);"></span>
					<span class="star no-light" ms-class="light : uploadInfo.dNum >= 5" ms-click="addScore('d',5);" onclick="turnLight(this);"></span>
					<div class="right" ms-text="scoreStyle[uploadInfo.dNum - 1]"></div>
				</div>
				<div class="scoreWarning" ms-if="scoreWarningOption">请完成所有评价项目</div>
			</div>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>

			<div class="content_bottom">
				<div class="content_bottom_provision">
					<input type="checkbox" ms-duplex-checked='submitDisable' value='true'>我已阅读并同意<a target="_blank" href="/aboutUs/firmintro/1#4">作品上传服务条款</a>
				</div>
				<div class="content_bottom_button" ms-css-cursor='submitDisable ? "pointer" : "not-allowed"' ms-css-background="submitDisable ? '#1A9FEB' : 'silver'" ms-click='submit("reComment")'>
					完成并发布
				</div>
			</div>

            <div class="upload_screen" style="height: 30px;"></div>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>

            <div class="upload_screen"></div>
		</div>

		<div style="clear: both; height: 100px;"></div>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		require(['lessonComment/directive', 'lessonComment/buyComment/upload'], function (directive, upload) {
			upload.commentID = {{$info -> id}} || null;
			upload.selectedLevel = '{{$info -> suitlevel}}'.split(/,/);
			upload.messageID = '{{$messageID}}' || null;
			upload.mineID = {{$mineID}} || null;
			upload.uploadInfo.aNum = '{{$aNum}}';
			upload.uploadInfo.bNum = '{{$bNum}}';
			upload.uploadInfo.cNum = '{{$cNum}}';
			upload.uploadInfo.dNum = '{{$dNum}}';
			console.log(upload.uploadInfo);
			avalon.directive('selectedlevel', {
				update: function(value) {
					var element = avalon(this.element), vmodel = this.vmodels[1];
					for (var i = 0; i < vmodel.selectedLevel.size(); i++) {
						element.attr('value') == vmodel.selectedLevel[i] && element.addClass('checked');
					};
				}
			});

            avalon.scan();
		});
	</script>
	<script>
		function turnLight(obj){
			$(obj).nextAll('span').addClass('no-light').removeClass('light');
			$(obj).prevAll('span').addClass('light');
			$(obj).addClass('light');
		}
	</script>
@endsection