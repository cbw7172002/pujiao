/**
 * Created by Mr.H on 2017/2/14.
 */
define([], function () {

    var synchroTest = avalon.define({
        $id: 'synchroTest',
        leadLearnInfo: [], // 课前导学
        classTeachInfo: [], // 课堂授课
        afterClassInfo: [], // 课后指导
        paperAttr: '课前导学测试习题',  // 试卷特征
        basicInfo: [], // 试卷基本信息
        paperInfo: [], // 作业试卷信息
        paperDisplay: false,
        // 测验试卷信息
        testInfo: {
            sChoose: [],
            mChoose: [],
            judge: [],
            completion: [],
            subjective: []
        },
        sChooseFlag: false,
        mChooseFlag: false,
        judgeFlag: false,
        completionFlag: false,
        subjectiveFlag: false,
        isAuthor : false,
        getData: function (url, type, data, model, callback) {
            $.ajax({
                url: url,
                type: type || 'GET',
                data: data || {},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        if (model == 'paperInfo'){
                            synchroTest.paperDisplay = true;
                            synchroTest[model] = response.data;
                            synchroTest.basicInfo = response.basicInfo;
                        } else if (model == 'testInfo') {
                            synchroTest.paperDisplay = true;
                            synchroTest.basicInfo = response.basicInfo;
                            if (response.data.sChoose) {
                                synchroTest.testInfo.sChoose = response.data.sChoose;
                                synchroTest.sChooseFlag = true;
                            } else {
                                synchroTest.testInfo.sChoose = [];
                                synchroTest.sChooseFlag = false;
                            }
                            if (response.data.mChoose) {
                                synchroTest.testInfo.mChoose = response.data.mChoose;
                                synchroTest.mChooseFlag = true;
                            } else {
                                synchroTest.testInfo.mChoose = [];
                                synchroTest.mChooseFlag = false;
                            }
                            if (response.data.judge) {
                                synchroTest.testInfo.judge = response.data.judge;
                                synchroTest.judgeFlag = true;
                            } else {
                                synchroTest.testInfo.judge = [];
                                synchroTest.judgeFlag = false;
                            }
                            if (response.data.completion) {
                                synchroTest.testInfo.completion = response.data.completion;
                                synchroTest.completionFlag = true;
                            } else {
                                synchroTest.testInfo.completion = [];
                                synchroTest.completionFlag = false;
                            }
                            if (response.data.subjective) {
                                synchroTest.testInfo.subjective = response.data.subjective;
                                synchroTest.subjectiveFlag = true;
                            } else {
                                synchroTest.testInfo.subjective = [];
                                synchroTest.subjectiveFlag = false;
                            }
                        }  else {
                            synchroTest[model] = response.data;
                            synchroTest.isAuthor = response.isAuthor;
                        }
                    }
                }, error: function (error) {
                }
            })
        },
        showPaper: function (type, el) {
            switch(type){
                case 1:
                    synchroTest.paperAttr = '课前导学测试习题';break;
                case 2:
                    synchroTest.paperAttr = '课堂授课测试习题';break;
                case 3:
                    synchroTest.paperAttr = '课后指导测试习题';break;
            }
            if (el.type == 1) {
                synchroTest.getData('/teacherCourse/getPaperInfo/' + el.paperId, 'GET', '', 'paperInfo');
                $('.student_paper').show().siblings().hide();
            } else {
                synchroTest.getData('/teacherCourse/getTestPaperInfo/' + el.paperId, 'GET', '', 'testInfo');
                $('.student_answer').show().siblings().hide();
            }
        },
        isTrue: function(a, b, c){
            var temp = String.fromCharCode(parseInt(a) + 65);
            if(c == 1){
                if (temp == b){
                    return true;
                }else{
                    return false;
                }
            }else{
                if (temp != b){
                    return true;
                }else{
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
        seeScore: function (option, isAnswer, paperId) {
            if (option) {
                if (isAnswer) {
                    location.href = '/evaluateManageTea/statistic/' + paperId;
                } else {
                    $('.shadow').show();
                    $('.notice').show();
                }
            } else {
                $('.shadow').hide();
                $('.notice').hide();
            }
        }
    });
    synchroTest.$watch('imgZoom', function (newVal) {
        synchroTest.imgZoom = newVal;
        // (!stuTestPaperStu.editing) ? stuTestPaperStu.imgZoom = newVal : stuTestPaperStu.imgZoom = null;
        synchroTest.enlarge();
    });
    return synchroTest;
});