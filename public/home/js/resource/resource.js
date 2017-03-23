$('.contain_resource_main_right_top_sel_li_more').click(function() {
    if($(this).hasClass('down2')) $(this).removeClass('down2').addClass('up2')
    else $(this).removeClass('up2').addClass('down2')
})
$('.contain_resource_main_right_top_sel_more').click(function () {
    if($(this).hasClass('down3')) {
        $(this).removeClass('down3').addClass('up3').html('收起选项');
        $('.contain_resource_main_right_top_sel_li_book').removeClass('hide')
    } else {
        $(this).removeClass('up3').addClass('down3').html('更多选项');
        $('.contain_resource_main_right_top_sel_li_book').addClass('hide')
    }
})