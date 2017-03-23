
//avalon.directive('bianse', {
//    update: function (value) {
//        $('.catalog_content_every_name').removeClass('gray')
//        $(this).parent().parent('.catalog_content_every_name').addClass('gray')
//    }
//});



define([], function (){

    var model = avalon.define({

        $id: 'studentCourseCatalog',
        courseId:'',    //课程id
        commentContent: [],//课程问答
        noteContent:[],  //提交笔记内容
        currentIndex: 'catalog',
        chapterId:'',

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


        //获取课程章节目录接口
        //CourseChapter:[],
        CourseChapter:{
            duidance:[],
            teaching:[],
            guidance:[]
        },
        getCourseChapter:function(courseId){
            model.courseId = courseId;
            $.ajax({
                type: 'get',
                url: '/studentCourse/getCourseChapter/'+courseId,
                success: function(response) {
                    if(response.status){
                        //model.CourseChapter = response.data;
                        model.CourseChapter.duidance = response.data.duidance;
                        console.log(model.CourseChapter.duidance)
                        model.CourseChapter.teaching = response.data.teaching;
                        model.CourseChapter.guidance = response.data.guidance;
                        console.log(model.CourseChapter)
                    }
                }
            });
        },

        gongxiangbiji:false,
        //获取共享笔记
        courseShareNote: [],
        getCourseShareNote:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'get',
                url: '/studentCourse/getCourseShareNote/'+courseId,
                success: function(response) {
                    if(response.status){
                        model.gongxiangbiji = false;
                        model.courseShareNote = response.data;
                        //console.log(response.data)
                    }else{
                        model.gongxiangbiji = true;
                    }
                }
            });
        },


        courseType:'',
        public:0,
        //提交笔记内容
        submitNote:function(courseId){
            //var parChapterId = parseInt(model.chapterId) - 1;
            var parChapterId = model.chapterId;
            //alert(parChapterId); return false;
            //var public ='';
            if( $("input[type='checkbox']").is(':checked')==true){
                model.public = '1';    //私密
            }else{
                model.public = '0';    //公开
            }
            //alert(model.public);return false;
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseJudgeType/' +parChapterId,
                success: function(response) {
                    if(response.status){
                       model.courseType = response.courseType;
                        var thisTime = '';
                        //console.log(thisTime);return;
                        var postdata = {};
                        if (!model.noteContent || !$.trim(model.noteContent)) {alert('请输入发布内容！');return false};
                        postdata = {courseId:courseId,parChapterId:parChapterId,time:thisTime,chapterId:model.chapterId,notecontent:model.noteContent,public:model.public};
                        $.ajax({
                            type: 'POST',
                            url: '/studentCourse/courseSubmitNote',
                            data:postdata,
                            success: function(response) {
                                if(response.status){
                                    model.getCourseMyNote(model.courseId);
                                    model.getCourseShareNote(model.courseId);
                                    model.noteContent = null
                                }else{
                                    alert('发布失败，请重新尝试');
                                }
                            }
                        });
                    }else{
                        //获取当前时间
                        var thisTime = model.theplayer.getPosition();
                        var postdata = {};
                        if (!model.noteContent || !$.trim(model.noteContent)) {alert('请输入发布内容！');return false};
                        postdata = {courseId:courseId,parChapterId:parChapterId,time:thisTime,chapterId:model.chapterId,notecontent:model.noteContent,public:model.public};
                        $.ajax({
                            type: 'POST',
                            url: '/studentCourse/courseSubmitNote',
                            data:postdata,
                            success: function(response) {
                                if(response.status){
                                    model.getCourseMyNote(model.courseId);
                                    model.getCourseShareNote(model.courseId);
                                    model.noteContent = null
                                }else{
                                    alert('发布失败，请重新尝试');
                                }
                            }
                        });
                    }
                }
            });

        },

        wodebiji:false,
        //获取我的笔记数据
        courseMyNote: [],
        getCourseMyNote:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'get',
                url: '/studentCourse/getCourseMyNotes/'+courseId,
                success: function(response) {
                    if(response.status){
                        model.wodebiji = false;
                        model.courseMyNote = response.data;
                    }else{
                        model.wodebiji = true;
                        model.courseMyNote = [];
                    }
                }
            });
        },





        //课程问答(评论)
        postComment:function(courseId){
            var postdata = {};
            if (!model.commentContent || !$.trim(model.commentContent)) {alert('请输入发布内容！');return false};
            postdata = {courseId:courseId,content:model.commentContent};
            $.ajax({
                type: 'POST',
                url: '/studentCourse/courseComment',
                data:postdata,
                success: function(response) {
                    if(response.status){
                        model.getCourseAskData(model.courseId);
                        model.commentContent = null
                    }else{
                        alert('发布失败，请重新尝试');
                    }
                }
            });
        },


        wodewenda:false,
        //获取课程问答(学生提问)
        courseAskData: [],  //评论结果集
        getCourseAskData:function(courseId){
            model.courseId = courseId
            //alert(model.courseId)
            $.ajax({
                type: 'GET',
                url: '/studentCourse/getCourseCommentCatalogAsk/'+courseId,
                success: function(response) {
                    if(response.status){
                        model.wodewenda = false;
                        model.courseAskData = response.data;
                        console.log(model.courseAskData)
                    }else{
                        model.wodewenda = true;
                        model.courseAskData = [];
                    }
                }
            });
        },



        autoCourseVideoData:[],
        noticeMsgData:false,
        getChapterData:function(chapterId){
            model.noticeMsgData = true;
            //$('.catalog_content_every_name').removeClass('gray')
            //$(this).parent().parent('.catalog_content_every_name').addClass('gray');
            $.ajax({
                type: 'GET',
                url: '/studentCourse/getCourseVideo/'+chapterId,
                success: function(response) {
                    if(response.status){
                        model.noticeMsgData = false;
                        model.autoCourseVideoData = response.data;
                    }
                }
            });
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




        theplayer:{},
        showVideo:true,
        pdfShow:false,
        isOnline:false,
        info:[],
        times:{},
        showtipcon:false,     //显示贴士框
        tipcon:'',            //贴士提示内容
        tipsecond:'',         //贴士提示时间
        nowtipId:'',          //当前贴士id
        haveAlert:[],         //已经播放的贴士key
        timeisShow:null,
        intervalObj:null,
        countTip:0,
        n:0,
        playBack:false,       //贴士是否全部点击
        isTip:false,          //视频是否有贴士
        //取贴士个数
        getCountTipData:function(chapterId){
            //alert(chapterId)
            $.ajax({
                type: 'GET',
                url: '/studentCourse/getCountTip/'+chapterId,
                success: function(response) {
                    if(response.status){
                        model.countTip = response.data;
                        //console.log(model.countTip);
                        model.isTip = true;
                    } else{
                        model.isTip = false;
                    }
                }
            });
        },

        guanbi:function(){
            clearInterval(model.intervalObj);
            model.showtipcon = false;
            jwplayer('mediaplayer').pause(false);
        },

        goOnStudy:function(){ //继续学习
            //alert(model.chapterId);
            clearInterval(model.intervalObj);
            model.showtipcon = false;
            jwplayer('mediaplayer').pause(false);
            //model.n = model.n+1;
            //if(model.n == model.countTip){
            //    //贴士已全部点击
            //    model.playBack = true;
            //}
            setTimeout(function(){
                model.times[model.timeisShow] = false;
            },1000)
        },
        goOnStudys:function(){
            clearInterval(model.intervalObj);
            model.showtipcon = false;
            jwplayer('mediaplayer').pause(false);
            setTimeout(function(){
                model.times[model.timeisShow] = false;
            },1000)
        },
        countTimes:function(){
            model.intervalObj = setInterval(function(){
                 model.tipsecond--;
                 if(model.tipsecond == 0){
                     clearInterval(model.intervalObj);
                     model.goOnStudys();
                 }
            },1000);
        },

        checkTipKey:function(tipkey){
            if($.inArray(tipkey, model.haveAlert) < 0){
                model.haveAlert.push(tipkey);
            }
        },
        clickAllTip:function(){
            if(model.haveAlert.length == model.countTip){
                return true;
            }else{
                return false;
            }
        },

        //点击播放视频或资源接口(章节标题)
        courseVideoData:[],
        chapterData:function(chapterId){
            //$('.catalog_content_every_name ')
            model.chapterId = parseInt(chapterId) + 1;
            $('.catalog_content_every_name').parent().removeClass('gray')
            $(this).parent().parent().parent().addClass('gray')
            model.getChapterData(chapterId)
            //$('.main_left_top').children("div:first-child").removeClass('gray_bac').addClass('baise');


            model.getCountTipData(model.chapterId);
            model.times = {};

            //获取视频当前时间
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseTipsTime/'+model.chapterId + '/' + model.courseId ,
                success: function(response) {
                    if(response.status){
                        console.log(response.data);
                        model.times = response.data;
                    }else{
                        model.times = {};
                    }
                }
            });
            //文档
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseDocument/'+model.chapterId + '/' + model.courseId ,
                success: function(response) {
                    if(response.status){
                        model.CourseVideo = response.data
                        console.log(response.data)
                    }
                }
            });

            $.ajax({
                type: 'GET',
                url: '/studentCourse/getDefaultInfo/'+model.chapterId,
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
                                //播放完成
                                onPlaylistComplete:function(){
                                    if(model.isTip ==true){                 //存在贴士
                                        if(model.clickAllTip()){          //贴士全部点击
                                            $.ajax({
                                                type: 'GET',
                                                url: '/studentCourse/courseOnPlay/'+model.chapterId + '/' + model.courseId ,
                                                success: function(response) {
                                                    model.CourseVideo = response.data
                                                }
                                            });
                                        }
                                    }else{                                   //不存在贴士,(播放完成即算学习)
                                        $.ajax({
                                            type: 'GET',
                                            url: '/studentCourse/courseOnPlay/'+model.chapterId + '/' + model.courseId ,
                                            success: function(response) {
                                                model.CourseVideo = response.data
                                            }
                                        });
                                    }
                                },
                                //点击开始播放
                                onPlay: function() {},

                                onTime: function() {
                                    var time =  jwplayer('mediaplayer').getPosition();
                                    console.log(time);
                                    time = Math.floor(time)
                                    if((model.times).hasOwnProperty(time) && !model.times[time+'isShow']){
                                    //if((model.times).hasOwnProperty(time)){
                                        model.checkTipKey(time+'isShow');
                                        model.timeisShow = time+'isShow';
                                        model.nowtipId = model.times[time+'id'];
                                        model.times[time+'isShow'] = true;
                                        model.tipcon = model.times[time];
                                        model.tipsecond = 18;
                                        model.showtipcon = true;
                                        //clearTimeout(model.timeoutObj);
                                        model.countTimes();
                                        jwplayer('mediaplayer').pause(true);
                                    }
                                },

                            },
                        });


                    }

                }
            });
        },


        //默认播放
        defaultChapter:[],
        getDefaultChapter:function(chapterId){
            model.chapterId = parseInt(chapterId) + 1;
            model.getCountTipData(model.chapterId);
            $('.main_left_top').children(":first").removeClass('gray_bac').addClass('baise');
            //$('li').first().css('background-color', 'red');

            model.times = {};
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseTipsTime/'+model.chapterId + '/' + model.courseId ,
                success: function(response) {
                    if(response.status){
                        console.log(response.data);
                        model.times = response.data;
                    }else{
                        model.times = {};
                    }
                }
            });
            //文档
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseDocument/'+model.chapterId + '/' + model.courseId ,
                success: function(response) {
                    if(response.status){
                        model.CourseVideo = response.data
                        console.log(response.data)
                    }
                }
            });

            $.ajax({
                type: 'GET',
                url: '/studentCourse/getDefaultInfo/'+model.chapterId,
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
                                //播放完成
                                onPlaylistComplete:function(){
                                    if(model.isTip ==true){                 //存在贴士
                                        if(model.clickAllTip()){          //贴士全部点击
                                            $.ajax({
                                                type: 'GET',
                                                url: '/studentCourse/courseOnPlay/'+model.chapterId + '/' + model.courseId ,
                                                success: function(response) {
                                                    model.CourseVideo = response.data
                                                }
                                            });
                                        }
                                    }else{                                   //不存在贴士,(播放完成即算学习)
                                        $.ajax({
                                            type: 'GET',
                                            url: '/studentCourse/courseOnPlay/'+model.chapterId + '/' + model.courseId ,
                                            success: function(response) {
                                                model.CourseVideo = response.data
                                            }
                                        });
                                    }
                                },
                                //点击开始播放
                                onPlay: function() {},

                                onTime: function() {
                                    var time =  jwplayer('mediaplayer').getPosition();
                                    console.log(time);
                                    time = Math.floor(time)
                                    if((model.times).hasOwnProperty(time) && !model.times[time+'isShow']){
                                        //if((model.times).hasOwnProperty(time)){
                                        model.checkTipKey(time+'isShow');
                                        model.timeisShow = time+'isShow';
                                        model.nowtipId = model.times[time+'id'];
                                        model.times[time+'isShow'] = true;
                                        model.tipcon = model.times[time];
                                        model.tipsecond = 18;
                                        model.showtipcon = true;
                                        //clearTimeout(model.timeoutObj);
                                        model.countTimes();
                                        jwplayer('mediaplayer').pause(true);
                                    }
                                },

                            },
                        });


                    }

                }
            });
        },


        //获取视频数据接口(top选项卡点击)
        CourseVideo:[],
        showCourseVideo:function(chapterId){
            //$(this).parent().removeClass('gray_bac').addClass('baise').siblings().addClass('gray_bac')
            $(this).parent().css('backgroundColor','#fff').siblings().css('backgroundColor','#eee');
            model.chapterId = chapterId;
            model.getCountTipData(model.chapterId);

            model.times = {};
            $.ajax({
                type: 'GET',
                url: '/studentCourse/courseTipsTime/'+chapterId + '/' + model.courseId ,
                success: function(response) {
                    if(response.status){
                        console.log(response.data);
                        model.times = response.data;
                    }else{
                        model.times = {};
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
                url: '/studentCourse/getShowCourseVideo/'+chapterId,
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
                                //播放完成
                                onPlaylistComplete:function(){
                                    if(model.isTip ==true){                 //存在贴士
                                        if(model.clickAllTip()){          //贴士全部点击
                                            $.ajax({
                                                type: 'GET',
                                                url: '/studentCourse/courseOnPlay/'+model.chapterId + '/' + model.courseId ,
                                                success: function(response) {
                                                    model.CourseVideo = response.data
                                                }
                                            });
                                        }
                                    }else{                                   //不存在贴士,(播放完成即算学习)
                                        $.ajax({
                                            type: 'GET',
                                            url: '/studentCourse/courseOnPlay/'+model.chapterId + '/' + model.courseId ,
                                            success: function(response) {
                                                model.CourseVideo = response.data
                                            }
                                        });
                                    }
                                },
                                //点击开始播放
                                onPlay: function() {},

                                onTime: function() {
                                    var time =  jwplayer('mediaplayer').getPosition();
                                    console.log(time);
                                    time = Math.floor(time)
                                    if((model.times).hasOwnProperty(time) && !model.times[time+'isShow']){
                                        //if((model.times).hasOwnProperty(time)){
                                        model.checkTipKey(time+'isShow');
                                        model.timeisShow = time+'isShow';
                                        model.nowtipId = model.times[time+'id'];
                                        model.times[time+'isShow'] = true;
                                        model.tipcon = model.times[time];
                                        model.tipsecond = 18;
                                        model.showtipcon = true;
                                        //clearTimeout(model.timeoutObj);
                                        model.countTimes();
                                        jwplayer('mediaplayer').pause(true);
                                    }
                                },

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

    //model.getCourseMyNote()
    return model;
});


