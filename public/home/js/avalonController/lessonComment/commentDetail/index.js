define([], function() {

	var comment = avalon.define({
		$id: 'commentController',
		studentInfo: [],
		teacherInfo: [],
		bought: false,
		descriptionOpen: false,
		aNum: '', bNum: '', cNum: '', dNum: '',
		scoreStyle : ['加油','加油','一般','优秀','完美'],
		descriptionSwitch: function(model, value) {
			comment[model] = value;
		},
		recommendlist: [],
		commentlist: [],
		isFollow: false,
        buying: false,
		followCourse: function() {
			if (comment.isFollow) {
				comment.popUp = 'unfollow'
			} else {
				comment.getData('/lessonComment/getFirst', 'isFollow', {table: 'collection', action: 2, data: {courseId: comment.commentID, userId: comment.mineID, type: 1}}, 'POST', function(response) {
					comment.getData('/lessonComment/videoIncrement', 'videoIncrement', {table: 'commentcourse', condition: {id: comment.commentID}, field: 'courseFav', action: 1}, 'POST');
				});
			}
		},
		popUp: false,
		popUpSwitch: function(value, action) {
			comment.popUp = value;
			switch (action) {
				case 'unfollow':
					comment.getData('/lessonComment/getFirst', 'isFollow', {table: 'collection', action: 3, data: {courseId: comment.commentID, userId: comment.mineID, type: 1}}, 'POST', function(response) {
						comment.getData('/lessonComment/videoIncrement', 'videoIncrement', {table: 'commentcourse', condition: {id: comment.commentID}, field: 'courseFav', action: 0}, 'POST');
					});
					break;
				case 'delComment':
					comment.getData('/lessonComment/getFirst', 'likes', {table: 'applycoursecomment', action: 3, data: {id: comment.commentlist[comment.deleteIndex].id, fromUserId: comment.mineID}}, 'POST', function(response) {
						response && comment.commentlist.removeAt(comment.deleteIndex);
						comment.deleteIndex = null;
					});
					break;
				case 'feedback':
					comment.feedBack = {type: '', content: '', tel: ''};
					comment.feedBackWarning = {type: false, content: false, tel: false};
					break;
				case 'feedbackSuccess':
					comment.feedBack = {type: '', content: '', tel: ''};
					comment.feedBackWarning = {type: false, content: false, tel: false};
					comment.popUpSwitch('feedback');
					break;
			}
		},
		getData: function(url, model, data, method, callback) {
			$.ajax({
				type: method || 'GET',
				url: url,
				data: data || {},
				dataType: "json",
				success: function(response) {
					if (model == 'teacherInfo') {
						if (parseInt(response.data.extra) > 0) {
							if (response.data.courseDiscount > 0) {
								response.data.extra = Math.ceil(response.data.extra * (response.data.courseDiscount / 10000));
							}
						} else {
							response.data.extra = '免费课程';
						}
					}
					if (model == 'likes' || model == 'feedBack' || model == 'selectCourseView') {
						callback(response.type);
						return;
					}
					if (model == 'submitComment') {
						callback && callback(response.data);
						return;
					}
					if (response.type) {
						comment[model] = response.data;
						model == 'orderInfo' && callback(response);
						(model == 'isFollow' && callback) && callback(response.data);
					}
					if (model == 'teacherInfo') {
						comment.getData('/lessonComment/getDetailInfo/'+ response.data.orderSn +'/0', 'studentInfo');
						comment.aNum = response.data.aNum;
						comment.bNum = response.data.bNum;
						comment.cNum = response.data.cNum;
						comment.dNum = response.data.dNum;
					}
					model == 'studentInfo' && comment.setVideo();
				},
				error: function(error) {
                    comment.buying = false;
                }
			});
		},
		overtime: false,
		thePlayer: {},
		videoType: true,
		videoTime: {videoOne: 0, videoTow: 0},
		playFlag: false,
        playLoading: false,
		setVideo: function(callback) {
			var model = comment.videoType ? 'studentInfo' : 'teacherInfo';
			var list = [];
            console.log(comment[model].courseHighPath);
            console.log(comment[model].courseMediumPath);
            console.log(comment[model].courseLowPath);
			comment[model].courseHighPath && list.push({
				label: "超清",
				file: comment[model].courseHighPath,
				height: 720,
				width: 1280,
				type: "mp4"
			});
			comment[model].courseMediumPath && list.push({
				default: true,
				label: "高清",
				file: comment[model].courseMediumPath,
				height: 360,
				width: 640,
				type: "mp4"
			});
			comment[model].courseLowPath && list.push({
				label: "标清",
				file: comment[model].courseLowPath,
				height: 180,
				width: 320,
				type: "mp4"
			});
			comment.thePlayer = jwplayer('mediaplayer').setup({
                flashplayer: 'jwplayer/jwplayer.flash.swf',
                playlist: [{
                    image: comment[model].coursePic,
                    sources: list
                }],
                id: 'playerID',
                width: '800',
                height: '450',
                aspectratio: '16:9',
                type: "mp4",
                smoothing: false,
                events: {
                    onPlay: function() {
                        setTimeout(function() {
                            comment.playLoading = false;
                        }, 1000);
                    }
                }
            });
            comment.thePlayer.onSeek(function() {
                comment.playLoading = true;
            });
            (!comment.videoType && !comment.bought && comment.teacherInfo.extra != '免费课程') && comment.thePlayer.onTime(function() {
	            if (comment.thePlayer.getPosition() >= 60) {
	                comment.thePlayer.play(false);
	                comment.thePlayer.remove();
	                comment.overtime = true;
	            }
	        });
			if (comment.orderType == 2 && !comment.videoType) {
				var data = {courseId: comment.commentID, courseType: 1, userId: comment.mineID, chapterId: comment.commentID};
				comment.getData('/lessonComment/getFirst', 'selectCourseView', {table: 'courseview', action: 1, data: data}, 'POST', function(response) {
					if (!response) {
						comment.thePlayer.onTime(function() {
							if (comment.thePlayer.getPosition() >= 60 && !comment.playFlag) {
								comment.playFlag = true;
								comment.getData('/lessonComment/getFirst', 'courseview', {table: 'courseview', action: 2, create_time: 1, data: data}, 'POST');
							}
						});
					}
				});
			}
			'function' === typeof callback && callback();
		},
		changeVideo: function() {
			comment.overtime = false;
			comment.thePlayer.play(false);
			comment.videoType ? comment.videoTime.videoOne = comment.thePlayer.getPosition() : comment.videoTime.videoTow = comment.thePlayer.getPosition();
			comment.videoType = !comment.videoType;
			comment.setVideo(function () {
				comment.videoType ? comment.thePlayer.seek(comment.videoTime.videoOne) : comment.thePlayer.seek(comment.videoTime.videoTow);
            	comment.thePlayer.play(true);
			});
		},
		clickLike: function(el) {
			el.isLike || comment.getData('/lessonComment/likesComment', 'likes', {id: el.id, likeUser: el.likeUser}, 'POST', function(response) {
				el.isLike = response;
				el.isLike && ++el.likeNum;
			});
		},
		deleteIndex: null,
		deleteComment: function(index) {
			comment.deleteIndex = index;
			comment.popUp = 'deleteComment'
		},
		replayInfo: {name: '', lengths: 0},
		replyWarning: false,
		replyComment: function(el) {
			comment.replayInfo = {name: '@'+ el.username +'： ', parentId: el.id, toUserId: el.fromUserId, toUserName: el.username, toUserType: el.type, commentContent: el.commentContent};
			comment.replayInfo.lengths = comment.replayInfo.name.length;
		},
		submitComment: function() {
			if (comment.replayInfo.name.length <= comment.replayInfo.lengths) {
				comment.replyWarning = true;
				return false;
			}
			var data = {courseId: comment.commentID, fromUserId: comment.mineID, commentContent: comment.replayInfo.name};
			if (comment.replayInfo.parentId && comment.replayInfo.toUserId) {
				data.parentId = comment.replayInfo.parentId;
				data.toUserId = comment.replayInfo.toUserId;
				data.commentContent = data.commentContent.split(/@*： /);
				data.commentContent.shift();
				data.commentContent = data.commentContent.join('');
			}
			comment.getData('/lessonComment/getFirst', 'submitComment', {table: 'applycoursecomment', action: 2, data: data}, 'POST', function(response) {
				comment.commentlist.unshift({
					commentContent: data.commentContent,
					created_at: '1秒前',
					id: response,
					isLike: false,
					likeNum: 0,
					likeUser: [],
					parentId: data.parentId || null,
					toUserId: data.toUserId || null,
					fromUserId: comment.mineID,
					pic: comment.minePic,
					type: comment.mineType,
					username: comment.mineUsername,
					toUserName: comment.replayInfo.toUserName,
					toUserType: comment.replayInfo.toUserType
				});
				if (comment.replayInfo.toUserName || comment.studentInfo.username != comment.mineUsername) {
					data = {
						type: comment.replayInfo.toUserName ? 5 : 6,
						username: comment.replayInfo.toUserName || comment.studentInfo.username,
						actionId: comment.commentID,
						fromUsername: comment.mineUsername,
						toUsername: comment.replayInfo.toUserName || comment.studentInfo.username,
						content: comment.replayInfo.commentContent || comment.studentInfo.extra,
						courseType: 1
					};
					comment.getData('/lessonComment/getFirst', 'submitComment', {table: 'usermessage', action: 2, data: data}, 'POST');
				}
				delete data;
				comment.replayInfo = {};
				comment.replayInfo.name = '';
				comment.replayInfo.lengths = 0;
			});
		},
		feedBack: {type: '', content: '', tel: ''},
		feedBackWarning: {type: false, content: false, tel: false},
		backContentLength: 0,
		submitFeedback: function() {
			for (var i in comment.feedBack) {
				if (comment.feedBack[i].length <= 0) {
					comment.feedBackWarning[i] = true;
					return false;
				}
			}
			if (comment.feedBack.content.length > 80) {
				comment.feedBackWarning.content = true;
				return false;
			}
			if (!comment.feedBack.tel.match(/1(3|5|7|8){1}[0-9]{9}/) && !comment.feedBack.tel.match(/[0-9a-zA-Z]{2,}@[0-9a-zA-Z]{2,}\.(com|net|cn){1}/) && !comment.feedBack.tel.match(/[0-9]{5,}/)) {
				comment.feedBackWarning.tel = true;
				return false;
			}
			comment.getData('/lessonComment/getFirst', 'feedBack', {table: 'coursefeedback', action: 2, data: {courseId: comment.commentID, courseType: 1, backType: comment.feedBack.type, backContent: comment.feedBack.content, tel: comment.feedBack.tel, username: comment.mineUsername}}, 'POST', function(response) {
				response ? comment.popUpSwitch('feedbackSuccess') : popUpSwitch(false, 'feedback');
			});
		},
		payType: '',
		payWarning: false,
		pay: function() {
            if (comment.payType === '') {
                comment.payWarning = true;
                return false;
            }
			if (!comment.buying) {
                comment.buying = true;
                var data = {
                    payType: comment.payType,
                    userName: comment.mineUsername,
                    userId: comment.mineID,
                    teacherId: comment.teacherInfo.teacherId,
                    teacherName: comment.teacherInfo.username,
                    orderType: 2,
                    //orderPrice: Math.ceil(comment.teacherInfo.extra / 100) * 100,
                    orderPrice: comment.teacherInfo.extra,
                    orderTitle: '学员'+ comment.mineUsername +'购买'+ comment.studentInfo.extra +'点评课程。',
                    courseId: comment.commentID
                };
                comment.getData('/lessonComment/generateOrder', 'orderInfo', data, 'POST', function(response) {
                    if (comment.payType) {
                        location.href = '/lessonComment/scan/'+ response.data;
                    } else {
                        location.href = '/lessonComment/alipay/'+ response.data +'/lessonComment&detail&'+ comment.commentID;
                    };
                });
            }
		},
		//  blade模板使用的变量
		commentID: null,
		mineID: null,
		mineUsername: null,
		mineType: null,
		minePic: null,
		orderType: null,
		bought: null
	});

	return comment;

});