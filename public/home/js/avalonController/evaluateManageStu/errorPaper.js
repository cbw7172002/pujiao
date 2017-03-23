/**
 * Created by Mr.H on 2017/1/16.
 */

define([], function () {
    var stuPaperStu = avalon.define({
        $id: 'errorPaperController',
        userId: '', // 用户ID
        paperId: '', // 试卷ID
        type: '', // 试卷类型
        basicInfo: [], // 试卷基本信息
        paperInfo: [], // 试卷详细信息
        paperDisplay: false,
        request: function (url, data, callback) {
            $.ajax({
                url: url,
                dataType: 'json',
                type: ('function' !== typeof data) ? 'POST' : 'GET',
                data: ('function' !== typeof data) ? data : null,
                success: function (response) {
                    if (response.type) {
                        ('function' === typeof data) ? data(null, response.data) : callback(null, response.data);
                    } else {
                        callback(new Error('请求失败'));
                    }
                },
                error: callback
            });
        },
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
        delQuestion: function (pid, userid, indexs, type, id) { // 多选判断选中的选项
                // 删除选择项
            if(confirm("确认要删除此题吗")) {
                //先去数据库删除更新 成功后删除页面数组
                stuPaperStu.request('/evaluateManageStu/delQuestion',{ pId:pid, userId:userid, type:type, qId:id },function (err, res) {
                    if (!err) {
                        $.each(stuPaperStu['paperInfo'], function (index, item) {
                            if (index == indexs) {
                                stuPaperStu['paperInfo'].splice(index, 1);
                            }
                        });
                    }else{
                        console.log(err);
                        alert("删除失败！");
                    }
                });
            }
        }
    });
    return stuPaperStu;
});