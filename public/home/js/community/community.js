$(document).ready(function(){


    $('.newstudent_video').hover(
        function(){
            $(this).find('.tanchuceng').slideDown("slow");
            $(this).find('.zhezhao').slideUp("slow");
        },
        function(){
            $(this).find('.tanchuceng').slideUp("slow");
            $(this).find('.zhezhao').slideDown("slow");
        }
    )



    // 超出部分隐藏(名师主页)
    $('.content_introduce div').each(function(){
        // var bbb = $(this).text().length;
        // alert(bbb);
        var maxwidth=45;
        if($(this).text().length>maxwidth){
            $($(this)).text($($(this)).text().substring(0,maxwidth));
            $($(this)).html($($(this)).html()+'…');
        }
    });



    // 超出部分隐藏(新闻资讯)
    $('.new_content_font div').each(function(){
        var maxwidth=30;
        if($(this).text().length>maxwidth){
            $($(this)).text($($(this)).text().substring(0,maxwidth));
            $($(this)).html($($(this)).html()+'…');
        }
    });

    $('.title_more .teacher_title span').click(function(){
        $(this).addClass('bottom_line').siblings().removeClass('bottom_line');
    })

});