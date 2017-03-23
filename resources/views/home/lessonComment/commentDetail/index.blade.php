@extends('layouts.layoutHome')
@section('title', '辅导课程')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/commentDetail/index.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonSubject/detail.css') }}">
@endsection

@section('content')
	<div class="commentDetail ms-controller" ms-controller="commentController">
		<div class="crumbs">
			<a href="/">首页</a> >
            <a href="/lessonSubject/list/2">辅导课程</a> >
			<a>课程详情</a>
		</div>

		<div class="current_video">
			<div class="video_block">
                <div class="overtime" style="background: black; position: absolute; z-index: 999; float: left;" ms-visible="!overtime && playLoading">
                    <div class="spinner">
                        <div class="spinner-container container1">
                            <div class="circle1"></div>
                            <div class="circle2"></div>
                            <div class="circle3"></div>
                            <div class="circle4"></div>
                        </div>
                        <div class="spinner-container container2">
                            <div class="circle1"></div>
                            <div class="circle2"></div>
                            <div class="circle3"></div>
                            <div class="circle4"></div>
                        </div>
                        <div class="spinner-container container3">
                            <div class="circle1"></div>
                            <div class="circle2"></div>
                            <div class="circle3"></div>
                            <div class="circle4"></div>
                        </div>
                    </div>
                </div>
                <div class="videobox" id="mediaplayer" ms-visible="!overtime && !playLoading"></div>
				<div class="overtime hide" ms-visible="overtime">
					<div style="clear: both; height: 100px;"></div>
					<div class="overtime_detail">
						<img class="palyinfo_detail_img" style="margin-top: 7px;" src="/home/image/lessonComment/commentDetail/download_warning.png">
						<div class="palyinfo_detail_text">尊敬的用户：</div>
						<div class="palyinfo_detail_text">试看已结束，请购买观看完整课程。</div>
					</div>
                    @if (\Auth::check() && \Auth::user() -> type != 3)
    					<a class="comment_button" style="margin: 30px auto 0; float: none; background: #1588E5; color: white; cursor: pointer; display: block;" ms-click="popUpSwitch('buyLesson')">立即购买</a>
                    @else
                        <a href="/index/login" class="overtime_tologin"><img src="{{asset('/home/image/lessonComment/commentDetail/gologin.png')}}" width="100%" height="100%"></a>
                    @endif
				</div>
			</div>
			<div class="video_bar">
				<div class="video_bar_title" ms-html="videoType ? '创课视频' : '辅导视频'"></div>
				<div class="video_bar_title hide" ms-visible="!bought && !videoType && teacherInfo.extra != '免费课程'" ms-click="popUpSwitch('buyLesson')" style="color: red; font-weight: bold; cursor: pointer;" ms-html="'￥' + Math.ceil(teacherInfo.extra / 100)"></div>
				<div class="video_bar_title hide" ms-visible="!bought && !videoType && teacherInfo.extra == '免费课程'" style="color: red; font-weight: bold; cursor: pointer;" ms-html="teacherInfo.extra"></div>
                @if (\Auth::check() && \Auth::user() -> type != 3)
                    <div class="video_bar_icon hide" ms-visible="bought || teacherInfo.extra == '免费课程'" ms-click="popUpSwitch('feedback')">
                        <img src="{{asset('/home/image/lessonComment/commentDetail/editor.png')}}">反馈
                    </div>
                    <div class="video_bar_icon hide" ms-visible="!isFollow" ms-click="followCourse()"><img src="{{asset('/home/image/lessonComment/commentDetail/collection.png')}}">收藏</div>
                    <div class="video_bar_icon hide" ms-visible="isFollow" ms-click="followCourse()"><img src="{{asset('/home/image/lessonComment/commentDetail/collection_active.png')}}">已收藏</div>
                @else
                    <a href="/index/login" class="video_bar_icon"><img src="{{asset('/home/image/lessonComment/commentDetail/editor.png')}}">反馈</a>
                    <a href="/index/login" class="video_bar_icon"><img src="{{asset('/home/image/lessonComment/commentDetail/collection.png')}}">收藏</a>
                @endif
				<div id="comment_anchor" class="video_bar_icon" ms-click="changeVideo()"><img src="{{asset('/home/image/lessonComment/commentDetail/exchang.png')}}">切换</div>
			</div>
		</div>


		<div class="switch_video" ms-click="changeVideo()">
			<img ms-attr-src="videoType ? teacherInfo.coursePic : studentInfo.coursePic" width="100%" height="100%">
            <div class="playButton">▲</div>
		</div>


		<div class="palyinfo">
			<div class="title">创课信息</div>
			<div class="palyinfo_detail">
				<a ms-attr-href="/lessonComment/student/[--studentInfo.userId--]" class="palyinfo_detail_img"><img ms-attr-src="studentInfo.pic" width="100%" height="100%"></a>
				<a ms-attr-href="/lessonComment/student/[--studentInfo.userId--]" class="palyinfo_detail_text" ms-html="studentInfo.username"></a>
				<div class="palyinfo_detail_time" ms-html="studentInfo.created_at"></div>
			</div>
			<div class="palyinfo_detail_title hide" ms-visible="studentInfo.extra" ms-html="'曲目： ' + studentInfo.extra"></div>
			<div class="palyinfo_detail_description hide" ms-class="fold: !descriptionOpen" ms-visible="studentInfo.message" ms-html="'留言： ' + studentInfo.message"></div>
            <div class="open hide" ms-if="studentInfo.message" ms-visible="!descriptionOpen" ms-click="descriptionSwitch('descriptionOpen', true)" ms-attr-title="studentInfo.message">展开>>></div>
			<div class="open hide" ms-visible="descriptionOpen" ms-click="descriptionSwitch('descriptionOpen', false)"><<<收起</div>

			<div style="clear: both;"></div>

			<div class="title">辅导名师</div>
			<div class="palyinfo_detail">
				<a ms-attr-href="/lessonComment/teacher/[--teacherInfo.teacherId--]" class="palyinfo_detail_img"><img ms-attr-src="teacherInfo.pic" width="100%" height="100%"></a>
				<a ms-attr-href="/lessonComment/teacher/[--teacherInfo.teacherId--]" class="palyinfo_detail_text" ms-html="teacherInfo.username"></a>
				<div class="palyinfo_detail_time" ms-html="teacherInfo.created_at"></div>
			</div>
            @if (\Auth::check() && \Auth::user() -> type != 3)
                 <a ms-attr-href="/lessonComment/teacher/[--teacherInfo.teacherId--]" class="palyinfo_button">我也要请老师辅导</a>
            @else 
			     <a href="/index/login" class="palyinfo_button">我也要请老师辅导</a>
            @endif
		</div>
        <div class="score">
            <div class="tip">名师评分</div>
            <div class="bot">
                <div class="left">创课想法</div>
                <span class="star" ms-if="1 <= aNum"></span>
                <span class="star" ms-if="2 <= aNum"></span>
                <span class="star" ms-if="3 <= aNum"></span>
                <span class="star" ms-if="4 <= aNum"></span>
                <span class="star" ms-if="5 <= aNum"></span>
                <div class="right" ms-text="scoreStyle[aNum - 1]"></div>
            </div>
            <div class="bot">
                <div class="left">内容设计</div>
                <span class="star" ms-if="1 <= bNum"></span>
                <span class="star" ms-if="2 <= bNum"></span>
                <span class="star" ms-if="3 <= bNum"></span>
                <span class="star" ms-if="4 <= bNum"></span>
                <span class="star" ms-if="5 <= bNum"></span>
                <div class="right" ms-text="scoreStyle[bNum - 1]"></div>
            </div>
            <div class="bot">
                <div class="left">学习方式</div>
                <span class="star" ms-if="1 <= cNum"></span>
                <span class="star" ms-if="2 <= cNum"></span>
                <span class="star" ms-if="3 <= cNum"></span>
                <span class="star" ms-if="4 <= cNum"></span>
                <span class="star" ms-if="5 <= cNum"></span>
                <div class="right" ms-text="scoreStyle[cNum - 1]"></div>
            </div>
            <div class="bot">
                <div class="left">配套器具</div>
                <span class="star" ms-if="1 <= dNum"></span>
                <span class="star" ms-if="2 <= dNum"></span>
                <span class="star" ms-if="3 <= dNum"></span>
                <span class="star" ms-if="4 <= dNum"></span>
                <span class="star" ms-if="5 <= dNum"></span>
                <div class="right" ms-text="scoreStyle[dNum - 1]"></div>
            </div>
        </div>

		<div class="comment">
			<div class="title" style="border: none; margin-top: 10px; text-indent: 0px; font-size: 18px;">评论</div>

            @if (\Auth::check() && \Auth::user() -> type != 3)
                 <textarea ms-duplex="replayInfo.name" class="comment_textarea"></textarea>
                 <div class="comment_button" id="replayButton" ms-on-mouseout="descriptionSwitch('replyWarning', false)" ms-click="submitComment()">发布</div>
                 <div class="teacherHomepage_detail_content_applyTip" ms-visible="replyWarning">请输入评论内容</div>
            @else
			     <div class="comment_textarea comment_textarea_nologin"><a href="/index/login" style="color: #3BA3FE;">请登录后发表评论</a></div>
			     <div class="comment_button" style="background: none;"></div>
            @endif

			<div style="clear: both; height: 60px;"></div>

			<div class="comment_list" ms-repeat="commentlist">
				<div style="display: table-cell;">
                    <a ms-attr-href="/lessonComment/[--el.type == 2 ? 'teacher' : 'student'--]/[--el.fromUserId--]">
					   <img ms-attr-src="el.pic">
                    </a>
                    <a ms-attr-href="/lessonComment/[--el.type == 2 ? 'teacher' : 'student'--]/[--el.fromUserId--]" class="username" ms-html="el.username"></a>
					<div class="time" ms-html="el.created_at"></div>

                    <div class="content" ms-if="!el.parentId" ms-html="el.commentContent"></div>
                    <div class="content" ms-if="el.parentId">
                        <a style="color: #37A6FF;" ms-attr-href="/lessonComment/[--el.toUserType == 2 ? 'teacher' : 'student'--]/[--el.toUserId--]" ms-html="'@ ' + el.toUserName + '： '"></a>
                        [--el.commentContent--]
                    </div>

                    @if (Auth::check())
                        <a class="bottom" href="#comment_anchor" ms-if="el.fromUserId != {{$mine['id']}}" ms-click="replyComment(el)">回复</a>
                        <div class="bottom" ms-if="el.fromUserId == {{$mine['id']}}" ms-click="deleteComment($index)">删除</div>
                        <div class="bottom" ms-class="islike: el.isLike" ms-click="clickLike(el)">点赞( [--el.likeNum || 0--] )</div>
                    @else
                        <a class="bottom" href="/index/login">点赞( [--el.likeNum || 0--] )</a>
                    @endif
				</div>
			</div>
		</div>


		<div class="recommend">
			<div class="title">最新辅导推荐</div>
			<a class="recommend_list" ms-repeat="recommendlist" ms-attr-href="/lessonComment/detail/[--el.id--]" style='text-decoration: none;'>
				<div class="recommend_title" ms-html="el.courseTitle"></div>
				<div class="recommend_detail">
					<div class="recommend_detail_teacher" ms-html="'讲师：' + el.teachername"></div>
					<div class="recommend_detail_learned"><img src="{{asset('/home/image/lessonComment/commentDetail/study.png')}}">[--el.coursePlayView--]人学过</div>
				</div>
				<div class="recommend_price" ms-visible="el.coursePrice > 0" ms-html="'￥ ' + Math.ceil(el.coursePrice / 100)"></div>
				<div class="recommend_price" ms-visible="el.coursePrice <= 0" ms-html="'免费课程'"></div>
			</a>
            <div style="width: 100%; height: 200px; line-height: 200px; text-align: center; display: none;" ms-visible="recommendlist.size() < 1">暂无数据</div>
		</div>


		<!-- 遮罩层 -->
        <div class="shadow hide" ms-popup="popUp" value="close"></div>

        <!-- 购买课程弹出层 -->
        <div class="buy_course hide" style="top: 500px;" ms-popup="popUp" value="buyLesson">
            <div class="buy_course_top">
                <span class="left">购买课程</span>
                <span class="right" ms-click="popUpSwitch(false)"></span>
            </div>
            <div class="buy_course_center">
                <div class="top" ms-html="'课程名称：' + studentInfo.extra"></div>
                <div class="center">课程价格：<span ms-html="'￥ ' + Math.ceil(teacherInfo.extra / 100) + ' 元'"></span></div>
                <div class="bot">
                    <div>支付方式：</div>
                    <div class="aliPay"><input type="radio" ms-duplex-number='payType' value='0'/><span></span></div>
                    <div class="weChat"><input type="radio" ms-duplex-number='payType' value="1"/><span></span></div>
                </div>
                <span style="color: red; display: block; margin: 5px auto; padding-left: 112px;" ms-visible="payWarning">请选择支付方式</span>
                <div class="clear"></div>
                <div class="pay_btn" ms-click="pay()">立即支付</div>
            </div>
        </div>

        <!-- 支付成功弹出层 -->
        <div class="pay_success hide" style="top: 500px;" ms-popup="popUp" value="buySuccess">
            <div class="pay_success_top">
                <span class="left">支付成功</span>
                <span class="right" ms-click="popUpSwitch(false)"></span>
            </div>
            <div class="pay_success_center">
                <div class="top"></div>
                <div class="center">支付成功 !</div>
                <div class="bot">您可以在个人中心查看订单详情</div>
                <div class="study_btn" ms-click="popUpSwitch(false)">开始学习</div>
            </div>
        </div>

		<!-- 反馈意见弹出层 -->
		<div class="feedback hide" ms-popup="popUp" value="feedback">
			<div class="feedback_top">
				<span class="left">意见反馈</span>
				<span class="right" ms-click="popUpSwitch(false, 'feedback')"></span>
			</div>
			<div class="feedback_center">
				<div class="top">
					<div class="question"><span>1</span><span class="last">选择问题类型：</span><span style="color: red" ms-visible="feedBackWarning.type">请选择问题类型</span></span>
					</div>
					<div class="choose">
						<input type="radio" ms-duplex-string="feedBack.type" value="内容无关"/><span>内容无关</span>
						<input type="radio" ms-duplex-string="feedBack.type" value="播放不了"/><span>播放不了</span>
						<input type="radio" ms-duplex-string="feedBack.type" value="其他问题"/><span>其他问题</span>
					</div>
				</div>
				<div class="clear"></div>
				<div class="center">
					<div class="question"><span>2</span><span class="last">填写问题描述：</span><span style="color: red" ms-visible="feedBackWarning.content">请填写80字数以内问题描述</span></div>
					<div class="content">
						<div class="textarea">
							<textarea name="" ms-duplex="feedBack.content" maxlength="80" placeholder="详细描述一些你遇到的问题或建议"></textarea>
						</div>
						<div><span ms-text="backContentLength"></span>/80字&nbsp;&nbsp;</div>
					</div>
				</div>
				<div class="feedback_center_last">
					<div class="question"><span>3</span><span class="last">留下联系方式：</span><span style="color: red" ms-visible="feedBackWarning.tel">请填写正确的联系方式</span></span>
					</div>
					<div class="contact_way">
						<input type="text" class="method" ms-duplex="feedBack.tel" placeholder="QQ/Email/手机号"/>
					</div>
				</div>
				<div class="clear"></div>
				<div class="feedback_btn" ms-click="submitFeedback()">提交反馈</div>
			</div>
		</div>

		<!-- 反馈成功弹出层 -->
        <div class="feedback_success hide" ms-popup="popUp" value="feedbackSuccess">
            <div class="feedback_success_top">
                <span class="left">反馈成功</span>
                <span class="right" ms-click="popUpSwitch(false, 'feedback')"></span>
            </div>
            <div class="feedback_success_center">
                <div class="top"></div>
                <div class="center">感谢您的反馈 !</div>
                <div class="bot">我们会尽快为您解决问题</div>
                <div class="keep_feedback" ms-click="popUpSwitch(false, 'feedbackSuccess')">继续反馈</div>
                <div class="close_feedback" ms-click="popUpSwitch(false, 'feedback')">关闭</div>
            </div>
        </div>

        <!-- 删除评论弹出层 -->
        <div class="delete_comment hide" ms-popup="popUp" value="deleteComment">
            <div class="top">
                <span>确认删除该评论？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch(false, 'delComment')">确定</span>
            </div>
        </div>

        <!--  取消收藏弹出层 -->
        <div class="delete_comment hide" ms-popup="popUp" value="unfollow">
            <div class="top">
                <span>确认取消收藏？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch(false, 'unfollow')">确定</span>
            </div>
        </div>

		<div style="clear: both; height: 150px;"></div>
	</div>
