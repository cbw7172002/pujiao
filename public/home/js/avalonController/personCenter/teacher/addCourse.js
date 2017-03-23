/**
 * Created by LT on 2017/1/18.
 */
define([], function () {
    // 我的收藏
    var addCourse = avalon.define({
        $id: 'addCourse',
        realInfo : false,
        loading: false,
        total: '',
        addCourseInfo : [],
        addCourseMsg : false,
        display: true,
        getAddCourseInfo : function(mineId, type){
            if( type == '1') { addCourse.addCourseMsg = false }         // type 是首次添加防止不加载数据
            if(addCourse.addCourseMsg || addCourse.loading) { return false; }
            addCourse.loading = true;
            $('#page_add_course').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/addCourse/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        data: {mineId: mineId},
                        dataType: 'json',
                        success: function (response) {
                            addCourse.total = response.total;
                            addCourse.loading = false;
                            if (response.status) {
                                response.total <= 8 ? addCourse.display = false : addCourse.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                addCourse.addCourseMsg = false;
                            }else{
                                addCourse.addCourseInfo = [];
                                addCourse.addCourseMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/member/addCourse/' + pageNumber + '/' + pageSize,
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
                        addCourse.addCourseInfo = data;
                    }

                }
            })
        }
    });
    return {
        addCourse: addCourse
    }
});