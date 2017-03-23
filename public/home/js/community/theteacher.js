$(document).ready(function(){

    // 超出部分隐藏
    $('.content_introduce div').each(function(){
        var maxwidth=100;
        if($(this).text().length>maxwidth){
            $($(this)).text($($(this)).text().substring(0,maxwidth));
            $($(this)).html($($(this)).html()+'…');
        }
    });



});