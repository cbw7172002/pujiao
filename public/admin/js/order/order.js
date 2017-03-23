

//订单状态
function selectCheck(id,courseStatus,orderSn){
    $.ajax({
        type: "get",
        data:{'id':id,'status':courseStatus,'orderSn':orderSn},
        url: "/admin/order/orderState",

        dataType: 'json',
        success: function (res) {
            if(res == 1){
                location.reload();//刷新页面
            }
        }
    })
}

//退款状态
function refundCheck(id,courseStatus){
    $.ajax({
        type: "get",
        data:{'id':id,'status':courseStatus},
        url: "/admin/order/refundState",

        dataType: 'json',
        success: function (res) {
            if(res == 1){
                location.reload();//刷新页面
            }
        }
    })
}


//多选
$(function() {
    $("#checkAll").click(function() {
        if($(this).prop('checked')){
            $('input[name="id[]"]').prop('checked',true);
        }else{
            $('input[name="id[]"]').prop('checked',false);
        }
    });

    $('input[name="id[]"]').click(function(){
        var chknum = $('input[name="id[]"]').size();
        var chk = 0;
        $('input[name="id[]"]').each(function(){
            if($(this).prop('checked') == true){
                chk++;
            }
        });
        if(chknum == chk){
            $("#checkAll").prop('checked',true);
        }else{
            $("#checkAll").prop('checked',false);
        }
    });


    //批量删除
    $('#alldelete').click(function(){
        var ids = [];
        $(':checkbox:checked').each(function(){
            ids.push($(this).val());
        });
        //console.log(ids);
        if(ids[0] == 'on'){
            ids.splice(0,1);
        }
        console.log(ids);
        $.ajax({
            type: "post",
            data:{id:ids},
            url: "/admin/sensitive/deletes",
            dataType: 'json',
            success: function (data) {
                if(data == 1){
                    alert('批量删除成功');
                    location.reload();
                }
            }
        });
    })

});