@endsection

@section('js')
    <script src="{{asset('home/jplayer/html5media.min.js')}}"></script>
    <!--[if gt IE 9]>
	<script type="text/javascript" src="{{asset('home/jplayer/jwplayer.html5.js')}}" type="text/javascript"></script>
    <![endif]-->
    <script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>
	<script type="text/javascript">
        require(['lessonComment/directive', 'lessonComment/commentDetail/index'], function (directive, comment) {
            comment.commentID = {{$commentID}} || null;
            comment.mineID = {{$mine['id']}} || null;
            comment.mineUsername = '{{$mine["username"]}}' || null;
            comment.mineType = parseInt('{{$mine["type"]}}' || null);
			comment.minePic = '{{$mine["pic"]}}' || null;

			comment.orderType = {{$orderType}} || null;
            comment.bought = Boolean({{$bought}} || null);
            if (comment.mineType == 2) {
				comment.bought = true;
			}
            console.log("是否购买： " + comment.bought);
            console.log("订单类型： " + comment.orderType);

            //  获取辅导信息
            comment.getData('/lessonComment/getDetailInfo/'+comment.commentID+'/1', 'teacherInfo');
            //  查看是否收藏
            comment.getData('/lessonComment/getFirst', 'isFollow', {table: 'collection', action: 1, data: {courseId: comment.commentID, userId: comment.mineID, type: 1}}, 'POST');
            //  最新辅导推荐
            comment.getData('/lessonComment/getNewComment', 'recommendlist');
            //  获取评论
            comment.getData('/lessonComment/getApplyComment/'+comment.commentID, 'commentlist');
            //  观看数递增
            comment.mineID && comment.getData('/lessonComment/videoIncrement', 'videoIncrement', {table: 'commentcourse', condition: {id: comment.commentID}, field: 'courseView', action: 1}, 'POST');

            comment.$watch('replayInfo.name', function(value, oldVlaue) {
                if ((comment.replayInfo.lengths > 0 && value[comment.replayInfo.lengths - 1] != ' ') || value.length < comment.replayInfo.lengths) {
                    comment.replayInfo = {};
                    comment.replayInfo.name = '';
                    comment.replayInfo.lengths = 0;
                    return;
                }
            });

            comment.$watch('feedBack.*', function(value, oldValue) {
                if (value != oldValue) {
                    comment.feedBackWarning = {type: false, content: false, tel: false}
                }
            });

			comment.$watch('feedBack.content', function(value, oldValue) {
				if (value.length <= 80) {
					comment.backContentLength = value.length;
				} else {
					comment.feedBack.content = oldValue;
					comment.backContentLength = oldValue.length;
				}
			});

            comment.$watch('payType', function(value) {
                if ('number' === typeof value) {
                    comment.payWarning = false
                }
            });

            avalon.scan();
		});
	</script>
@endsection