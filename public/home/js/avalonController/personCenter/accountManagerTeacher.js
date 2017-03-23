/**
 * Created by LT on 2017/1/16.
 */

define(['/teacher/bindSubject', '/teacher/addCourse'], function (bindSubject, addCourse) {

    var accountManagerTeacher = avalon.define({
        $id: 'accountManagerTeacher',
        education: '',
        professional: '',
        infoMsg: null, //修改结果显示
        infoMsg_success: '* 修改成功',
        infoMsg_error: '* 修改失败',
        msg_intro: true,
        intro: '',
        holdButton: function() {
            if(accountManagerTeacher.intro.length >= 200){
                $("#msg_intro").html('* 个人简介输入的字数过多，请重新输入');
                accountManagerTeacher.msg_intro = false;
            }else {
                $("#msg_intro").html('');
                accountManagerTeacher.msg_intro = true;
            }
            if(accountManagerTeacher.msg_intro){
                accountManagerTeacher.infoMsg = null;
                $.ajax({
                    type: "post",
                    url: "/member/infoUphold",
                    data: { table: 'users', userId: accountManagerTeacher.mineId, data: {sex: $("input[name='sex']:checked").val(), school: $("#inputSchool").val(), education: $("#inputEducation1").val(), professional: $("#inputTech").val(), intro: accountManagerTeacher.intro } },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            accountManagerTeacher.infoMsg = true;
                            accountManagerTeacher.intro = response.intro;
                        } else {
                            accountManagerTeacher.infoMsg = false;
                        }
                        setTimeout(function () {
                            $('.infoMsg').slideUp(500);
                            accountManagerTeacher.infoMsg = null;
                            //window.location.reload();
                        }, 3000);
                    }
                });
            }

        },


        //修改 -- 绑定学科
        subjectId: '',
        deleteSubjectId: '',
        //修改 -- 任课
        courseId: '',
        deleteCourseId: '',
        bindSubjects: 0,

        popUp: false,
        popUpSwitch: function (value, id) {
            accountManagerTeacher.popUp = value;
            if(value == 'sure') {
                if($("#id_label_single").val() && $("#id_label_single1").val() && $("#id_label_single2").val() && $("#id_label_single3").val()) {
                    $.ajax({
                        type: "post",
                        url: "/member/getBindSubjects",
                        data: { table: 'teachersubject', action: 2, data: { tId: accountManagerTeacher.mineId, gradeId: $("#id_label_single").val(), subjectId: $("#id_label_single1").val(), bookId: $("#id_label_single2").val(), editionId: $("#id_label_single3").val() } },
                        dataType: 'json',
                        success: function (response) {
                            if(response.type) {
                                alert('不能重复添加！');
                                accountManagerTeacher.popUp = 'bindSubject';
                            } else {
                                if (response.status) {
                                    accountManagerTeacher.popUp = false;
                                    accountManagerTeacher.bindSubjects += 1;
                                    bindSubject.bindSubject.getBindSubjectInfo(accountManagerTeacher.mineId, 1);
                                } else {
                                    accountManagerTeacher.popUp = true;
                                }
                            }
                        }
                    });

                } else {
                    alert('选项不能为空');
                    accountManagerTeacher.popUp = 'bindSubject';
                }

            }
            //修改绑定学科 -- 点击修改
            if(value == 'changeSubject') {
                accountManagerTeacher.subjectId = id;
            }
            //执行修改
            if(value == 'changeSure') {
                if($(".grade").val() && $(".subject").val() && $(".book").val() && $(".edition").val()) {
                    $.ajax({
                        type: "post",
                        url: "/member/getBindSubjects",
                        data: { table: 'teachersubject', action: 4, id: accountManagerTeacher.subjectId, data: { tId: accountManagerTeacher.mineId, gradeId: $(".grade").val(), subjectId: $(".subject").val(), bookId: $(".book").val(), editionId: $(".edition").val() } },
                        dataType: 'json',
                        success: function (response) {
                            if(response.type) {
                                alert('此学科信息已存在，不能重复！');
                                accountManagerTeacher.popUp = 'changeSubject';
                            } else {
                                if (response.status) {
                                    accountManagerTeacher.popUp = false;
                                    bindSubject.bindSubject.getBindSubjectInfo(accountManagerTeacher.mineId);
                                } else {
                                    accountManagerTeacher.popUp = 'changeSubject';
                                }
                            }
                        }
                    });

                } else {
                    alert('选项不能为空');
                    accountManagerTeacher.popUp = 'changeSubject';
                }
            }

            //点击删除
            if(value == 'deleteSubject') {
                accountManagerTeacher.deleteSubjectId = id;
            }
            //执行删除
            if(value == 'sureDeleteSubject') {
                $.ajax({
                    type: "post",
                    url: "/member/getBindSubjects",
                    data: { table: 'teachersubject', action: 5, id: accountManagerTeacher.deleteSubjectId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            accountManagerTeacher.popUp = false;
                            if(accountManagerTeacher.bindSubjects) {accountManagerTeacher.bindSubjects -= 1;}
                            bindSubject.bindSubject.getBindSubjectInfo(accountManagerTeacher.mineId);
                        } else {
                            alert('删除已绑定学科失败！');
                            accountManagerTeacher.popUp = 'deleteSubject';
                        }
                    }
                });
            }
            //新增任课 -- 确定
            if(value == 'sureCourse') {
                if($("#id_label_single4").val() && $("#id_label_single5").val() && $("#id_label_single6").val()) {
                    $.ajax({
                        type: "post",
                        url: "/member/getBindSubjects",
                        data: { table: 'teacherteach', action: 2, data: { tId: accountManagerTeacher.mineId, gradeId: $("#id_label_single4").val(), classId: $("#id_label_single5").val(), subjectId: $("#id_label_single6").val()} },
                        dataType: 'json',
                        success: function (response) {
                            if(response.type) {
                                alert('不能重复添加！');
                                accountManagerTeacher.popUp = 'addCourse';
                            } else {
                                if (response.status) {
                                    addCourse.addCourse.getAddCourseInfo(accountManagerTeacher.mineId, 1);
                                    accountManagerTeacher.popUp = false;
                                } else {
                                    alert('新增任课失败！');
                                    accountManagerTeacher.popUp = false;
                                }

                            }
                        }
                    });

                } else {
                    alert('选项不能为空');
                    accountManagerTeacher.popUp = 'addCourse';
                }
            }

            //新增任课 -- 点击修改
            if(value == 'changeCourse') {
                accountManagerTeacher.courseId = id;
            }
            //新增任课 -- 执行修改
            if(value == 'sureChangeCourse') {
                if($(".id_label_single4").val() && $(".id_label_single5").val() && $(".id_label_single6").val()) {
                    $.ajax({
                        type: "post",
                        url: "/member/getBindSubjects",
                        data: { table: 'teacherteach', action: 4, id: accountManagerTeacher.courseId, data: { tId: accountManagerTeacher.mineId, gradeId: $(".id_label_single4").val(), classId: $(".id_label_single5").val(), subjectId: $(".id_label_single6").val()} },
                        dataType: 'json',
                        success: function (response) {
                            if(response.type) {
                                alert('此任课信息已存在，不能重复！');
                                accountManagerTeacher.popUp = 'changeCourse';
                            } else {
                                if (response.status) {
                                    accountManagerTeacher.popUp = false;
                                    addCourse.addCourse.getAddCourseInfo(accountManagerTeacher.mineId);
                                } else {
                                    alert('修改任课信息失败！');
                                    accountManagerTeacher.popUp = false;
                                }
                            }
                        }
                    });

                } else {
                    alert('选项不能为空');
                    accountManagerTeacher.popUp = 'changeCourse';
                }
            }
            //新增任课 -- 点击删除
            if(value == 'deleteCourse') {
                accountManagerTeacher.deleteCourseId = id;
            }
            //执行删除
            if(value == 'sureDeleteCourse') {
                $.ajax({
                    type: "post",
                    url: "/member/getBindSubjects",
                    data: { table: 'teacherteach', action: 5, id: accountManagerTeacher.deleteCourseId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            accountManagerTeacher.popUp = false;
                            addCourse.addCourse.getAddCourseInfo(accountManagerTeacher.mineId);
                        } else {
                            alert('删除任课失败！');
                            accountManagerTeacher.popUp = 'deleteCourse';
                        }
                    }
                });

            }

        },
        //选项卡
        tabStatus: '',
        changeTab: function (value, type) {
            //锚点赋值
            window.location.hash = value;
            if (value == 'bindSubject') {
                bindSubject.bindSubject.bindSubjectInfo.length == '0' ? bindSubject.bindSubject.getBindSubjectInfo(accountManagerTeacher.mineId) : bindSubject.bindSubject.bindSubjectInfo;
            }
            if (value == 'addCourse') {
                addCourse.addCourse.addCourseInfo.length == '0' ? addCourse.addCourse.getAddCourseInfo(accountManagerTeacher.mineId) : addCourse.addCourse.addCourseInfo;
            }
            if (value == 'bindPhone') {

            }
            accountManagerTeacher.tabStatus = value;

        },

        //修改密码
        passMsg: true, //错误信息标识
        newPassMsg: true,
        surePassMsg: true,
        currentPass: '',
        newPass:'',
        sureNewPass: '',
        validateCurrent: function(password) {

            if (!password.match(/^[A-Za-z0-9_]{6,16}$/)) {
                //格式错误
                if(password.match(/^\s+$/g)) {
                    $("input[name='currentPass']").parent().next().html('* 当前密码不能为空');
                }else {
                    $("input[name='currentPass']").parent().next().html('* 6—16位字母/数字/下划线');
                }
                accountManagerTeacher.passMsg = true;
            } else {
                if(password.length >= 6){
                    $.ajax({
                        type: "POST",
                        url: "/member/checkPassword",
                        data: {table: 'users',action: 1, userId: accountManagerTeacher.mineId, password: password},
                        dataType: 'json',
                        success: function (response) {
                            if(response.status) {
                                $("input[name='currentPass']").parent().next().html('');
                                accountManagerTeacher.passMsg = false;
                            } else {
                                $("input[name='currentPass']").parent().next().html('* 当前密码输入不正确');
                                accountManagerTeacher.passMsg = true;
                            }
                        }
                    });
                }

            }

        },

        validateNew: function(password) {
            if (!password.match(/^[A-Za-z0-9_]{6,16}$/)) {
                //格式错误
                if(password.match(/^\s+$/g)) {
                    $("input[name='newPass']").parent().next().html('* 新密码不能为空');
                }else {
                    $("input[name='newPass']").parent().next().html('* 6—16位字母/数字/下划线');
                }
                accountManagerTeacher.newPassMsg = true;
            } else {
                if( !$("input[name='sureNewPass']").val() || password != $("input[name='sureNewPass']").val()) {
                    $("input[name='newPass']").parent().next().html('* 两次密码输入不正确');
                    accountManagerTeacher.newPassMsg = true;
                    $("input[name='sureNewPass']").parent().next().html('* 两次密码输入不正确');
                    accountManagerTeacher.surePassMsg = true;
                } else {
                    $("input[name='newPass']").parent().next().html('');
                    accountManagerTeacher.newPassMsg = false;
                    $("input[name='sureNewPass']").parent().next().html('');
                    accountManagerTeacher.surePassMsg = false;
                }

            }
        },
        validateSure: function(password) {
            if (!password.match(/^[A-Za-z0-9_]{6,16}$/)) {
                //格式错误
                if(password.match(/^\s+$/g)) {
                    $("input[name='sureNewPass']").parent().next().html('* 确认密码不能为空');
                }else {
                    $("input[name='sureNewPass']").parent().next().html('* 6—16位字母/数字/下划线');
                }
                accountManagerTeacher.surePassMsg = true;
            } else {
                if( !$("input[name='newPass']").val() || password != $("input[name='newPass']").val()) {
                    $("input[name='newPass']").parent().next().html('* 两次密码输入不正确');
                    accountManagerTeacher.newPassMsg = true;
                    $("input[name='sureNewPass']").parent().next().html('* 两次密码输入不正确');
                    accountManagerTeacher.surePassMsg = true;
                } else {
                    $("input[name='newPass']").parent().next().html('');
                    accountManagerTeacher.newPassMsg = false;
                    $("input[name='sureNewPass']").parent().next().html('');
                    accountManagerTeacher.surePassMsg = false;
                }
            }
        },

        changePassButton: function(pass, newPass, surePass , userId) {
            if(!accountManagerTeacher.passMsg && !accountManagerTeacher.newPassMsg && !accountManagerTeacher.surePassMsg) {
                accountManagerTeacher.infoMsg = null;
                $.ajax({
                    type: "POST",
                    url: "/member/checkPassword",
                    data: {table: 'users',action: 2, userId: userId, password: newPass},
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            accountManagerTeacher.infoMsg = true;
                        } else {
                            accountManagerTeacher.infoMsg = false;
                        }
                        setTimeout(function () {
                            $('.infoMsg').slideUp(500);
                            accountManagerTeacher.infoMsg = null;
                            accountManagerTeacher.currentPass = '';
                            accountManagerTeacher.newPass = '';
                            accountManagerTeacher.sureNewPass = '';
                            //window.location.reload();
                        }, 3000);
                    }
                });
            }

        },
        //修改
        phoneIndex: 'changePhone',
        changePhone: function (value) {
            accountManagerTeacher.phoneIndex = value;
        },
        phone: '', //原手机号
        code: '',  //验证码
        checkPhone: false,//验证原手机号
        checkCode: false,//验证验证码
        sendOldAgain: true,

        newPhone: '', //新手机号
        newCode: '',  //新验证码
        checkNewPhone: false,//验证新手机号
        checkNewCode: false,//验证新验证码
        sendNewAgain: true,


        //下一步
        next: 'getCode',
        changeNext: function (value) {
            switch (value) {
                case 'getCode1':
                    if (accountManagerTeacher.checkPhone && accountManagerTeacher.checkCode) {
                        $(".phone_part2_switch_top div:first-child").next().addClass('blue_common').siblings().removeClass('blue_common');
                        accountManagerTeacher.next = value;
                    }
                    break;
                case 'getCode2':
                    if (accountManagerTeacher.checkNewPhone && accountManagerTeacher.checkNewCode) {
                        $.ajax({
                            type: "post",
                            url: "/member/changePhone",
                            data: {phone: accountManagerTeacher.newPhone, userId: accountManagerTeacher.mineId},
                            dataType: 'json',
                            // async:false,
                            success: function (response) {
                                if (response.status) {
                                    $(".phone_part2_switch_top div:last-child").addClass('blue_common').siblings().removeClass('blue_common');
                                    accountManagerTeacher.next = value;
                                } else {
                                    alert('修改失败');
                                }
                            }
                        });

                    }
                    break;
            }
        },
        //检查原手机号是否存在
        checkHave: function (phone) {
            $.ajax({
                type: "post",
                url: "/member/unique/users/phone",
                data: {phone: phone},
                dataType: 'json',
                // async:false,
                success: function (response) {
                    if (response.status) {  //true手机号存在 ，false手机号不存在
                        accountManagerTeacher.checkPhone = true;
                        $('.msgAa').html(' ');
                    } else {
                        accountManagerTeacher.checkPhone = false;
                        $('.msgAa').html('* 该手机号码尚未注册');
                    }
                }
            });
        },
        //检查绑定手机号是否存在
        checkHaveb: function (phone) {
            if(phone == $('.change_phone_part1 span:last-child').attr('title')) {
                accountManagerTeacher.checkNewPhone = true;
                $('.msgCc').html(' ');
            } else {
                $.ajax({
                    type: "post",
                    url: "/member/unique/users/phone",
                    data: {phone: phone},
                    dataType: 'json',
                    // async:false,
                    success: function (response) {
                        if (response.status) {  //true手机号存在 ，false手机号不存在
                            accountManagerTeacher.checkNewPhone = false;
                            $('.msgCc').html('* 该手机号码已被注册');
                        } else {
                            accountManagerTeacher.checkNewPhone = true;
                            $('.msgCc').html(' ');
                        }
                    }
                });
            }

        },
        getCode: function (value) {
            switch (value) {
                case 'code':
                    if (accountManagerTeacher.checkPhone && accountManagerTeacher.sendOldAgain) {
                        $.ajax({
                            type: "get",
                            url: "/index/getMessage/" + accountManagerTeacher.phone,
                            // async:false,
                            success: function (data) {
                                // if(data.type== true){
                                // code = data.info;
                                // }else{
                                // alert('验证码获取失败');
                                // }
                            }
                        });
                        // console.log('点击');
                        //计数60s
                        var countdown = 90;
                        accountManagerTeacher.sendOldAgain = false;//重新发送按钮 不能点击
                        var myTime = setInterval(function () {
                            countdown--;
                            $('.get_old_code span').html(countdown); // 通知视图模型的变化
                            if (countdown == 0) {
                                accountManagerTeacher.sendOldAgain = true;//重新发送按钮 可以点击
                                $('.get_old_code span').html('重发');
                                clearInterval(myTime);
                            }
                        }, 1000);
                    }
                    break;
                case 'newCode':
                    if (accountManagerTeacher.checkNewPhone && accountManagerTeacher.sendNewAgain) {
                        $.ajax({
                            type: "get",
                            url: "/index/getMessage/" + accountManagerTeacher.newPhone,
                            // async:false,
                            success: function (data) {
                                // if(data.type== true){
                                // codeb = data.info;
                                // }else{
                                // alert('验证码获取失败');
                                // }
                            }
                        });
                        // console.log('点击');
                        //计数60s
                        var countdown = 90;
                        accountManagerTeacher.sendNewAgain = false;//重新发送按钮 不能点击
                        var myTime = setInterval(function () {
                            countdown--;
                            $('.get_new_code span').html(countdown); // 通知视图模型的变化
                            if (countdown == 0) {
                                accountManagerTeacher.sendNewAgain = true;//重新发送按钮 可以点击
                                $('.get_new_code span').html('重发');
                                clearInterval(myTime);
                            }
                        }, 1000);
                    }
                    break;
            }

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
                        accountManagerTeacher[model] = response.data;
                    }
                    callback && callback(response);
                },
                error: function(error) {

                }
            });
        },

        //模板变量
        mineId: ''
    });

    accountManagerTeacher.$watch("phone", function (a, b) {//a
        if (a.length == 11) {
            if (!a.match(/^1(3|5|8|7){1}[0-9]{9}$/)) {
                accountManagerTeacher.checkPhone = false;
                $('.msgAa').html('* 手机号格式错误');
            } else if (a != $('.change_phone_part1 span:last-child').attr('title')) {
                accountManagerTeacher.checkPhone = false;
                $('.msgAa').html('* 请输入原绑定手机号');
            } else {
                accountManagerTeacher.checkHave(a);
            }
        }
    });

    accountManagerTeacher.$watch("code", function (a, b) {//a
        if (a.length == 6) {
            console.log('hello');
            $.ajax({
                type: "get",
                url: "/index/checkCode/" + a,
                // async:false,
                success: function (data) {
                    if (data == 1) {
                        accountManagerTeacher.checkCode = true;
                        $('.msgBb').html(' ');
                    } else {
                        accountManagerTeacher.checkCode = false;
                        $('.msgBb').html('* 验证码错误');
                    }
                }
            });
        }
        // if(a != code){
        //     sideBar.checkCode = false;
        //     $('.Msgbb').html('* 验证码错误');
        // }else{
        //     sideBar.checkCode = true;
        //     $('.Msgbb').html(' ');
        // }
    });

    accountManagerTeacher.$watch("newPhone", function (a, b) {//a
        if (a.length == 11) {
            if (!a.match(/^1(3|5|8|7){1}[0-9]{9}$/)) {
                accountManagerTeacher.checkNewPhone = false;
                $('.msgCc').html('* 手机号格式错误');
            } else {
                accountManagerTeacher.checkHaveb(a);
            }
        }
    });

    accountManagerTeacher.$watch("newCode", function (a, b) {//a
        if (a.length == 6) {
            $.ajax({
                type: "get",
                url: "/index/checkCode/" + a,
                // async:false,
                success: function (data) {
                    if (data == 1) {
                        accountManagerTeacher.checkNewCode = true;
                        $('.msgDd').html(' ');
                    } else {
                        accountManagerTeacher.checkNewCode = false;
                        $('.msgDd').html('* 验证码错误');
                    }
                }
            });
        }
        // if(a != codeb){
        //     sideBar.checkNewCode = false;
        //     $('.Msgdd').html('* 验证码错误');
        // }else{
        //     sideBar.checkNewCode = true;
        //     $('.Msgdd').html(' ');
        // }
    });

    return accountManagerTeacher;
});