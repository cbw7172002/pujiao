define([], function() {
    var detail = avalon.define({
        $id: "commentdetail",
        popup:false,
        content:'',
        orderid:'',
        commentdetailpop:function(orderid){
            detail.popup = true;
            detail.orderid = orderid;
        },
        submit:function(){
            if(!detail.content){
                $('#errortext').css('border','1px solid red');
                return false;
            }
            $('#errortext').keyup(function(){
                $(this).css('border','1px solid #ccc');
            });
            $.ajax({
                type: "post",
                data:{orderid:detail.orderid,content:detail.content},
                url: "/admin/order/remark",
                dataType: 'json',
                success: function (result) {
                    if(result.state == 1){
                        detail.popup = false;
                        detail.content = '';
                    }
                }
            });
        },

        deldetail:function(){
            detail.popup = false;
            $('#errortext').css('border','1px solid #ccc');
        }
    })

    return detail;
})