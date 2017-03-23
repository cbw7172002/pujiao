
define([], function () {
    var uId = location.href.split('/').pop();

    var question = avalon.define({
        $id: 'teacherAnswerController',
        teacherAnswerInfo: [],
        teaAnswerMsg: false,
        loading: false,
        page: false,
        getDate:function(para){
            // console.log('请求数据...');
            if(question.teaAnswerMsg) { return false }
            question.loading = true;
            $('#page_question').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/member/getTeacherAnswer/'+para+'/'+this.pageNumber+'/'+this.pageSize+'/'+uId,
                        success: function(response) {
                            question.loading = false;
                            if(response.status){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);

                                question.teaAnswerMsg = false;
                                if(response.count / 4 > 1){
                                    question.page = true;
                                }
                            }else{
                                question.teacherAnswerInfo = [];
                                question.teaAnswerMsg = true;
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/member/getTeacherAnswer/'+para+'/'+pageNumber+'/'+pageSize+'/'+uId,
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
                        question.teacherAnswerInfo = data;
                        console.log(data)
                    }

                }
            })
        }
    });

    return question;
});