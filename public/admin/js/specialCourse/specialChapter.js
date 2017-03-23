

//课程状态
function courseCheck(id,courseStatus){
    $.ajax({
        type: "get",
        data:{'id':id,'status':courseStatus},
        url: "/admin/specialCourse/specialChapterState",

        dataType: 'json',
        success: function (res) {
            if(res == 1){
                location.reload();//刷新页面
            }
        }
    })
}

//意见反馈状态
function feedbackState(id,feedbackStatus){
    $.ajax({
        type: "get",
        data:{'id':id,'status':feedbackStatus},
        url: "/admin/specialCourse/specialFeedbackState",

        dataType: 'json',
        success: function (res) {
            if(res == 1){
                location.reload();//刷新页面
            }
        }
    })
}

//意见反馈状态
function coursedataCheck(id,feedbackStatus){
    $.ajax({
        type: "get",
        data:{'id':id,'status':feedbackStatus},
        url: "/admin/specialCourse/courseDataState",

        dataType: 'json',
        success: function (res) {
            if(res == 1){
                location.reload();//刷新页面
            }
        }
    })
}