

//课程状态
function courseCheck(id,courseStatus,teachName){
    if(courseStatus == 2){
        $('#pupUpback2').css({'display':'block'});
        console.log(teachName);
        $('.actionId').val(id);
        $('.state').val(courseStatus);
        $('.username').val(teachName);
    }else{
        $.ajax({
            type: "get",
            data:{'id':id,'courseStatus':courseStatus,'username':teachName},
            url: "/admin/specialCourse/specialCourseState",

            dataType: 'json',
            success: function (res) {
                if(res == 1){
                    location.reload();//刷新页面
                }
            }
        })
    }

}


$('#nobtn').click(function(){
    var id = $('.actionId').val();
    var courseStatus = $('.state').val();
    var username = $('.username').val();
    var content = $('#errortext').val();
    var fromUsername = $('.fromUsername').val();
    if(!content){
        alert('请填写未通过原因');
        return false;
    }
    $.ajax({
        type: "get",
        data:{'id':id,'courseStatus':courseStatus,username:username,content:content,fromUsername:fromUsername},
        url: "/admin/specialCourse/specialCourseState",

        dataType: 'json',
        success: function (res) {
            if(res == 1){
                location.reload();//刷新页面
            }
        }
    })
});


//关闭弹窗
$('#closenopass').click(function(){
    $('#pupUpback2').css({'display':'none'});
});