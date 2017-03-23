avalon.directive("changecolor", {
    init: function (binding) {
        var elem = binding.element
        avalon(elem).bind("click", function () {
            $(this).addClass('top_active').siblings('div').removeClass('top_active');
        })
    }
});

define([], function () {
    var indexTea = avalon.define({
        $id: 'indexTeaController',
        gradeInfo: [], // 全部年级信息
        subjectInfo: [], // 全部科目信息
        classInfo: [], // 全部班级信息
        teacherId: '',
        teacherClass: [], // 当前登录老师所教班级
        teacherSubject: [], // 当前登录老师所教学科
        teacherGrade: [], // 当前登录老师所教学科
        hashInfo: [],
        nsb: false,
        // 课程归属信息
        lessonType: [],
        // 选中的课程归属
        lessonTypeCheck: '', typeWarn: false,
        selectType: function (a) {
            indexTea.lessonTypeCheck = a;
            indexTea.getData('/evaluateManageTea/getLessonChapter', 'POST', {id: a}, 'lessonChapter');
            indexTea.lessonChapterCheck = 0;
            if(a !== ''){
                indexTea.typeWarn = false;
            }
        },
        // 所属章节信息
        lessonChapter: [],
        lessonChapterCheck: 0, chapterWarn: false,
        selectChapter: function (a) {
            indexTea.lessonChapterCheck = a;
            if(a !== 0){
                indexTea.chapterWarn = false;
            }
        },
        // 试卷标题
        lessonTitle: '', titleWarn: false,
        // 存放添加布置第一步的变量
        lessonTemp: '',
        // 平台题库
        paperInfo: [],
        choosePaper: function (flag1, flag2) {
            indexTea.lessonTemp = indexTea.lessonTypeCheck + '-' + indexTea.lessonChapterCheck + '/' + indexTea.lessonTitle;
            if (indexTea.lessonTypeCheck == '') {
                indexTea.typeWarn = true; return;
            } else {
                indexTea.typeWarn = false;
            }
            if (indexTea.lessonChapterCheck == 0) {
                indexTea.chapterWarn = true; return;
            } else {
                indexTea.chapterWarn = false;
            }
            if (indexTea.lessonTitle == '') {
                indexTea.titleWarn = true; return;
            } else {
                indexTea.titleWarn = false;
            }
            if (indexTea.lessonTypeCheck && indexTea.lessonChapterCheck && indexTea.lessonTitle) {
                $('.' + flag1).hide();
                $('.' + flag2).show();
                indexTea.getPaperInfo(1);
            }
        },
        blueOption: true,
        getPaperInfo: function (flag) {
            if (flag == 1) {
                indexTea.getData('/evaluateManageTea/getPaper', 'POST', {'chapter': indexTea.lessonChapterCheck, 'type': 0}, 'paperInfo');
                indexTea.blueOption = true;
            } else {
                indexTea.getData('/evaluateManageTea/getPaper', 'POST', {'chapter': indexTea.lessonChapterCheck, 'type': 1}, 'paperInfo');
                indexTea.blueOption = false;
            }

        },
        // 搜索
        condition: '',
        conditionSearch: function (value) {
            if (value == '') {
                alert('请输入查询条件');
            } else {
                if (indexTea.changeValue == 'onlineQuestion') {
                    indexTea.getOnlineInfo();
                } else if (indexTea.changeValue == 'testDecorated') {
                    indexTea.getTestInfo();
                } else if (indexTea.changeValue == 'questionCorrect') {
                    indexTea.getQuestionInfo();
                } else {
                    indexTea.getQueryInfo();
                }
            }
        },
        colorOption: true,
        changeSort: function (sort) {
            if (sort == 1) {
                indexTea.colorOption = true;
            } else {
                indexTea.colorOption = false;
            }
            indexTea.online.sort = sort;
            indexTea.getOnlineInfo();
        },
        getData: function (url, type, data, model, callback) {
            $.ajax({
                url: url,
                type: type || 'GET',
                data: data || {},
                dataType: 'json',
                success: function (response) {
                    if(response.status || response.type){
                        if (model == 'teacherInfo'){
                            indexTea.teacherClass = response.class;
                            indexTea.teacherSubject = response.subject;
                            indexTea.teacherGrade = response.grade;
                        } else if (model == 'questionCorrectDetail'){
                            indexTea.questionCorrectDetail = response.data;
                            indexTea.num = response.num;
                        } else if (model == 'subjective'){
                            indexTea.subjective.title = response.data.title;
                            indexTea.subjective.score = response.data.score;
                            indexTea.subjective.trueAnswer = response.data.answer;
                            indexTea.submitScore.questionId = response.data.id;
                            $('.question_detail').show();
                            $('.shadow').show();
                        } else if (model == 'submitSubject'){
                            $('.question_detail').hide();
                            $('.shadow').hide();
                            indexTea.studentPaperId = window.location.hash.split('#')[2];
                            indexTea.studentClassId = window.location.hash.split('#')[3];
                            indexTea.showPaper('question_correct', indexTea.studentPaperId, indexTea.studentClassId, 2);
                        } else{
                            indexTea[model] = response.data;
                        }
                    } else {
                        if (model == 'questionCorrectDetail'){
                            indexTea.questionCorrectDetail = [];
                            indexTea.num = [];
                        }
                    }
                }, error: function (error) {
                }
            })
        },
        num: [], // 解答题题号
        questionCorrectDetail: [], // 解答题内容
        studentPaperId: '',
        studentClassId: '',
        showPaper: function (flag, paperId, classId, type) { // 显示试卷详细内容
            if (parseInt(type) == 1) {
                window.open('/evaluateManageTea/index#questionCorrect#' + paperId + '#' + classId);
            } else {
                indexTea.studentPaperId = paperId;
                indexTea.studentClassId = classId;
                $('.' + flag).css('display', 'none');
                $('div[name="questionCorrect"]').css('display', 'none');
                $('.' + flag + '_detail').css('display', 'block');
                indexTea.getData('/evaluateManageTea/getQuestionCorrectDetail/' + paperId + '/' + classId, 'GET', '', 'questionCorrectDetail');
            }
        },
        subjective: {
            index: '',
            title: '',
            answer: '',
            trueAnswer: '',
            score: ''
        },
        showQuestion: function (flag, type, el, answerId) { // type 1 => open 2 => close 弹窗
            if (type == 1) {
                if (flag == 'question_detail'){
                    indexTea.subjective.index = el.index;
                    indexTea.subjective.answer = el.answer;
                    indexTea.submitScore.answerId = answerId;
                    indexTea.submitScore.score = el.score;
                    indexTea.submitScore.comment = el.comment;
                    indexTea.submitScore.questionId = el.id;
                    indexTea.getData('/evaluateManageTea/getSubjective', 'POST', {id: el.id, type: el.type}, 'subjective');
                }else{
                    $('.' + flag).show();
                    $('.shadow').show();
                }
            } else {
                if(flag == 'test_decorated_pop' || flag == 'choose_paper_pop'){ // 关闭第一步弹窗
                    indexTea.lessonTypeCheck = '';
                    indexTea.lessonChapterCheck = 0;
                    indexTea.lessonTitle = '';
                }
                $('.' + flag).hide();
                $('.shadow').hide();
            }
        },
        submitScore: {
            answerId: '',
            score: '',
            comment: '',
            index: '',
            aScore: '',
            questionId: ''
        },
        submitSubject: function (index, score, comment) {
            indexTea.submitScore.index = index;
            indexTea.submitScore.aScore = score;
            indexTea.submitScore.comment = comment;
            indexTea.getData('/evaluateManageTea/submitSubject', 'POST', {info: indexTea.submitScore}, 'submitSubject');
        },
        // 选项切换
        changeValue: 'onlineQuestion',
        changeOption: function (value) {
            indexTea.changeValue = value;
            indexTea.condition = '';
            window.location.hash = value;
            if (window.location.hash.split('#').length == 4) {
                $('div[name="questionCorrect"]').css('display', 'none');
                $('.question_correct').css('display', 'none');
            } else {
                if (value == 'questionCorrect') {
                    $('div[name="questionCorrect"]').css('display', 'block');
                    $('.question_correct').css('display', 'block');
                }
                $('.question_correct_detail').css('display', 'none');
            }
            switch (value) {
                case 'onlineQuestion':
                    indexTea.getOnlineInfo();
                    break;
                case 'testDecorated':
                    indexTea.getTestInfo();
                    break;
                case 'questionCorrect':
                    indexTea.getQuestionInfo();
                    break;
                case 'queryResult':
                    indexTea.getQueryInfo();
                    break;
            }
        },
        // 在线题库条件
        onlineGradeId: true, onlineSubjectId: true, onlineType: true,
        online: {
            gradeId: [],
            subjectId: [],
            type: [],
            sort: 1
        },
        // 试题布置条件
        testGradeId: true, testSubjectId: true, testType: true,
        test: {
            gradeId: [],
            subjectId: [],
            type: []
        },
        // 试题批改条件
        questionSubjectId: true, questionClassId: true, questionStatus: true, questionType: true,
        question: {
            subjectId: [],
            classId: [],
            status: [],
            type: []
        },
        // 成绩查询条件
        querySubjectId: true, queryClassId: true, queryType: true,
        query: {
            subjectId: [],
            classId: [],
            type: []
        },
        // 添加选择项
        addNum: function (flag, key, value) {
            indexTea[flag][key].push(value);
            var temp = key.slice(0, 1).toUpperCase() + key.slice(1);
            indexTea[flag + temp] = false;
            indexTea.chooseFun(flag);
        },
        // 删除选择项
        delNum: function (flag, key, value) {
            $.each(indexTea[flag][key], function (index, item) {
                // index是索引值（即下标）   item是每次遍历得到的值；
                if (item == value) {
                    indexTea[flag][key].splice(index, 1);
                }
            });
            var temp = key.slice(0, 1).toUpperCase() + key.slice(1);
            indexTea[flag + temp] = false;
            indexTea.chooseFun(flag);
        },
        // 筛选条件
        selectAll: function (flag, key) {
            var temp = key.slice(0, 1).toUpperCase() + key.slice(1);
            $("div[name=" + flag + temp + "]").addClass('option_active').siblings('div').removeClass('option_active');
            // flag : onlineGradeId -> 在线题库(全部年级) onlineSubjectId -> 在线题库(全部科目) onlineType -> 在线题库(全部类型)
            // flag : testGradeId -> 试题布置(全部年级) testSubjectId -> 试题布置(全部科目) testType -> 试题布置(全部类型)
            // flag : questionSubjectId -> 试题批改(全部学科) questionClassId -> 试题批改(全部班级) questionStatus -> 试题批改(全部状态) questionType -> 试题批改(全部类型)
            // flag : querySubjectId -> 成绩查询(全部学科) queryClassId -> 成绩查询(全部班级)  queryType-> 成绩查询(全部类型)
            indexTea[flag + temp] = true;
            indexTea[flag][key] = [];
            indexTea.chooseFun(flag);
        },
        chooseFun: function (flag) {
            switch (flag) {
                case 'online':
                    indexTea.getOnlineInfo();
                    break;
                case 'test':
                    indexTea.getTestInfo();
                    break;
                case 'question':
                    indexTea.getQuestionInfo();
                    break;
                case 'query':
                    indexTea.getQueryInfo();
                    break;
            }
        },
        onlineInfo: [],
        onlineDisplay: false,
        onlineLoading: true,
        onlineMsg: false,
        getOnlineInfo: function () {
            $('.page_online').html('<div id="page_online"></div>');
            $('#page_online').pagination({
                    dataSource: function (done) {
                        $.ajax({
                            type: 'POST',
                            url: '/evaluateManageTea/getOnlineQuestion',
                            data: {
                                pageNumber: this.pageNumber,
                                pageSize: this.pageSize,
                                type: indexTea.onlineType ? 'all' : indexTea.online.type,
                                gradeId: indexTea.onlineGradeId ? 'all' : indexTea.online.gradeId,
                                subjectId: indexTea.onlineSubjectId ? 'all' : indexTea.online.subjectId,
                                sort: indexTea.online.sort,
                                condition: indexTea.condition
                            },
                            dataType: "json",
                            success: function (response) {
                                indexTea.onlineLoading = false;
                                if (response.status) {
                                    if (response.count < 0) {
                                        indexTea.onlineDisplay = false;
                                    } else {
                                        indexTea.onlineDisplay = true;
                                    }
                                    var format = [];
                                    format['data'] = response.data;
                                    format['totalNumber'] = response.count;
                                    done(format);
                                } else {
                                    indexTea.onlineMsg = true;
                                    indexTea.onlineInfo = [];
                                }
                            }
                        });
                    },
                    getData: function (pageNumber, pageSize) {
                        var self = this;
                        $.ajax({
                            type: 'POST',
                            url: '/evaluateManageTea/getOnlineQuestion',
                            data: {
                                pageNumber: pageNumber,
                                pageSize: pageSize,
                                type: indexTea.onlineType ? 'all' : indexTea.online.type,
                                gradeId: indexTea.onlineGradeId ? 'all' : indexTea.online.gradeId,
                                subjectId: indexTea.onlineSubjectId ? 'all' : indexTea.online.subjectId,
                                sort: indexTea.online.sort,
                                condition: indexTea.condition
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
                            indexTea.onlineInfo = data;
                        }
                    }
                })
        },
        testInfo: [],
        testDisplay: false,
        testLoading: true,
        testMsg: false,
        getTestInfo: function () {
            $('.page_test').html('<div id="page_test"></div>');
            $('#page_test').pagination({
                dataSource: function (done) {
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageTea/getTestInfo',
                        data: {
                            pageNumber: this.pageNumber,
                            pageSize: this.pageSize,
                            type: indexTea.testType ? 'all' : indexTea.test.type,
                            gradeId: indexTea.testGradeId ? 'all' : indexTea.test.gradeId,
                            subjectId: indexTea.testSubjectId ? 'all' : indexTea.test.subjectId,
                            condition: indexTea.condition,
                            teacherId: indexTea.teacherId
                        },
                        dataType: "json",
                        success: function (response) {
                            indexTea.testLoading = false;
                            if (response.status) {
                                if (response.count < 0) {
                                    indexTea.testDisplay = false;
                                } else {
                                    indexTea.testDisplay = true;
                                }
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                            } else {
                                indexTea.testMsg = true;
                                indexTea.testInfo = [];
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageTea/getTestInfo',
                        data: {
                            pageNumber: pageNumber,
                            pageSize: pageSize,
                            type: indexTea.testType ? 'all' : indexTea.test.type,
                            gradeId: indexTea.testGradeId ? 'all' : indexTea.test.gradeId,
                            subjectId: indexTea.testSubjectId ? 'all' : indexTea.test.subjectId,
                            condition: indexTea.condition,
                            teacherId: indexTea.teacherId
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
                        indexTea.testInfo = data;
                    }
                }
            })
        },
        questionInfo: [],
        questionDisplay: false,
        questionLoading: true,
        questionMsg: false,
        getQuestionInfo: function () {
            $('.page_question').html('<div id="page_question"></div>');
            $('#page_question').pagination({
                dataSource: function (done) {
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageTea/getQuestionInfo',
                        data: {
                            pageNumber: this.pageNumber,
                            pageSize: this.pageSize,
                            teacherId: indexTea.teacherId,
                            type: indexTea.questionType ? 'all' : indexTea.question.type,
                            status: indexTea.questionStatus ? 'all' : indexTea.question.status,
                            classId: indexTea.questionClassId ? 'all' : indexTea.question.classId,
                            subjectId: indexTea.questionSubjectId ? 'all' : indexTea.question.subjectId,
                            condition: indexTea.condition
                        },
                        dataType: "json",
                        success: function (response) {
                            indexTea.questionLoading = false;
                            if (response.status) {
                                if (response.count < 0) {
                                    indexTea.questionDisplay = false;
                                } else {
                                    indexTea.questionDisplay = true;
                                }
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                            } else {
                                indexTea.questionMsg = true;
                                indexTea.questionInfo = [];
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageTea/getQuestionInfo',
                        data: {
                            pageNumber: pageNumber,
                            pageSize: pageSize,
                            teacherId: indexTea.teacherId,
                            type: indexTea.questionType ? 'all' : indexTea.question.type,
                            status: indexTea.questionStatus ? 'all' : indexTea.question.status,
                            classId: indexTea.questionClassId ? 'all' : indexTea.question.classId,
                            subjectId: indexTea.questionSubjectId ? 'all' : indexTea.question.subjectId,
                            condition: indexTea.condition
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
                        indexTea.questionInfo = data;
                    }
                }
            })
        },
        queryInfo: [],
        queryDisplay: false,
        queryLoading: true,
        queryMsg: false,
        getQueryInfo: function () {
            $('.page_query').html('<div id="page_query"></div>');
            $('#page_query').pagination({
                dataSource: function (done) {
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageTea/getQueryInfo',
                        data: {
                            pageNumber: this.pageNumber,
                            pageSize: this.pageSize,
                            teacherId: indexTea.teacherId,
                            type: indexTea.queryType ? 'all' : indexTea.query.type,
                            subjectId: indexTea.querySubjectId ? 'all' : indexTea.query.subjectId,
                            classId: indexTea.queryClassId ? 'all' : indexTea.query.classId,
                            condition: indexTea.condition
                        },
                        dataType: "json",
                        success: function (response) {
                            indexTea.queryLoading = false;
                            if (response.status) {
                                if (response.count < 0) {
                                    indexTea.queryDisplay = false;
                                } else {
                                    indexTea.queryDisplay = true;
                                }
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                            } else {
                                indexTea.queryMsg = true;
                                indexTea.queryInfo = [];
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/evaluateManageTea/getQueryInfo',
                        data: {
                            pageNumber: pageNumber,
                            pageSize: pageSize,
                            teacherId: indexTea.teacherId,
                            type: indexTea.queryType ? 'all' : indexTea.query.type,
                            subjectId: indexTea.querySubjectId ? 'all' : indexTea.query.subjectId,
                            classId: indexTea.queryClassId ? 'all' : indexTea.query.classId,
                            condition: indexTea.condition
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
                        indexTea.queryInfo = data;
                    }
                }
            })
        },
        goStudentPaper: function (userId, paperType, paperId) {
            if (paperId) {
                if (paperType == 1) {
                    location.href = '/evaluateManageTea/homeScore/' + paperId + '/' + userId;
                } else {
                    location.href = '/evaluateManageTea/testScore/' + paperId + '/' + userId;
                }
            } else {
                alert('该学生未答题！');
            }

        }
    });
    return indexTea;
});