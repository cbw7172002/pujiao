/**
 * Created by LT on 2016/7/8.
 */
define([], function () {
    var myFansTeacher = avalon.define({
        $id: 'myFansTeacher',
        realInfo: false,
        loading: false,
        total: '',
        myFans:false,
        display:true,
        myFansList: [],
        getMyFansInfo: function (userId) {
            if( myFansTeacher.myFans ) { return false }
            myFansTeacher.loading = true;
            $('#page_friend').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/myFriends/' + this.pageNumber + '/' + this.pageSize + '/' + userId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            myFansTeacher.total = response.total;
                            myFansTeacher.loading = false;
                            if (response.type) {
                                response.total <= 20 ? myFansTeacher.display = false : myFansTeacher.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                myFansTeacher.myFans = false;
                            }else{
                                myFansTeacher.total = 0;
                                myFansTeacher.myFans = true;
                                myFansTeacher.myFansList = [];
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/member/myFriends/' + pageNumber + '/' + pageSize + '/' + userId,
                        success: function (response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 20,
                pageNumber: 1,
                totalNumber: 1,
                className: "paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function (data) {
                    if (data) {
                        myFansTeacher.myFansList = data;
                    }

                }
            })
        }
    });
    return {
        myFansTeacher: myFansTeacher
    }
});