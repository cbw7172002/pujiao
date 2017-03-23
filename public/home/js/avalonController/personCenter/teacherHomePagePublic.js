/**
 * Created by LT on 2017/1/18.
 */

define(['/teacher/myFocusTeacher','/teacher/myFansTeacher','/teacher/teacherCourse','/teacher/teacherAnswer'], function (myFocusTeacher, myFansTeacher, teacherCourse, teacherAnswer) {
    var teacherHomePagePublic = avalon.define({
        $id: 'teacherHomePagePublic',
        userInfo: {
            pic: '/home/image/layout/default.png',
            username: '---',
            realname: '',
            type: 1,
            school: '---',
            sex: 1,
            subjectName: '---',
            subjectNames: ''
        },
        resourceCount : 0,
        courseCount: 0,
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

            //教师课程
            if (value == 'teacherCourse') {
                teacherCourse.teacherCourseInfo.length == '0' ? teacherCourse.getDate(1) : false;
            }
            //教师问答
            if (value == 'teacherAnswer') {
                teacherAnswer.teacherAnswerInfo.length == '0' ? teacherAnswer.getDate(1) : false;
            }

            teacherHomePagePublic.tabStatus = value;
        },

        //关注
        followUser: function() {
            if (teacherHomePagePublic.isFollow) {
                teacherHomePagePublic.popUp = 'unFollow';
            } else {
                teacherHomePagePublic.getData('/member/followUser', 'isFollow', {table: 'friends', action: 2, data: {fromUserId: teacherHomePagePublic.mineId, toUserId: teacherHomePagePublic.hisId}}, 'POST', function(response) {
                    if(response.status) {
                        myFansTeacher.myFansTeacher.getMyFansInfo(teacherHomePagePublic.hisId);
                        teacherHomePagePublic.fansNum++ ;
                    }
                });
            }
        },

        //弹出层
        popUp: false,
        popUpSwitch: function(value, unFollow) {
            teacherHomePagePublic.popUp = value;
            unFollow && teacherHomePagePublic.getData('/member/followUser', 'isFollow', {table: 'friends', action: 3, data: {fromUserId: teacherHomePagePublic.mineId, toUserId: teacherHomePagePublic.hisId}}, 'POST', function(response) {
                if(response.status) {
                    myFansTeacher.myFansTeacher.getMyFansInfo(teacherHomePagePublic.hisId);
                    teacherHomePagePublic.fansNum-- ;
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
                        teacherHomePagePublic[model] = response.data;
                    } else {
                        teacherHomePagePublic['fansNum'] = response.data || 0;
                        teacherHomePagePublic['courseNum'] = response.data || 0;
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

    return teacherHomePagePublic;
});