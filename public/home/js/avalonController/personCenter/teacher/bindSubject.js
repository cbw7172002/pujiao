/**
 * Created by LT on 2017/1/18.
 */
define([], function () {
    // 我的收藏
    var bindSubject = avalon.define({
        $id: 'bindSubject',
        realInfo : false,
        total: '',
        loading: false,
        bindSubjectInfo : [],
        bindSubjectMsg : false,
        display: true,
        getBindSubjectInfo : function(mineId, type){
            if( type == '1') bindSubject.bindSubjectMsg = false;
            if( bindSubject.bindSubjectMsg || bindSubject.loading) { return false }
            bindSubject.loading = true;
            $('#page_bind_subject').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/bindSubject/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        data: {mineId: mineId},
                        dataType: 'json',
                        success: function (response) {
                            bindSubject.total = response.total;
                            bindSubject.loading = false;
                            if (response.status) {
                                response.total <= 8 ? bindSubject.display = false : bindSubject.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                bindSubject.bindSubjectMsg = false;
                            }else{
                                bindSubject.bindSubjectInfo = [];
                                bindSubject.bindSubjectMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/member/bindSubject/' + pageNumber + '/' + pageSize,
                        data: {mineId: mineId},
                        dataType: 'json',
                        success: function (response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 8,
                pageNumber: 1,
                totalNumber: 1,
                className: "paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function (data) {
                    if (data) {
                        bindSubject.bindSubjectInfo = data;
                    }

                }
            })
        }
    });
    return {
        bindSubject: bindSubject
    }
});