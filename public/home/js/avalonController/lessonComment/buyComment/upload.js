define(['lessonComment/PrimecloudPaas'], function(PrimecloudPaas) {

	var upload = avalon.define({
		$id: 'uploadController',
		progressBar: 0,
		uploadStatus: 1,
		submitDisable: false,
		uploadTip: '',
		uploadInfo: {
			fileID: null,
			courseTitle: null,
			message: '',
			courseLowPath: '',
			courseMediumPath: '',
			courseHighPath: '',
			aNum:'',
			bNum:'',
			cNum:'',
			dNum:''
		},
		scoreStyle : ['加油','加油','一般','优秀','完美'],
		scoreWarningOption: false,
		titleLength: 0,
		messageLength: 0,
		warning: {title: false},
		getData: function(url, model, data, method, callback) {
			$.ajax({
				type: method || 'GET',
				url: url,
				data: data || {},
				dataType: "json",
				success: function(response) {
					if (response.type) {
						if (model == 'uploadResource') {
							if (upload.uploadStatus == 2 && response.data.code != 401) {
								if (response.data.data.AllowUpload == 2) {
									upload.progressBar = 100;
									setTimeout(function() {
										upload.endUpload('视频上传完成！');
										upload.uploadInfo.fileID = response.data.data.FileID;
										upload.getData('/lessonComment/transformation', 'transformation', {fileID: upload.uploadInfo.fileID}, 'POST');
									}, 1000);
								} else {
                                    var deleteSecond;
									if (response.data.data.AllowUpload == 1) {
                                        deleteSecond = 5000;
                                        var uploadData = {
                                            url: response.data.data.UUrl,
                                            method: "POST",
                                            data: {
                                                filedata: upload.file
                                            }
                                        };
                                        if (response.data.data.UploadLength > 0) {
                                            uploadData.resume = response.data.data.UploadLength;
                                            console.log('断点续传');
                                            console.log(response.data.data);
                                            console.log(uploadData);
                                        }
                                        upload.paas.requestUpload(uploadData);
                                    } else {
                                        deleteSecond = 1000;
                                    }
									console.log('文件上传进度： ' + parseInt(response.data.data.UploadLength / response.data.data.FileLenth * 100) + '%');
									upload.progressBar = 25 + response.data.data.UploadLength / response.data.data.FileLenth * 100 * 0.75;
									setTimeout(function() {
										upload.getData('/lessonComment/uploadResource', 'uploadResource', {md5: upload.fileMD5, filename: upload.fileName, directory: '/'}, 'POST');
									}, deleteSecond);
								} 
							} else {
								upload.endUpload('上传中断');
							}
						}
						if (model == 'transformation') {
							if (response.data.code == 200 && response.data.data.Waiting < 0) {
								for (var i in response.data.data.FileList) {
									switch(response.data.data.FileList[i].Level) {
										case 1:
											upload.uploadInfo.courseLowPath = response.data.data.FileList[i].FileID;
											break;
										case 2:
											upload.uploadInfo.courseMediumPath = response.data.data.FileList[i].FileID;
											break;
										case 3:
											upload.uploadInfo.courseHighPath = response.data.data.FileList[i].FileID;
											break;
									}
								}
							}
						}
						if (model == 'finishUpload' || model == 'isReload') {
							callback && callback(function () {
								window.location.href = '/member/student/'+ upload.mineType +'#lessonComment';
							});
						}
						if (model == 'comment') {
							callback && callback(function () {
								window.location.href = '/member/famousTeacher#waitComment';
							});
						}
						model == 'deleteMessage' && callback();
					}
				},
				error: function(error) {
                    model == 'uploadResource' && upload.endUpload('网络异常，请重试');
                }
			});
		},
		file: null,
		fileName: null,
		fileMD5: null,
		paas: null,
		uploadResource: function(value) {
			upload.paas = new PrimecloudPaas();
			upload.uploadStatus = 2;
			upload.uploadTip = '';
			upload.uploadInfo.fileID = null;
			upload.paas.MD5(upload.file, function(result){
				if (result) {
					upload.fileName = upload.paas.splitFileName(value);
					$('#md5container').val(result);
					upload.fileMD5 = $('#md5container').val();
					upload.getData('/lessonComment/uploadResource', 'uploadResource', {md5: upload.fileMD5, filename: upload.fileName, directory: '/'}, 'POST');
					console.log('------------------------------------  扫描完成  ---------------------------------------');
				} else {
					upload.endUpload('上传失败，请重试');
				}
			}, function(pos, size){
				if (Math.ceil(size / 1024 / 1024) > 1000) {
					upload.endUpload('上传文件过大');
					console.log(Math.ceil(size / 1024 / 1024) + ' MB');
					return false;
				}
				if (upload.uploadStatus != 2) {
					upload.endUpload('上传中断');
					return false;
				}
				console.log('文件扫描进度： ' + parseInt(pos / size * 100) + '%');
				upload.progressBar = pos / size * 100 * 0.25;
			});
		},
		endUpload: function(tip) {
			if (upload.paas) {
				upload.paas.endUpload();
				upload.paas.endMD5();
				upload.paas = null;
			}
			upload.progressBar = 0;
			upload.uploadStatus = 3;
			upload.uploadTip = tip || '';
		},
		submit: function(isReload) {
			if (!upload.submitDisable) return false;
			if (!upload.uploadInfo.fileID) {
				upload.uploadStatus = 3
				upload.uploadTip = '<span style="color: red;">请先上传视频</span>';
				return false;
			}
			if (isReload === 'comment' || isReload === 'reComment') {
				if(upload.uploadInfo.aNum == '' || upload.uploadInfo.bNum == '' || upload.uploadInfo.cNum == '' || upload.uploadInfo.dNum == ''){
					upload.scoreWarningOption = true;
					return false;
				}else{
					upload.scoreWarningOption = false;
				}
				// if (upload.selectedLevel.size() < 1) {
				// 	upload.levelWarning = true;
				// 	return false;
				// }
				// upload.uploadInfo.suitlevel = upload.selectedLevel.join(',');
				delete upload.uploadInfo.message;
				upload.uploadInfo.state = 1;
				if (isReload === 'reComment') {
					delete upload.uploadInfo.courseTitle;
					upload.getData('/lessonComment/getFirst', 'comment', {data: upload.uploadInfo, action: 4, condition: {id: upload.commentID}, table: 'commentcourse'}, 'POST', function (callback) {
						if (upload.messageID) {
							upload.getData('/lessonComment/getFirst', 'deleteMessage', {action: 3, table: 'usermessage', data: {id: upload.messageID}}, 'POST', function() {
								callback();
							});
						} else {
							upload.getData('/lessonComment/getFirst', 'deleteMessage', {action: 3, table: 'usermessage', data: {actionId: upload.commentID}}, 'POST', function() {
								callback();
							});
						}
					});
				} else {
					upload.getData('/lessonComment/finishComment', 'comment', upload.uploadInfo, 'POST', function(callback) {
						if (upload.messageID) {
							upload.getData('/lessonComment/getFirst', 'deleteMessage', {action: 3, table: 'usermessage', data: {id: upload.messageID}}, 'POST', function() {
								callback();
							});
						} else {
							upload.getData('/lessonComment/getFirst', 'deleteMessage', {action: 3, table: 'usermessage', data: {actionId: upload.applyID}}, 'POST', function() {
								callback();
							});
						}
					});
				}
			} else {
				delete upload.uploadInfo.aNum;
				delete upload.uploadInfo.bNum;
				delete upload.uploadInfo.cNum;
				delete upload.uploadInfo.dNum;
				for (var i in upload.warning) {
					if (upload[i+'Length'] < 1) {
						upload.warning[i] = true;
						return false;
					}
				}
				if (isReload) {
					for (var i in upload.temp) {
						upload.temp[i] == upload.uploadInfo[i] && delete upload.uploadInfo[i];
					}
					upload.uploadInfo.state = 1;
					upload.getData('/lessonComment/getFirst', 'isReload', {data: upload.uploadInfo, table: 'applycourse', condition: {id: upload.applyID}, action: 4}, 'POST', function(callback) {
						if (upload.messageID) {
							upload.getData('/lessonComment/getFirst', 'deleteMessage', {action: 3, table: 'usermessage', data: {id: upload.messageID}}, 'POST', function() {
								callback();
							});
						} else {
							upload.getData('/lessonComment/getFirst', 'deleteMessage', {action: 3, table: 'usermessage', data: {actionId: upload.applyID}}, 'POST', function() {
								callback();
							});
						}
					});
				} else {
					upload.uploadInfo.userId = upload.mineID;
					upload.getData('/lessonComment/finishUpload', 'finishUpload', {data: upload.uploadInfo, orderID: upload.orderID}, 'POST', function (callback) {
						callback();
					});
				}
			}
			
		},
		// suitlevel: [
		// 	{title: '钢琴一级', id: 1}, 
		// 	{title: '钢琴二级', id: 2}, 
		// 	{title: '钢琴三级', id: 3}, 
		// 	{title: '钢琴四级', id: 4}, 
		// 	{title: '钢琴五级', id: 5}, 
		// 	{title: '钢琴六级', id: 6}, 
		// 	{title: '钢琴七级', id: 7}, 
		// 	{title: '钢琴八级', id: 8}, 
		// 	{title: '钢琴九级', id: 9}, 
		// 	{title: '钢琴十级', id: 10}
		// ],
		// selectedLevel: [],
		// levelWarning: false,
		// selectLevel: function() {
		// 	upload.levelWarning = false;
		// 	if (avalon(this).attr('class')) {
		// 		avalon(this).removeClass('checked');
		// 		for (var i in upload.selectedLevel) {
		// 			if (upload.selectedLevel[i] == avalon(this).attr('value')) {
		// 				upload.selectedLevel.remove(upload.selectedLevel[i]);
		// 			}
		// 		}
		// 	} else {
		// 		avalon(this).addClass('checked');
		// 		upload.selectedLevel.push(avalon(this).attr('value'));
		// 	}
		// },
		addScore: function(name,num){
			upload.uploadInfo[name+'Num'] = num;
		},
		//  blade模板使用的变量
		mineID: null,
		mineType: null,
		applyID: null,
		temp: null,
		messageID: null,
		orderID: null,
		commentID: null
	});

	return upload;

});