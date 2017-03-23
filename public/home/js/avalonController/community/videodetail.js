define([], function () {
    var hotId = location.href.split('/').pop();
    var model = avalon.define({
        $id: 'hotvideo',

        hotvideos :[],
        getData:function(hotId){
            $.ajax({
                url : '/community/getvideodetail/'+ hotId,
                type : 'get',
                dataType : 'json',
                success: function(response){
                    if(response.statuss){
                        model.hotvideos = response.data;
                    }
                    var data = response.data;
                    model.setVideo(data);
                },
            })

        },

        theplayer:{},
        Yes:true,
        setVideo:function(data){
            model.Yes = true;
            model.theplayer = jwplayer("mediaplayer").setup({
                flashplayer: 'jwplayer/jwplayer.flash.swf',
                file:data[0].coursePath,
                height:515,
                width:800,
                aspectratio: '16:9',
                type: "mp4",
                image:data[0].cover,
            });
            //console.log(data);
            //onPause: function () { console.log("暂停!!!"); },
            //onPlay: function () { console.log("开始播放!!!"); },
            model.theplayer.onPause(function(){
                model.Yes = false;
            })

            model.theplayer.onPlay(function(){
                if(model.Yes==true){
                    $.ajax({
                        url: '/community/playAmount/' + hotId,
                        type: 'get',
                        dataType: 'json',
                        success:function(response){
                            console.log(response)
                        }
                    })
                }
            })


        },
    });

    model.getData(hotId);
    //model.setVideo();
    return model;
});