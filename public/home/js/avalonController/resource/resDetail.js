define([], function () {
    
    var vm = avalon.define({
        $id: 'resDetailcontroller',
        info: '',
        introduce:true,
        comment:false,
        tabs:function(type){
            if(type == 'introduce'){
                vm.introduce = true;
                vm.comment = false;
                $(this).addClass('sel').siblings().removeClass('sel');
            }
            if(type == 'comment'){
                vm.introduce = false;
                vm.comment = true;
                $(this).addClass('sel').siblings().removeClass('sel');
            }
        },
        showVideo:true,
        isOnline:false,
        resdescribe:false,
        showWholeBtn:false,
        getDetail:function(id){
            $.ajax({
                type: "get",
                url: "/resource/getDetail/" + id,
                success: function(data){
                    console.log(data);
                    vm.info = data;
                    if(!vm.info.resourceIntro){
                        vm.resdescribe = true;
                    }
                    if(data.resourceFormat.match(/(xls|xlsx|doc|docx|pdf|ppt|jpg|jpeg|png|swf)/i)){
                        vm.showWholeBtn = true;
                        console.log('gggggg');
                        //如果需要转码直接读取数据
                        if(data.isTranscode == 1){
                            data.resourcePath = data.resourcePath;
                            vm.showVideo = false;
                            vm.isOnline = false;
                        }else{
                            //不需要转码的文档调用在线浏览
                            if(data.resourceFormat.match(/(pdf|doc|docx|xls|xlsx|ppt|pptx)/i)){
                                vm.isOnline = true;
                                var type = '';
                                switch (data.resourceFormat){
                                    case 'pdf':
                                        type = 0;
                                        break;
                                    case 'doc':
                                        type = 1;
                                        break;
                                    case 'docx':
                                        type = 2;
                                        break;
                                    case 'xls':
                                        type = 3;
                                        break;
                                    case 'xlsx':
                                        type = '4';
                                        break;
                                    case 'ppt':
                                        type = '5';
                                        break;
                                    case 'pptx':
                                        type = 6;
                                        break;

                                }
                                vm.onlinePlay(data.download,type,800,480);
                            }else{
                                console.log(data.resourcePath);
                                vm.info.resourcePath = data.resourcePath;
                                vm.showVideo = false;
                                vm.isOnline = false;
                            }
                        }
                    }else{
                        vm.showVideo = true;

                        var arr = [];
                        if(vm.info.courseHighPath){
                            arr.push({label:'超清',file:vm.info.courseHighPath,width:'800',height:'480',type:'mp4'})
                        }
                        if(vm.info.courseMediumPath){
                            arr.push({label:'高清',file:vm.info.courseMediumPath,width:'800',height:'480',type:'mp4'})
                        }
                        if(vm.info.courseLowPath){
                            arr.push({label:'标清',file:vm.info.courseLowPath,width:'800',height:'480',type:'mp4'})
                        }
                        //console.log(arr);
                        jwplayer('myplayer').setup({
                            flashplayer: '/home/jplayer/jwplayer.flash.swf',
                            id: 'playerID',
                            image: vm.info.resourcePic,
                            width: '800',
                            height: '480',
                            type: "mp4",
                            levels:arr
                        });
                    }

                },
                error:function(XMLHttpRequest, textStatus, errorThrown){

                }
            });


        },

        //调用在线浏览
        onlinePlay:function(fileUrl,type,width,height){
            //console.log(fileUrl);
            $.ajax({
                type: "get",
                async: false,
                dataType: 'jsonp',
                jsonpCallback: 'callback',
                url: "http://182.18.34.215:7777/?Method=View&FileUrl=" + fileUrl + "&Type=" + type + "&Width=" + width + "&Height=" + height,
                success: function(data){
                    console.log(data);
                    if(data.code == '200'){
                        $('#onlinePlay').html(data.data.iframe);

                    }else{
                        alert('操作失败，请重新尝试！a');
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown){
                    alert('操作失败，请重新尝试！b');
                    //console.log(XMLHttpRequest);
                    //console.log(textStatus);
                    //console.log(errorThrown);
                }
            });
        },

        //下载资源
        getDown:function(detailId){
            if(!confirm('确定下载？')){
                return false;
            }
            $.ajax({
                type: "get",
                url: "/resource/getDown/" + detailId,
                success: function(data){
                    if(data.code == '200'){
                        window.location.href=data.data;
                        ++ vm.info.resourceDownload;
                    }else{
                        alert('操作失败，请重新尝试！');
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown){
                    alert('操作失败，请重新尝试！');
                }
            });

        },
        //添加取消收藏
        addCollection:function(resId){
            $.ajax({
                type: "get",
                url: "/resource/addCollection/" + resId,
                success: function(data){
                    if(data.code == 1){
                        alert(data.msg);
                        if(data.msg == '成功收藏'){
                            vm.info.isCollection = 1;
                            ++ vm.info.resourceFav;
                        }else if(data.msg == '取消收藏'){
                            vm.info.isCollection = 0;
                            -- vm.info.resourceFav;
                        }

                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown){
                    alert('操作失败，请重新尝试！');
                }
            });
        },
        //获取评论接口
        commentinfo:[],
        commentMsg:false,
        getCommentInfo:function(resId){
            $.ajax({
                type: "get",
                url: "/resource/getCommentInfo/" + resId,
                success: function(data){
                    console.log(data);
                    if(data == ''){
                        vm.commentMsg = true;
                        vm.commentinfo = [];
                    }else{
                        vm.commentinfo = data;
                        vm.commentMsg = false;
                    }



                },
                error:function(XMLHttpRequest, textStatus, errorThrown){

                }
            });
        },
        // 发布评论开关
        descriptionSwitch: function (model, value) {
            vm[model] = value;
        },
        tousername: '', parentId: '', commentContentLength: '', commentContent: '',
        // 回复评论
        replyComment: function (tousername, id) {
            if (tousername) {
                vm.tousername = '';
                vm.parentId = '';
                vm.commentContent = '';
                vm.commentContentLength = '';
            }
            vm.tousername = tousername;
            vm.parentId = id;
            vm.commentContent = '@' + tousername + ':';
            vm.commentContentLength = vm.commentContent.length;
        },
        //发布评论
        isclick:false,
        replyWarning:false,
        publishComment:function(id, content){
            if(!content) return false;
            if(vm.isclick) return false ;
            vm.isclick = true;
            var data = {resourceId: id, commentContent: content};
            if(content == '' ||content.match(/\s*/) == null || content.length <= vm.commentContentLength){
                vm.replyWarning = true;
                return false;
            }

            //回复的内容
            if (vm.tousername && vm.parentId) {
                data.tousername = vm.tousername;
                data.parentId = vm.parentId;

                data.commentContent = data.commentContent.split(/@*:/);
                data.commentContent.shift();
                data.commentContent = data.commentContent.join('');
            }
            console.log(data);
            $.ajax({
                type: "post",
                data:data,
                url: "/resource/publishComment",
                success: function(data){
                    vm.isclick = false;
                    vm.commentContent = '';
                    vm.getCommentInfo(id);

                },
                error:function(XMLHttpRequest, textStatus, errorThrown){

                }
            });

        },
        //删除评论
        popUp:false,
        comId:null,
        resId:null,
        deleteComment:function(id,resId){
            vm.comId = id;
            vm.resId = resId;
            vm.popUp = 'deleteComment';
        },
        //弹窗处理
        popUpSwitch:function(value,id){
            console.log('bbbbb');
            if(id == 'delComment'){
                $.ajax({
                    type: "get",
                    url: "/resource/deleteComment/" + vm.comId,
                    success: function(data){
                        console.log(data);
                        console.log('----');
                        if(data == 1){
                            console.log('----+++');
                            vm.getCommentInfo(vm.resId);
                        }
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown){

                    }
                });
            }
            vm.popUp = value;
        },
        //点赞
        addLike:function(el){
            $.ajax({
                type: "get",
                url: "/resource/addLike/" + el.id,
                success: function(data){
                    if(data){
                        el.isLike = true;
                        el.isLike && ++ el.likeTotal;
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown){

                }
            });
        },
        //获取相关资源数据
        relationinfo:[],
        relationmsg:false,
        getRealtion:function(resId){
            //console.log(resId);return false;
            $.ajax({
                type: "get",
                url: "/resource/getRealtion/" + resId,
                success: function(data){
                    console.log(data);
                    if(data == ''){
                        vm.relationmsg = true;
                    }else{
                        vm.relationinfo = data;
                        vm.relationmsg = false;
                    }

                },
                error:function(XMLHttpRequest, textStatus, errorThrown){

                }
            });
        }


    });


    return vm;
    
});