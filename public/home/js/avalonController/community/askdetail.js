
define([], function () {

    var askdetail = avalon.define({
        $id: 'askdetail',
        //commentshow:false,
        qesId:null,  //问答id
        commentContent:null,//回答内容
        comment:null,       //对回答评论内容
        toUname:null, //默认输入评论头 /@ *** :
        toUnamelength:0,//默认输入评论头长度

        askstuName:'',
        askteaName:'',
        caina:'',

        qesId:null, //问答id
        comId:null, //评论id
        datas: [],  //评论结果集

        isfav:false,    //问答是否收藏
        isthumb:false,  //问答是否点赞
        iscainai:false,   //问答是否采纳

        //获取问答 点赞、收藏状态
        getaskstatus:function(qesId){
            askdetail.qesId = qesId;
            $.ajax({
                type: 'GET',
                url: '/community/getaskstatus/'+qesId,
                success: function(response) {
                    askdetail.isthumb = response.isthumb;
                    askdetail.isfav = response.isfav;
                    askdetail.iscainai = response.iscainai;
                }
            });
        },
        //问答点赞
        qesthumb:function(qesId){
            $.ajax({
                type: 'GET',
                url: '/community/qesthumb/'+qesId,
                success: function(response) {
                    if(response.status){
                        askdetail.getaskstatus(qesId);
                    }
                }
            });
        },
        //问答收藏
        qesfav:function (qesId) {
            $.ajax({
                type: 'GET',
                url: '/community/qesfav/'+qesId,
                success: function(response) {
                    if(response.status){
                        askdetail.getaskstatus(qesId);
                    }
                }
            });
        },
        //问答取消收藏
        qesdefav:function (qesId) {
            $.ajax({
                type: 'GET',
                url: '/community/qesdefav/'+qesId,
                success: function(response) {
                    if(response.status){
                        askdetail.getaskstatus(qesId);
                    }
                }
            });
        },



        //是否采纳
        cainaclick:function(queId,qesId,answerContent,iscaina){
            if(iscaina){
                return false;
            }
            var postdata = {};
            postdata = {queId:queId,answerContent:answerContent,qesId:qesId};
            $.ajax({
                type: 'GET',
                url: '/community/cainaclick',
                //url: '/community/cainaclick/'+queId+'/'+qesId+'/'+answerContent+'/'+answerContent,
                data:postdata,
                success: function(response) {
                    if(response.status){
                        askdetail.getaskstatus(queId);
                        window.location.reload();
                    }
                }
            });
        },


        //评论
        comments:function(qesId){
            var comment = $('#comment').val();  //获取文本框内容
            var postdata = {};                                //提交的对象
            if (!comment || !$.trim(comment)) {alert('请输入发布内容！');return false};
            postdata = {qesId:qesId,parentId:askdetail.comId,tousername:askdetail.toUname,answerContent:comment};
            //alert(postdata)
            $.ajax({
                type: 'POST',
                url: '/community/qesreplys',
                data:postdata,
                success: function(response) {
                    if(response.status){
                        $('#comment').val('');
                        askdetail.getComment(askdetail.qesId);
                        // alert('发布成功');
                    }else{
                        alert('发布失败，请重新尝试');
                    }
                }
            });
        },


        //content:'',
        // 回复
        postComment:function(qesId){

            //var ue = UE.getEditor('commentContent',{
            //    initialFrameHeight:280,
            //    //textarea:'content',
            //});
            //var content = ue.getContent();
            var commentContent = $('#commentContent').val();  //获取文本框内容
            var postdata = {};                                //提交的对象
            if (!commentContent || !$.trim(commentContent)) {alert('请输入发布内容！');return false};

            postdata = {qesId:qesId,answerContent:commentContent,stuName:askdetail.askstuName,teaName:askdetail.askteaName};

            $.ajax({
                type: 'POST',
                url: '/community/qesreply',
                data:postdata,
                success: function(response) {
                    if(response.status){
                        $('#commentContent').val('');
                        askdetail.getComment(askdetail.qesId);
                        // alert('发布成功');
                    }else{
                        alert('发布失败，请重新尝试');
                    }
                }
            });
        },

        // 获取评论
        getComment:function(qesId){
            askdetail.qesId = qesId;
            $.ajax({
                type: 'GET',
                url: '/community/getqescomment/'+qesId,
                success: function(response) {
                    if(response.status){
                        askdetail.datas = response.data;
                        //console.log(response.data);
                    }else{
                        askdetail.datas = [];
                    }
                }
            });
        },
        // 回复评论
        //anscomment:function(cmId,toUname){
        //    // alert(cmId);
        //    askdetail.toUname = toUname;                             //获取回复对象名称
        //    askdetail.comId   = cmId;                                //获取回复对应评论id
        //    $('#commentContent').val('@'+toUname+':');               //添加回复头
        //    askdetail.toUnamelength = ('@'+toUname+':').length;      //获取回复对象长度
        //},
        anscomment:function(cmId,toUname){
            //alert(cmId);
            askdetail.toUname = toUname;                             //获取回复对象名称
            askdetail.comId   = cmId;                                //获取回复对应评论id
            //askdetail.commentshow = true;
            $("div[title='ansarea"+cmId+"']").show()
            $("div[title='comment"+cmId+"']").hide()
            $("div[title='comments"+cmId+"']").show()
        },
        //回复--收起回复
        anscomments:function(cmId,toUname){
            //alert(cmId);
            askdetail.toUname = toUname;                             //获取回复对象名称
            askdetail.comId   = cmId;                                //获取回复对应评论id
            $("div[title='ansarea"+cmId+"']").hide()
            $("div[title='comment"+cmId+"']").show()
            $("div[title='comments"+cmId+"']").hide()

        },




        noteShow:function(id){
            askdetail.id = id;
            $("div[title='note"+id+"']").html('')
        },

        // 点赞
        favcomment:function(cmId){
            // alert(cmId);
            $.ajax({
                type: 'GET',
                url: '/community/favqescomment/'+cmId,
                success: function(response) {
                    if(response.status){
                        askdetail.getComment(askdetail.qesId);
                        // alert('已赞');
                    }else{
                        alert('操作失败，请重新尝试');
                    }
                }
            });

        },
        // 删除评论
        delcomment:function(cmId){
            // alert(cmId);
            if(confirm('确认删除？')){
                $.ajax({
                    type: 'GET',
                    url: '/community/delqescomment/'+cmId,
                    success: function(response) {
                        if(response.status){
                            askdetail.getComment(askdetail.qesId);
                            alert('已删除');
                        }else{
                            alert('操作失败，请重新尝试');
                        }
                    }
                });
            }

        }
    });

    return askdetail;
    // askdetail.getdata(1);

});


