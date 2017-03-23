/**
 * Created by Mr.H on 2017/2/7.
 */
define([], function () {
    var comment = avalon.define({
        $id: 'commentController',
        commentInfo: [],
        answerMsg : false,
        loading: false,
        noRead: '---',
        display: true,
        getCommentInfo: function (username) {
            if( comment.answerMsg ) { return false }
            comment.loading = true;
            $('#page_comment').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/getCommentInfo/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        dataType: 'json',
                        data : {username:username},
                        success: function (response) {
                            comment.loading = false;
                            comment.noRead = response.noRead - 0;
                            if (response.status) {
                                if(response.count <= 10){
                                    comment.display = false;
                                }
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                            }else{
                                comment.commentInfo = [];
                                comment.answerMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data : {username:username},
                        url: '/member/getCommentInfo/' + pageNumber + '/' + pageSize,
                        success: function (response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 10,
                pageNumber: 1,
                totalNumber: 1,
                className: "paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function (data) {
                    if (data) {
                        comment.commentInfo = data;
                    }
                }
            })
        },
        changeNoticeStatus: function(noticeId){
            $.ajax({
                url: '/member/changeNoticeStatus/' + noticeId,
                type : 'GET',
                dataType : 'json',
                success : function(response){
                    if(response){
                        if(response.status) {
                            if(comment.noRead == 0) {
                                comment.noRead = 0;
                            } else {
                                comment.noRead --;
                            }
                        }
                        comment.realJump();
                    }
                }
            })
        },
        actionId: '',
        userType: '',
        messageType: '',
        jumpTo: function (el) {
            comment.changeNoticeStatus(el.id);
            comment.actionId = el.actionId;
            comment.userType = el.userType;
            comment.messageType = el.type;
        },
        realJump: function () {
            if (parseInt(comment.userType) == 1) {
                switch(parseInt(comment.messageType)) {
                    case 6:
                        location.href = '/community/askDetail/' + comment.actionId;
                        break;
                    case 7:
                        location.href = '/community/askDetail/' + comment.actionId;
                        break;
                }
            } else {
                switch(parseInt(comment.messageType)) {
                    case 6:
                        location.href = '/community/askDetail/' + comment.actionId;
                        break;
                    case 7:
                        location.href = '/community/askDetail/' + comment.actionId;
                        break;
                    case 8:
                        location.href = '/resource/resDetail/' + comment.actionId;
                        break;
                }
            }
        }
    });
    return {
        comment: comment
    }
});