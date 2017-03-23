$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.txt').focus(function(){
    $(this).parent().prev().find('.clearInput').removeClass('hide');
})

$('.txt').blur(function(){
    var self = $(this);
    setTimeout(function(){
        self.parent().prev().find('.clearInput').addClass('hide');
    }, 500);
})

$('.clearInput').click(function(){
    $(this).parent().next().find('input').val('');
})

var postcheck = function(){
    var checkLogin = false;
    var username = $('.uname').val();
    var password = $('.psd').val();

    if(!username){
        $('.errorMsg').html('* 请输入用户名或手机号！');
        $('.uname').parent().siblings('.cuo').removeClass('hide');
        return false;
        exit;
    }else if(!password){
        $('.errorMsg').html('* 请输入密码！');
        $('.psd').parent().siblings('.cuo').removeClass('hide');
        return false;
        exit;
    }else{
        $.ajax({
            type: "post",
            url: "/index/getCheckRes",
            data:{username:username,password:password},
            async:false,
            success: function(data){  //1 账号不存在，2正确登陆，3密码错误
                if(data == 1){
                    $('.errorMsg').html('* 您输入的账号不存在！');
                    $('.uname').parent().siblings('.cuo').removeClass('hide');
                    // window.event.returnValue = false;
                    // return false;
                }else if(data == 3){
                    $('.errorMsg').html('* 您输入的密码错误！');
                    $('.psd').parent().siblings('.cuo').removeClass('hide');
                    // window.event.returnValue = false;
                    // return false;
                }else if(data == 4){
                    $('.errorMsg').html('* 该账号被禁用，请联系管理员！');
                }else if(data == 2){
                    // return true;
                    checkLogin = true;
                }
            },
            error:function(XMLHttpRequest, textStatus, errorThrown){
                $('.errorMsg').html('* 未知错误，请重新尝试！');
                // window.event.returnValue = false;
                // return false;
            }
        });
    }

    return checkLogin;                //tmp
}


$('.txt').focus(function () {
    $('.errorMsg').html('');
    $(this).parent().siblings('.cklogo').addClass('hide');
});


