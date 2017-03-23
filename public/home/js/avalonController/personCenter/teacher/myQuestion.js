
define([], function () {
    var question = avalon.define({
        $id: 'questionController',
        questionInfo: [],
        questionMsg: false,
        loading: false,
        page: false,
        getDate:function(para){
            question.loading = true;
            // console.log('请求数据...');
            $('#page_question').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/member/getQuestion/'+para+'/'+this.pageNumber+'/'+this.pageSize,
                        success: function(response) {
                            question.loading = false;
                            if(response.status){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);

                                question.questionMsg = false;
                                if(response.count / 6 > 1){
                                    question.page = true;
                                }
                            }else{
                                question.questionInfo = [];
                                question.questionMsg = true;
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/member/getQuestion/'+para+'/'+pageNumber+'/'+pageSize,
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
                        question.questionInfo = data;
                        console.log(data)
                    }

                }
            })
        }
    });

    return question;
});