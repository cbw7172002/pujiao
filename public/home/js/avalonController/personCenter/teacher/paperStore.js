/**
 * Created by LT on 2017/1/18.
 */
define([], function () {
    // 我的收藏
    var paperStore = avalon.define({
        $id: 'paperStore',
        realInfo : false,
        total: '',
        count: 0, //筛选条件显示与否
        paperStoreInfo : [],
        paperStoreMsg : false,
        loading: false,
        display: true,
        isShow: false,
        selSubjectId: 0,
        selTypeId: 1000,
        gradesShowNum: 9,
        selecting: function(type, id) {
            if(type == 1) {
                paperStore.selSubjectId = id;
            } else if(type == 2) {
                paperStore.selTypeId = id;
            }
            paperStore.getPaperStoreInfo();
        },
        selMoreShow: function() {
            if(paperStore.gradesShowNum == 9) {
                paperStore.gradesShowNum = 1000;
            } else {
                paperStore.gradesShowNum = 9;
            }
        },
        formationData:function(){
            var data =  { where:{subjectId:paperStore.selSubjectId, type:paperStore.selTypeId} };
            if(data.where.hasOwnProperty('subjectId') && data.where.subjectId == 0) {
                delete data.where.subjectId;
            }
            if(data.where.hasOwnProperty('type') && data.where.type == 1000) {
                delete data.where.type;
            }
            return data;
        },
        getPaperStoreInfo : function(){
            if( paperStore.loading ) { return false }
            paperStore.loading = true;
            paperStore.paperStoreMsg = false;
            $('#page_paperStore').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/paperStore/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        data: paperStore.formationData(),
                        dataType: 'json',
                        success: function (response) {
                            paperStore.total = response.total;
                            paperStore.count = response.count;
                            paperStore.loading = false;
                            if (response.status) {
                                response.total <= 10 ? paperStore.display = false : paperStore.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                paperStore.paperStoreMsg = false;
                            }else{
                                paperStore.paperStoreInfo = [];
                                paperStore.paperStoreMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/member/paperStore/' + pageNumber + '/' + pageSize,
                        data: paperStore.formationData(),
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
                        paperStore.paperStoreInfo = data;
                    }

                }
            })
        }
    });
    return {
        paperStore: paperStore
    }
});