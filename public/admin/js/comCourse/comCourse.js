//审核状态
function selectCheck(id,checkid,username,teachername,teacherPhone,orderSn){
    var id = id;
    var stateid = checkid;
    var username = username;
    var teacherPhone = teacherPhone;
    var orderSn = orderSn;

    if(stateid == 0){
        $('#pupUpback2').css({'display':'block'});
        $('.actionId').val(id);
        $('.state').val(stateid);
        $('.orderSn').val(orderSn);
        $('.username').val(username);
        $('.toUsername').val(teachername);
    }
    if(stateid == 1){
        location.reload();//刷新页面
    }
    if(stateid == 2){
        $('#pupUpback1').css({'display':'block'});
        $('.redactionId').val(id);
        $('.redstate').val(stateid);
        $('.redfromUsername').val(username);
        $('.redusername').val(teachername);

        $('.teacherPhone').val(teacherPhone);
        $('.redorderSn').val(orderSn);
    }

    //$.ajax({
    //    type: "get",
    //    data:{'id':id,'state':stateid,'orderSn':orderSn,'username':username,'teachername':teachername},
    //    url: "/admin/commentCourse/commentState",
    //
    //    dataType: 'json',
    //    success: function (res) {
    //        if(res.state == 0){
    //            $('#pupUpback2').css({'display':'block'});
    //            $('.actionId').val(id);
    //            $('.username').val(username);
    //            $('.toUsername').val(teachername);
    //        }
    //        if(res.state == 1){
    //            location.reload();//刷新页面
    //        }
    //        if(res.state == 2){
    //            $('#pupUpback1').css({'display':'block'});
    //            $('.redactionId').val(id);
    //            $('.redfromUsername').val(username);
    //            $('.redusername').val(teachername);
    //
    //            $('.teacherPhone').val(teacherPhone);
    //            $('.orderSn').val(orderSn);
    //        }
    //    }
    //});
}

$('#suer_btn0').click(function(){
    //判断是否重复提交
    if($(this).attr('class') == 'suer_btn clicking'){
        return false;
    }else{
        $(this).addClass('clicking');
    }

    var actionId = $('.redactionId').val();
    var fromUsername = $('.redfromUsername').val();
    var username = $('.redusername').val();
    var toUsername = $('.redtoUsername').val();
    var token = $('.token').val();

    var phone = $('.teacherPhone').val();
    var orderSn = $('.redorderSn').val();

    //修改审核状态为通过
    var state = $('.redstate').val();
    $.ajax({
        type: "get",
        data:{'id':actionId,'state':state,'orderSn':orderSn},
        url: "/admin/commentCourse/commentState",

        dataType: 'json',
        success: function (res) {

        }
    });

    $.ajax({
        type: "post",
        data:{actionId:actionId,fromUsername:fromUsername,username:username,toUsername:toUsername,phone:phone,orderSn:orderSn,_token:token},
        url: "/admin/commentCourse/sendMessage",
        dataType: 'json',
        success: function (res) {
            $(this).removeClass('clicking');
            location.reload();
        }
    });
    //$('#pupUpback1').css({'display':'none'});
});

//审核未通过
$('#nobtn').click(function(){
    var content = $("textarea[name='content']").val();
    if(!content){
        return false;
    }
    var id = $('.actionId').val();
    var state = $('.state').val();
    var orderSn = $('.orderSn').val();
    //修改审核状态为未通过
    $.ajax({
        type: "get",
        data:{'id':id,'state':state,'orderSn':orderSn},
        url: "/admin/commentCourse/commentState",

        dataType: 'json',
        success: function (res) {
            location.reload();
        }
    });
    //发送未通过消息
    var username = $('.username').val();
    var toUsername = $('.toUsername').val();
    var fromUsername = $('.fromUsername').val();
    var _token = $('._token').val();
    $.ajax({
        type: "post",
        data:{actionId:id,username:username,toUsername:toUsername,fromUsername:fromUsername,content:content,_token:_token},
        url: "/admin/commentCourse/noPassMsg",

        dataType: 'json',
        success: function (res) {

        }
    });
});


//关闭审核未通过的窗口
$('#closenopass').click(function(){
    $('#pupUpback2').css({'display':'none'});
});
//关闭审核通过的窗口
$('#closepass').click(function(){
    $('#pupUpback1').css({'display':'none'});
});



//课程状态
function courseCheck(id,courseStatus){
    $.ajax({
        type: "get",
        data:{'id':id,'courseStatus':courseStatus},
        url: "/admin/commentCourse/comcourseState",

        dataType: 'json',
        success: function (res) {
            if(res == 1){
                location.reload();//刷新页面
            }
        }
    })
}
