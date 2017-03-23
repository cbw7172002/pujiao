/**
 * Created by Mr.H on 2017/1/19.
 */
avalon.directive("changecolor", {
    init: function (binding) {
        var elem = binding.element;
        avalon(elem).bind("click", function () {
            $(this).addClass('top_active').siblings('div').removeClass('top_active');
        })
    }
});
define([], function () {
    var indexStu = avalon.define({
        $id: 'indexStuController',
        examInfo: [],
        errorInfo: [],
        subjectInfo: [],
        userId: '',
        // 搜索
        condition: '',
        conditionSearch: function (value) {
            if (value == '') {
                alert('请输入查询条件');
            } else {
                if (indexStu.changeValue == 'exam') {
                    indexStu.getExamInfo(indexStu.userId);
                } else {
                    indexStu.getExamError(indexStu.userId);
                }
            }
        },
        // 试题和错题切换
        changeValue: 'exam',
        changeOption: function (value) {
            indexStu.changeValue = value;
            if (indexStu.changeValue == 'exam') {
                if (!indexStu.examInfo.length) {
                    indexStu.getExamInfo(indexStu.userId);
                }
            } else {
                if (!indexStu.errorInfo.length) {
                    indexStu.getExamError(indexStu.userId);
                }
            }
        },
        // 我的试题筛选条件
        myTestType: true,
        myTestStatus: true,
        myTest: {
            type: [],
            status: []
        },
        myErrorSubjectId: true,
        myErrorType: true,
        myError: {
            subjectId: [],
            type: []
        },
        getData: function (url, type, data, model, callback) {
            $.ajax({
                url: url,
                type: type || 'GET',
                data: data || {},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        indexStu[model] = response.data;
                    }
                    if (response.type) {
                        indexStu[model] = response.data;
                    }
                }, error: function (error) {
                }
            })
        },
        // 添加选择项
        addNum: function (flag, key, value) {
            indexStu[flag][key].push(value);
            var temp = key.slice(0, 1).toUpperCase() + key.slice(1);
            indexStu[flag + temp] = false;
            indexStu.chooseFun(flag);
        },
        // 删除选择项
        delNum: function (flag, key, value) {
            $.each(indexStu[flag][key], function (index, item) {
                // index是索引值（即下标）   item是每次遍历得到的值；
                if (item == value) {
                    indexStu[flag][key].splice(index, 1);
                }
            });
            var temp = key.slice(0, 1).toUpperCase() + key.slice(1);
            indexStu[flag + temp] = false;
            indexStu.chooseFun(flag);
        },
        // 筛选条件
        selectAll: function (flag, key) {
            var temp = key.slice(0, 1).toUpperCase() + key.slice(1);
            $("div[name=" + flag + temp + "]").addClass('option_active').siblings('div').removeClass('option_active');
            // flag : myTestType -> 我的试题(全部类型) myTestStatus -> 我的试题(全部状态)
            // flag : myErrorSubjectId -> 我的错题(全部科目) myErrorType -> 我的错题(全部类型)
            indexStu[flag + temp] = true;
            indexStu[flag][key] = [];
            indexStu.chooseFun(flag);
        },
        chooseFun: function (flag) {
            switch (flag) {
                case 'myTest':
                    indexStu.getExamInfo(indexStu.userId);
                    break;
                case 'myError':
                    indexStu.getExamError(indexStu.userId);
                    break;
            }
        },
        // 获取我的错题集
        errorLoading: true,
        errorMsg: false,
        errorDisplay: false,
        getExamError: function (userId) {
            $('.page_error').html('<div id="page_error"></div>');
            $('#page_error').pagination({
                dataSource: function (done) {
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageStu/getExamError',
                        data: {
                            pageNumber: this.pageNumber,
                            pageSize: this.pageSize,
                            userId: userId,
                            subjectId: indexStu.myErrorSubjectId ? 'all' : indexStu.myError.subjectId,
                            type: indexStu.myErrorType ? 'all' : indexStu.myError.type,
                            condition: indexStu.condition
                        },
                        dataType: "json",
                        success: function (response) {
                            indexStu.errorLoading = false;
                            if (response.status) {
                                if (response.count < 0) {
                                    indexStu.errorDisplay = false;
                                } else {
                                    indexStu.errorDisplay = true;
                                }
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                            } else {
                                indexStu.errorMsg = true;
                                indexStu.errorInfo = [];
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageStu/getExamError',
                        data: {
                            pageNumber: pageNumber,
                            pageSize: pageSize,
                            userId: userId,
                            type: indexStu.myErrorSubjectId ? 'all' : indexStu.myError.subjectId,
                            status: indexStu.myErrorType ? 'all' : indexStu.myError.type,
                            condition: indexStu.condition
                        },
                        dataType: "json",
                        success: function (response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 15,
                pageNumber: 1,
                totalNumber: 1,
                className: "paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function (data) {
                    if (data) {
                        indexStu.errorInfo = data;
                    }
                }
            })
        },
        // 获取我的试题
        examLoading: true,
        examMsg: false,
        examDisplay: false,
        getExamInfo: function (userId) {
            $('.page_exam').html('<div id="page_exam"></div>');
            $('#page_exam').pagination({
                dataSource: function (done) {
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageStu/getExamInfo',
                        data: {
                            pageNumber: this.pageNumber,
                            pageSize: this.pageSize,
                            userId: userId,
                            type: indexStu.myTestType ? 'all' : indexStu.myTest.type,
                            status: indexStu.myTestStatus ? 'all' : indexStu.myTest.status,
                            condition: indexStu.condition
                        },
                        dataType: "json",
                        success: function (response) {
                            indexStu.examLoading = false;
                            if (response.status) {
                                if (response.count < 0) {
                                    indexStu.examDisplay = false;
                                } else {
                                    indexStu.examDisplay = true;
                                }
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                            } else {
                                indexStu.examMsg = true;
                                indexStu.examInfo = [];
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageStu/getExamInfo',
                        data: {
                            pageNumber: pageNumber,
                            pageSize: pageSize,
                            userId: userId,
                            type: indexStu.myTestType ? 'all' : indexStu.myTest.type,
                            status: indexStu.myTestStatus ? 'all' : indexStu.myTest.status,
                            condition: indexStu.condition
                        },
                        dataType: "json",
                        success: function (response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 15,
                pageNumber: 1,
                totalNumber: 1,
                className: "paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function (data) {
                    if (data) {
                        indexStu.examInfo = data;
                    }
                }
            })
        },
        // 弹窗信息
        popInfo: {
            title: '',
            paperId: '',
            submitTime: '',
            completeTime: '',
            type: ''
        },
        // 开始答题
        popValue: false,
        // el.score, el.type, el.title, el.submitTime, el.completeTime, 'startTest'
        popUp: function (value, type, el) {
            if (type == 1){ // 打开弹窗
                if (el.answerId == null) { // 未答题
                    indexStu.popInfo.title = el.title;
                    indexStu.popInfo.submitTime = el.submitTime;
                    indexStu.popInfo.completeTime = el.completeTime;
                    indexStu.popInfo.paperId = el.id;
                    indexStu.popInfo.type = el.type;
                    indexStu.popValue = value;
                } else { // 已答题
                    indexStu.popValue = false;
                    if (el.type == 1) { // 作业已答页面
                        location.href = '/evaluateManageStu/studentPaperStu/' + el.id + '/' + indexStu.userId;
                    } else { // 测验已答页面
                        location.href = '/evaluateManageStu/studentTestPaperStu/' + el.id + '/' + indexStu.userId;
                    }
                }
            } else { // 关闭弹窗
                indexStu.popValue = false;
            }
        },
        goToTest: function (type, paperId) {
            indexStu.popUp(false, 2);
            if (type == 1) {
                window.open('/evaluateManageStu/studentNoAnswer/' + paperId);
            } else {
                window.open('/evaluateManageStu/studentTestNoAnswer/' + paperId);
            }
        }
    });
    return indexStu;
});