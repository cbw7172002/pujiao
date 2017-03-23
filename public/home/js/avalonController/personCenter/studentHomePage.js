/**
 * Created by LT on 2017/1/16.
 */
define(['/teacher/resourceStore', '/teacher/courseStore', '/teacher/answerStore', '/teacher/myFocusTeacher', '/teacher/myFansTeacher','/teacher/myQuestion','/teacher/waitAnswer', '/notice', '/comment','/teacher/studentCourseQa'], function (resourceStore, courseStore, answerStore, myFocusTeacher, myFansTeacher, myQuestion, waitAnswer, notice, comment, studentCourseQa) {
        var studentHomePage = avalon.define({
            $id: 'studentHomePage',
            userInfo: {
                pic: '/home/image/layout/default.png',
                username: '---',
                realname: '---',
                type: 1,
                school: '---',
                sex: 1,
                gradeId: '---',
                classId: '---'
            },
            //弹出层
            popUp: false,
            noticeId: null,
            commentId: null,

            resourceMessage: '', //资源删除提示
            resourceBtn: '',
            storeId: '',
            storeType: '',

            resourceTypeList: [], //资源分类

            popUpSwitch: function (value, needId, resId) {
                if(value == 'deleteNotice'){ //获取删除的ID
                    studentHomePage.noticeId = needId;
                }
                if(value == 'sureNotice'){ // 执行删除通知
                    studentHomePage.getData('/member/deleteMessage', 'deleteNotice', {id: studentHomePage.noticeId}, 'POST');
                }
                if(value == 'deleteComment'){
                    studentHomePage.commentId = needId;
                }
                if(value == 'sureComment'){ // 执行删除评论回复
                    studentHomePage.getData('/member/deleteMessage', 'deleteComment', {id: studentHomePage.commentId}, 'POST');
                }
                if(value == 'resourceMessage') {
                    if ( needId == 1) {
                        studentHomePage.resourceMessage = '该资源已被删除，如有需要，请前往资源中心查找相关资源';
                        studentHomePage.resourceBtn = '删除资源';
                        studentHomePage.storeId = resId;
                        studentHomePage.storeType = 'resoreceStore';
                    }

                    if ( needId == 2) {
                        studentHomePage.resourceMessage = '该课程已被删除，如有需要，请前往课程中心查找相关课程';
                        studentHomePage.resourceBtn = '删除课程';
                        studentHomePage.storeId = resId;
                        studentHomePage.storeType = 'courseStore';
                    }
                }
                studentHomePage.popUp = value;
            },
            //删除我的收藏（资源 && 课程）
            deleteStore: function (storeType, storeId) {
                studentHomePage.popUp = false ;
                if(storeId) {
                    $.ajax({
                        url: '/member/deleteMyResource',
                        type: 'POST',
                        data: {table: 'resourcestore', data: {id: storeId}},
                        dataType: 'json',
                        success: function (response) {
                            if (response.status) {
                                if(storeType == 'resoreceStore') { resourceStore.resourceStore.getCollectionInfo()}
                                if(storeType == 'courseStore') { courseStore.courseStore.getCourseStoreInfo()}
                            } else {
                                alert("删除该收藏失败！") ;
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
                    notice.notice.noticeInfo.length == '0' ? notice.notice.getNoticeInfo(studentHomePage.mineUsername) : notice.notice.noticeInfo;
                }

                if (value == 'commentAnswer') {
                    comment.comment.commentInfo.length == '0' ? comment.comment.getCommentInfo(studentHomePage.mineUsername) : comment.comment.commentInfo;
                }

                if (value == 'myFocus') {
                    myFocusTeacher.myFocusTeacher.myFocusList.length == '0' ? myFocusTeacher.myFocusTeacher.getMyFocusInfo(userId) : myFocusTeacher.myFocusTeacher.myFocusList;
                }

                if (value == 'myFriends') {
                    myFansTeacher.myFansTeacher.myFansList.length == '0' ? myFansTeacher.myFansTeacher.getMyFansInfo(userId) : myFansTeacher.myFansTeacher.myFansList;
                }


                if (value == 'resourceStore') {
                    resourceStore.resourceStore.collectionInfo.length == '0' ? resourceStore.resourceStore.getCollectionInfo() : resourceStore.resourceStore.collectionInfo;
                }

                if (value == 'courseStore') {
                    courseStore.courseStore.courseStoreInfo.length == '0' ? courseStore.courseStore.getCourseStoreInfo() : courseStore.courseStore.courseStoreInfo;
                }

                if (value == 'auditingStore') {
                    answerStore.answerStore.answerStoreInfo.length == '0' ? answerStore.answerStore.getAnswerStoreInfo() : answerStore.answerStore.answerStoreInfo;
                }
                //课程问答
                if (value == 'studentCourseQa') {
                    studentCourseQa.studentCourseQaInfo.length == '0' ? studentCourseQa.getDate(1) : false; //1 等待回答，2已回答
                }
                //我的回答
                if (value == 'myAuditing') {
                    myQuestion.questionInfo.length == '0' ? myQuestion.getDate(1) : false; //1 未回答，2已回答
                }
                //等待回答
                if (value == 'waitAuditing') {
                    waitAnswer.questionsInfo.length == '0' ? waitAnswer.getDate(1) : false;
                }

                studentHomePage.tabStatus = value;
            },

            findHaveNotice: function () {
                $.ajax({
                    url: '/member/findHaveNotice',
                    type: 'POST',
                    data: {username: studentHomePage.mineUsername},
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 1) {// 通知消息 评论回复消息
                            studentHomePage.noReadNotice = true;
                            studentHomePage.noReadComment = true;
                        } else if (response.status == 2) {// 通知消息
                            studentHomePage.noReadNotice = true;
                        } else if (response.status == 3) {// 评论回复消息
                            studentHomePage.noReadComment = true;
                        } else {
                            studentHomePage.noReadNotice = false;
                            studentHomePage.noReadComment = false;
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
                                notice.notice.getNoticeInfo(studentHomePage.mineUsername);
                                studentHomePage.popUp = false;
                            } else if (model == 'deleteComment') {
                                comment.comment.getCommentInfo(studentHomePage.mineUsername);
                                studentHomePage.popUp = false;
                            } else {
                                studentHomePage[model] = response.data;
                            }
                        }

                        callback && callback(response);
                    },
                    error: function(error) {

                    }
                });
            },

            //模板变量
            mineId : '',
            mineUsername : ''
        });

        return studentHomePage;
    });