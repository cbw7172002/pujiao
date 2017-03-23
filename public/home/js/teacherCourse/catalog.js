$('.right_top_catalog').click(function() {
    $('.right_top_catalog').addClass('div_back').siblings().removeClass('div_back');
    $('.catalog_img').addClass('catalog_img_bai');
    $('.catalog_name').addClass('catalog_name_bai');

    $('.note_img').removeClass('note_img_bai');
    $('.note_name').removeClass('note_name_bai');

    $('.answer_img').removeClass('answer_img_bai');
    $('.answer_name').removeClass('answer_name_bai');

    $('.catalog_content').show();
    $('.note_content').hide();
    $('.answer_content').hide();


});

$('.right_top_note').click(function() {
    $('.right_top_note').addClass('div_back').siblings().removeClass('div_back');
    $('.note_img').addClass('note_img_bai');
    $('.note_name').addClass('note_name_bai');

    $('.catalog_img').removeClass('catalog_img_bai');
    $('.catalog_name').removeClass('catalog_name_bai');

    $('.answer_img').removeClass('answer_img_bai');
    $('.answer_name').removeClass('answer_name_bai');

    $('.note_content').show();
    $('.catalog_content').hide();
    $('.answer_content').hide();

});



$('.right_top_answers').click(function() {
    $('.right_top_answers').addClass('div_back').siblings().removeClass('div_back');
    $('.answer_img').addClass('answer_img_bai');
    $('.answer_name').addClass('answer_name_bai');

    $('.note_img').removeClass('note_img_bai');
    $('.note_name').removeClass('note_name_bai');

    $('.catalog_img').removeClass('catalog_img_bai');
    $('.catalog_name').removeClass('catalog_name_bai');

    $('.answer_content').show();
    $('.note_content').hide();
    $('.catalog_content').hide();


});







$('.my_note').click(function(){
    $(this).addClass('blue')
    $('.share_note').removeClass('blue')
    $('.my_note_content').show()
    $('.share_note_content').hide()
})

$('.share_note').click(function(){
    $(this).addClass('blue')
    $('.my_note').removeClass('blue')
    $('.share_note_content').show()
    $('.my_note_content').hide()
})


//提示提示
$('.tips_popup_button_button').click(function(){
    $('.tips_popup').hide();
})
$('.guanbi').click(function(){
    $('.tips_popup').hide();
})

