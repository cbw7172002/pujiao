/**
 * Created by Mr.H on 2017/1/16.
 * 教师查看批改学生 测验样式 的试卷
 */

define([], function () {
    var stuPaperStu = avalon.define({
        $id: 'stuPaperStuController',
        userId: '', // 用户ID
        paperId: '', // 试卷ID
        type: '', // 试卷类型
        basicInfo: [], // 试卷基本信息
        paperInfo: [], // 试卷详细信息
        paperDisplay: false,
        getData: function (url, type, data, model, callback) {
            $.ajax({
                url: url,
                type: type || 'GET',
                data: data || {},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        stuPaperStu.paperDisplay = true;
                        if (model == 'paperInfo') {
                            stuPaperStu[model] = response.data;
                            stuPaperStu.basicInfo = response.basicInfo;
                            stuPaperStu.answer = response.answer;
                        } else if(model == 'submitPaper'){
                            alert('批改成功！');
                            window.location.href = '/evaluateManageTea/index#questionCorrect#' + stuPaperStu.paperId + '#' + stuPaperStu.basicInfo.classId;
                        }
                    } else {
                        if (model == 'submitPaper') {
                            alert('批改失败！');
                        }
                    }
                }, error: function (error) {
                }
            })
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
        // 提交试卷
        submitAnswer: function () {
            var postInfo = {};
            $.each(stuPaperStu.paperInfo, function (index, item) {
                var temp = {};
                if (item.type == 5) {
                    temp.index = item.sort;
                    temp.type = item.type;
                    temp.id = item.id;
                    temp.answer = item.userAnswer;
                    temp.score = item.getScore;
                    temp.comment = item.comment;
                } else if (item.type == 2) {
                    temp.index = item.sort;
                    temp.type = item.type;
                    temp.id = item.id;
                    temp.answer = item.userAnswer2;
                    temp.score = item.userScore;
                } else {
                    temp.index = item.sort;
                    temp.type = item.type;
                    temp.id = item.id;
                    temp.answer = item.userAnswer;
                    temp.score = item.userScore;
                }
                postInfo[index] = temp;
            });
            var temp = JSON.stringify(postInfo);
            stuPaperStu.getData('/evaluateManageTea/submitHomeScore', 'POST', {
                userId: stuPaperStu.userId,
                pId: stuPaperStu.paperId,
                type: stuPaperStu.basicInfo.type,
                answer: temp
            }, 'submitPaper');
        },
        changeTitle: function (index) {
            var match = stuPaperStu.paperInfo[index].title.match(/_____/g);
            for (var i in match) {
                var tem = 'ans' + i;
                var str = '<u>&nbsp;&nbsp;&nbsp;' + stuPaperStu.paperInfo[index][tem] + '&nbsp;&nbsp;&nbsp;</u>';
                stuPaperStu.paperInfo[index].title = stuPaperStu.paperInfo[index].title.replace('_____', str);
            }
            return stuPaperStu.paperInfo[index].title;
        },
        imgZoom: null,
        showImg: false,
        enlarge: function (close) {
            if (stuPaperStu.imgZoom) {
                if (close) {
                    stuPaperStu.showImg = false;
                    stuPaperStu.imgZoom = null;
                } else {
                    stuPaperStu.showImg = true;
                }
            }
        },
        init: function () {
            window.addPaper = window.addPaper || stuPaperStu;
        }
    });
    stuPaperStu.$watch('imgZoom', function (newVal) {
        stuPaperStu.imgZoom = newVal;
        // (!stuTestPaperStu.editing) ? stuTestPaperStu.imgZoom = newVal : stuTestPaperStu.imgZoom = null;
        stuPaperStu.enlarge();
    });
    return stuPaperStu;
});