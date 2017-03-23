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



//左侧栏选项卡
$(".courseintro").click(function() {
    $(".content_right_courseintro_tabs").show().siblings().hide();
    $(".courseintro_top").show();
})

$(".courselist").click(function() {
    $(".content_right_courselist_tabs").show().siblings().hide();
    $(".courseintro_top").show();
})

$(".synchrotest").click(function() {
    $(".content_right_synchrotest_tabs").show().siblings().hide();
    $(".courseintro_top").show();
})

$(".coursenote").click(function() {
    $(".content_right_coursenote_tabs").show().siblings().hide();
    $(".courseintro_top").show();
})

$(".coursequestion").click(function() {
    $(".content_right_coursequestion_tabs").show().siblings().hide();
    $(".courseintro_top").show();
})


//更多选项
$('.more_option').click(function () {
    $('.gengduo').show();
    $('.mycourse_screen_version').show();
    $('.more_option').hide();
    $('.stop_option').show();
})

$('.stop_option').click(function () {
    $('.gengduo').hide();
    $('.mycourse_screen_version').hide();
    $('.stop_option').hide();
    $('.more_option').show();
})