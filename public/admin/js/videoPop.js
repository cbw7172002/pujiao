var player = function(url){
    jwplayer('myplayer').setup({
        flashplayer: 'jwplayer/jwplayer.flash.swf',
        file: url,
        image:'/home/image/index/vdo.png',
        width: '700',
        height: '400',
        type:'mp4'
    });
}
function lookVideo(courseLowPathurl){
    player(courseLowPathurl);
    $('#videodetailpupUpback').css('display','block');
}
$('.deldetail').click(function(){
    player('');
    $('#videodetailpupUpback').css('display','none');
})


