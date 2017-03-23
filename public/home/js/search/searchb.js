// 课程样式
// $w = $('.img_big').width(); //280
// $h = $('.img_big').height();//180
// $w2 = $w + 40;
// $h2 = $h + 40;
// $('.img_big').hover(function () {
//     $(this).stop().animate({height: $h2, width: $w2, left: "-20px", top: "-20px"}, 1);
// }, function () {
//     $(this).stop().animate({height: $h, width: $w, left: "0px", top: "0px"}, 1);
// });

$('.img_big').live('mouseover',function(){
    $(this).stop().animate({height: 220, width: 320, left: "-20px", top: "-20px"}, 'fast');
})
$('.img_big').live('mouseout',function(){
    $(this).stop().animate({height: 180, width: 280, left: "0px", top: "0px"}, 'fast');
})
//
$('.selOrd').click(function(){
    $(this).addClass('gl');
    $(this).siblings().removeClass('gl');
})

function getdatab(para,ord){
    $('#demob').pagination({
        dataSource: function(done) {
            $.ajax({
                type: 'GET',
                url: '/index/getCoursebb/'+para+'/'+this.pageNumber+'/'+this.pageSize+'/'+ord,
                success: function(response) {
                    if(response.status){
                        var format = [];
                        format['data'] = response.data;
                        format['totalNumber'] = response.count;
                        done(format);
                        // done(response.data);
                        if(response.count/8 > 1){
                            $('#demob').removeClass('hide');
                        }
                    }else{
                        $('.nofindbb').removeClass('hide');
                    }
                }
            });
        },
        getData: function(pageNumber,pageSize) {
            var self = this;
            $.ajax({
                type: 'GET',
                url: '/index/getCoursebb/'+para+'/'+pageNumber+'/'+pageSize+'/'+ord,
                success: function(response) {
                    self.callback(response.data);
                }
            });
        },
        pageSize: 12,
        pageNumber :1,
        totalNumber :1,
        className:"paginationjs-theme-blue",
        showGoInput: true,
        showGoButton: true,
        callback: function(data) {
            if(data){
                var str = '';
                $.each(data,function(id,info){
                    str += "<a href='/lessonComment/detail/"+info.id+"'><div class=\"recommend_con_con_li\" > " + "<div class=\"contain_lesson_center_data_info_top\">" + " <img src=\""+info.img+"\" width=\"280\" height=\"180\" class=\"img_big\"/> " + "</div> " + "<div class=\"contain_lesson_center_data_info_bot\">" + "<span class=\"top\">"+info.title+"</span> " + "<div class=\"center\"> " + "<span class=\"leftt\">讲师 : "+info.teacher+"</span>" + "<span class=\"right study\">"+info.countpeople+"人学过</span> " + "</div> " + "<span class=\"bot\">￥"+info.price+"</span> " + "</div>" + "</div></a>";
                })
                $('.con_con_con_b').html(str);
            }

        }
    })
}


if(searchVal){
    getdatab(searchVal,1);
}else{
    $('.nofindbb').removeClass('hide');
}

var sel = function(para){
    getdatab(searchVal,para);
}

