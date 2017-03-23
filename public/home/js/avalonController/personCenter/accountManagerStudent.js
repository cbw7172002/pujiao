/**
 * Created by LT on 2017/1/16.
 */

define([], function () {

    var accountManagerStudent = avalon.define({
        $id: 'accountManagerStudent',
        infoMsg: '', //修改结果显示
        infoMsg_success: '* 修改成功',
        infoMsg_error: '* 修改失败',

        //选项卡
        tabStatus: '',
        changeTab: function (value, type) {
            //锚点赋值
            window.location.hash = value;
            accountManagerStudent.tabStatus = value;
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
                accountManagerStudent.passMsg = true;
            } else {
                if(password.length >= 6){
                    $.ajax({
                        type: "POST",
                        url: "/member/checkPassword",
                        data: {table: 'users',action: 1, userId: accountManagerStudent.mineId, password: password},
                        dataType: 'json',
                        success: function (response) {
                            if(response.status) {
                                $("input[name='currentPass']").parent().next().html('');
                                accountManagerStudent.passMsg = false;
                            } else {
                                $("input[name='currentPass']").parent().next().html('* 当前密码输入不正确');
                                accountManagerStudent.passMsg = true;
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
                accountManagerStudent.newPassMsg = true;
            } else {
                if( !$("input[name='sureNewPass']").val() || password != $("input[name='sureNewPass']").val()) {
                    $("input[name='newPass']").parent().next().html('* 两次密码输入不正确');
                    accountManagerStudent.newPassMsg = true;
                    $("input[name='sureNewPass']").parent().next().html('* 两次密码输入不正确');
                    accountManagerStudent.surePassMsg = true;
                } else {
                    $("input[name='newPass']").parent().next().html('');
                    accountManagerStudent.newPassMsg = false;
                    $("input[name='sureNewPass']").parent().next().html('');
                    accountManagerStudent.surePassMsg = false;
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
                accountManagerStudent.surePassMsg = true;
            } else {
                if( !$("input[name='newPass']").val() || password != $("input[name='newPass']").val()) {
                    $("input[name='newPass']").parent().next().html('* 两次密码输入不正确');
                    accountManagerStudent.newPassMsg = true;
                    $("input[name='sureNewPass']").parent().next().html('* 两次密码输入不正确');
                    accountManagerStudent.surePassMsg = true;
                } else {
                    $("input[name='newPass']").parent().next().html('');
                    accountManagerStudent.newPassMsg = false;
                    $("input[name='sureNewPass']").parent().next().html('');
                    accountManagerStudent.surePassMsg = false;
                }
            }
        },

        changePassButton: function(pass, newPass, surePass , userId) {
            if(!accountManagerStudent.passMsg && !accountManagerStudent.newPassMsg && !accountManagerStudent.surePassMsg) {
                accountManagerStudent.infoMsg = '';
                $.ajax({
                    type: "POST",
                    url: "/member/checkPassword",
                    data: {table: 'users',action: 2, userId: userId, password: newPass},
                    dataType: 'json',
                    success: function (response) {
                        response.status ? accountManagerStudent.infoMsg = true : accountManagerStudent.infoMsg = false;
                        setTimeout(function () {
                            $('.infoMsg').slideUp(500);
                            accountManagerStudent.infoMsg = '';
                            accountManagerStudent.currentPass = '';
                            accountManagerStudent.newPass = '';
                            accountManagerStudent.sureNewPass = '';
                        }, 3000);
                    }
                });
            }

        },
        //修改
        phoneIndex: 'changePhone',
        changePhone: function (value) {
            accountManagerStudent.phoneIndex = value;
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
                    if (accountManagerStudent.checkPhone && accountManagerStudent.checkCode) {
                        $(".phone_part2_switch_top div:first-child").next().addClass('blue_common').siblings().removeClass('blue_common');
                        accountManagerStudent.next = value;
                    }
                    break;
                case 'getCode2':
                    if (accountManagerStudent.checkNewPhone && accountManagerStudent.checkNewCode) {
                        $.ajax({
                            type: "post",
                            url: "/member/changePhone",
                            data: {phone: accountManagerStudent.newPhone, userId: accountManagerStudent.mineId},
                            dataType: 'json',
                            // async:false,
                            success: function (response) {
                                if (response.status) {
                                    $(".phone_part2_switch_top div:last-child").addClass('blue_common').siblings().removeClass('blue_common');
                                    accountManagerStudent.next = value;
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
                        accountManagerStudent.checkPhone = true;
                        $('.msgAa').html(' ');
                    } else {
                        accountManagerStudent.checkPhone = false;
                        $('.msgAa').html('* 该手机号码尚未注册');
                    }
                }
            });
        },
        //检查绑定手机号是否存在
        checkHaveb: function (phone) {
            if(phone == $('.change_phone_part1 span:last-child').attr('title')) {
                accountManagerStudent.checkNewPhone = true;
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
                            accountManagerStudent.checkNewPhone = false;
                            $('.msgCc').html('* 该手机号码已被注册');
                        } else {
                            accountManagerStudent.checkNewPhone = true;
                            $('.msgCc').html(' ');
                        }
                    }
                });
            }

        },
        getCode: function (value) {
            switch (value) {
                case 'code':
                    if (accountManagerStudent.checkPhone && accountManagerStudent.sendOldAgain) {
                        $.ajax({
                            type: "get",
                            url: "/index/getMessage/" + accountManagerStudent.phone,
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
                        accountManagerStudent.sendOldAgain = false;//重新发送按钮 不能点击
                        var myTime = setInterval(function () {
                            countdown--;
                            $('.get_old_code span').html(countdown); // 通知视图模型的变化
                            if (countdown == 0) {
                                accountManagerStudent.sendOldAgain = true;//重新发送按钮 可以点击
                                $('.get_old_code span').html('重发');
                                clearInterval(myTime);
                            }
                        }, 1000);
                    }
                    break;
                case 'newCode':
                    if (accountManagerStudent.checkNewPhone && accountManagerStudent.sendNewAgain) {
                        $.ajax({
                            type: "get",
                            url: "/index/getMessage/" + accountManagerStudent.newPhone,
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
                        accountManagerStudent.sendNewAgain = false;//重新发送按钮 不能点击
                        var myTime = setInterval(function () {
                            countdown--;
                            $('.get_new_code span').html(countdown); // 通知视图模型的变化
                            if (countdown == 0) {
                                accountManagerStudent.sendNewAgain = true;//重新发送按钮 可以点击
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
                        accountManagerStudent[model] = response.data;
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
    accountManagerStudent.$watch("phone", function (a, b) {//a
        if (a.length == 11) {
            if (!a.match(/^1(3|5|8|7){1}[0-9]{9}$/)) {
                accountManagerStudent.checkPhone = false;
                $('.msgAa').html('* 手机号格式错误');
            } else if (a != $('.change_phone_part1 span:last-child').attr('title')) {
                accountManagerStudent.checkPhone = false;
                $('.msgAa').html('* 请输入原绑定手机号');
            } else {
                accountManagerStudent.checkHave(a);
            }
        }
    });

    accountManagerStudent.$watch("code", function (a, b) {//a
        if (a.length == 6) {
            console.log('hello');
            $.ajax({
                type: "get",
                url: "/index/checkCode/" + a,
                // async:false,
                success: function (data) {
                    if (data == 1) {
                        accountManagerStudent.checkCode = true;
                        $('.msgBb').html(' ');
                    } else {
                        accountManagerStudent.checkCode = false;
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

    accountManagerStudent.$watch("newPhone", function (a, b) {//a
        if (a.length == 11) {
            if (!a.match(/^1(3|5|8|7){1}[0-9]{9}$/)) {
                accountManagerStudent.checkNewPhone = false;
                $('.msgCc').html('* 手机号格式错误');
            } else {
                accountManagerStudent.checkHaveb(a);
            }
        }
    });

    accountManagerStudent.$watch("newCode", function (a, b) {//a
        if (a.length == 6) {
            $.ajax({
                type: "get",
                url: "/index/checkCode/" + a,
                // async:false,
                success: function (data) {
                    if (data == 1) {
                        accountManagerStudent.checkNewCode = true;
                        $('.msgDd').html(' ');
                    } else {
                        accountManagerStudent.checkNewCode = false;
                        $('.msgDd').html('* 验证码错误');
                    }
                }
            });
        }
    });

    return accountManagerStudent;
});