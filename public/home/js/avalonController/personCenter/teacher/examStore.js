/**
 * Created by LT on 2017/1/18.
 */
define([], function () {
    // 我的收藏
    var examStore = avalon.define({
        $id: 'examStore',
        realInfo : false,
        loading: false,
        total: '',
        count: 0, //筛选条件显示与否
        examStoreInfo : [],
        examStoreMsg : false,
        display: true,
        isShow: false,
        selSubjectId: 0,
        selTypeId: 0,
        gradesShowNum: 9,
        selecting: function(type, id) {
            if(type == 1) {
                examStore.selSubjectId = id;
            } else if (type == 2) {
                examStore.selTypeId = id;
            }
            examStore.getExamStoreInfo();
        },
        selMoreShow: function() {
            if(examStore.gradesShowNum == 9) {
                examStore.gradesShowNum = 1000;
            } else {
                examStore.gradesShowNum = 9;
            }
        },
        // showAnswer: function() {
        //     if($(this).parent('div').siblings('.question_answer').hasClass('hide')) {
        //         $(this).parent('div').siblings('.question_answer').removeClass('hide');
        //         $(this).parent('div').css('border-bottom', '1px dotted #f9881c');
        //     } else {
        //         $(this).parent('div').siblings('.question_answer').addClass('hide');
        //         $(this).parent('div').css('border', '1px solid silver');
        //     }
        // },
        //我的试题收藏
        collection: function (id) {
            if ($(this).hasClass('question_border_top_img_red')) {
                $.ajax({
                    url: '/member/followUser',
                    type: 'POST',
                    data: {table: 'resourcestore',action: 3, data: {id: id}},
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            $(this).removeClass('question_border_top_img_red').addClass('question_border_top_img');
                            $(this).html('收藏');
                            examStore.getExamStoreInfo();
                        }
                    }
                });
            } else {
                $(this).removeClass('question_border_top_img').addClass('question_border_top_img_red');
                $(this).html('取消收藏');
            }
        },
        formationData:function(){
            var data =  { condition:{subjectId:examStore.selSubjectId, examType:examStore.selTypeId} };
            if(data.condition.hasOwnProperty('subjectId') && data.condition.subjectId == 0) {
                delete data.condition.subjectId;
            }
            if(data.condition.hasOwnProperty('examType') && data.condition.examType == 0) {
                delete data.condition.examType;
            }
            return data;
        },
        getExamStoreInfo : function(){
            if( examStore.loading ) { return false }
            examStore.loading = true;
            examStore.examStoreMsg = false;
            $('#page_examStore').pagination({
                dataSource: function (done) {
                    $.ajax({
                        url: '/member/examStore/' + this.pageNumber + '/' + this.pageSize,
                        type: 'POST',
                        data: examStore.formationData(),
                        dataType: 'json',
                        success: function (response) {
                            examStore.total = response.total;
                            examStore.loading = false;
                            //筛选条件显示与否
                            examStore.count = response.count;
                            if (response.status) {
                                response.total <= 3 ? examStore.display = false : examStore.display = true;
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                                examStore.examStoreMsg = false;
                            }else{
                                examStore.examStoreInfo = [];
                                examStore.examStoreMsg = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/member/examStore/' + pageNumber + '/' + pageSize,
                        data: examStore.formationData(),
                        dataType: 'json',
                        success: function (response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 3,
                pageNumber: 1,
                totalNumber: 1,
                className: "paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function (data) {
                    if (data) {
                        examStore.examStoreInfo = data;
                    }

                }
            })
        }
    });
    avalon.directive('shoudetail', {
        init: function (binding) {
            var elem = binding.element;
            avalon(elem).bind("click", function () {
                if($(this).parent('div').siblings('.question_answer').hasClass('hide')) {
                    $(this).parent('div').siblings('.question_answer').removeClass('hide');
                    $(this).parent('div').css('border-bottom', '1px dotted #f9881c');
                } else {
                    $(this).parent('div').siblings('.question_answer').addClass('hide');
                    $(this).parent('div').css('border', '1px solid silver');
                }
            })
        }
    });
    return {
        examStore: examStore
    }
});