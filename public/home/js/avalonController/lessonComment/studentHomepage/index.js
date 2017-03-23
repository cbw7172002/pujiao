define([], function () {

	var user = avalon.define({
		$id: 'userHomepage',
		userInfo: {
			pic: '/home/image/layout/default.png',
			username: '...',
			type: null,
			city: '...',
			sex: 1,
			created_at: '...'
		},
		isFollow: false,
		fansNum: 0,
		videoNum: 0,
		tabStatus: 0,
		changeTabStatus: function() {
			if (!user.loading) {
				user.tabStatus != avalon(this).attr('value') ? user.tabStatus = avalon(this).attr('value') : false;
				user.jump = null;
			}
		},
		specialLesson: [],
		commentLesson: [],
		order: {special: 0, comment: 0},
		changeOrder: function(key, value) {
			if (!user.loading && user.order[key] != value) {
				user.order[key] = value;
				user.getData(user.videoUrl, key+'Lesson', {userid: user.userID, order: value, type: user.tabStatus, page: user.page[key]}, 'POST');
			}
		},
		specialTotal : 0,
		commentTotal : 0,
		getData: function(url, model, data, method, callback) {
			if (model == 'specialLesson' || model == 'commentLesson') {
				user.loading = true;
			}
			$.ajax({
				type: method || 'GET',
				url: url,
				data: data || {},
				dataType: 'json',
				success: function(response) {
					if (response.type) {
						if (model == 'userInfo' && user.checks) {
							response.data.stock = 0;
						}
						user[model] = response.data;
					}
					if (model == 'specialCount' || model == 'commentCount') {
						user.videoNum += response.data || 0;

						user[model] = Math.ceil(user[model]/6);
						for (var i = 1; i <= user[model]; i++) {
							user[model+'Number'].push(i);
						}
					}
					if (model == 'specialCount') {
						user.specialTotal = response.data;
					}
					if (model == 'commentCount') {
						user.commentTotal = response.data;
					}
					if (model == 'specialLesson' || model == 'commentLesson') {
						user.loading = false;
					}
					callback && callback(response);
				},
				error: function(error) {
					if (model == 'specialLesson' || model == 'commentLesson') {
						user.loading = false;
					}
				}
			});
		},
		loading: false,
		page: {special: 1, comment: 1},
		specialCount: 0,
		specialCountNumber: [],
		commentCount: 0,
		commentCountNumber: [],
		jump: null,
		jumping: function(model) {
			if (user.jump != user.page[model] && user.jump <= user[model+'Count'] && user.jump != null && typeof user.jump === 'number' && user.jump != 0) {
				user.page[model] = user.jump;
				user.getData(user.videoUrl, model+'Lesson', {userid: user.userID, order: user.order[model], type: user.tabStatus, page: user.page[model]}, 'POST');
			}
			user.jump = null;
		},
		skip: function(model, direction) {
			if (typeof direction === 'boolean') {
				direction ? ++user.page[model] : --user.page[model];
			}
			if (typeof direction === 'number') {
				if (user.page[model] == direction) {
					return false;
				}
				user.page[model] = direction;
			}
			user.getData(user.videoUrl, model+'Lesson', {userid: user.userID, order: user.order[model], type: user.tabStatus, page: user.page[model]}, 'POST');
		},
		followUser: function() {
			if (user.isFollow) {
				user.popUp = 'unfollow';
			} else {
				user.getData('/lessonComment/getFirst', 'isFollow', {table: 'friends', action: 2, data: {fromUserId: user.mineID, toUserId: user.userID}}, 'POST', function(response) {
					response.type && user.fansNum++;
					user.getData('/lessonComment/getFirst', 'submitComment', {table: 'usermessage', action: 2, data: {
						type: 3,
						username: user.userInfo.username,
						actionId: user.mineID,
						fromUsername: user.mineName,
						toUsername: user.userInfo.username,
						content: '刚刚关注了您，点击进入该用户个人公开主页。'
					}}, 'POST');
				});
			}
		},
		popUp: false,
		popUpSwitch: function(value, unfollow) {
			user.popUp = value;
			unfollow && user.getData('/lessonComment/getFirst', 'isFollow', {table: 'friends', action: 3, data: {fromUserId: user.mineID, toUserId: user.userID}}, 'POST', function(response) {
				response.type && user.fansNum--;
			});
		},
		//  blade模板使用的变量
		userID: null,
		mineID: null,
		mineName: null,
		videoUrl: null,
		checks: null,
        
        //解决问题
        ordcolor:1,
        questionInfo: [],
        noticeMsg: false,
        isshowpage: false,
        getquestion:function(usertype,para){
            // console.log('请求数据...');
            user.ordcolor = para;
            $('#page_question').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/community/getQuestionb/'+user.userID+'/'+usertype+'/'+para+'/'+this.pageNumber+'/'+this.pageSize,
                        success: function(response) {
                            if(response.status){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                                
                                user.noticeMsg = false;
                                if(response.count / 6 > 1){
                                    user.isshowpage = true;
                                }
                            }else{
                                user.questionInfo = [];
                                user.noticeMsg = true;
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/community/getQuestionb/'+user.userID+'/'+usertype+'/'+para+'/'+pageNumber+'/'+pageSize,
                        success: function(response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 6,
                pageNumber :1,
                totalNumber :1,
                className:"paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function(data) {
                    if(data){
                        user.questionInfo = data;
                    }

                }
            })
        },

		collectionInfo:[],
		collectionMsg:false,
		isshowpagecol:true,
		cord:0,
		getcollertion:function (ord,ordcolor) {
			user.cord = ordcolor;
			$('#page_collection').pagination({
				dataSource: function (done) {
					$.ajax({
						url: '/community/getCollectionInfo/'+ user.userID +'/' + this.pageNumber + '/' + this.pageSize + '/' + ord,
						type: 'GET',
						dataType: 'json',
						success: function (response) {
							user.total = response.total;
							if (response.status) {
								user.collectionMsg = false;
								if(response.total <= 6){
									user.isshowpagecol = false;
								}
								var format = [];
								format['data'] = response.data;
								format['totalNumber'] = response.total;
								done(format);
							}else{
								user.collectionInfo = [];
								user.collectionMsg = true;
							}
						},
					});
				},
				getData: function (pageNumber, pageSize) {
					var self = this;
					$.ajax({
						type: 'GET',
						url: '/community/getCollectionInfo/'+ user.userID +'/'  + pageNumber + '/' + pageSize  + '/' + ord,
						success: function (response) {
							self.callback(response.data);
						}
					});
				},
				pageSize: 6,
				pageNumber: 1,
				totalNumber: 1,
				className: "paginationjs-theme-blue",
				showGoInput: true,
				showGoButton: true,
				callback: function (data) {
					if (data) {
						user.collectionInfo = data;
					}

				}
			})
		}

	});

	return user;

});