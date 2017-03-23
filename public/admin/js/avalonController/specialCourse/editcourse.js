define([], function() {
    var detail = avalon.define({
        $id: "specialcommentdetail",

        //添加课程时课程类型为打折时出现折扣
        watchSelect:false,
        discountPrice:'',//折扣后价钱
        coursePrice:'',
        typeSelect:function(id,coursePrice){
            detail.discountPrice = Math.ceil(coursePrice * 0.9);
            if(id == 1){
                detail.watchSelect = true;
            }else{
                detail.watchSelect = false;
            }
        },

        //选择折扣
        discountSelect:function(discountid,coursePrice){
            detail.discountPrice = Math.ceil( detail.coursePrice * (discountid/10));
        },

    })

    $("input[name='coursePrice']").change(function(){
        var aa = $('#zhekou').val();
        var price = $(this).val();
        detail.coursePrice = price;
        //console.log(price);
        detail.discountPrice = Math.ceil( price * (aa / 10));
    })


    return detail;

})