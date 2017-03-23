define([], function() {

	var comment = avalon.define({
		$id: 'waitCommentController',
		studentInfo: [],
		getData: function(url, model, data, method, callback) {
			$.ajax({
				type: method || 'GET',
				url: url,
				data: data || {},
				dataType: "json",
				success: function(response) {
					if (response.type) comment[model] = response.data;
					model == 'studentInfo' && comment.setVideo(function () {});
				},
				error: function(error) {
					alert('数据加载失败，请重试');
				}
			});
		},
		thePlayer: {},
		setVideo: function(callback) {
			var list = [];
			comment.studentInfo.courseHighPath && list.push({
				label: "超清",
				file: comment.studentInfo.courseHighPath,
				height: 720,
				width: 1280,
				type: "mp4"
			});
			comment.studentInfo.courseMediumPath && list.push({
				default: true,
				label: "高清",
				file: comment.studentInfo.courseMediumPath,
				height: 360,
				width: 640,
				type: "mp4"
			});
			comment.studentInfo.courseLowPath && list.push({
				label: "标清",
				file: comment.studentInfo.courseLowPath,
				height: 180,
				width: 320,
				type: "mp4"
			});
			comment.thePlayer = jwplayer('mediaplayer').setup({
                flashplayer: 'jwplayer/jwplayer.flash.swf',
                playlist: [{
                    image: comment.studentInfo.coursePic,
                    sources: list
                }],
                id: 'playerID',
                width: '800',
                height: '450',
                aspectratio: '16:9',
                type: "mp4"
            });
		},
		//  blade模板使用的变量
		commentID: null,
		orderSn: null,
		messageID: null
	});

	return comment;

});