/**
 * Created by Mr.H on 2017/1/16.
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