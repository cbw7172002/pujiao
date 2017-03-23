$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
//时间搜索筛选
//$('.searchtype').change(function(){
//    if($('.searchtype option:selected').text() == '时间筛选'){
//        $('#search1').css({'display':'none'});
//        $('#search2').css({'display':'block'});
//    }else{
//        $('#search1').css({'display':'block'});
//        $('#search2').css({'display':'none'});
//    }
//})


$('.searchtype').change(function(){
    if($('.searchtype option:selected').text() == '全部'){
        $("input[name='beginTime']").val('');
        $("input[name='endTime']").val('');
    }
})
