define([], function() {

	var uploadComment = avalon.define({
		$id: 'uploadCommentController',
		progressBar: 30,
		//	1 not loading, 2 loding, 3 load success
		uploadStatus: 1,
		suitlevel: [
			{title: '钢琴一级', id: 1}, 
			{title: '钢琴二级', id: 2}, 
			{title: '钢琴三级', id: 3}, 
			{title: '钢琴四级', id: 4}, 
			{title: '钢琴五级', id: 5}, 
			{title: '钢琴六级', id: 6}, 
			{title: '钢琴七级', id: 7}, 
			{title: '钢琴八级', id: 8}, 
			{title: '钢琴九级', id: 9}, 
			{title: '钢琴十级', id: 10}
		],
		selectedLevel: [],
		levelWarning: false,
		selectLevel: function() {
			uploadComment.levelWarning = false;
			if (avalon(this).attr('class')) {
				avalon(this).removeClass('checked');
				for (var i in uploadComment.selectedLevel) {
					if (uploadComment.selectedLevel[i] == avalon(this).attr('value')) {
						uploadComment.selectedLevel.remove(uploadComment.selectedLevel[i]);
					};
				}
			} else {
				if (uploadComment.selectedLevel[2]) {
					uploadComment.levelWarning = true;
				} else {
					avalon(this).addClass('checked');
					uploadComment.selectedLevel.push(avalon(this).attr('value'));
				};
			};
		},
		submitDisable: false
	});

	return uploadComment;

});