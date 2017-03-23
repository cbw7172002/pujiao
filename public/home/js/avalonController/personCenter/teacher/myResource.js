/**
 * Created by LT on 2017/1/18.
 */
define([], function () {
    // 我的收藏
    var myResource = avalon.define({
        $id: 'myResource',
        realInfo : false,
        loading: false,
        total: 0,
        myResourceInfo : [],
        myResourceMsg : false,
        display: true,
        isShow: false,
        getMyResourceInfo : function(type){
            if(myResource.loading) { return false }
            myResource.loading = true;
            myResource.myResourceMsg = false;
            $('#page_myResource').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/myResource/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        data: {typeId: type},
                        dataType: 'json',
                        success: function (response) {
                            myResource.total = response.total;
                            myResource.loading = false;
                            if (response.status) {
                                response.total <= 10 ? myResource.display = false : myResource.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                myResource.myResourceMsg = false;
                            }else{
                                myResource.myResourceInfo = [];
                                myResource.myResourceMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/member/myResource/' + pageNumber + '/' + pageSize,
                        data: {typeId: type},
                        dataType: 'json',
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
                        myResource.myResourceInfo = data;
                    }

                }
            })
        }
    });
    return {
        myResource: myResource
    }
});