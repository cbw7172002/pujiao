
define([], function () {
    var uId = location.href.split('/').pop();

    var question = avalon.define({
        $id: 'teacherCourseQaController',
        teacherCourseQaInfo: [],
        teaAnswerMsg: false,
        loading: false,
        page: false,
        getDate:function(para){
            question.loading = true;
            question.teaAnswerMsg = false;
            // console.log('请求数据...');
            $('#page_questionQa').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/member/getTeacherCourseQa/'+para+'/'+this.pageNumber+'/'+this.pageSize+'/'+uId,
                        success: function(response) {
                            question.loading = false;
                            if(response.status){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                console.log(response.count);
                                done(format);

                                question.teaAnswerMsg = false;
                                if(response.count / 6 > 1){
                                    question.page = true;
                                }
                            }else{
                                question.teacherCourseQaInfo = [];
                                question.teaAnswerMsg = true;
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/member/getTeacherCourseQa/'+para+'/'+pageNumber+'/'+pageSize+'/'+uId,
                        success: function(response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 6,
                pageNumber :1,
                totalNumber :1,
                className:"paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function(data) {
                    if(data){
                        question.teacherCourseQaInfo = data;
                        console.log(data)
                    }

                }
            })
        }
    });

    return question;
});