$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



var checkUphone = false;
var checkUcode  = false;

//手机号验证
$('.uphone').blur(function(){
    var phone = $('.uphone').val();
    var self = $(this);

    if(!phone){//为空
        $(this).parent().parent().next().html('* 请输入手机号');
        $(this).parent().siblings('.cuo').removeClass('hide');
        checkUphone = false;
        return false;
    }else if(!phone.match(/^1(3|5|8|7){1}[0-9]{9}$/)){//格式错误
        $(this).parent().parent().next().html('* 手机号格式错误');
        $(this).parent().siblings('.cuo').removeClass('hide');
        checkUphone = false;
        return false;
    }else{
        $.ajax({
            type: "post",
            url: "/index/getCheckUphone",
            data:{phone:phone},
            // async:false,
            success: function(data){
                if(data == 2){  //1手机号存在 ，2手机号不存在
                    self.parent().parent().next().html('* 该手机号码尚未注册');
                    self.parent().siblings('.cuo').removeClass('hide');
                    checkUphone = false;
                    return false;
                }else{
                    checkUphone = true;
                    self.parent().siblings('.dui').removeClass('hide');
                }
            }
        });
    }
})

//获取验证码
var getMsg = function(){
    setTimeout(function(){

        if(!checkUphone){//手机号验证没通过
            // $('.uphone').trigger('blur');
            return false;
        }else{//手机号验证通过

            //获取验证码
            var phone = $('.uphone').val();
            $.ajax({
                type: "get",
                url: "/index/getMessage/"+phone,
                // async:false,
                success: function(data){
                    if(data.type== true){
                        code = data.info;
                    }else{
                        code = data.info;
                    }
                }
            });

            //计数60s
            var countdown = 60;
            $(".getyzm").attr({ disabled: "disabled"});//重新发送按钮 不能点击
            var myTime = setInterval(function() {
                countdown--;
                $('.getyzm').html(countdown); // 通知视图模型的变化
                if(countdown == 0){
                    $('.getyzm').html('重发').removeAttr("disabled");//重新发送按钮 可以点击
                    clearInterval(myTime);
                }
            }, 1000);

        }
    }, 900);

}


//验证码验证
$('.txtt').blur(function(){
    var ucode = $('.txtt').val();
    var self = $(this);

    if(!ucode) {//为空
        $(this).parent().parent().next().html('* 请输入验证码');
        $(this).parent().siblings('.cuo').removeClass('hide');
        checkUcode  = false;
    }else{
        $.ajax({
            type: "get",
            url: "/index/checkCode/"+ucode,
            // async:false,
            success: function(data){
                if(data == 1){
                    self.parent().siblings('.dui').removeClass('hide');
                    checkUcode  = true;
                }else{
                    self.parent().siblings('.cuo').removeClass('hide');
                    self.parent().parent().next().html('* 验证码错误');
                    checkUcode  = false;
                }
            }
        });
        /*
        if (ucode == code) {//验证码正确
            $(this).parent().siblings('.dui').removeClass('hide');
            checkUcode  = true;
        } else {//验证码错误
            $(this).parent().siblings('.cuo').removeClass('hide');
            $(this).parent().parent().next().html('* 验证码错误');
            checkUcode  = false;
        }
        */
    }
})


var nextCheck = function(){
    setTimeout(function(){

        if(checkUphone && checkUcode){
            var phone = $('.uphone').val();
            window.location.href = '/index/resetpsd/'+phone;
        }else{
            $('.uphone').trigger('blur');
            $('.txtt').trigger('blur');
            return false;
        }
    }, 500);

}


//输入框获得焦点清除提示信息
$('.txt').focus(function () {
    $(this).parent().parent().next().html('');
    $(this).parent().siblings('.cklogo').addClass('hide');
});
$('.txtt').focus(function () {
    $(this).parent().parent().next().html('');
    $(this).parent().siblings('.cklogo').addClass('hide');
});