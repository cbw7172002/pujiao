/**
 * Created by LT on 2016/7/8.
 */
define([], function () {
    var myFocusTeacher = avalon.define({
        $id: 'myFocusTeacher',
        realInfo: false,
        loading: false,
        total: '',
        myFocus : false,
        display:true,
        myFocusList: [],
        getMyFocusInfo: function (userId) {
            if( myFocusTeacher.myFocus ) { return false }
            myFocusTeacher.loading = true;
            $('#page_focus').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/myFocus/'+ this.pageNumber + '/' + this.pageSize + '/' + userId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            myFocusTeacher.total = response.total;
                            myFocusTeacher.loading = false;
                            if (response.type) {
                                response.total <= 20 ? myFocusTeacher.display = false : myFocusTeacher.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                myFocusTeacher.myFocus = false;
                            }else{
                                myFocusTeacher.total =0;
                                myFocusTeacher.myFocus = true;
                                myFocusTeacher.myFocusList = [];
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/member/myFocus/' + pageNumber + '/' + pageSize + '/' + userId,
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
                        myFocusTeacher.myFocusList = data;
                    }
                }
            })
        }
    });
    return {
        myFocusTeacher: myFocusTeacher
    }
});