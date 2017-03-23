//$(".course_back").hover(
//    function () {
//        var str1 = $(this).attr('class');
//        var str2 = 'intro'
//        console.log(str1.indexOf(str2) > -1); //true
//        if(str1.indexOf(str2) > -1){
//            $(this).addClass("active_hover").prev('span').addClass('span_active');
//        }
//    },
//    function () {
//        $(this).removeClass("active_hover").prev('span').removeClass('span_active');
//    }
//);

$(".course_back").hover(
    function () {
        $(this).addClass("active_hover").prev('span').addClass('span_active');
    },
    function () {
        $(this).removeClass("active_hover").prev('span').removeClass('span_active');
    }
);


$(".mycourse").click(function() {
    $(".content_right_mycourse").show()
    $(".content_right_allcourse").hide()
    $('.mycourse').addClass('span_active').siblings().removeClass('span_active')
})

$(".allcourse").click(function() {
    $(".content_right_allcourse").show()
    $(".content_right_mycourse").hide()
    $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
})



//更多选项
$('.more_option').click(function () {
    $('.version').show();
    $('.more_option').hide();
    $('.stop_option').show();
})

$('.stop_option').click(function () {
    $('.version').hide();
    $('.stop_option').hide();
    $('.more_option').show();
})


//热门 -- 最新
$('.hot_new_hot').click(function(){
    $('.hot_new_hot').addClass('blue')
    $('.hot_new_new').removeClass('blue')
})
$('.hot_new_new').click(function(){
    $('.hot_new_new').addClass('blue')
    $('.hot_new_hot').removeClass('blue')
})


$('.release').click(function(){
    $(this).addClass('hot_new_blue').siblings().removeClass('hot_new_blue')
})

$('#release1').click(function(){
    $('.mycourse_select_content1').show();
    $('.mycourse_select_content2').hide();
    $('.mycourse_select_content3').hide();
    $('.noticeMsg1').show()
    $('.noticeMsg2').hide()
    $('.noticeMsg3').hide()
})

$('#release2').click(function(){
    $('.mycourse_select_content2').show();
    $('.mycourse_select_content1').hide();
    $('.mycourse_select_content3').hide();
    $('.noticeMsg2').show()
    $('.noticeMsg1').hide()
    $('.noticeMsg3').hide()
})

$('#release3').click(function(){
    $('.mycourse_select_content3').show();
    $('.mycourse_select_content1').hide();
    $('.mycourse_select_content2').hide();
    $('.noticeMsg3').show()
    $('.noticeMsg1').hide()
    $('.noticeMsg2').hide()
})


$('.all_grade').click(function() {
    $('.all_grade').addClass('hot_new_blue').siblings().removeClass('hot_new_blue').addClass('hot_new_gray')
})

$('.all_subject').click(function() {
    $('.all_subject').addClass('hot_new_blue').siblings().removeClass('hot_new_blue').addClass('hot_new_gray')
})

$('.all_edition').click(function() {
    $('.all_edition').addClass('hot_new_blue').siblings().removeClass('hot_new_blue').addClass('hot_new_gray')
})

$('.all_book').click(function() {
    $('.all_book').addClass('hot_new_blue').siblings().removeClass('hot_new_blue').addClass('hot_new_gray')
})

if (typeof window.screenX !== "number") {
    document.createElement("progress");
    document.createElement("ie");
}
