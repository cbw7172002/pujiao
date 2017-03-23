/**
 * Created by Mr.H on 2017/2/22.
 */

define([], function () {
    var stuTestNoAnswer = avalon.define({
        $id: 'stuTestNoAnswerController',
        userId: '', // 用户ID
        paperId: '', // 试卷ID
        basicInfo: [], // 试卷基本信息
        paperDisplay: false,
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
                        stuTestNoAnswer.timeSpend = new Date().getTime();
                        if (model == 'testInfo') {
                            stuTestNoAnswer.paperDisplay = true;
                            stuTestNoAnswer.basicInfo = response.basicInfo;
                            stuTestNoAnswer.testInfo.sChoose = response.data.sChoose;
                            stuTestNoAnswer.testInfo.mChoose = response.data.mChoose;
                            stuTestNoAnswer.testInfo.judge = response.data.judge;
                            stuTestNoAnswer.testInfo.completion = response.data.completion;
                            stuTestNoAnswer.testInfo.subjective = response.data.subjective;
                        } else if (model == 'saveAnswer') {

                        } else if (model == 'moveAnswer') {

                        }else {
                            $('.shadow').show();
                            $('.report').show();
                        }
                    }
                }, error: function (error) {
                }
            })
        },
        // 数字转换字母
        changeCode: function (value) {
            return String.fromCharCode(parseInt(value) + 65);
        },
        // 单选题 测试
        tSingleChoose: function (index, oldIndex, trueAnswer, score) {
            $(this).next('div').removeClass('no_choose').addClass('has_choose');
            $(this).parent('div').parent('div').siblings('div').children().children(':nth-child(2)').addClass('no_choose').removeClass('has_choose');
            var answer = stuTestNoAnswer.changeCode(index);
            stuTestNoAnswer.testInfo.sChoose[oldIndex].newAnswer = answer;
            if (answer == trueAnswer) {
                stuTestNoAnswer.testInfo.sChoose[oldIndex].newScore = score;
            } else {
                stuTestNoAnswer.testInfo.sChoose[oldIndex].newScore = 0;
            }
        },
        // 多选题 测试
        tMChooseTemp: [],
        manyCF: 0,
        tManyChoose: function (index, oldIndex, trueAnswer, score) {
            if (oldIndex != stuTestNoAnswer.manyCF) {
                stuTestNoAnswer.tMChooseTemp = [];
            }
            stuTestNoAnswer.manyCF = oldIndex;
            if (stuTestNoAnswer.testInfo.mChoose[oldIndex].newAnswer != '') {
                stuTestNoAnswer.tMChooseTemp = stuTestNoAnswer.testInfo.mChoose[oldIndex].newAnswer.split('┼┼');
            }
            if ($(this).next('div').hasClass('no_choose')) {
                $(this).next('div').removeClass('no_choose').addClass('has_choose');
                stuTestNoAnswer.tMChooseTemp.push(stuTestNoAnswer.changeCode(index));
            } else {
                $(this).next('div').removeClass('has_choose').addClass('no_choose');
                $.each(stuTestNoAnswer.tMChooseTemp, function (i, item) {
                    // index是索引值（即下标）   item是每次遍历得到的值；
                    if (stuTestNoAnswer.changeCode(index) == item) {
                        stuTestNoAnswer.tMChooseTemp.splice(i, 1);
                    }
                });
            }
            stuTestNoAnswer.testInfo.mChoose[oldIndex].newAnswer = stuTestNoAnswer.tMChooseTemp.sort().join('┼┼');
            if (trueAnswer.sort().toString() == stuTestNoAnswer.tMChooseTemp.sort().toString()) {
                stuTestNoAnswer.testInfo.mChoose[oldIndex].newScore = score;
            } else {
                stuTestNoAnswer.testInfo.mChoose[oldIndex].newScore = 0;
            }
        },
        // 判断题 测试
        tJudgeTest: function (answer, oldIndex, trueAnswer, score) {
            $(this).next('div').removeClass('no_choose').addClass('has_choose');
            $(this).parent('div').siblings('div').children(':nth-child(2)').addClass('no_choose').removeClass('has_choose');
            stuTestNoAnswer.testInfo.judge[oldIndex].newAnswer = answer;
            if (answer == trueAnswer) {
                stuTestNoAnswer.testInfo.judge[oldIndex].newScore = score;
            } else {
                stuTestNoAnswer.testInfo.judge[oldIndex].newScore = 0;
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
        submitAnswer: function () {
            stuTestNoAnswer.saveFlag = false;
            stuTestNoAnswer.timeSpend = stuTestNoAnswer.changeTime(new Date().getTime() - stuTestNoAnswer.timeSpend + parseInt(stuTestNoAnswer.timeLeave));
            if (stuTestNoAnswer.testInfo.sChoose) {
                var a = stuTestNoAnswer.changeObj(stuTestNoAnswer.testInfo.sChoose);
            }
            if (stuTestNoAnswer.testInfo.mChoose) {
                var b = stuTestNoAnswer.changeObj(stuTestNoAnswer.testInfo.mChoose);
            }
            if (stuTestNoAnswer.testInfo.judge) {
                var c = stuTestNoAnswer.changeObj(stuTestNoAnswer.testInfo.judge);
            }
            if (stuTestNoAnswer.testInfo.completion) {
                var d = stuTestNoAnswer.changeObj(stuTestNoAnswer.testInfo.completion);
            }
            if (stuTestNoAnswer.testInfo.subjective) {
                var e = stuTestNoAnswer.changeObj(stuTestNoAnswer.testInfo.subjective);
            }

            var postInfo = {
                'sChoose': a,
                'mChoose': b,
                'judge': c,
                'completion': d,
                'subjective': e
            };
            stuTestNoAnswer.getData('/evaluateManageStu/submitTestPaper', 'POST', {
                userId: stuTestNoAnswer.userId,
                pId: stuTestNoAnswer.paperId,
                type: stuTestNoAnswer.basicInfo.type,
                answer: JSON.stringify(postInfo)
            }, 'submitTestPaper');
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
                            stuTestNoAnswer.report.TNum += 1; // 正确题数加一
                        } else {
                            if (temp.newAnswer === '┼┼') {
                                temp.newScore = '';
                            } else {
                                temp.newScore = (item.score / item.answerNum) * tem;
                            }
                        }
                        if (temp.newAnswer === '┼┼') { // 未答题数加一
                            stuTestNoAnswer.report.NNum += 1;
                        }
                    } else {
                        temp.newAnswer = item.ans0;
                        if (item.ans0 == item.answer) {
                            temp.newScore = item.score;
                            stuTestNoAnswer.report.TNum += 1; // 正确题数加一
                        } else {
                            if (item.ans0 === '') {
                                temp.newScore = '';
                            } else {
                                temp.newScore = 0;
                            }
                        }
                        if (item.ans0 === '') {// 未答题数加一
                            stuTestNoAnswer.report.NNum += 1;
                        }
                    }
                } else if (item.type == 5) {
                    temp.comment = '';
                    temp.newScore = item.newScore;
                    temp.newAnswer = item.newAnswer;
                    stuTestNoAnswer.report.subjectiveNum += 1; // 计算解答题数目
                } else {
                    temp.newAnswer = item.newAnswer;
                    if (item.newAnswer === '') { // 未答题数加一
                        stuTestNoAnswer.report.NNum += 1;
                        temp.newScore = '';
                    } else {
                        temp.newScore = item.newScore;
                    }
                    if (item.newScore == item.score) { // 正确题数加一
                        stuTestNoAnswer.report.TNum += 1;
                    }
                }
                temp.answer = item.answer;
                temp.score = item.score;
                postInfo[index] = temp;
            });
            // 填充报告信息
            var aLen = stuTestNoAnswer.testInfo.sChoose ? stuTestNoAnswer.testInfo.sChoose.length : 0;
            var bLen = stuTestNoAnswer.testInfo.mChoose ? stuTestNoAnswer.testInfo.mChoose.length : 0;
            var cLen = stuTestNoAnswer.testInfo.judge ? stuTestNoAnswer.testInfo.judge.length : 0;
            var dLen = stuTestNoAnswer.testInfo.completion ? stuTestNoAnswer.testInfo.completion.length : 0;
            var eLen = stuTestNoAnswer.testInfo.subjective ? stuTestNoAnswer.testInfo.subjective.length : 0;
            stuTestNoAnswer.report.num = (aLen + bLen + cLen + dLen + eLen) - stuTestNoAnswer.report.subjectiveNum;
            stuTestNoAnswer.report.kNum = aLen + bLen + cLen + dLen + eLen;
            stuTestNoAnswer.report.time = stuTestNoAnswer.timeSpend;
            stuTestNoAnswer.report.FNum = stuTestNoAnswer.report.num - stuTestNoAnswer.report.TNum - stuTestNoAnswer.report.NNum; // 错误数目
            stuTestNoAnswer.report.fPercent = stuTestNoAnswer.changePercent(stuTestNoAnswer.report.FNum, stuTestNoAnswer.report.num); // 错误数目百分比
            stuTestNoAnswer.report.tPercent = stuTestNoAnswer.changePercent(stuTestNoAnswer.report.TNum, stuTestNoAnswer.report.num); // 正确数目百分比
            stuTestNoAnswer.report.nPercent = stuTestNoAnswer.changePercent(stuTestNoAnswer.report.NNum, stuTestNoAnswer.report.num); // 正确数目百分比
            return JSON.stringify(postInfo);
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
                            if (item.ans0 === '') {
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
        changeTitle: function (index) {
            var match = stuTestNoAnswer.testInfo.completion[index].title.match(/_____/g);
            for (var i in match) {
                var tem = 'ans' + i;
                var str = '<input type="text" ms-duplex="a.' + tem + '" />';
                stuTestNoAnswer.testInfo.completion[index].title = stuTestNoAnswer.testInfo.completion[index].title.replace('_____', str);
            }
            return stuTestNoAnswer.testInfo.completion[index].title;
        },
        imgZoom: null,
        showImg: false,
        enlarge: function (close) {
            if (stuTestNoAnswer.imgZoom) {
                if (close) {
                    stuTestNoAnswer.showImg = false;
                    stuTestNoAnswer.imgZoom = null;
                } else {
                    stuTestNoAnswer.showImg = true;
                }
            }
        },
        init: function () {
            window.addPaper = window.addPaper || stuTestNoAnswer;
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
        goToPaper: function (type) {
            if (type == 1) {
                location.href = '/evaluateManageStu/studentTestPaperStu/' + stuTestNoAnswer.paperId + '/' + stuTestNoAnswer.userId;
            } else {
                location.href = '/evaluateManageStu/index';
            }
        },
        saveFlag: true,
        saveAnswer: function () {
            if (stuTestNoAnswer.saveFlag) {
                if (stuTestNoAnswer.testInfo.sChoose) {
                    var a = stuTestNoAnswer.saveObj(stuTestNoAnswer.testInfo.sChoose);
                }
                if (stuTestNoAnswer.testInfo.mChoose) {
                    var b = stuTestNoAnswer.saveObj(stuTestNoAnswer.testInfo.mChoose);
                }
                if (stuTestNoAnswer.testInfo.judge) {
                    var c = stuTestNoAnswer.saveObj(stuTestNoAnswer.testInfo.judge);
                }
                if (stuTestNoAnswer.testInfo.completion) {
                    var d = stuTestNoAnswer.saveObj(stuTestNoAnswer.testInfo.completion);
                }
                if (stuTestNoAnswer.testInfo.subjective) {
                    var e = stuTestNoAnswer.saveObj(stuTestNoAnswer.testInfo.subjective);
                }
                var postInfo = {
                    'sChoose': a,
                    'mChoose': b,
                    'judge': c,
                    'completion': d,
                    'subjective': e
                };
                stuTestNoAnswer.getData('/evaluateManageStu/saveTestAnswer', 'POST', {
                    userId: stuTestNoAnswer.userId,
                    pId: stuTestNoAnswer.paperId,
                    type: stuTestNoAnswer.basicInfo.type,
                    answer: JSON.stringify(postInfo)
                }, 'saveAnswer');
            }
        },
        seeScore: function () {
            location.href = '/evaluateManageStu/studentTestPaperStu/' + stuTestNoAnswer.paperId + '/' + stuTestNoAnswer.userId;
        },
        isTrue: function (a, b, c) { // 单选判断选中的选项
            var temp = String.fromCharCode(parseInt(a) + 65);
            if (c == 1) { // 判断是否正确
                if (temp == b) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (temp != b) {
                    return true;
                } else { // 判断是否错误
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
        moveAnswer: function () {
            stuTestNoAnswer.getData('/evaluateManageStu/moveAnswer', 'POST', {
                userId: stuTestNoAnswer.userId,
                pId: stuTestNoAnswer.paperId
            }, 'moveAnswer');
        }
    });
    stuTestNoAnswer.$watch('imgZoom', function (newVal) {
        stuTestNoAnswer.imgZoom = newVal;
        // (!stuTestPaperStu.editing) ? stuTestPaperStu.imgZoom = newVal : stuTestPaperStu.imgZoom = null;
        stuTestNoAnswer.enlarge();
    });
    return stuTestNoAnswer;
});