
define([], function (){

    var model = avalon.define({

        $id: 'teacherCourseCatalog',
        commentContents:[],//回答学生的提问
        courseId:'',    //课程id
        commentId:'',   //提问id
        currentIndex: 'catalog',
        commentContent: [],//课程问答
        promptContent:[],  //贴士发布
        promptEditContent:'', //修改贴士提交
        currentIndex: 'catalog',
        chapterId:'',
        tipsId:'',
        userId:'',

        tabs: function(index){
            window.location.hash = index;
            model.currentIndex = index;
            if(index == 'catalog'){
                $('.right_top_catalog').addClass('div_back').siblings().removeClass('div_back');$('.catalog_img').addClass('catalog_img_bai');$('.catalog_name').addClass('catalog_name_bai');
                $('.note_img').removeClass('note_img_bai');$('.note_name').removeClass('note_name_bai');
                $('.answer_img').removeClass('answer_img_bai');$('.answer_name').removeClass('answer_name_bai');
            }else if(index == 'note'){$('.right_top_note').addClass('div_back').siblings().removeClass('div_back');$('.note_img').addClass('note_img_bai');$('.note_name').addClass('note_name_bai');
                $('.catalog_img').removeClass('catalog_img_bai');$('.catalog_name').removeClass('catalog_name_bai');
                $('.answer_img').removeClass('answer_img_bai');$('.answer_name').removeClass('answer_name_bai');
            }else if(index == 'answers'){
                $('.right_top_answers').addClass('div_back').siblings().removeClass('div_back');$('.answer_img').addClass('answer_img_bai');$('.answer_name').addClass('answer_name_bai');
                $('.note_img').removeClass('note_img_bai');$('.note_name').removeClass('note_name_bai');
                $('.catalog_img').removeClass('catalog_img_bai');$('.catalog_name').removeClass('catalog_name_bai');
            }
        },


        //formTime
        formTime:function (value) {
            var theTime = parseInt(value);// 秒
            var theTime1 = 0;// 分
            var theTime2 = 0;// 小时
            var stra = '00', strb = '00', strc = '00';
            if(theTime >= 60) {
                theTime1 = parseInt(theTime/60);
                theTime = parseInt(theTime%60);
                if(theTime1 >= 60) {
                    theTime2 = parseInt(theTime1/60);
                    theTime1 = parseInt(theTime1%60);
                }
            }
            stra = parseInt(theTime) < 10 ? '0'+parseInt(theTime) : parseInt(theTime);
            if(theTime1 > 0) strb = parseInt(theTime1) < 10 ? '0'+parseInt(theTime1) : parseInt(theTime1);
            if(theTime2 > 0) strc = parseInt(theTime2) < 10 ? '0'+parseInt(theTime2) : parseInt(theTime2);
            return strc+':'+strb+':'+stra;
        },


        popUpSwitch:function(){
            $('.tips_popup').show();
        },



        //获取课程章节目录接口
        CourseChapter:{
            duidance:[],
            teaching:[],
            guidance:[]
        },
        getCourseChapter:function(courseId){
            model.courseId = courseId;
            $.ajax({
                type: 'get',
                url: '/teacherCourse/getCourseChapter/'+courseId,
                success: function(response) {
                    if(response.status){
                        model.CourseChapter.duidance = response.data.duidance;
                        model.CourseChapter.teaching = response.data.teaching;
                        model.CourseChapter.guidance = response.data.guidance;
                        console.log(model.CourseChapter)
                    }
                }
            });
        },




        //获取共享笔记
        courseShareNote: [],
        getCourseShareNote:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'get',
                url: '/teacherCourse/getCourseShareNote/'+courseId,
                success: function(response) {
                    if(response.status){
                        model.courseShareNote = response.data;
                        //console.log(response.data)
                    }
                }
            });
        },



        teacherId:'',
        //提交贴士内容
        submitNote:function(courseId){
            //获取当前时间
            var thisTime = model.theplayer.getPosition();

            //验证课程对应的老师
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/courseTeacher/'+courseId + '/' + model.chapterId + '/' + thisTime,
                success: function(response) {
                    if(response.status){
                        model.teacherId = response.data;
                        model.isTrue = response.isTrue;
                        if(model.isTrue == true){
                            alert('贴士添加时间不能相同');
                            return false;
                        }
                        if(model.teacherId != model.userId){
                            alert('只有本课程老师可以发布贴士');
                        }else{
                            //chapterId = model.chapterId;
                            //alert(courseId);return false;
                            //if(thisTime < 1){
                            //    alert('请先播放视频在添加贴士');
                            //    return false;
                            //}
                            var postdata = {};
                            if(model.promptContent.length > 50){
                                alert('最多只有输入50个字符或汉字')
                                return false;
                            }
                            if (!model.promptContent || !$.trim(model.promptContent)) {alert('请输入发布内容！');return false};
                            postdata = {courseId:courseId,time:thisTime,chapterId:model.chapterId,tipscontent:model.promptContent};
                            model.promptContent = null;
                            $.ajax({
                                type: 'POST',
                                url: '/teacherCourse/courseSubmitTips',
                                data:postdata,
                                success: function(response) {
                                    if(response.status){
                                        console.log(response.data);
                                        model.courseTips.push(response.data);
                                        model.promptContent = null
                                    }else{
                                        alert('发布失败，请重新尝试');
                                    }
                                }
                            });
                        }
                    }
                }
            });

        },


        //获取贴士数据
        //courseTips:[
        //    //{
        //    //    promptEditContent:'',
        //    //},
        //],
        //getCourseTips:function(courseId){
        //    var chapterId = parseInt(model.chapterId)+1;
        //    //var chapterId = model.chapterId;
        //    $.ajax({
        //        type: 'get',
        //        url: '/teacherCourse/getCourseTips/'+courseId+'/'+chapterId,
        //        success: function(response) {
        //            if(response.status){
        //                console.log(response.data)
        //                model.courseTips = response.data;
        //            }else{
        //                model.courseTips = [];
        //            }
        //        }
        //    });
        //},


        //弹出修改框
        pop_edit:function(tipsId,tipscontent){
                $(this).parent().parent().hide().siblings('.note_areass2').show()
                //$(this).parent().parent().siblings('.note_areass1').children('.edit_delete').children('.edit_edit').hide()
                $('.edit_delete').hide()
                model.promptEditContent = tipscontent
                model.tipsId = tipsId;
        },

        //修改贴士
        modifyTips:function(tipsId,tipscontent,index){
            var postdata = {};
            if (!model.promptEditContent || !$.trim(model.promptEditContent)) {alert('请输入发布内容！');return false};
            postdata = {tipsId:model.tipsId,tipscontent:model.promptEditContent};
            $.ajax({
                type: 'POST',
                url: '/teacherCourse/promptEditContent',
                data:postdata,
                success: function(response) {
                    if(response.status){
                        console.log(response.d);
                        model.courseTips.splice(index,1,response.d);
                        //model.chapterData(parseInt(model.chapterId)-1,model.courseId)
                        //model.getCourseTips(model.courseId);
                        $('.edit_delete').show()
                    }else{
                        alert('发布失败，请重新尝试');
                    }
                }
            });
        },



        //删除贴士
        deleteTips:function(tipsId,index){
            //alert(tipsId); return false
            if(confirm('确认删除？')){
                $.ajax({
                    url : '/teacherCourse/deleteTips/' + tipsId,
                    type : 'get',
                    dataType : 'json',
                    success: function(response){
                        if(response.status){
                            alert('删除贴士成功')
                            model.courseTips.splice(index,1);
                            //model.getCourseTips(model.courseId);
                            //location.reload()
                            //model.chapterData(parseInt(model.chapterId)-1,model.courseId)
                        }else{
                            alert('操作失败，请重新尝试');
                        }
                    },
                })
            }
        },


        //获取我的笔记数据
        courseMyNote: [],
        getCourseMyNote:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'get',
                url: '/teacherCourse/getCourseMyNotes/'+courseId,
                success: function(response) {
                    if(response.status){
                        console.log(response.data)
                        model.courseMyNote = response.data;
                    }else{
                        model.courseMyNote = [];
                    }
                }
            });
        },


        //回答学生的提问
        postReply:function(courseId,commentId){
            $('.hide_show').show()
            model.courseId = courseId;
            model.commentId = commentId;
        },

        //课程问答
        postComments:function(){
            var postdata = {};
            if (!model.commentContents || !$.trim(model.commentContents)) {alert('请输入发布内容！');return false};
            postdata = {courseId:model.courseId,parentId:model.commentId,answer:model.commentContents};
            $.ajax({
                type: 'POST',
                url: '/teacherCourse/courseComment',
                data:postdata,
                success: function(response) {
                    if(response.status){
                        model.getCourseAskData(model.courseId);
                        $('.hide_show').hide()
                    }else{
                        alert('发布失败，请重新尝试');
                    }
                }
            });
        },

        wodewenda:false,
        //获取课程问答
        courseAskData: [],  //评论结果集
        getCourseAskData:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getCourseCommentCatalogAsk/'+courseId,
                success: function(response) {
                    if(response.status){
                        //console.log(response.data)
                        model.wodewenda = false;
                        model.courseAskData = response.data;
                    }else{
                        model.wodewenda = true;
                        model.courseAskData = [];
                    }
                }
            });
        },



        autoCourseVideoData:[],
        getChapterData:function(chapterId){
            //$('.catalog_content_every_name').removeClass('gray')
            //$(this).parent().parent('.catalog_content_every_name').addClass('gray');
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getCourseVideo/'+chapterId,
                success: function(response) {
                    //model.info = data;
                    if(response.status){
                        model.autoCourseVideoData = response.data
                        //console.log(model.autoCourseVideoData)

                    }
                }
            });
        },


        theplayer:{},
        showVideo:true,
        pdfShow:false,
        isOnline:false,
        info:[],
        documentOrVideo:'',
        courseTips:[],
        wodetieshi:false,
        //点击播放视频或资源接口(章节标题)
        courseVideoData:[],
        chapterData:function(chapterId,courseId){
            model.chapterId = parseInt(chapterId) + 1;
            //$('.catalog_content_every_name').removeClass('gray')
            //$(this).parent().parent('.catalog_content_every_name').addClass('gray');
            $('.catalog_content_every_name').parent().removeClass('gray')
            $(this).parent().parent().parent().addClass('gray')
            model.getChapterData(chapterId)
            //获取贴士
            $.ajax({
                type: 'get',
                url: '/teacherCourse/getCourseTips/'+courseId+'/'+ model.chapterId,
                success: function(response) {
                    if(response.status){
                        console.log(response.data)
                        model.wodetieshi = false;
                        model.courseTips = response.data;
                    }else{
                        model.wodetieshi = true;
                        model.courseTips = [];
                    }
                }
            });
            //验证视频或者文档
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/courseDocumentVideo/'+model.chapterId,
                success: function(response) {
                    if(response.status){
                        model.documentOrVideo = response.data
                        console.log(model.documentOrVideo)
                    }
                }
            });

            //文档
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseDocument/'+chapterId + '/' + model.courseId ,
                success: function(response) {
                    if(response.status){
                        model.CourseVideo = response.data
                        console.log(response.data)
                    }
                }
            });

            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getDefaultInfo/'+chapterId,
                success: function(response) {
                    model.info = response.data;
                    var data = response.data;
                    console.log(data);
                    //if(data.courseFormat.match(/(xls|xlsx|doc|docx|pdf|ppt|jpg|jpeg|png|swf)/i)){
                    if(data.courseFormat.match(/(xls|xlsx|doc|docx|pdf|ppt|jpg|jpeg|png|swf)/i)){
                            //下载的pdf浏览
                        if(data.isTranscode == 1){
                            response.data.coursePath = data.coursePath;
                            model.showVideo = false;
                            model.isOnline = false;
                            model.pdfShow = true;
                        }else{
                            //在线浏览
                            if(data.courseFormat.match(/(pdf|doc|docx|xls|xlsx|ppt|pptx)/i)){
                                model.isOnline = true;
                                model.showVideo = false;
                                model.pdfShow = false;
                                var type = '';
                                switch (data.courseFormat){
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
                                model.onlinePlay(data.download,type,800,480);
                            }else{
                                response.data.coursePath = data.coursePath;
                                model.showVideo = false;
                                model.isOnline = false;
                                model.pdfShow = true;

                            }
                        }

                    }else{
                        model.showVideo = true;
                        model.pdfShow = false;
                        model.isOnline = false;
                        var arr = [];
                        if(model.info.courseHighPath){
                            arr.push({label:'超清',file:model.info.courseHighPath,width:'800',height:'450',type:'mp4'})
                        }
                        if(model.info.courseMediumPath){
                            arr.push({label:'高清',file:model.info.courseMediumPath,width:'800',height:'450',type:'mp4'})
                        }
                        if(model.info.courseLowPath){
                            arr.push({label:'标清',file:model.info.courseLowPath,width:'800',height:'450',type:'mp4'})
                        }
                        //console.log(arr);
                        model.theplayer = jwplayer('mediaplayer').setup({
                            flashplayer: 'jwplayer/jwplayer.flash.swf',
                            id: 'playerID',
                            image: model.info.resourcePic,
                            width: '800',
                            height: '450',
                            type: "mp4",
                            levels:arr,
                            events: {
                                onPlay: function() {
                                    $.ajax({
                                        type: 'GET',
                                        url: '/studentCourse/courseOnPlay/'+chapterId + '/' + model.courseId ,
                                        success: function(response) {
                                            if(response.status){
                                                model.CourseVideo = response.data
                                                console.log(response.data)
                                                console.log('开始播放')
                                            }
                                        }
                                    });
                                },

                                onTime: function() {
                                    return jwplayer('mediaplayer').getPosition();
                                }

                            },
                        });


                    }

                }
            });
        },


        //默认播放
        defaultChapter:[],
        getDefaultChapter:function(chapterId,courseId){
            model.chapterId = parseInt(chapterId) + 1;
            $.ajax({
                type: 'get',
                url: '/teacherCourse/getCourseTips/'+courseId+'/'+ model.chapterId,
                success: function(response) {
                    if(response.status){
                        console.log(response.data)
                        model.wodetieshi = false;
                        model.courseTips = response.data;
                    }else{
                        model.wodetieshi = true;
                        model.courseTips = [];
                    }
                }
            });
            //验证视频或者文档
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/courseDocumentVideo/'+model.chapterId,
                success: function(response) {
                    if(response.status){
                        model.documentOrVideo = response.data
                        console.log(model.documentOrVideo)
                    }
                }
            });
            //文档
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseDocument/'+chapterId + '/' + model.courseId ,
                success: function(response) {
                    if(response.status){
                        model.CourseVideo = response.data
                        console.log(response.data)
                    }
                }
            });

            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getDefaultInfo/'+chapterId,
                success: function(response) {
                    model.info = response.data;
                    var data = response.data;
                    console.log(data);
                    if(data.courseFormat.match(/(xls|xlsx|doc|docx|pdf|ppt|jpg|jpeg|png|swf)/i)){
                        //下载的pdf浏览
                        if(data.isTranscode == 1){
                            response.data.coursePath = data.coursePath;
                            model.showVideo = false;
                            model.isOnline = false;
                            model.pdfShow = true;
                        }else{
                            //在线浏览
                            if(data.courseFormat.match(/(pdf|doc|docx|xls|xlsx|ppt|pptx)/i)){
                                model.isOnline = true;
                                model.showVideo = false;
                                model.pdfShow = false;
                                var type = '';
                                switch (data.courseFormat){
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
                                model.onlinePlay(data.download,type,800,480);
                            }else{
                                response.data.coursePath = data.coursePath;
                                model.showVideo = false;
                                model.isOnline = false;
                                model.pdfShow = true;

                            }
                        }

                    }else{
                        model.showVideo = true;
                        model.pdfShow = false;
                        model.isOnline = false;
                        var arr = [];
                        if(model.info.courseHighPath){
                            arr.push({label:'超清',file:model.info.courseHighPath,width:'800',height:'450',type:'mp4'})
                        }
                        if(model.info.courseMediumPath){
                            arr.push({label:'高清',file:model.info.courseMediumPath,width:'800',height:'450',type:'mp4'})
                        }
                        if(model.info.courseLowPath){
                            arr.push({label:'标清',file:model.info.courseLowPath,width:'800',height:'450',type:'mp4'})
                        }
                        //console.log(arr);
                        model.theplayer = jwplayer('mediaplayer').setup({
                            flashplayer: 'jwplayer/jwplayer.flash.swf',
                            id: 'playerID',
                            image: model.info.resourcePic,
                            width: '800',
                            height: '450',
                            type: "mp4",
                            levels:arr,
                            events: {
                                onPlay: function() {
                                    $.ajax({
                                        type: 'GET',
                                        url: '/studentCourse/courseOnPlay/'+chapterId + '/' + model.courseId ,
                                        success: function(response) {
                                            if(response.status){
                                                model.CourseVideo = response.data
                                                console.log(response.data)
                                                console.log('开始播放')
                                            }
                                        }
                                    });
                                },

                                onTime: function() {
                                    return jwplayer('mediaplayer').getPosition();
                                }

                            },
                        });


                    }

                }
            });
        },


        //获取视频数据接口(top选项卡点击)
        CourseVideo:[],
        showCourseVideo:function(chapterId){
            //$(this).parent().removeClass('gray_bac').siblings().addClass('gray_bac')
            $(this).parent().css('backgroundColor','#fff').siblings().css('backgroundColor','#eee');
            model.chapterId = chapterId;
            $.ajax({
                type: 'get',
                url: '/teacherCourse/getCourseTips/'+model.courseId+'/'+ chapterId,
                success: function(response) {
                    if(response.status){
                        console.log(response.data+"----------------")
                        model.wodetieshi = false;
                        model.courseTips = response.data;
                    }else{
                        model.wodetieshi = true;
                        model.courseTips = [];
                    }
                }
            });

            //验证视频或者文档
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/courseDocumentVideo/'+chapterId,
                success: function(response) {
                    if(response.status){
                        model.documentOrVideo = response.data
                        console.log(model.documentOrVideo+"--33-------------")
                    }
                }
            });
            //文档
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseDocument/'+chapterId + '/' + model.courseId ,
                success: function(response) {
                    if(response.status){
                        model.CourseVideo = response.data
                        console.log(response.data)
                    }
                }
            });

            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getShowCourseVideo/'+chapterId,
                success: function(response) {
                    model.info = response.data;

                    var data = response.data;
                    console.log(data+333333333333333);

                    if(data.courseFormat.match(/(xls|xlsx|doc|docx|pdf|ppt|jpg|jpeg|png|swf)/i)){
                        //下载的pdf浏览
                        if(data.isTranscode == 1){
                            response.data.coursePath = data.coursePath;
                            model.showVideo = false;
                            model.isOnline = false;
                            model.pdfShow = true;
                        }else{
                            //在线浏览
                            if(data.courseFormat.match(/(pdf|doc|docx|xls|xlsx|ppt|pptx)/i)){
                                model.isOnline = true;
                                model.showVideo = false;
                                model.pdfShow = false;
                                var type = '';
                                switch (data.courseFormat){
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
                                model.onlinePlay(data.download,type,800,480);
                            }else{
                                response.data.coursePath = data.coursePath;
                                model.showVideo = false;
                                model.isOnline = false;
                                model.pdfShow = true;

                            }
                        }

                    }else{
                        model.showVideo = true;
                        model.pdfShow = false;
                        model.isOnline = false;
                        var arr = [];
                        if(model.info.courseHighPath){
                            arr.push({label:'超清',file:model.info.courseHighPath,width:'800',height:'450',type:'mp4'})
                        }
                        if(model.info.courseMediumPath){
                            arr.push({label:'高清',file:model.info.courseMediumPath,width:'800',height:'450',type:'mp4'})
                        }
                        if(model.info.courseLowPath){
                            arr.push({label:'标清',file:model.info.courseLowPath,width:'800',height:'450',type:'mp4'})
                        }
                        //console.log(arr);
                        model.theplayer = jwplayer('mediaplayer').setup({
                            flashplayer: 'jwplayer/jwplayer.flash.swf',
                            id: 'playerID',
                            image: model.info.resourcePic,
                            width: '800',
                            height: '450',
                            type: "mp4",
                            levels:arr,
                            events: {
                                onPlay: function() {
                                    $.ajax({
                                        type: 'GET',
                                        url: '/studentCourse/courseOnPlay/'+chapterId + '/' + model.courseId ,
                                        success: function(response) {
                                            if(response.status){
                                                model.CourseVideo = response.data
                                                console.log(response.data)
                                                console.log('开始播放')
                                            }
                                        }
                                    });
                                },

                                onTime: function() {
                                    return jwplayer('mediaplayer').getPosition();
                                }

                            },
                        });

                    }

                }
            });
        },




        onlineifram:'',
        //调用在线浏览
        onlinePlay:function(fileUrl,type,width,height){
            //console.log(fileUrl);
            //model.onlineifram = '';
            $.ajax({
                type: "get",
                async: false,
                dataType: 'jsonp',
                jsonpCallback: 'callback',
                url: "http://182.18.34.215:7777/?Method=View&FileUrl=" + fileUrl + "&Type=" + type + "&Width=" + width + "&Height=" + height,
                success: function(data){
                    console.log(data);
                    if(data.code == '200'){
                        console.log('sdff');
                        model.onlineifram = data.data.iframe;
                        //$('#onlinePlay').html(data.data.iframe);

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
        }





    });


    return model;
});
