define([], function() {
    var detail = avalon.define({
        $id: "teachercommentdetail",
        info: [],
        popup:false,
        commentdetailpop:function(id){
            detail.popup = true;
            $.ajax({
                type: "get",
                url: "/admin/commentCourse/detailTeacherCommentCourse/" + id,

                dataType: 'json',
                success: function (result) {
                    if(result.code){
                        detail.info = result.data;
                    }
                }
            });
        },
        deldetail:function(){
            detail.popup = false;
        }
    })

    return detail;
})