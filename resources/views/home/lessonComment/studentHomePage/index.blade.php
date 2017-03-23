@extends('layouts.layoutHome')

@section('title', '学员主页')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/teacherHomepage/index.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonSubject/detail.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">
@endsection

@section('content')
	<div class="teacherHomepage ms-controller" ms-controller="userHomepage">
		<div class="teacherHomepage_crumbs">
			<a href="/">首页</a> >
			<a>学员主页</a>
		</div>

		<div class="teacherHomepage_introduce">
			<div class="teacherHomepage_introduce_header"><img ms-attr-src="userInfo.pic" width="100%" height="100%"></div>
			<div class="teacherHomepage_introduce_name" ms-html="userInfo.username"></div>
			<!-- <div class="studentHomepage_introduce_compant hide" style="margin-top: 92px;" ms-visible="userInfo.type == 0">
				<img src="{{asset('/home/image/lessonComment/studentHomepage/student.png')}}">普通学员
			</div>
			<div class="studentHomepage_introduce_compant hide" style="margin-top: 92px;" ms-visible="userInfo.type == 1">
				<img src="{{asset('/home/image/lessonComment/studentHomepage/teacher.png')}}">教师会员
			</div> -->
			<div class="studentHomepage_introduce_compant" style="margin-top: 92px;color:#AEAFAE">
				{{$school}}
			</div>
			<div class="teacherHomepage_introduce_videoNumber">
				<div class="teacherHomepage_introduce_number_img"><img src="{{asset('/home/image/lessonComment/teacherHomepage/fensiss.png')}}" width="100%" height="100%"></div>
				<div class="teacherHomepage_introduce_number_text">好友</div>
				<div class="teacherHomepage_introduce_number" ms-html="fansNum"></div>
			</div>
			<div class="teacherHomepage_introduce_fansNumber">
				<div class="teacherHomepage_introduce_number_img"><img src="{{asset('/home/image/lessonComment/teacherHomepage/tiwens.png')}}" width="50" height="50" style="margin-left:8px;margin-top:10px;"></div>
				<div class="teacherHomepage_introduce_number_text">问答</div>
				<div class="teacherHomepage_introduce_number">{{$askcount}}</div>
			</div>
			<div class="teacherHomepage_introduce_bottom">
                @if (Auth::check())
                    @if (Auth::user() -> id != $userID)
                        <div id="buttonhover" class="teacherHomepage_introduce_bottom_button" ms-buttonhover="isFollow" ms-click="followUser()"></div>
                    @else
                        <div id="buttonhover" class="teacherHomepage_introduce_bottom_button isFollow" style="background: none !important;"></div>
                    @endif
                @else
                    <a href="/index/login" id="buttonhover" class="teacherHomepage_introduce_bottom_button" ms-buttonhover="isFollow"></a>
                @endif
				<div class="teacherHomepage_introduce_bottom_block" style="margin: 0;">
					<img src="{{asset('/home/image/lessonComment/teacherHomepage/sex.png')}}" width="100%" height="100%">
					<div ms-html="userInfo.sex == 2 ? '女' : '男'"></div>
				</div>
				<div class="teacherHomepage_introduce_bottom_block">
					<img src="{{asset('/home/image/lessonComment/teacherHomepage/collge.png')}}" width="100%" height="100%" style="width: 30px; height: 30px;margin-top:-3px">
					<div>{{$stuMajor ? $stuMajor : '未知'}}</div>
				</div>
				<div class="teacherHomepage_introduce_bottom_block" ms-attr-title="'已加入创课在线' + userInfo.created_at + '天'" style="width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
					<img src="{{asset('/home/image/lessonComment/teacherHomepage/time.png')}}" width="100%" height="100%">
					<div ms-text="'已加入创课在线' + userInfo.created_at + '天'"></div>
				</div>
			</div>
		</div>

		<div class="teacherHomepage_detail">
			<div class="teacherHomepage_detail_tab" style="height: 100px;">
				<div id="tabs" ms-tabstatus="tabStatus" ms-click="changeTabStatus()" value="0">收藏课程</div>
				<div id="tabs" ms-tabstatus="tabStatus" ms-click="changeTabStatus()" value="1">在线问答</div>
			</div>

			<div class="teacherHomepage_detail_special" ms-visible="tabStatus == 0">
				<div class="teacherHomepage_detail_content_title">创客课程</div>
				<div class="teacherHomepage_detail_special_order">
					<span ms-css-color="cord == 0 ? 'rgb(215,12,24)' : 'rgb(102, 102, 102)'" ms-click="getcollertion('created_at',0)">最新</span> -
					<span ms-css-color="cord == 1 ? 'rgb(215,12,24)' : 'rgb(102, 102, 102)'" ms-click="getcollertion('completecount',1)">热门</span>
				</div>
				<div style="width: 100%; height: 300px; line-height: 300px; text-align: center; display: none; font-size: 16px;" ms-visible='collectionMsg'>暂无数据</div>
				<div class="teacherHomepage_detail_video">
					<a target="_blank" class="teacherHomepage_detail_video_block" ms-repeat="collectionInfo" ms-attr-href="'/lessonSubject/detail/' + el.id">
						<div class='img' style='overflow: hidden; position: relative;'>
							<img class='img_big' ms-attr-src="el.coursePic" width='285' height='182' ms-imgbig>
						</div>
						<div class="title" ms-html="el.courseTitle"></div>
						<div class="detail">
							<div class="time"><img src="/home/image/lessonComment/teacherHomepage/classes.png">[--el.classHour--] 课时</div>
							<div class="learned"><img src="/home/image/lessonComment/teacherHomepage/classes.png">[--parseInt(el.completecount) + parseInt(el.courseStudyNum)--] 人学过</div>
						</div>
						<!-- <div class="price" ms-if="el.coursePrice > 0" ms-html="'￥ ' + Math.ceil(el.coursePrice / 100)"></div> -->
						<!-- <div class="price" ms-if="el.coursePrice <= 0" ms-html="'免费课程'"></div> -->
					</a>
				</div>
				<div class="clear"></div>
				<div ms-if="isshowpagecol" class="pagecon_parent" style="margin-top:0px;">
					<div class="pagecon">
						<div id="page_collection"></div>
					</div>
				</div>
				<!-- <div class="spinner " ms-visible="loading">
					<div class="rect1"></div>
					<div class="rect2"></div>
					<div class="rect3"></div>
					<div class="rect4"></div>
					<div class="rect5"></div>
				</div> -->
			</div>

			<div class="teacherHomepage_detail_special hide" ms-visible="tabStatus == 1">
				<div class="teacherHomepage_detail_content_title">提问列表</div>
				<div class="teacherHomepage_detail_special_order">
					<span ms-css-color="ordcolor == 1 ? 'rgb(215,12,24)' : 'rgb(102, 102, 102)'" ms-click="getquestion(0,1)">最新</span> -
					<span ms-css-color="ordcolor == 2 ? 'rgb(215,12,24)' : 'rgb(102, 102, 102)'" ms-click="getquestion(0,2)">热门</span>
				</div>
				<!-- <div style="width: 100%; height: 300px; line-height: 300px; text-align: center; display: none; font-size: 16px;" ms-visible='commentLesson.size() < 1 && !loading'>暂无数据</div> -->

	            <div class="center_right_notice">
	                {{--//问答循环开始--}}
	                <div class="center_right_myask" ms-repeat="questionInfo">
	                    <div style="height:20px;"></div>
	                    <div class="center_right_myask_top">
	                        <!-- <div class="center_right_myask_top_l"><img ms-src="el.pic" alt="" width="60" height="60"></div> -->
	                        <div class="center_right_myask_top_r">
	                            <div class="center_right_myask_top_r_t" ms-html="'来自 '+el.type">来自   绘画</div>
	                            {{--<div class="center_right_myask_top_r_t" ms-if="el.type == 2">来自   高数</div>--}}
	                            {{--未回答--}}
	                            <div class="center_right_myask_top_r_b" ms-if="el.status == 1"><a ms-href="'/community/answer/'+el.id" ms-html="el.qestitle"></a></div>
	                            {{--已回答--}}
	                            <div class="center_right_myask_top_r_b" ms-if="el.status == 2"><a ms-href="'/community/askDetail/'+el.id" ms-html="el.qestitle"></a></div>
	                        </div>
	                    </div>
	                    <div style="height:20px;"></div>
	                    <div class="center_right_myask_bot">
	                        <div class="center_right_myask_bot_r" ms-if="el.status == 1">未回答</div>
	                        <div class="center_right_myask_bot_r" ms-if="el.status == 2">已回答</div>
	                        <div class="center_right_myask_bot_m" ms-html="el.asktime | truncate(12,'.')"></div>
	                        <div class="center_right_myask_bot_l">发布时间</div>
	                    </div>
	                </div>
	                {{--<div class="center_right_myask"></div>--}}
	                {{--//通知循环结束--}}
	                <div ms-visible="noticeMsg" class="warning_msg">暂无数据...</div>
	            </div>
	            <div class="clear"></div>
	            <div ms-if="isshowpage" class="pagecon_parent" style="margin-top:40px;">
	                <div class="pagecon">
	                    <div id="page_question"></div>
	                </div>
	            </div>


				<!-- <div class="spinner " ms-visible="loading">
					<div class="rect1"></div>
					<div class="rect2"></div>
					<div class="rect3"></div>
					<div class="rect4"></div>
					<div class="rect5"></div>
				</div> -->
			</div>

			<div class="teacherHomepage_page" ms-visible="tabStatus == 0 && specialTotal > 6">
				<div class="prev" ms-visible="page.special != 1" ms-click="skip('special', false)"><</div>
				<div class="next" ms-repeat="specialCountNumber" ms-html="el" ms-page="page.special" ms-class="active: el == page.special" ms-click="skip('special', el)"></div>
				<div class="next" ms-visible="page.special <= (specialCount - 4) && specialCount >= 9">...</div>
				<div class="next" ms-html="specialCount" ms-visible="specialCount >= 9 && page.special < (specialCount - 3)" ms-click="skip('special', specialCount)"></div>
				<div class="next" ms-visible="page.special != specialCount && specialCount != 1" ms-click="skip('special', true)">></div>
				<input type="text" ms-visible="specialCount > 1" ms-duplex-number='jump'>
				<button ms-visible="specialCount > 1" ms-click="jumping('special')">跳转</button>
			</div>

			<div class="teacherHomepage_page" ms-visible="tabStatus == 1 && commentTotal > 6">
				<div class="prev" ms-visible="page.comment != 1" ms-click="skip('comment', false)"><</div>
				<div class="next" ms-repeat="commentCountNumber" ms-html="el" ms-page="page.comment" ms-class="active: el == page.comment" ms-click="skip('comment', el)"></div>
				<div class="next" ms-visible="page.comment <= (commentCount - 4) && commentCount >= 9">...</div>
				<div class="next" ms-html="commentCount" ms-visible="commentCount >= 9 && page.comment < (commentCount - 3)"></div>
				<div class="next" ms-visible="page.comment != commentCount && commentCount != 1" ms-click="skip('comment', true)">></div>
				<input type="text" ms-visible="commentCount > 1" ms-duplex-number='jump'>
				<button ms-visible="commentCount > 1" ms-click="jumping('comment')">跳转</button>
			</div>
		</div>

		<!-- 遮罩层 -->
        <div class="shadow hide" ms-popup="popUp" value="close"></div>
        <!-- 删除评论弹出层 -->
        <div class="delete_comment hide" ms-popup="popUp" value="unfollow">
            <div class="top">
                <span>确认取消关注？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch(false, true)">确定</span>
            </div>
        </div>

		<div style="clear: both; height: 40px;"></div>
	</div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
	<script type="text/javascript">
		require(['lessonComment/directive', 'lessonComment/studentHomepage/index'], function (directive, user) {
			user.userID = {{$userID}} || null;
			user.mineID = {{$mineID}} || null;
			user.mineName = '{{$mineName}}' || null;
			user.videoUrl = '/lessonComment/getVideo';

			//	获取用户信息
			user.getData('/lessonComment/getStuInfo/' + user.userID, 'userInfo');
			//	获取好友总数
			user.getData('/lessonComment/getCount', 'fansNum', {table: 'friends', data: {toUserId: user.userID}}, 'POST');
			//	获取专题课程
			//user.getData(user.videoUrl, 'specialLesson', {userid: user.userID, order: user.order.special, type: 0, page: user.page.special}, 'POST');
			//	获取专题课程总数
			//user.getData('/lessonComment/getVideoCount', 'specialCount', {userid: user.userID, type: 0}, 'POST');
			//	获取辅导课程
			//user.getData(user.videoUrl, 'commentLesson', {userid: user.userID, order: user.order.comment, type: 1, page: user.page.comment}, 'POST');
			//	获取辅导课程总数
			//user.getData('/lessonComment/getVideoCount', 'commentCount', {userid: user.userID, type: 1}, 'POST');
			//	查看是否关注
			user.mineID && user.getData('/lessonComment/getFirst', 'isFollow', {table: 'friends', action: 1, data: {fromUserId: user.mineID, toUserId: user.userID}}, 'POST');

			//获取收藏课程
			user.getcollertion('created_at',0);
            //获取问答
            user.getquestion(0,1);

            avalon.scan();
		});
	</script>
@endsection