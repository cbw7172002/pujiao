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
$(".courseintro").click(function () {
    $(".content_right_courseintro_tabs").show().siblings().hide();
    $('.content_right_courseintro').show().siblings().hide();
    $(".courseintro_top").show();
    $('.courseintro').addClass('span_active').siblings().removeClass('span_active')
})

$(".courselist").click(function () {
    $(".content_right_courselist_tabs").show().siblings().hide();
    $('.content_right_courseintro').show().siblings().hide();
    $(".courseintro_top").show();
    $('.courselist').addClass('span_active').siblings().removeClass('span_active')
})

$(".synchrotest").click(function () {
    $(".content_right_synchrotest_tabs").show().siblings().hide();
    $('.content_right_courseintro').show().siblings().hide();
    $(".courseintro_top").show();
    $('.synchrotest').addClass('span_active').siblings().removeClass('span_active')
})

$(".coursenote").click(function () {
    $(".content_right_coursenote_tabs").show().siblings().hide();
    $('.content_right_courseintro').show().siblings().hide();
    $(".courseintro_top").show();
    $('.coursenote').addClass('span_active').siblings().removeClass('span_active')
})

$(".coursequestion").click(function () {
    $(".content_right_coursequestion_tabs").show().siblings().hide();
    $('.content_right_courseintro').show().siblings().hide();
    $(".courseintro_top").show();
    $('.coursequestion').addClass('span_active').siblings().removeClass('span_active')
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

$('.course_note_my').click(function () {
    $('.course_note_my').addClass('blue')
    $('.course_note_share').removeClass('blue')
    $('.course_note_border').show()
    $('.course_note_borders').hide()

})

$('.course_note_share').click(function () {
    $('.course_note_share').addClass('blue')
    $('.course_note_my').removeClass('blue')
    $('.course_note_border').hide()
    $('.course_note_borders').show()
})



//是否收藏
$('.courseintro_top_detail_img').click(function(){
    var courseId = $('.courseintro_top_detail_img').attr("name")

    $.ajax({
        url : '/teacherCourse/collectionCourse/'+courseId,
        type : 'get',
        dataType : 'json',
        success: function(response){
            if(response.status){
                $('.courseintro_top_detail_img').hide()
                $('.courseintro_top_detail_img_del').show()
            }
        },
    })
})

$('.courseintro_top_detail_img_del').click(function(){
    var courseId = $('.courseintro_top_detail_img_del').attr("name")

    $.ajax({
        url : '/teacherCourse/collectionCourseDel/'+courseId,
        type : 'get',
        dataType : 'json',
        success: function(response){
            if(response.status){
                $('.courseintro_top_detail_img_del').hide()
                $('.courseintro_top_detail_img').show()
            }
        },
    })

})