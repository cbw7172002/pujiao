/**
 * Created by LT on 2017/1/18.
 */
define([], function () {
    // 我的收藏
    var resourceStore = avalon.define({
        $id: 'resourceStore',
        realInfo : false,
        total: '',
        collectionInfo : [],
        collectionMsg : false,
        loading: false,
        display: true,
        isShow: false,
        getCollectionInfo : function(type){
            if( resourceStore.loading ) { return false }
            resourceStore.loading = true;
            resourceStore.collectionMsg = false;
            $('#page_resourceStore').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/resourceStore/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        data: {typeId: type},
                        dataType: 'json',
                        success: function (response) {
                            resourceStore.total = response.total;
                            resourceStore.loading = false;
                            if (response.status) {
                                response.total <= 10 ? resourceStore.display = false : resourceStore.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                resourceStore.collectionMsg = false;
                            }else{
                                resourceStore.collectionInfo = [];
                                resourceStore.collectionMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/member/resourceStore/' + pageNumber + '/' + pageSize,
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
                        resourceStore.collectionInfo = data;
                    }

                }
            })
        }
    });
    return {
        resourceStore: resourceStore
    }
});