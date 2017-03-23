$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var url = window.location.href;
var userPhone = url.split('/')[url.split('/').length-1];

var checkUpassword  = false;
var checkUrepassword  = false;


//密码验证
$('.upsd').blur(function(){
    var upassword = $('.upsd').val();

    if(!upassword){//为空
        $(this).parent().parent().next().html('* 请输入密码');
        $(this).parent().siblings('.cuo').removeClass('hide');
        checkUpassword  = false;
        return false;
    }else if (!upassword.match(/^[A-Za-z0-9]{6,12}$/)) {//格式错误
        $(this).parent().parent().next().html('* 6—12位数字/字母(不区分大小写)');
        $(this).parent().siblings('.cuo').removeClass('hide');
        checkUpassword  = false;
        return false;
    }else{//密码格式正确
        $(this).parent().siblings('.dui').removeClass('hide');
        checkUpassword  = true;
    }
})

//确认密码 验证
$('.repsd').blur(function(){
    var repassword = $('.repsd').val();

    if(!checkUpassword){//密码验证没通过
        $('.upsd').trigger('blur');
        $(this).parent().siblings('.cuo').removeClass('hide');
        checkUrepassword  = false;
    }else{
        if(!repassword){//为空
            $(this).parent().parent().next().html('* 请输入确认密码');
            $(this).parent().siblings('.cuo').removeClass('hide');
            checkUrepassword  = false;
        }else{
            var upassword = $('.upsd').val();
            if(repassword == upassword){//确认密码一致
                $(this).parent().siblings('.dui').removeClass('hide');
                checkUrepassword  = true;
            }else{//确认密码不一致
                $(this).parent().parent().next().html('* 两次密码输入不一致，请重新输入');
                $(this).parent().siblings('.cuo').removeClass('hide');
                checkUrepassword  = false;
            }
        }
    }

})

function count(){
    $('.delete_comment').removeClass('hide');
    var countdown = 3;
    $(".getyzm").attr({ disabled: "disabled"});//重新发送按钮 不能点击
    var myTime = setInterval(function() {
        countdown--;
        if(countdown == 0){
            window.location.href = '/';
            clearInterval(myTime);
        }
        console.log(countdown);
    }, 1000);
}


var submitInfo = function(){
    var upassword = $('.upsd').val();
    if(checkUpassword && checkUrepassword){
        $.ajax({
            type: "post",
            url: "/index/resetPassword",
            data:{phone:userPhone,password:upassword},
            async:false,
            success: function(data){ //1修改成功 2修改失败
                if(data == 1){
                    count();
                }else{
                    alert('修改失败');
                }
            }
        });
    }else{
        $('.upsd').trigger('blur');
        $('.repsd').trigger('blur');
    }
}


$('.txt').focus(function () {
    $(this).parent().parent().next().html('');
    $(this).parent().siblings('.cklogo').addClass('hide');
});