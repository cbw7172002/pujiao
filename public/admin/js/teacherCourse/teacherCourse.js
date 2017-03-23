//审核状态
function selectCheck(id,checkid,userId,username,teachername,studentPhone,orderSn){
    var id = id;
    var stateid = checkid;

    if(stateid == 0){ //审核未通过
        $('#pupUpback2').css({'display':'block'});
        $('.actionId').val(id);
        $('.state').val(stateid);
        $('.orderSn').val(orderSn);
        $('.username').val(teachername);
        $('.toUsername').val(username);
    }
    if(stateid == 1){ //审核中
        location.reload();//刷新页面
    }
    if(stateid == 2){ //审核通过
        $('#pupUpback1').css({'display':'block'});
        $('.redactionId').val(id);
        $('.redstate').val(stateid);
        $('.redfromUsername').val(teachername);
        $('.redusername').val(username);
        $('.reduserId').val(userId);

        $('.redstudentPhone').val(studentPhone);
        $('.redorderSn').val(orderSn);
    }

    //$.ajax({
    //    type: "get",
    //    data:{'id':id,'state':stateid,'orderSn':orderSn,'username':username,'userId':userId,'teachername':teachername},
    //    url: "/admin/commentCourse/teacherState",
    //
    //    dataType: 'json',
    //    success: function (res) {
    //        if(res.state == 0){
    //            $('#pupUpback2').css({'display':'block'});
    //            $('.actionId').val(id);
    //            $('.username').val(teachername);
    //            $('.toUsername').val(username);
    //        }
    //        if(res.state == 1){
    //            location.reload();//刷新页面
    //        }
    //        if(res.state == 2){
    //            $('#pupUpback1').css({'display':'block'});
    //            $('.redactionId').val(id);
    //            $('.redfromUsername').val(teachername);
    //            $('.redusername').val(username);
    //            $('.reduserId').val(userId);
    //
    //            $('.redstudentPhone').val(studentPhone);
    //            $('.redorderSn').val(orderSn);
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
    var userId = $('.reduserId').val();
    var username = $('.redusername').val();
    var toUsername = $('.redtoUsername').val();
    var token = $('._token').val();

    var phone = $('.redstudentPhone').val();
    var orderSn = $('.redorderSn').val();

    //修改审核状态为通过
    var state = $('.redstate').val();
    $.ajax({
        type: "get",
        data:{'id':actionId,'state':state,'orderSn':orderSn},
        url: "/admin/commentCourse/teacherState",

        dataType: 'json',
        success: function (res) {

        }
    });

    //发送消息
    $.ajax({
        type: "post",
        data:{actionId:actionId,fromUsername:fromUsername,userId:userId,username:username,toUsername:toUsername,phone:phone,orderSn:orderSn,_token:token},
        url: "/admin/commentCourse/sendStudentMessage",
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
        url: "/admin/commentCourse/teacherState",
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
            //location.reload();
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
        url: "/admin/commentCourse/teaccourseState",

        dataType: 'json',
        success: function (res) {
            if(res == 1){
                location.reload();//刷新页面
            }
        }
    })
}
