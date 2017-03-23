/**
 * Created by Mr.H on 2017/2/22.
 */
define([], function () {

    var stuTestPaperStu = avalon.define({
        $id: 'stuTestPaperStu',
        basicInfo: [], // 试卷基本信息
        userId: '', // 登录用户ID
        paperDisplay: false,
        // 测验试卷信息
        testInfo: {
            sChoose: [],
            mChoose: [],
            judge: [],
            completion: [],
            subjective: []
        },
        getData: function (url, type, data, model, callback) {
            $.ajax({
                url: url,
                type: type || 'GET',
                data: data || {},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        if (model == 'testInfo') {
                            stuTestPaperStu.paperDisplay = true;
                            stuTestPaperStu.basicInfo = response.basicInfo;
                            stuTestPaperStu.testInfo.sChoose = response.data.sChoose;
                            stuTestPaperStu.testInfo.mChoose = response.data.mChoose;
                            stuTestPaperStu.testInfo.judge = response.data.judge;
                            stuTestPaperStu.testInfo.completion = response.data.completion;
                            stuTestPaperStu.testInfo.subjective = response.data.subjective;
                        } else {
                            stuTestPaperStu[model] = response.data;
                        }
                    }
                }, error: function (error) {
                }
            })
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
            var match = stuTestPaperStu.testInfo.completion[index].title.match(/_____/g);
            for (var i in match) {
                var tem = 'ans' + i;
                var str = '<u>&nbsp;&nbsp;&nbsp;' + stuTestPaperStu.testInfo.completion[index][tem] + '&nbsp;&nbsp;&nbsp;</u>';
                stuTestPaperStu.testInfo.completion[index].title = stuTestPaperStu.testInfo.completion[index].title.replace('_____', str);
            }
            return stuTestPaperStu.testInfo.completion[index].title;
        },
        imgZoom: null,
        showImg: false,
        enlarge: function (close) {
            if (stuTestPaperStu.imgZoom) {
                if (close) {
                    stuTestPaperStu.showImg = false;
                    stuTestPaperStu.imgZoom = null;
                } else {
                    stuTestPaperStu.showImg = true;
                }
            }
        },
        init: function () {
            window.addPaper = window.addPaper || stuTestPaperStu;
        }
    });
    stuTestPaperStu.$watch('imgZoom', function (newVal) {
        stuTestPaperStu.imgZoom = newVal;
        // (!stuTestPaperStu.editing) ? stuTestPaperStu.imgZoom = newVal : stuTestPaperStu.imgZoom = null;
        stuTestPaperStu.enlarge();
    });
    return stuTestPaperStu;
});