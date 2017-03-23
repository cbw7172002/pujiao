/**
 * Created by LT on 2017/1/16.
 */
define(['/teacher/resourceStore', '/teacher/courseStore', '/teacher/examStore', '/teacher/paperStore', '/teacher/answerStore', '/teacher/myFocusTeacher', '/teacher/myFansTeacher','/teacher/myQuestion','/teacher/waitAnswer', '/notice', '/comment', '/teacher/myResource','/teacher/teacherCourseQa'], function (resourceStore, courseStore, examStore, paperStore, answerStore, myFocusTeacher, myFansTeacher, myQuestion, waitAnswer, notice, comment, myResource, teacherCourseQa) {
        var teacherHomePage = avalon.define({
            $id: 'teacherHomePage',
            userInfo: {
                pic: '/home/image/layout/default.png',
                username: '---',
                realname: '---',
                type: 0,
                school: '---',
                subjectName: '---',
                subjectNames: '---',
                sex: 1,
            },
            resourceCount: 0,
            courseCount: 0,
            //弹出层
            popUp: false,
            noticeId: null,
            commentId: null,

            resourceTypeList: [], //资源分类
            subjectList: [], //試題--科目
            subjectLists: [], //試卷--科目

            //pop提示信息
            resourceMessage: '',
            resourceBtn : '',
            storeId: '',
            storeType: '',
            popUpSwitch: function (value, needId, resId) {

                teacherHomePage.popUp = value;
                if (value == 'deleteNotice') { //获取删除的ID
                    teacherHomePage.noticeId = needId;
                }
                if (value == 'sureNotice') { // 执行删除通知
                    teacherHomePage.getData('/member/deleteMessage', 'deleteNotice', {id: teacherHomePage.noticeId}, 'POST');
                }
                if (value == 'deleteComment') {
                    teacherHomePage.commentId = needId;
                }
                if (value == 'sureComment') { // 执行删除评论回复
                    teacherHomePage.getData('/member/deleteMessage', 'deleteComment', {id: teacherHomePage.commentId}, 'POST');
                }
                if(value == 'resourceMessage') {
                    if ( needId == 1) {
                        teacherHomePage.resourceMessage = '资源格式转换中，暂不可查看，请稍后刷新页面重试。';
                        teacherHomePage.resourceBtn = '知道了';
                    }
                    if ( needId == 2) {
                        teacherHomePage.resourceMessage = '该资源已被删除，如有需要，请前往资源中心查找相关资源。';
                        teacherHomePage.resourceBtn = '删除资源';
                        teacherHomePage.storeId = resId;
                        teacherHomePage.storeType = 'resourceStore';
                    }
                    if ( needId == 3) {
                        teacherHomePage.resourceMessage = '该课程已被删除，如有需要，请前往课程中心查找相关课程。';
                        teacherHomePage.resourceBtn = '删除课程';
                        teacherHomePage.storeId = resId;
                        teacherHomePage.storeType = 'courseStore';
                    }
                }

            },
            deleteStore: function (storeType, storeId) {
                teacherHomePage.popUp = false ;
                if(storeId) {
                    $.ajax({
                        url: '/member/deleteMyResource',
                        type: 'POST',
                        data: {table: 'resourcestore', data: {id: storeId}},
                        dataType: 'json',
                        success: function (response) {
                            if (response.status) {
                                if(storeType == 'resourceStore') { resourceStore.resourceStore.getCollectionInfo()}
                                if(storeType == 'courseStore') { courseStore.courseStore.getCourseStoreInfo()}
                            } else {
                                alert("删除该收藏失败！") ;
                            }
                        }
                    })
                }
            },

            // 删除我的资源
            deleteMyResource : function (resId) {
                if(resId && confirm('确定要删除我的资源吗？')) {
                    $.ajax({
                        url: '/member/deleteMyResource',
                        type: 'POST',
                        data: {table: 'resource', data: {id: resId}},
                        dataType: 'json',
                        success: function (response) {
                            if (response.status) {
                                myResource.myResource.getMyResourceInfo();
                            } else {
                                alert("删除我的资源失败") ;
                            }
                        }
                    })
                }
            },
            //选项卡
            tabStatus: '',
            changeTab: function (value, userId) {
                //锚点赋值
                window.location.hash = value;

                if (value == 'wholeNotice') {
                    notice.notice.noticeInfo.length == '0' ? notice.notice.getNoticeInfo(teacherHomePage.mineUsername) : notice.notice.noticeInfo;
                }
                if (value == 'commentAnswer') {
                    comment.comment.commentInfo.length == '0' ? comment.comment.getCommentInfo(teacherHomePage.mineUsername) : comment.comment.commentInfo;
                }
                //课程问答
                if (value == 'teacherCourseQa') {
                    teacherCourseQa.teacherCourseQaInfo.length == '0' ? teacherCourseQa.getDate(1) : false; //1 等待回答，2我的回答
                }
                //我的回答
                if (value == 'myAuditing') {
                    myQuestion.questionInfo.length == '0' ? myQuestion.getDate(1) : false; //1 未回答，2已回答
                }
                //等待回答
                if (value == 'waitAuditing') {
                    waitAnswer.questionsInfo.length == '0' ? waitAnswer.getDate(1) : false;
                }
                //我的资源
                if (value == 'myResource') {
                    myResource.myResource.myResourceInfo.length == '0' ? myResource.myResource.getMyResourceInfo() : myResource.myResource.myResourceInfo;
                }

                if (value == 'resourceStore') {
                    resourceStore.resourceStore.collectionInfo.length == '0' ? resourceStore.resourceStore.getCollectionInfo() : resourceStore.resourceStore.collectionInfo;
                }

                if (value == 'courseStore') {
                    courseStore.courseStore.courseStoreInfo.length == '0' ? courseStore.courseStore.getCourseStoreInfo() : courseStore.courseStore.courseStoreInfo;
                }

                if(value == 'examStore') {
                    examStore.examStore.examStoreInfo.length == '0' ? examStore.examStore.getExamStoreInfo() : examStore.examStore.examStoreInfo;
                }

                if(value == 'paperStore') {
                    paperStore.paperStore.paperStoreInfo.length == '0' ? paperStore.paperStore.getPaperStoreInfo() : paperStore.paperStore.paperStoreInfo;
                }

                if (value == 'auditingStore') {
                    answerStore.answerStore.answerStoreInfo.length == '0' ? answerStore.answerStore.getAnswerStoreInfo() : answerStore.answerStore.answerStoreInfo;
                }

                if (value == 'myFocus') {
                    myFocusTeacher.myFocusTeacher.myFocusList.length == '0' ? myFocusTeacher.myFocusTeacher.getMyFocusInfo(userId) : myFocusTeacher.myFocusTeacher.myFocusList;
                }

                if (value == 'myFriends') {
                    myFansTeacher.myFansTeacher.myFansList.length == '0' ? myFansTeacher.myFansTeacher.getMyFansInfo(userId) : myFansTeacher.myFansTeacher.myFansList;
                }
                teacherHomePage.tabStatus = value;
            },

            findHaveNotice: function () {
                $.ajax({
                    url: '/member/findHaveNotice',
                    type: 'POST',
                    data: {username: teacherHomePage.mineUsername},
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 1) {// 通知消息 评论回复消息
                            teacherHomePage.noReadNotice = true;
                            teacherHomePage.noReadComment = true;
                        } else if (response.status == 2) {// 通知消息
                            teacherHomePage.noReadNotice = true;
                        } else if (response.status == 3) {// 评论回复消息
                            teacherHomePage.noReadComment = true;
                        } else {
                            teacherHomePage.noReadNotice = false;
                            teacherHomePage.noReadComment = false;
                        }
                    }
                })
            },

            //ajax
            getData: function(url, model, data, method, callback) {
                $.ajax({
                    type: method || 'GET',
                    url: url,
                    data: data || {},
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            if (model == 'deleteNotice'){
                                notice.notice.getNoticeInfo(teacherHomePage.mineUsername);
                                teacherHomePage.popUp = false;
                            } else if (model == 'deleteComment') {
                                comment.comment.getCommentInfo(teacherHomePage.mineUsername);
                                teacherHomePage.popUp = false;
                            } else {
                                teacherHomePage[model] = response.data;
                                //console.log(teacherHomePage[model])
                            }
                        }
                        callback && callback(response);
                    },
                    error: function(error) {

                    }
                });
            },

            imgZoom: null,
            showImg: false,
            enlarge: function (close) {
                if (teacherHomePage.imgZoom) {
                    if (close) {
                        teacherHomePage.showImg = false;
                        teacherHomePage.imgZoom = null;
                    } else {
                        teacherHomePage.showImg = true;
                    }
                }
            },
            init: function () {
                window.addPaper = window.addPaper || teacherHomePage;
            },

            //模板变量
            mineId : ''
        });
        teacherHomePage.$watch('imgZoom', function (newVal) {
                teacherHomePage.imgZoom = newVal ;
                teacherHomePage.enlarge();
            });
        return teacherHomePage;
    });