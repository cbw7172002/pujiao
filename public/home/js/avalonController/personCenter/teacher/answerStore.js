/**
 * Created by LT on 2017/1/18.
 */
define([], function () {
    // 我的收藏
    var answerStore = avalon.define({
        $id: 'answerStore',
        realInfo : false,
        total: '',
        answerStoreInfo : [],
        answerStoreMsg : false,
        loading: false,
        display: true,
        isShow: false,
        getAnswerStoreInfo : function(){
            if( answerStore.answerStoreMsg || answerStore.loading) { return false }
            answerStore.loading = true;
            $('#page_answerStore').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/answerStore/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        data: {},
                        dataType: 'json',
                        success: function (response) {
                            answerStore.total = response.total;
                            answerStore.loading = false;
                            if (response.status) {
                                response.total <= 10 ? answerStore.display = false : answerStore.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                answerStore.answerStoreMsg = false;
                            }else{
                                answerStore.answerStoreInfo = [];
                                answerStore.answerStoreMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/member/answerStore/' + pageNumber + '/' + pageSize,
                        data: {},
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
                        answerStore.answerStoreInfo = data;
                    }

                }
            })
        }
    });
    return {
        answerStore: answerStore
    }
});