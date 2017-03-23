/**
 * Created by LT on 2017/1/18.
 */
define([], function () {
    // 我的收藏
    var courseStore = avalon.define({
        $id: 'courseStore',
        realInfo : false,
        total: '---',
        courseStoreInfo : [],
        courseStoreMsg : false,
        loading: false,
        display: true,
        isShow: false,
        getCourseStoreInfo : function(){
            if( courseStore.courseStoreMsg || courseStore.loading ) { return false }
            courseStore.loading = true;
            $('#page_courseStore').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/courseStore/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        data: {},
                        dataType: 'json',
                        success: function (response) {
                            courseStore.total = response.total;
                            courseStore.loading = false;
                            if (response.status) {
                                response.total <= 6 ? courseStore.display = false : courseStore.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                courseStore.courseStoreMsg = false;
                            }else{
                                courseStore.courseStoreInfo = [];
                                courseStore.courseStoreMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/member/courseStore/' + pageNumber + '/' + pageSize,
                        data: {},
                        dataType: 'json',
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
                        courseStore.courseStoreInfo = data;
                    }

                }
            })
        }
    });
    return {
        courseStore: courseStore
    }
});