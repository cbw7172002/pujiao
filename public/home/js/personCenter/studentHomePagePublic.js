/**
 * Created by LT on 2017/1/18.
 */
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
    $(".right_resource_top ul li").click(function () {
        $(this).addClass('resource_font_diff').siblings().removeClass('resource_font_diff');
    });

    //url定位选项卡
    var href = location.href.split('#');
    var tem = href[0].split('/');
    var url = '';
    //教师公开和学生公开
    if (href[1]) {
        url = href[1];
    } else {
        if (tem[4] == 'teacherHomePagePublic') url = 'teacherCourse';
        if (tem[4] == 'studentHomePagePublic') url = 'studentCourse';
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

document.getElementById("shadow").addEventListener("wheel", function(e) {
    e.preventDefault();
});



