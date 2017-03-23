define([], function() {
    var detail = avalon.define({
        $id: "commentdetail",
        info: [],
        popup:false,
        commentdetailpop:function(id){
            detail.popup = true;
            $.ajax({
                type: "get",
                url: "/admin/commentCourse/detailCommentCourse/" + id,

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