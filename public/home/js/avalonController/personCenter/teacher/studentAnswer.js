
define([], function () {
    var uId = location.href.split('/').pop();

    var question = avalon.define({
        $id: 'studentAnswerController',
        studentAnswerInfo: [],
        loading: false,
        stuAnswerMsg: false,
        page: false,
        getDate:function(para){
            if( question.stuAnswerMsg ) { return false }
            question.loading = true;
            // console.log('请求数据...');
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

                                question.stuAnswerMsg = false;
                                if(response.count / 4 > 1){
                                    question.page = true;
                                }
                            }else{
                                question.studentAnswerInfo = [];
                                question.stuAnswerMsg = true;
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
                        question.studentAnswerInfo = data;
                        console.log(data)
                    }

                }
            })
        }
    });

    return question;
});