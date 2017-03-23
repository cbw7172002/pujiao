/**
 * Created by DIY on 2017/3/1.
 */
$(function(){
    $("input[name='multiple']").click(function() {
        if($(this).prop('checked') == true){
            $("input[name='check[]']").prop('checked',true);
        }else{
            $("input[name='check[]']").prop('checked',false);
        }
    })

    $("input[name='check[]']").click(function() {
        if($(this).prop('checked') != true){
            $("input[name='multiple']").prop('checked',false);
        }else{
//                    console.log($(this).attr('value'));
            var total = 0;
            var obj = $("input[name='check[]']");
            var check = true;
            for(var i=0;i<obj.length;i++){
                if(obj[i].checked == true){//判断选中的个数
                    total += 1;
                }
            }
            //获取总数
            var count = $("input[name='count']").val();
            if(count % 15 != 0){
                if(total == (count % 15)){//是否和本页显示的15条相等
                    $("input[name='multiple']").prop('checked',true);
                }else{
                    $("input[name='multiple']").prop('checked',false);
                }
            }else{
                if(total == 15){//是否和本页显示的15条相等
                    $("input[name='multiple']").prop('checked',true);
                }else{
                    $("input[name='multiple']").prop('checked',false);
                }
            }
        }
    })


})
