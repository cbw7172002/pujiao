/**
 * Created by Mr.H on 2017/1/23.
 * 教师查看试卷详情 可收藏试卷试题
 */
define([], function () {
    var testPaperTea = avalon.define({
        $id: 'testPaperTeaController',
        paperId: '', // 试卷ID
        title: '', // 试卷Title
        subjectId: '', // 科目ID
        paperInfo: [],
        paperDisplay: false,
        getData: function (url, type, data, model) {
            $.ajax({
                url: url,
                type: type || 'GET',
                data: data || {},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        if(model){
                            if(model == 'collectP'){
                                testPaperTea.isCollection = !testPaperTea.isCollection;
                            }else if (model == 'paperInfo'){
                                testPaperTea[model] = response.data;
                                testPaperTea.paperDisplay = true;
                            }
                        }
                    }
                }, error: function (error) {
                }
            })
        },
        isCollection: false,
        collectPaper: function(value) {
            testPaperTea.getData('/evaluateManageTea/collectPaper', 'POST', {'paperId': value, 'title': testPaperTea.title, 'subjectId': testPaperTea.subjectId}, 'collectP');
        },
        collectQuestion: function (questionId, type, a, b) {
            testPaperTea.getData('/evaluateManageTea/collectQuestion', 'POST', {'questionId': questionId, 'examType': type, 'subjectId': a, 'chapterId': b}, '');
        },
        imgZoom: null,
        showImg: false,
        enlarge: function (close) {
            if (testPaperTea.imgZoom) {
                if (close) {
                    testPaperTea.showImg = false;
                    testPaperTea.imgZoom = null;
                } else {
                    testPaperTea.showImg = true;
                }
            }
        },
        init: function () {
            window.addPaper = window.addPaper || testPaperTea;
        }
    });
    testPaperTea.$watch('imgZoom', function (newVal) {
        testPaperTea.imgZoom = newVal;
        testPaperTea.enlarge();
    });
    return testPaperTea;
});