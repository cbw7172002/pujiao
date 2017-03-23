/**
 * Created by LT on 2017/1/18.
 */

define(['/teacher/myFocusTeacher','/teacher/myFansTeacher','/teacher/studentCourse','/teacher/studentAnswer' ], function (myFocusTeacher, myFansTeacher,studentCourse,studentAnswer) {
    var studentHomePagePublic = avalon.define({
        $id: 'studentHomePagePublic',
        userInfo: {
            pic: '/home/image/layout/default.png',
            username: '---',
            realname: '---',
            type: 0,
            school: '---',
            sex: 1,
            gradeId: '---',
            classId: '---'
        },
        isFollow: false,

        //选项卡
        tabStatus: '',
        changeTab: function (value, type) {
            //锚点赋值
            window.location.hash = value;

            if (value == 'HisFocus') {
                myFocusTeacher.myFocusTeacher.myFocusList.length == '0' ? myFocusTeacher.myFocusTeacher.getMyFocusInfo(type) : myFocusTeacher.myFocusTeacher.myFocusList;
            }

            if (value == 'HisFriends') {
                myFansTeacher.myFansTeacher.myFansList.length == '0' ? myFansTeacher.myFansTeacher.getMyFansInfo(type) : myFansTeacher.myFansTeacher.myFansList;
            }


            if (value == 'myQuestion') {
                //question.questionInfo.length == '0' ? question.getDate(1) : false; //1 未回答，2已回答
            }

            if (value == 'collectQuestion') {
                //collectQuestion.questionInfo.length == '0' ? collectQuestion.getDate() : false;
            }
            if (value == 'myAuditing') {
                //myAuditing.audit.courseInfo.length == '0' ? myAuditing.audit.getDate() : false;
            }

            //学生在学课程
            if (value == 'studentCourse') {
                studentCourse.studentCourseInfo.length == '0' ? studentCourse.getDate(1) : false;
            }
            //他的问答
            if (value == 'studentAnswer') {
                studentAnswer.studentAnswerInfo.length == '0' ? studentAnswer.getDate(1) : false;
            }

            studentHomePagePublic.tabStatus = value;
        },

        //关注
        followUser: function() {
            if (studentHomePagePublic.isFollow) {
                studentHomePagePublic.popUp = 'unFollow';
            } else {
                studentHomePagePublic.getData('/member/followUser', 'isFollow', {table: 'friends', action: 2, data: {fromUserId: studentHomePagePublic.mineId, toUserId: studentHomePagePublic.hisId}}, 'POST', function(response) {
                    if(response.status) {
                        myFansTeacher.myFansTeacher.getMyFansInfo(studentHomePagePublic.hisId);
                        studentHomePagePublic.fansNum++ ;
                    }
                });
            }
        },

        //弹出层
        popUp: false,
        popUpSwitch: function(value, unFollow) {
            studentHomePagePublic.popUp = value;
            unFollow && studentHomePagePublic.getData('/member/followUser', 'isFollow', {table: 'friends', action: 3, data: {fromUserId: studentHomePagePublic.mineId, toUserId: studentHomePagePublic.hisId}}, 'POST', function(response) {
                if(response.status) {
                    myFansTeacher.myFansTeacher.getMyFansInfo(studentHomePagePublic.hisId);
                    studentHomePagePublic.fansNum-- ;
                }
            });
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
                        studentHomePagePublic[model] = response.data;
                    } else {
                        studentHomePagePublic['fansNum'] = response.data || 0;
                        studentHomePagePublic['courseNum'] = response.data || 0;
                    }

                    callback && callback(response);
                },
                error: function(error) {

                }
            });
        },

        //模板变量
        hisId : '', //公开
        mineId: ''  //登录Id
    });

    return studentHomePagePublic;
});