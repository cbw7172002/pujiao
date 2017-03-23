$(function() {
    $(".flexslider").flexslider({
        slideshowSpeed: 4000, //展示时间间隔ms
        animationSpeed: 400, //滚动时间ms
        touch: true //是否支持触屏滑动
    });
});

// 名师推荐


// avalon.directive("foo", {
//
//     init: function (binding) {
//
//         $('.teacher_con_con_li').hover(
//             function(){
//
//                 if($(this).find('.stp').is(":animated")){
//                     return false;
//                 }else{
//                     $(this).find('.teacher_con_con_li_info').slideDown("slow");
//                     $(this).find('.teacher_con_con_li_foot').slideDown("slow");
//                 }
//             },
//             function(){
//                 $(this).find('.teacher_con_con_li_foot').slideUp("slow");
//                 $(this).find('.teacher_con_con_li_info').slideUp("slow");
//             }
//         )
//     }
//
// })



// 课程
// $w = $('.img_big').width();
// $h = $('.img_big').height();
// $w2 = $w + 40;
// $h2 = $h + 40;
// $('.img_big').hover(function () {
//     $(this).stop().animate({height: $h2, width: $w2, left: "-20px", top: "-20px"}, 1);
// }, function () {
//     $(this).stop().animate({height: $h, width: $w, left: "0px", top: "0px"}, 1);
// });


avalon.directive("lessonfoo", {

    init: function (binding) {

        // var elem = binding.element
        //
        // var remove = avalon(elem).bind("mouseover", function () {
        //     $w = $('.img_big').width();
        //     $h = $('.img_big').height();
        //     $w2 = $w + 40;
        //     $h2 = $h + 40;
        //     $('.img_big').hover(function () {
        //         $(this).stop().animate({height: $h2, width: $w2, left: "-20px", top: "-20px"}, 1);
        //     }, function () {
        //         $(this).stop().animate({height: $h, width: $w, left: "0px", top: "0px"}, 1);
        //     });
        // })
        // binding.roolback = function () {
        //
        //     avalon(elem).unbind("mouseover", remove)
        //
        // }

        $w = $('.img_big').width();
        $h = $('.img_big').height();
        $w2 = $w + 40;
        $h2 = $h + 40;
        $('.img_big').hover(function () {
            $(this).stop().animate({height: $h2, width: $w2, left: "-20px", top: "-20px"}, 'fast');
        }, function () {
            $(this).stop().animate({height: $h, width: $w, left: "0px", top: "0px"}, 'fast');
        });
    }

})