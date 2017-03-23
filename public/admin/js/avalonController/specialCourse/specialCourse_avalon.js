define([], function() {
    var detail = avalon.define({
        $id: "specialcommentdetail",
        info: [],
        popup:false,
        commentdetailpop:function(id){
            detail.popup = true;
            $.ajax({
                type: "get",
                url: "/admin/specialCourse/detailSpecialCourse/" + id,

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
        },

        //添加课程时课程类型为打折时出现折扣
        watchSelect:false,
        price:'',
        discountPrice:'',//折扣后价钱
        typeSelect:function(id){
            detail.discountPrice = Math.ceil(detail.price * 0.9);
            if(id == 1){
                detail.watchSelect = true;
            }else{
                detail.watchSelect = false;
            }
        },

        //选择折扣
        discountSelect:function(discountid){
            detail.discountPrice = Math.ceil(detail.price * (discountid/10));
        },


        //编辑课程价格时
        aa:false,
        //editdiscountPrice:'',//折扣后价钱
        //edittypeSelect:function(editid,coursePrice){
        //    alert(editid);
        //    //detail.editdiscountPrice = Math.round(coursePrice * 0.9);
        //    //detail.editprice = coursePrice;
        //    if(editid == 1){
        //        detail.editwatchSelect = true;
        //    }else{
        //        detail.editwatchSelect = false;
        //    }
        //},
        //
        ////选择折扣
        //editdiscountSelect:function(editdiscountid,coursePrice){
        //    detail.editdiscountPrice = Math.round(coursePrice * (editdiscountid/10));
        //}



    })

    $("input[name='coursePrice']").change(function(){
        var aa = $('#zhekou').val();
        detail.discountPrice =  Math.ceil(detail.price * (aa / 10));
    })


    return detail;

})