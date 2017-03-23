
define([], function () {
    var question = avalon.define({
        $id: 'questionsController',
        questionsInfo: [],
        waitAuditingMsg: false,
        loading: false,
        page: false,
        getDate:function(para){
            question.loading = true;
            question.waitAuditingMsg = false;
            // console.log('请求数据...');
            $('#page_question').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/member/getWaitAnswer/'+para+'/'+this.pageNumber+'/'+this.pageSize,
                        success: function(response) {
                            question.loading = false;
                            if(response.status){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);

                                question.waitAuditingMsg = false;
                                if(response.count / 6 > 1){
                                    question.page = true;
                                }
                            }else{
                                question.questionsInfo = [];
                                question.waitAuditingMsg = true;
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/member/getWaitAnswer/'+para+'/'+pageNumber+'/'+pageSize,
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
                        question.questionsInfo = data;
                        console.log(data)
                    }

                }
            })
        }
    });

    return question;
});