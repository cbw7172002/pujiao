/**
 * Created by Mr.H on 2016/7/10.
 */
define([], function () {
    var notice = avalon.define({
        $id: 'noticeController',
        noticeInfo: [],
        noticeMsg: false,
        loading: false,
        isRead: true,
        noRead: '---',
        display: true,
        getNoticeInfo: function (username) {
            if( notice.noticeMsg ) { return false }
            notice.loading = true;
            $('#page_notice').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/getNoticeInfo/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        dataType: 'json',
                        data: {username: username},
                        success: function (response) {
                            notice.loading = false;
                            notice.noRead = response.noRead - 0;
                            if (response.status) {
                                if(response.count <= 10){
                                    notice.display = false;
                                }
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                            } else {
                                notice.noticeMsg = true;
                                notice.noticeInfo = [];
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data: {username: username},
                        url: '/member/getNoticeInfo/' + pageNumber + '/' + pageSize,
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
                        notice.noticeInfo = data;
                    }

                }
            })
        },
        changeNoticeStatus: function (noticeId) {
            $.ajax({
                url: '/member/changeNoticeStatus/' + noticeId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response) {
                        if (response.status ){
                            if(notice.noRead == 0) {
                                notice.noRead = 0;
                            } else {
                                notice.noRead --;
                            }
                        }
                        notice.realJump();
                    }
                }
            })
        },
        actionId: '',
        userType: '',
        messageType: '',
        jumpTo: function (el) {
            notice.changeNoticeStatus(el.id);
            notice.actionId = el.actionId;
            notice.userType = el.userType;
            notice.messageType = el.type;
        },
        realJump: function () {
            if (notice.userType == '1') {
                switch(parseInt(notice.messageType)) {
                    case 1:
                        location.href = '/studentCourse/stuDetail/' + notice.actionId;
                        break;
                    case 2:
                        location.href = '/evaluateManageStu/index#questionCorrect';
                        break;
                    case 3:
                        location.href = '/evaluateManageStu/index#questionCorrect';
                        break;
                    case 4:
                        location.href = '/studentCourse/stuDetail/' + notice.actionId + '#question';
                        break;
                    case 5:
                        location.href = '/studentCourse/stuDetail/' + notice.actionId;
                        break;
                }
            } else {
                switch(parseInt(notice.messageType)) {
                    case 5:
                        location.href = '/teacherCourse/teaDetail/' + notice.actionId;
                        break;
                    case 9:
                        location.href = '/teacherCourse/teaDetail/' + notice.actionId;
                        break;
                    case 10:
                        location.href = '/teacherCourse/list#my';
                        break;
                    case 11:
                        location.href = '/teacherCourse/teaDetail/' + notice.actionId;
                        break;
                    case 12:
                        location.href = '/evaluateManageTea/statistic/' + notice.actionId;
                        break;
                }
            }
        }
    });
    return {
        notice: notice
    }
});