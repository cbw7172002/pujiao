/**
 * Created by Mr.H on 2017/2/14.
 */
define([], function () {

    var synchroTest = avalon.define({
        $id: 'synchroTest',
        leadLearnInfo: [], // 课前导学
        classTeachInfo: [], // 课堂授课
        afterClassInfo: [], // 课后指导
        paperAttr: '',  // 试卷特征
        basicInfo: [], // 试卷基本信息
        paperInfo: [], // 作业详细信息
        userId: '', // 登录用户ID
        // 测验试卷信息
        testInfo: {
            sChoose: [],
            mChoose: [],
            judge: [],
            completion: [],
            subjective: []
        },
        timeSpend: 0,
        timeLeave: '',
        getData: function (url, type, data, model, callback) {
            $.ajax({
                url: url,
                type: type || 'GET',
                data: data || {},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        if (model == 'paperInfo') {
                            synchroTest.timeSpend = new Date().getTime();
                            synchroTest[model] = response.data;
                            synchroTest.basicInfo = response.basicInfo;
                        } else if (model == 'submitPaper') {
                            $('.shadow').show();
                            $('.report').show();
                        } else if (model == 'submitTestPaper') {
                            $('.shadow').show();
                            $('.report').show();
                        } else if (model == 'saveHomework') {
                            synchroTest.seeScore(true);
                        } else if (model == 'testInfo') {
                            synchroTest.timeSpend = new Date().getTime();
                            synchroTest.basicInfo = response.basicInfo;
                            synchroTest.testInfo.sChoose = response.data.sChoose;
                            synchroTest.testInfo.mChoose = response.data.mChoose;
                            synchroTest.testInfo.judge = response.data.judge;
                            synchroTest.testInfo.completion = response.data.completion;
                            synchroTest.testInfo.subjective = response.data.subjective;
                            $('.student_test').show().siblings().hide();
                            synchroTest.min = synchroTest.basicInfo.showTime;
                            synchroTest.sec = 0;
                            synchroTest.timeLeave = synchroTest.basicInfo.timeLeave;
                            synchroTest.setTime();
                        } else if (model == 'testAnswerInfo') {
                            synchroTest.basicInfo = response.basicInfo;
                            synchroTest.testInfo.sChoose = response.data.sChoose;
                            synchroTest.testInfo.mChoose = response.data.mChoose;
                            synchroTest.testInfo.judge = response.data.judge;
                            synchroTest.testInfo.completion = response.data.completion;
                            synchroTest.testInfo.subjective = response.data.subjective;
                        } else if (model == 'saveTestAnswer') {

                        } else if (model == 'moveAnswer') {

                        } else {
                            synchroTest[model] = response.data;
                        }
                    }
                }, error: function (error) {
                }
            })
        },
        popContent: {
            title: '',
            paperId: '',
            submitTime: '',
            completeTime: '',
            type: ''
        },
        showPop: function (el, type) {
            if (el.isAnswer) {
                if (el.type == 1) { // 作业已答样式
                    synchroTest.getData('/studentCourse/getHomeWorkInfo/' + el.paperId + '/' + synchroTest.userId, 'GET', '', 'paperInfo');
                    $('.student_paper').show().siblings().hide();
                } else { // 测验已答样式
                    synchroTest.getData('/studentCourse/getTestAnswerInfo/' + el.paperId + '/' + synchroTest.userId, 'GET', '', 'testAnswerInfo');
                    $('.student_answer').show().siblings().hide();
                }
            } else {
                $('.shadow').show();
                $('.start_test').show();
            }
            synchroTest.popContent.type = type; // type 表示 课前 课堂 课后
            synchroTest.popContent.paperType = el.type; // 作业样式 测验样式
            synchroTest.popContent.title = el.title;
            synchroTest.popContent.paperId = el.paperId;
            synchroTest.popContent.submitTime = el.submitTime;
            synchroTest.popContent.completeTime = el.completeTime;
        },
        closePop: function () {
            $('.shadow').hide();
            $('.start_test').hide();
        },
        getPaper: function (paperId, paperType, type) {
            switch (paperType) {
                case 1:
                    switch (type) {
                        case 1:
                            synchroTest.paperAttr = '课前导学作业习题';
                            break;
                        case 2:
                            synchroTest.paperAttr = '课堂授课作业习题';
                            break;
                        case 3:
                            synchroTest.paperAttr = '课后指导作业习题';
                            break;
                    }
                    break;
                case 2:
                    switch (type) {
                        case 1:
                            synchroTest.paperAttr = '课前导学测试习题';
                            break;
                        case 2:
                            synchroTest.paperAttr = '课堂授课测试习题';
                            break;
                        case 3:
                            synchroTest.paperAttr = '课后指导测试习题';
                            break;
                    }
                    break;
            }
            $('.shadow').hide();
            $('.start_test').hide();
            if (paperType == 1) { // 作业未答样式
                synchroTest.getData('/studentCourse/getPaperInfo/' + paperId, 'GET', '', 'paperInfo');
                $('.student_homework').show().siblings().hide();
            } else { // 测验未答样式
                synchroTest.getData('/studentCourse/getTestPaperInfo/' + paperId, 'GET', '', 'testInfo');
            }
        },
        // 数字转换字母
        changeCode: function (value) {
            return String.fromCharCode(parseInt(value) + 65);
        },
        // 单选题 作业
        singleChoose: function (index, sort, trueAnswer, score) {
            $(this).next('div').removeClass('no_choose').addClass('has_choose');
            $(this).parent('div').parent('div').siblings('div').children().children(':nth-child(2)').addClass('no_choose').removeClass('has_choose');
            var answer = synchroTest.changeCode(index);
            synchroTest.paperInfo[sort - 1].newAnswer = answer;
            if (answer == trueAnswer) {
                synchroTest.paperInfo[sort - 1].newScore = score;
            } else {
                synchroTest.paperInfo[sort - 1].newScore = 0;
            }
        },
        // 多选题 作业
        mChooseTemp: [],
        mChooseOption: true,
        manyCF: 0,
        manyChoose: function (index, sort, trueAnswer, score) {
            if (index != synchroTest.manyCF) {
                synchroTest.mChooseTemp = [];
            }
            synchroTest.manyCF = index;
            if (synchroTest.mChooseOption && (synchroTest.paperInfo[sort - 1].newAnswer != '')) {
                synchroTest.mChooseTemp = synchroTest.paperInfo[sort - 1].newAnswer.split('┼┼');
            }
            if ($(this).next('div').hasClass('no_choose')) {
                $(this).next('div').removeClass('no_choose').addClass('has_choose');
                synchroTest.mChooseTemp.push(synchroTest.changeCode(index));
            } else {
                $(this).next('div').removeClass('has_choose').addClass('no_choose');
                $.each(synchroTest.mChooseTemp, function (i, item) {
                    // index是索引值（即下标）   item是每次遍历得到的值；
                    if (synchroTest.changeCode(index) == item) {
                        synchroTest.mChooseTemp.splice(i, 1);
                    }
                });
            }
            synchroTest.paperInfo[sort - 1].newAnswer = synchroTest.mChooseTemp.sort().join('┼┼');
            if (trueAnswer.sort().toString() == synchroTest.mChooseTemp.sort().toString()) {
                synchroTest.paperInfo[sort - 1].newScore = score;
            } else {
                synchroTest.paperInfo[sort - 1].newScore = 0;
            }
        },
        // 判断题 作业
        judgeTest: function (answer, sort, trueAnswer, score) {
            $(this).next('div').removeClass('no_choose').addClass('has_choose');
            $(this).parent('div').siblings('div').children(':nth-child(2)').addClass('no_choose').removeClass('has_choose');
            synchroTest.paperInfo[sort - 1].newAnswer = answer;
            if (answer == trueAnswer) {
                synchroTest.paperInfo[sort - 1].newScore = score;
            } else {
                synchroTest.paperInfo[sort - 1].newScore = 0;
            }
        },
        // 单选题 测试
        tSingleChoose: function (index, oldIndex, trueAnswer, score) {
            $(this).next('div').removeClass('no_choose').addClass('has_choose');
            $(this).parent('div').parent('div').siblings('div').children().children(':nth-child(2)').addClass('no_choose').removeClass('has_choose');
            var answer = synchroTest.changeCode(index);
            synchroTest.testInfo.sChoose[oldIndex].newAnswer = answer;
            if (answer == trueAnswer) {
                synchroTest.testInfo.sChoose[oldIndex].newScore = score;
            } else {
                synchroTest.testInfo.sChoose[oldIndex].newScore = 0;
            }
        },
        // 多选题 测试
        tMChooseTemp: [],
        tManyCF: 0,
        tManyChoose: function (index, oldIndex, trueAnswer, score) {
            if (oldIndex != synchroTest.tManyCF) {
                synchroTest.mChooseTemp = [];
            }
            synchroTest.tManyCF = oldIndex;
            if (synchroTest.testInfo.mChoose[oldIndex].newAnswer != '') {
                synchroTest.tMChooseTemp = synchroTest.testInfo.mChoose[oldIndex].newAnswer.split('┼┼');
            }
            if ($(this).next('div').hasClass('no_choose')) {
                $(this).next('div').removeClass('no_choose').addClass('has_choose');
                synchroTest.tMChooseTemp.push(synchroTest.changeCode(index));
            } else {
                $(this).next('div').removeClass('has_choose').addClass('no_choose');
                $.each(synchroTest.tMChooseTemp, function (i, item) {
                    // index是索引值（即下标）   item是每次遍历得到的值；
                    if (synchroTest.changeCode(index) == item) {
                        synchroTest.tMChooseTemp.splice(i, 1);
                    }
                });
            }
            synchroTest.testInfo.mChoose[oldIndex].newAnswer = synchroTest.tMChooseTemp.sort().join('┼┼');
            if (trueAnswer.sort().toString() == synchroTest.tMChooseTemp.sort().toString()) {
                synchroTest.testInfo.mChoose[oldIndex].newScore = score;
            } else {
                synchroTest.testInfo.mChoose[oldIndex].newScore = 0;
            }
        },
        // 判断题 测试
        tJudgeTest: function (answer, oldIndex, trueAnswer, score) {
            $(this).next('div').removeClass('no_choose').addClass('has_choose');
            $(this).parent('div').siblings('div').children(':nth-child(2)').addClass('no_choose').removeClass('has_choose');
            synchroTest.testInfo.judge[oldIndex].newAnswer = answer;
            if (answer == trueAnswer) {
                synchroTest.testInfo.judge[oldIndex].newScore = score;
            } else {
                synchroTest.testInfo.judge[oldIndex].newScore = 0;
            }
        },
        report: { // 报告
            num: 0, // 作答总数
            kNum: 0, // 总题数
            time: 0, // 耗费时间
            TNum: 0, // 正确题数
            tPercent: 0,
            FNum: 0, // 错误题数
            fPercent: 0,
            NNum: 0, // 未答题数
            nPercent: 0,
            subjectiveNum: 0 // 解答题数目
        },
        // 提交试卷
        submitAnswer: function (type) {
            if (type == 1) { // 作业试卷
                synchroTest.timeSpend = synchroTest.changeTime(new Date().getTime() - synchroTest.timeSpend);
                var postInfo = {};
                $.each(synchroTest.paperInfo, function (index, item) {
                    var temp = {};
                    temp.index = item.sort;
                    temp.type = item.type;
                    temp.id = item.id;
                    if (item.type == 1) {
                        if (item.answer == item.newAnswer) {
                            synchroTest.report.TNum += 1; // 正确题数加一
                            temp.score = item.score;
                        } else {
                            if (item.newAnswer === '') {
                                temp.score = '';
                            } else {
                                temp.score = 0;
                            }
                        }
                        temp.answer = item.newAnswer;
                        if (item.newAnswer === '') { // 未答题数加一
                            synchroTest.report.NNum += 1;
                        }
                    } else if (item.type == 2) {
                        temp.answer = item.newAnswer;
                        if (item.answerStr === item.newAnswer) {
                            synchroTest.report.TNum += 1; // 正确题数加一
                            temp.score = item.newScore;
                        } else {
                            if (item.newAnswer === '') {
                                temp.score = '';
                            } else {
                                temp.score = 0;
                            }
                        }
                        if (item.newAnswer === '') { // 未答题数加一
                            synchroTest.report.NNum += 1;
                        }
                    } else if (item.type == 3) {
                        if (item.answer == item.newAnswer) {
                            synchroTest.report.TNum += 1; // 正确题数加一
                            temp.score = item.score;
                        } else {
                            if (item.newAnswer === '') {
                                temp.score = '';
                            } else {
                                temp.score = 0;
                            }
                        }
                        temp.answer = item.newAnswer;
                        if (item.newAnswer === '') { // 未答题数加一
                            synchroTest.report.NNum += 1;
                        }
                    } else if (item.type == 4) {
                        if (item.answerNum > 1) {
                            var arr = [];
                            var tem = 0;
                            for (var i = 0; i < item.answerNum; i++) {
                                if (item['ans' + i] === item['right' + i]) {
                                    tem = tem + 1;
                                }
                                arr.push(item['ans' + i]);
                            }
                            temp.answer = arr.join('┼┼');
                            if (tem == item.answerNum) {
                                temp.score = item.score;
                                synchroTest.report.TNum += 1; // 正确题数加一
                            } else {
                                if (temp.answer === '┼┼') {
                                    temp.score = '';
                                } else {
                                    temp.score = (item.score / item.answerNum) * tem;
                                }
                            }
                            if (temp.answer === '┼┼') { // 未答题数加一
                                synchroTest.report.NNum += 1;
                            }
                        } else {
                            temp.answer = item.ans0;
                            if (temp.answer == item.answer) {
                                temp.score = item.score;
                                synchroTest.report.TNum += 1; // 正确题数加一
                            } else {
                                if (item.ans0 === '') {
                                    temp.score = '';
                                } else {
                                    temp.score = 0;
                                }
                            }
                            if (temp.answer === '') { // 未答题数加一
                                synchroTest.report.NNum += 1;
                            }
                        }
                    } else if (item.type == 5) { // 解答题
                        temp.answer = item.newAnswer;
                        temp.score = '';
                        temp.comment = '';
                        synchroTest.report.subjectiveNum += 1; // 计算解答题数目
                    }
                    postInfo[index] = temp;
                });
                // 填充报告信息
                synchroTest.report.num = synchroTest.paperInfo.length - synchroTest.report.subjectiveNum;
                synchroTest.report.kNum = synchroTest.paperInfo.length;
                synchroTest.report.time = synchroTest.timeSpend;
                synchroTest.report.FNum = synchroTest.report.num - synchroTest.report.TNum - synchroTest.report.NNum; // 错误数目
                synchroTest.report.fPercent = synchroTest.changePercent(synchroTest.report.FNum, synchroTest.report.num); // 错误数目百分比
                synchroTest.report.tPercent = synchroTest.changePercent(synchroTest.report.TNum, synchroTest.report.num); // 正确数目百分比
                synchroTest.report.nPercent = synchroTest.changePercent(synchroTest.report.NNum, synchroTest.report.num); // 正确数目百分比
                var temp = JSON.stringify(postInfo);
                synchroTest.getData('/studentCourse/submitPaper', 'POST', {
                    userId: synchroTest.userId,
                    pId: synchroTest.popContent.paperId,
                    type: synchroTest.basicInfo.type,
                    answer: temp
                }, 'submitPaper');
            } else { // 测验试卷
                var timeTemp = new Date().getTime() - synchroTest.timeSpend + parseInt(synchroTest.timeLeave);
                synchroTest.timeSpend = synchroTest.changeTime(timeTemp);
                synchroTest.saveFlag = false;
                if (synchroTest.testInfo.sChoose) {
                    var a = synchroTest.changeObj(synchroTest.testInfo.sChoose);
                }
                if (synchroTest.testInfo.mChoose) {
                    var b = synchroTest.changeObj(synchroTest.testInfo.mChoose);
                }
                if (synchroTest.testInfo.judge) {
                    var c = synchroTest.changeObj(synchroTest.testInfo.judge);
                }
                if (synchroTest.testInfo.completion) {
                    var d = synchroTest.changeObj(synchroTest.testInfo.completion);
                }
                if (synchroTest.testInfo.subjective) {
                    var e = synchroTest.changeObj(synchroTest.testInfo.subjective);
                }
                var postInfo = {
                    'sChoose': a,
                    'mChoose': b,
                    'judge': c,
                    'completion': d,
                    'subjective': e
                };
                synchroTest.getData('/studentCourse/submitTestPaper', 'POST', {
                    userId: synchroTest.userId,
                    pId: synchroTest.popContent.paperId,
                    type: synchroTest.basicInfo.type,
                    answer: JSON.stringify(postInfo)
                }, 'submitTestPaper');
            }
        },
        // 保存试卷
        saveAnswer: function () {
            var postInfo = {};
            $.each(synchroTest.paperInfo, function (index, item) {
                var temp = {};
                temp.index = item.sort;
                temp.type = item.type;
                temp.id = item.id;
                if (item.type == 4) {
                    if (item.answerNum > 1) {
                        var arr = [];
                        for (var i = 0; i < item.answerNum; i++) {
                            arr.push(item['ans' + i]);
                        }
                        temp.answer = arr.join('┼┼');
                    } else {
                        temp.answer = item.ans0;
                    }
                } else if (item.type == 5) { // 解答题
                    temp.answer = item.newAnswer;
                } else {
                    temp.answer = item.newAnswer;
                }
                postInfo[index] = temp;
            });
            var temp = JSON.stringify(postInfo);
            synchroTest.getData('/studentCourse/saveHomework', 'POST', {
                userId: synchroTest.userId,
                pId: synchroTest.popContent.paperId,
                type: synchroTest.basicInfo.type,
                answer: temp
            }, 'saveHomework');
        },
        changeObj: function (obj) {
            var postInfo = {};
            $.each(obj, function (index, item) {
                var temp = {};
                temp.id = item.id;
                temp.index = item.sort;
                temp.type = item.type;
                if (item.type == 4) {
                    if (item.answerNum > 1) {
                        var arr = [];
                        var tem = 0;
                        for (var i = 0; i < item.answerNum; i++) {
                            if (item['ans' + i] === item['right' + i]) {
                                tem = tem + 1;
                            }
                            arr.push(item['ans' + i]);
                        }
                        temp.newAnswer = arr.join('┼┼');
                        if (tem == item.answerNum) {
                            temp.newScore = item.score;
                            synchroTest.report.TNum += 1; // 正确题数加一
                        } else {
                            if (temp.newAnswer === '┼┼') {
                                temp.newScore = '';
                            } else {
                                temp.newScore = (item.score / item.answerNum) * tem;
                            }
                        }
                        if (temp.newAnswer === '┼┼') { // 未答题数加一
                            synchroTest.report.NNum += 1;
                        }
                    } else {
                        temp.newAnswer = item.ans0;
                        if (item.ans0 == item.answer) {
                            temp.newScore = item.score;
                            synchroTest.report.TNum += 1; // 正确题数加一
                        } else {
                            if (item.ans0 == '') {
                                temp.newScore = '';
                            } else {
                                temp.newScore = 0;
                            }
                        }
                        if (item.ans0 === '') {// 未答题数加一
                            synchroTest.report.NNum += 1;
                        }
                    }

                } else if (item.type == 5) {
                    temp.comment = '';
                    temp.newScore = item.newScore;
                    temp.newAnswer = item.newAnswer;
                    synchroTest.report.subjectiveNum += 1; // 计算解答题数目
                } else {

                    temp.newAnswer = item.newAnswer;
                    if (item.newAnswer === '') { // 未答题数加一
                        synchroTest.report.NNum += 1;
                        temp.newScore = '';
                    } else {
                        temp.newScore = item.newScore;
                    }
                    if (item.newScore == item.score) { // 正确题数加一
                        synchroTest.report.TNum += 1;
                    }
                }
                temp.answer = item.answer;
                temp.score = item.score;
                postInfo[index] = temp;
            });
            // 填充报告信息
            var aLen = synchroTest.testInfo.sChoose ? synchroTest.testInfo.sChoose.length : 0;
            var bLen = synchroTest.testInfo.mChoose ? synchroTest.testInfo.mChoose.length : 0;
            var cLen = synchroTest.testInfo.judge ? synchroTest.testInfo.judge.length : 0;
            var dLen = synchroTest.testInfo.completion ? synchroTest.testInfo.completion.length : 0;
            var eLen = synchroTest.testInfo.subjective ? synchroTest.testInfo.subjective.length : 0;
            synchroTest.report.num = (aLen + bLen + cLen + dLen + eLen) - synchroTest.report.subjectiveNum;
            synchroTest.report.kNum = aLen + bLen + cLen + dLen + eLen;
            synchroTest.report.time = synchroTest.timeSpend;
            synchroTest.report.FNum = synchroTest.report.num - synchroTest.report.TNum - synchroTest.report.NNum; // 错误数目
            synchroTest.report.fPercent = synchroTest.changePercent(synchroTest.report.FNum, synchroTest.report.num); // 错误数目百分比
            synchroTest.report.tPercent = synchroTest.changePercent(synchroTest.report.TNum, synchroTest.report.num); // 正确数目百分比
            synchroTest.report.nPercent = synchroTest.changePercent(synchroTest.report.NNum, synchroTest.report.num); // 正确数目百分比
            return JSON.stringify(postInfo);
        },
        isTrue: function (a, b, c) {
            var temp = String.fromCharCode(parseInt(a) + 65);
            if (c == 1) {
                if (temp == b) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (temp != b) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        isMTrue: function (a, b, c) { // 多选判断选中的选项
            var temp = String.fromCharCode(parseInt(a) + 65);
            if (c == 1) { // 判断是否正确
                if (b.indexOf(temp) == -1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                if (b.indexOf(temp) == -1) {
                    return true;
                } else { // 判断是否错误
                    return false;
                }
            }
        },
        changeTitle: function (index) {
            var match = synchroTest.paperInfo[index].title.match(/_____/g);
            for (var i in match) {
                var tem = 'ans' + i;
                var str = '<input type="text" ms-duplex="a.' + tem + '" />';
                synchroTest.paperInfo[index].title = synchroTest.paperInfo[index].title.replace('_____', str);
            }
            return synchroTest.paperInfo[index].title;
        },
        changeTestTitle: function (index) {
            var match = synchroTest.testInfo.completion[index].title.match(/_____/g);
            for (var i in match) {
                var tem = 'ans' + i;
                var str = '<input type="text" ms-duplex="a.' + tem + '" />';
                synchroTest.testInfo.completion[index].title = synchroTest.testInfo.completion[index].title.replace('_____', str);
            }
            return synchroTest.testInfo.completion[index].title;
        },
        showTitle: function (index) {
            var match = synchroTest.paperInfo[index].title.match(/_____/g);
            for (var i in match) {
                var tem = 'ans' + i;
                var str = '<u>&nbsp;&nbsp;&nbsp;' + synchroTest.paperInfo[index][tem] + '&nbsp;&nbsp;&nbsp;</u>';
                synchroTest.paperInfo[index].title = synchroTest.paperInfo[index].title.replace('_____', str);
            }
            return synchroTest.paperInfo[index].title;
        },
        showTestTitle: function (index) {
            var match = synchroTest.testInfo.completion[index].title.match(/_____/g);
            for (var i in match) {
                var tem = 'ans' + i;
                var str = '<u>&nbsp;&nbsp;&nbsp;' + synchroTest.testInfo.completion[index][tem] + '&nbsp;&nbsp;&nbsp;</u>';
                synchroTest.testInfo.completion[index].title = synchroTest.testInfo.completion[index].title.replace('_____', str);
            }
            return synchroTest.testInfo.completion[index].title;
        },
        imgZoom: null,
        showImg: false,
        enlarge: function (close) {
            if (synchroTest.imgZoom) {
                if (close) {
                    synchroTest.showImg = false;
                    synchroTest.imgZoom = null;
                } else {
                    synchroTest.showImg = true;
                }
            }
        },
        init: function () {
            window.addPaper = window.addPaper || synchroTest;
        },
        changePercent: function (num, total) { // 换算百分比
            return (Math.round(num / total * 10000) / 100.00 + "%");// 小数点后两位百分比
        },
        changeTime: function (value) {
            //计算出小时数
            var leave1 = value % (24 * 3600 * 1000);
            var hours = Math.floor(leave1 / (3600 * 1000));
            //计算相差分钟数
            var leave2 = leave1 % (3600 * 1000);
            var minutes = Math.floor(leave2 / (60 * 1000));
            //计算相差秒数
            var leave3 = leave2 % (60 * 1000);
            var seconds = Math.round(leave3 / 1000);
            if (hours && minutes && seconds) {
                return (hours + "小时" + minutes + "分钟" + seconds + "秒");
            } else if (minutes && seconds) {
                return (minutes + "分钟" + seconds + "秒");
            } else {
                return (seconds + "秒");
            }
        },
        goToPaper: function (type, flag) {
            if (flag == 1) {
                $('.report').hide();
                $('.shadow').hide();
                if (type == 1) {
                    synchroTest.getData('/studentCourse/getHomeWorkInfo/' + synchroTest.popContent.paperId + '/' + synchroTest.userId, 'GET', '', 'paperInfo');
                    $('.student_paper').show().siblings().hide();
                } else {
                    synchroTest.getData('/studentCourse/getTestAnswerInfo/' + synchroTest.popContent.paperId + '/' + synchroTest.userId, 'GET', '', 'testAnswerInfo');
                    $('.student_answer').show().siblings().hide();
                }
            } else {
                $('.report').hide();
                $('.shadow').hide();
            }
        },
        seeScore: function (option) {
            if (option) {
                $('.shadow').show();
                $('.notice').show();
            } else {
                $('.shadow').hide();
                $('.notice').hide();
            }
        },
        min: 0,
        sec: 0,
        showTime: function () {
            if (synchroTest.min > 0) {
                if (synchroTest.sec > 0) {
                    synchroTest.sec = synchroTest.sec - 1;
                } else {
                    synchroTest.min = synchroTest.min - 1;
                    synchroTest.sec = 59;
                    if (synchroTest.min < 10 && synchroTest.min > 0) {
                        synchroTest.min = '0' + synchroTest.min;
                    }
                }
            } else {
                if (synchroTest.sec > 0) {
                    synchroTest.sec = synchroTest.sec - 1;
                }
            }
            if (synchroTest.min == 0) {
                synchroTest.min = '00';
            }
            if (synchroTest.sec < 10) {
                synchroTest.sec = '0' + synchroTest.sec;
            }
            document.getElementById('m').innerHTML = synchroTest.min;
            document.getElementById('s').innerHTML = synchroTest.sec;
            if (synchroTest.min == '00' && synchroTest.sec == 0) {
                clearInterval(synchroTest.setTime);
                synchroTest.submitAnswer(2);
            }
        },
        setTime: function () {
            if (synchroTest.min == 'noTime') {
                document.getElementById('m').innerHTML = '00';
                document.getElementById('s').innerHTML = '00';
                $('.shadow').show();
                $('.notice').show();
                synchroTest.moveAnswer();
            } else {
                setInterval(function () {
                    synchroTest.showTime();
                    synchroTest.saveTestAnswer();
                }, 1000);
            }
        },
        saveFlag: true,
        saveTestAnswer: function () {
            if (synchroTest.saveFlag) {
                if (synchroTest.testInfo.sChoose) {
                    var a = synchroTest.saveObj(synchroTest.testInfo.sChoose);
                }
                if (synchroTest.testInfo.mChoose) {
                    var b = synchroTest.saveObj(synchroTest.testInfo.mChoose);
                }
                if (synchroTest.testInfo.judge) {
                    var c = synchroTest.saveObj(synchroTest.testInfo.judge);
                }
                if (synchroTest.testInfo.completion) {
                    var d = synchroTest.saveObj(synchroTest.testInfo.completion);
                }
                if (synchroTest.testInfo.subjective) {
                    var e = synchroTest.saveObj(synchroTest.testInfo.subjective);
                }
                var postInfo = {
                    'sChoose': a,
                    'mChoose': b,
                    'judge': c,
                    'completion': d,
                    'subjective': e
                };
                synchroTest.getData('/studentCourse/saveTestAnswer', 'POST', {
                    userId: synchroTest.userId,
                    pId: synchroTest.popContent.paperId,
                    type: synchroTest.basicInfo.type,
                    answer: JSON.stringify(postInfo)
                }, 'saveTestAnswer');
            }
        },
        saveObj: function (obj) {
            var postInfo = {};
            $.each(obj, function (index, item) {
                var temp = {};
                temp.id = item.id;
                temp.index = item.sort;
                temp.type = item.type;
                if (item.type == 4) {
                    if (item.answerNum > 1) {
                        var arr = [];
                        var tem = 0;
                        for (var i = 0; i < item.answerNum; i++) {
                            if (item['ans' + i] === item['right' + i]) {
                                tem = tem + 1;
                            }
                            arr.push(item['ans' + i]);
                        }
                        temp.newAnswer = arr.join('┼┼');
                        if (tem == item.answerNum) {
                            temp.newScore = item.score;
                        } else {
                            if (temp.newAnswer === '┼┼') {
                                temp.newScore = '';
                            } else {
                                temp.newScore = (item.score / item.answerNum) * tem;
                            }
                        }
                    } else {
                        temp.newAnswer = item.ans0;
                        if (item.ans0 == item.answer) {
                            temp.newScore = item.score;
                        } else {
                            if (item.ans0 == '') {
                                temp.newScore = '';
                            } else {
                                temp.newScore = 0;
                            }
                        }
                    }
                } else if (item.type == 5) {
                    temp.comment = '';
                    temp.newScore = item.newScore;
                    temp.newAnswer = item.newAnswer;
                } else {

                    temp.newAnswer = item.newAnswer;
                    if (item.newAnswer === '') {
                        temp.newScore = '';
                    } else {
                        temp.newScore = item.newScore;
                    }
                }
                temp.answer = item.answer;
                temp.score = item.score;
                postInfo[index] = temp;
            });
            return JSON.stringify(postInfo);
        },
        moveAnswer: function () {
            synchroTest.getData('/studentCourse/moveAnswer', 'POST', {
                userId: synchroTest.userId,
                pId: synchroTest.popContent.paperId
            }, 'moveAnswer');
        },
        seeExplain: function () {
            synchroTest.getData('/studentCourse/getTestAnswerInfo/' + synchroTest.popContent.paperId + '/' + synchroTest.userId, 'GET', '', 'testAnswerInfo');
            $('.shadow').hide();
            $('.student_answer').show().siblings().hide();
        }
    });
    synchroTest.$watch('imgZoom', function (newVal) {
        synchroTest.imgZoom = newVal;
        synchroTest.enlarge();
    });
    return synchroTest;
});