/**
 * Created by Mr.H on 2017/1/16.
 */

define([], function () {
    var stuNoAnswer = avalon.define({
        $id: 'stuNoAnswerController',
        userId: '', // 用户ID
        paperId: '', // 试卷ID
        basicInfo: [], // 试卷基本信息
        paperInfo: [], // 试卷详细信息
        paperDisplay: false,
        timeSpend: 0,
        getData: function (url, type, data, model, callback) {
            $.ajax({
                url: url,
                type: type || 'GET',
                data: data || {},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        stuNoAnswer.timeSpend = new Date().getTime();
                        if (model == 'paperInfo') {
                            stuNoAnswer.paperDisplay = true;
                            stuNoAnswer[model] = response.data;
                            stuNoAnswer.basicInfo = response.basicInfo;
                            stuNoAnswer.answer = response.answer;
                        } else if (model == 'submitPaper') {
                            $('.shadow').show();
                            $('.report').show();
                        } else {
                            stuNoAnswer.seeScore(true);
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
        // 单选题
        singleChoose: function (index, sort, trueAnswer, score) {
            $(this).next('div').removeClass('no_choose').addClass('has_choose');
            $(this).parent('div').parent('div').siblings('div').children().children(':nth-child(2)').addClass('no_choose').removeClass('has_choose');
            var answer = stuNoAnswer.changeCode(index);
            stuNoAnswer.paperInfo[sort - 1].newAnswer = answer;
            if (answer == trueAnswer) {
                stuNoAnswer.paperInfo[sort - 1].newScore = score;
            } else {
                stuNoAnswer.paperInfo[sort - 1].newScore = 0;
            }
        },
        // 多选题
        mChooseTemp: [],
        mChooseFlag: true,
        manyCF: 0,
        manyChoose: function (index, sort, trueAnswer, score) {
            if (index != stuNoAnswer.manyCF) {
                stuNoAnswer.mChooseTemp = [];
            }
            stuNoAnswer.manyCF = index;
            if (stuNoAnswer.mChooseFlag && (stuNoAnswer.paperInfo[sort - 1].newAnswer != '')) {
                stuNoAnswer.mChooseTemp = stuNoAnswer.paperInfo[sort - 1].newAnswer.split('┼┼');
            }
            if ($(this).next('div').hasClass('no_choose')) {
                $(this).next('div').removeClass('no_choose').addClass('has_choose');
                stuNoAnswer.mChooseTemp.push(stuNoAnswer.changeCode(index));
            } else {
                $(this).next('div').removeClass('has_choose').addClass('no_choose');
                $.each(stuNoAnswer.mChooseTemp, function (i, item) {
                    // index是索引值（即下标）   item是每次遍历得到的值；
                    if (stuNoAnswer.changeCode(index) == item) {
                        stuNoAnswer.mChooseTemp.splice(i, 1);
                    }
                });
            }
            stuNoAnswer.paperInfo[sort - 1].newAnswer = stuNoAnswer.mChooseTemp.sort().join('┼┼');
            if (trueAnswer.sort().toString() == stuNoAnswer.mChooseTemp.sort().toString()) {
                stuNoAnswer.paperInfo[sort - 1].newScore = score;
            } else {
                stuNoAnswer.paperInfo[sort - 1].newScore = 0;
            }
        },
        // 判断题
        judgeTest: function (answer, sort, trueAnswer, score) {
            $(this).next('div').removeClass('no_choose').addClass('has_choose');
            $(this).parent('div').siblings('div').children(':nth-child(2)').addClass('no_choose').removeClass('has_choose');
            stuNoAnswer.paperInfo[sort - 1].newAnswer = answer;
            if (answer == trueAnswer) {
                stuNoAnswer.paperInfo[sort - 1].newScore = score;
            } else {
                stuNoAnswer.paperInfo[sort - 1].newScore = 0;
            }
        },
        changeTitle: function (index) {
            var match = stuNoAnswer.paperInfo[index].title.match(/_____/g);
            for (var i in match) {
                var tem = 'ans' + i;
                var str = '<input type="text" ms-duplex="a.' + tem + '" />';
                stuNoAnswer.paperInfo[index].title = stuNoAnswer.paperInfo[index].title.replace('_____', str);
            }
            return stuNoAnswer.paperInfo[index].title;
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
            stuNoAnswer.timeSpend = stuNoAnswer.changeTime(new Date().getTime() - stuNoAnswer.timeSpend);
            var postInfo = {};
            $.each(stuNoAnswer.paperInfo, function (index, item) {
                var temp = {};
                temp.index = item.sort;
                temp.type = item.type;
                temp.id = item.id;
                if (item.type == 1) {
                    if (item.answer == item.newAnswer) {
                        stuNoAnswer.report.TNum += 1; // 正确题数加一
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
                        stuNoAnswer.report.NNum += 1;
                    }
                } else if (item.type == 2) {
                    temp.answer = item.newAnswer;
                    if (item.answerStr === item.newAnswer) {
                        stuNoAnswer.report.TNum += 1; // 正确题数加一
                        temp.score = item.newScore;
                    } else {
                        if (item.newAnswer === '') {
                            temp.score = '';
                        } else {
                            temp.score = 0;
                        }
                    }
                    if (item.newAnswer === '') { // 未答题数加一
                        stuNoAnswer.report.NNum += 1;
                    }
                } else if (item.type == 3) {
                    if (item.answer == item.newAnswer) {
                        stuNoAnswer.report.TNum += 1; // 正确题数加一
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
                        stuNoAnswer.report.NNum += 1;
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
                            stuNoAnswer.report.TNum += 1; // 正确题数加一
                        } else {
                            if (temp.answer === '┼┼') {
                                temp.score = '';
                            } else {
                                temp.score = (item.score / item.answerNum) * tem;
                            }
                        }
                        if (temp.answer === '┼┼') { // 未答题数加一
                            stuNoAnswer.report.NNum += 1;
                        }
                    } else {
                        temp.answer = item.ans0;
                        if (temp.answer == item.answer) {
                            temp.score = item.score;
                            stuNoAnswer.report.TNum += 1; // 正确题数加一
                        } else {
                            if (temp.answer === '') {
                                temp.score = '';
                            } else {
                                temp.score = 0;
                            }
                        }
                        if (temp.answer === '') { // 未答题数加一
                            stuNoAnswer.report.NNum += 1;
                        }
                    }
                } else if (item.type == 5) { // 解答题
                    temp.answer = item.newAnswer;
                    temp.score = '';
                    temp.comment = '';
                    stuNoAnswer.report.subjectiveNum += 1; // 计算解答题数目
                }
                postInfo[index] = temp;
            });
            // 填充报告信息
            stuNoAnswer.report.num = stuNoAnswer.paperInfo.length - stuNoAnswer.report.subjectiveNum;
            stuNoAnswer.report.kNum = stuNoAnswer.paperInfo.length;
            stuNoAnswer.report.time = stuNoAnswer.timeSpend;
            stuNoAnswer.report.FNum = stuNoAnswer.report.num - stuNoAnswer.report.TNum - stuNoAnswer.report.NNum; // 错误数目
            stuNoAnswer.report.fPercent = stuNoAnswer.changePercent(stuNoAnswer.report.FNum, stuNoAnswer.report.num); // 错误数目百分比
            stuNoAnswer.report.tPercent = stuNoAnswer.changePercent(stuNoAnswer.report.TNum, stuNoAnswer.report.num); // 正确数目百分比
            stuNoAnswer.report.nPercent = stuNoAnswer.changePercent(stuNoAnswer.report.NNum, stuNoAnswer.report.num); // 正确数目百分比
            // 填充报告信息
            var temp = JSON.stringify(postInfo);
            stuNoAnswer.getData('/evaluateManageStu/submitPaper', 'POST', {
                userId: stuNoAnswer.userId,
                pId: stuNoAnswer.paperId,
                type: stuNoAnswer.basicInfo.type,
                answer: temp
            }, 'submitPaper');
        },
        // 保存试卷
        saveAnswer: function () {
            var postInfo = {};
            $.each(stuNoAnswer.paperInfo, function (index, item) {
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
            stuNoAnswer.getData('/evaluateManageStu/saveHomework', 'POST', {
                userId: stuNoAnswer.userId,
                pId: stuNoAnswer.paperId,
                type: stuNoAnswer.basicInfo.type,
                answer: temp
            }, 'saveHomework');
        },
        imgZoom: null,
        showImg: false,
        enlarge: function (close) {
            if (stuNoAnswer.imgZoom) {
                if (close) {
                    stuNoAnswer.showImg = false;
                    stuNoAnswer.imgZoom = null;
                } else {
                    stuNoAnswer.showImg = true;
                }
            }
        },
        init: function () {
            window.addPaper = window.addPaper || stuNoAnswer;
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
            if (seconds) {
                return (seconds + "秒");
            } else if (minutes) {
                return (minutes + "分钟" + seconds + "秒");
            } else {
                return (hours + "小时" + minutes + "分钟" + seconds + "秒");
            }
        },
        goToPaper: function (type) {
            if (type == 1) {
                location.href = '/evaluateManageStu/studentPaperStu/' + stuNoAnswer.paperId + '/' + stuNoAnswer.userId;
            } else {
                location.href = '/evaluateManageStu/index';
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
        }
    });
    stuNoAnswer.$watch('imgZoom', function (newVal) {
        stuNoAnswer.imgZoom = newVal;
        // (!stuTestPaperStu.editing) ? stuTestPaperStu.imgZoom = newVal : stuTestPaperStu.imgZoom = null;
        stuNoAnswer.enlarge();
    });

    return stuNoAnswer;
});