
define([], function (){

    var model = avalon.define({

        $id: 'studentCourseDetail',
        currentIndex: 'intro',
        commentContent:null,//课程问答
        modifyContent:'',   //修改内容
        noteId:'',


        tabs: function(index){
            window.location.hash = index;
            model.currentIndex = index;
            if(index == 'intro'){
                $('.courseintro').addClass('span_active').siblings().removeClass('span_active')
            }else if(index == 'list'){
                $('.courselist').addClass('span_active').siblings().removeClass('span_active')
            }else if(index == 'test'){
                $('.synchrotest').addClass('span_active').siblings().removeClass('span_active')
            }else if(index == 'note'){
                $('.coursenote').addClass('span_active').siblings().removeClass('span_active')
            }else if(index == 'question'){
                $('.coursequestion').addClass('span_active').siblings().removeClass('span_active')
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


        //类型选择事件(我的笔记)
        getNotes: function(){
            $("#myWholeNotes").on('change',function(){
                var type = null;
                if($(this).val()) {
                    type = parseInt($(this).val());
                    //type = parseInt($(this).val()) - 1;
                } else {
                    type = null;
                }
                $("#myWholeChapter").val(' ');
                $("#myWholeChapter").select2(
                    {
                        minimumResultsForSearch: Infinity,
                        ajax: {
                            url  : '/studentCourse/getWholeChapter/' + model.courseId + '/' + type,
                            type : 'get',
                            dataType : 'json',
                            processResults: function (data) {
                                console.log(data)
                                return {
                                    results: data.data
                                };
                            },
                        },
                    }
                )
            })
        },



        getSelectNote: function(){
            $("#myWholeChapter").on('change',function(){
                var value = $(this).val();
                console.log(value);
                $.ajax({
                    type: 'get',
                    url: '/studentCourse/getCourseDetailMyNotes/'+model.courseId,
                    data:{
                        chapterId:value,
                    },
                    success: function(response) {
                        if(response.status){
                            //model.getCourseMyNote()
                            model.courseMyNote = response.data;
                        }else{
                            model.courseMyNote = [];
                        }
                    }
                });
            })
        },




        //类型选择事件(公共笔记)
        getShareNotes: function(){
            $("#shareWholeNotes").on('change',function(){
                var type = null;
                if($(this).val()) {
                    type = parseInt($(this).val());
                    //type = parseInt($(this).val()) - 1;
                } else {
                    type = null;
                }
                $("#shareWholeChapter").val(' ');
                $("#shareWholeChapter").select2(
                    {
                        minimumResultsForSearch: Infinity,
                        ajax: {
                            url  : '/studentCourse/getWholeChapter/' + model.courseId + '/' + type,
                            type : 'get',
                            dataType : 'json',
                            processResults: function (data) {
                                console.log(data)
                                return {
                                    results: data.data
                                };
                            },
                        },
                    }
                )
            })
        },



        getShareSelectNote: function(){
            $("#shareWholeChapter").on('change',function(){
                var value = $(this).val();
                console.log(value);
                $.ajax({
                    type: 'get',
                    url: '/studentCourse/getCourseDetailShareNote/'+model.courseId,
                    data:{
                        chapterId:value,
                    },
                    success: function(response) {
                        if(response.status){
                            model.courseShareNote = response.data;
                        }else{
                            model.courseShareNote = [];
                        }
                    }
                });
            })
        },



        gongxiangbiji:false,
        //获取共享笔记
        courseShareNote: [],
        getCourseShareNote:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'get',
                url: '/studentCourse/getCourseDetailShareNote/'+courseId,
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


        wodebiji:false,
        //获取我的笔记数据
        courseMyNote: [],
        getCourseMyNote:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'get',
                url: '/studentCourse/getCourseDetailMyNotes/'+courseId,
                success: function(response) {
                    if(response.status){
                        model.wodebiji = false;
                        model.courseMyNote = response.data;
                    }else{
                        model.courseMyNote = [];
                        model.wodebiji = true;
                    }
                }
            });
        },


        //删除笔记
        deleteNote:function(noteId){

            if(confirm('确认删除？')){
                $.ajax({
                    url : '/studentCourse/deleteNote/' + noteId,
                    type : 'get',
                    dataType : 'json',
                    success: function(response){
                        if(response.status){
                            alert('删除笔记成功')
                            model.getCourseMyNote(model.courseId);
                            model.getCourseShareNote(model.courseId);
                            //location.reload()
                        }else{
                            alert('操作失败，请重新尝试');
                        }
                    },
                })
            }
        },


        //修改笔记
        modifyNote:function(noteId,notecontent){
            $('.modify_notes').show()
            model.modifyContent = notecontent
            model.noteId = noteId;
        },

        //修改笔记内容
        postCommentModify:function(){
            var postdata = {};
            if (!model.modifyContent || !$.trim(model.modifyContent)) {alert('请输入发布内容！');return false};
            postdata = {noteId:model.noteId,notecontent:model.modifyContent};
            $.ajax({
                type: 'POST',
                url: '/studentCourse/modifyContent',
                data:postdata,
                success: function(response) {
                    if(response.status){
                        model.getCourseMyNote(model.courseId)
                        model.getCourseShareNote(model.courseId)
                        $('.modify_notes').hide()
                        model.modifyContent = null
                    }else{
                        alert('发布失败，请重新尝试');
                    }
                }
            });
        },

        //私密转公开
        privateNote:function(noteId){
            if(confirm('确定将笔记转为公开？')){
                $.ajax({
                    url : '/studentCourse/privateNote/' + noteId,
                    type : 'get',
                    dataType : 'json',
                    success: function(response){
                        if(response.status){
                            alert('笔记已转为公开')
                            model.getCourseMyNote(model.courseId)
                            model.getCourseShareNote(model.courseId)
                            //location.reload()
                        }else{
                            alert('操作失败，请重新尝试');
                        }
                    },
                })
            }
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

        wodewendas:false,
        //获取课程问答(学生提问)
        courseAskData: [],  //评论结果集
        getCourseAskData:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'GET',
                url: '/studentCourse/getCourseCommentAsk/'+courseId,
                //data:{courseId:courseId},
                success: function(response) {
                    if(response.status){
                        model.wodewendas = false;
                        model.courseAskData = response.data;
                        console.log(response.data)
                    }else{
                        model.wodewendas = true;
                        model.courseAskData = [];
                    }
                }
            });
        },


        //获取课程章节目录接口
        catalogh:false,
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
                    //console.log(response);
                    if(response.status){
                        model.CourseChapter.duidance = response.data.duidance;
                        model.CourseChapter.teaching = response.data.teaching;
                        model.CourseChapter.guidance = response.data.guidance;
                        model.catalogh = true;
                    }else{
                        model.catalogh = false;
                    }
                }
            });
        },





    });



    return model;
});
