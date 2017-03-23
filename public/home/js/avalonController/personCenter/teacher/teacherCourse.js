
define([], function () {
    var uId = location.href.split('/').pop();

    var question = avalon.define({
        $id: 'teacherCourseController',
        teacherCourseInfo: [],
        teaCourseMsg: false,
        loading: false,
        page: false,
        getDate:function(para){
            // console.log('请求数据...');
            question.loading = true;
            question.teaCourseMsg = false;
            $('#page_question').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/member/getTeacherCourse/'+para+'/'+this.pageNumber+'/'+this.pageSize+'/'+uId,
                        success: function(response) {
                            question.loading = false;
                            if(response.status){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);

                                question.teaCourseMsg = false;
                                if(response.count / 4 > 1){
                                    question.page = true;
                                }
                            }else{
                                question.teacherCourseInfo = [];
                                question.teaCourseMsg = true;
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/member/getTeacherCourse/'+para+'/'+pageNumber+'/'+pageSize+'/'+uId,
                        success: function(response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 4,
                pageNumber :1,
                totalNumber :1,
                className:"paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function(data) {
                    if(data){
                        question.teacherCourseInfo = data;
                        console.log(data)
                    }

                }
            })
        }
    });

    return question;
});