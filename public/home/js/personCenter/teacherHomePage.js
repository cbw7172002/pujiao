$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //个人中心选项卡切换
    $(".account_common").click(function () {
        $(this).addClass('blue_common');
        $(this).siblings().removeClass('blue_common');
    })


    //我的资源收藏 span切换
    $(".right_resource_top").on('click', 'li', function() {
         $(this).addClass('resource_font_diff').siblings().removeClass('resource_font_diff');
    });

    //url定位选项卡
    var href = location.href.split('#');
    var tem = href[0].split('/');
    var url = '';
    //学员
    if (tem[4] == 'teacherHomePage' || tem[4] == 'studentHomePage') {
        if (href[1]) {
            url = href[1];
        } else {
            url = 'wholeNotice';
        }

    }
    //console.log(url);
    $("div[name='" + url + "']").addClass('blue_common');
    $("div[name='" + url + "']").siblings().removeClass('blue_common');


    //hover
    $(".account_common").hover(
        function () {
            $(this).addClass("active_hover").prev('span').addClass('span_active');
        },
        function () {
            $(this).removeClass("active_hover").prev('span').removeClass('span_active');

        });
});
$('.txtt').focus(function () {
    $(this).next().html('');
});


//我的问答选择项
$('.center_right_top_bot_li').click(function () {
    $(this).addClass('sel');
    $(this).siblings().removeClass('sel');
})

//我的提问 -- 我的回答
$('.my_question').click(function(){
    $('.my_question').addClass('question_blue')
    $('.my_answer').removeClass('question_blue')
})
$('.my_answer').click(function(){
    $('.my_answer').addClass('question_blue')
    $('.my_question').removeClass('question_blue')
})

//試卷收藏 -- 加載更多科目
$(".right_subject_flag").on('click', function() {
    if($(this).hasClass('down')) $(this).removeClass('down').addClass('up')
    else $(this).removeClass('up').addClass('down')
});

document.getElementById("shadow").addEventListener("wheel", function(e) {
    e.preventDefault();
});