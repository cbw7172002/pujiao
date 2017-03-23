define([], function (){

    var model = avalon.define({

        $id: 'teacherCourseDetail',
        currentIndex: 'intro',
        commentContent:null,//课程问答
        commentContents:null,//回答学生的提问
        courseId:'',    //课程id
        commentId:'',   //提问id
        modifyContent:'',   //修改内容
        noteId:'',
        classList: [],


        condition: {
            quesType: 0,
            selectClass: false,
            classSelected: ['全部'],
            classSelectedText: '全部',
            submitStatus: true,
            column: false
        },
        changeModel: function (action) {
            switch (action) {
                case 'selectClass':
                    model.condition.selectClass = !model.condition.selectClass;
                    break;
            }
        },

        tabs: function(index){
            window.location.hash = index;

            model.currentIndex = index;
            if(index == 'intro'){
                $('.courseintro').addClass('span_active').siblings().removeClass('span_active');
            }else if(index == 'list'){
                $('.courselist').addClass('span_active').siblings().removeClass('span_active');
            }else if(index == 'test'){
                $('.synchrotest').addClass('span_active').siblings().removeClass('span_active');
            }else if(index == 'note'){
                $('.coursenote').addClass('span_active').siblings().removeClass('span_active');
            }else if(index == 'question'){
                $('.coursequestion').addClass('span_active').siblings().removeClass('span_active');
            }else if(index == 'study'){
                $('.coursestudy').addClass('span_active').siblings().removeClass('span_active');
            }
        },


        changeModel: function (action, index) {
            switch (action) {
                case 'selectClass':
                    model.condition.selectClass = !model.condition.selectClass;
                    break;
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
                            url  : '/teacherCourse/getWholeChapter/' + model.courseId + '/' + type,
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
                            url  : '/teacherCourse/getWholeChapter/' + model.courseId + '/' + type,
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
                url: '/teacherCourse/getCourseDetailShareNote/'+courseId,
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
                url: '/teacherCourse/getCourseDetailMyNotes/'+courseId,
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


        //删除笔记
        deleteNote:function(noteId){

            if(confirm('确认删除？')){
                $.ajax({
                    url : '/teacherCourse/deleteNote/' + noteId,
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
                    url : '/teacherCourse/privateNote/' + noteId,
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

        wodewendas:false,
        //获取课程问答
        courseAskData: [],  //评论结果集
        getCourseAskData:function(courseId){
            model.courseId = courseId
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getCourseCommentAsk/'+courseId,
                success: function(response) {
                    if(response.status){
                        model.wodewendas = false;
                        //console.log(response.data)
                        model.courseAskData = response.data;
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


        //获取年级班级接口
        getGradeClass:function(courseId){
            //model.courseId = courseId
            $.ajax({
                type: 'post',
                url: '/teacherCourse/getCourseGradeClass',
                data:{courseId:courseId},
                success: function(response) {
                    if(response.status){
                        model.classList = response.data;
                    }else{
                        model.classList = [];
                    }
                }
            });
        },


        //筛选学习情况
        // getLearningState: function(){
        //     $("#learningState").on('change',function(){
        //         var value = $(this).val();
        //         console.log(value);
        //         return false;
        //         $.ajax({
        //             type: 'get',
        //             url: '/teacherCourse/getCourseStudyNo',
        //             data:{
        //                 chapterId:value,
        //             },
        //             success: function(response) {
        //                 if(response.status){
        //                     model.courseShareNote = response.data;
        //                 }else{
        //                     model.courseShareNote = [];
        //                 }
        //             }
        //         });
        //     })
        // },



        //尚未学习接口
        studyNo:[],
        getStudyNo:function(courseId, condition){
            model.courseId = courseId

            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getCourseStudyNo',
                data:{
                    courseId:model.courseId,
                    condition:condition
                },
                success: function(response) {
                    if(response.status){
                        console.log(response.data)
                        model.studyNo = response.data;
                    }else{
                        model.studyNo = [];
                    }
                }
            });
        },


        //学习完成
        studyFinish:[],
        getStudyFinish:function(courseId,condition){
            model.courseId = courseId
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getCourseStudyFinish',
                data:{
                    courseId:model.courseId,
                    condition:condition
                },
                success: function(response) {
                    if(response.status){
                        console.log(response.data)
                        model.studyFinish = response.data;
                    }else{
                        model.studyFinish = [];
                    }
                }
            });
        },



        //正在学习
        studySchedule:[],
        getStudySchedule:function(courseId,condition){
            model.courseId = courseId
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/getCourseStudySchedule',
                data:{
                    courseId:model.courseId,
                    condition:condition
                },
                success: function(response) {
                    if(response.status){
                        var length = response.data.length;
                        model.studySchedule = response.data;
                        console.log(response.data)
                    }else{
                        model.studySchedule = [];
                    }
                }
            });
        },
    });


    avalon.directive('selectclass', {
        update: function (value) {
            model.condition.classSelected.indexOf(value[0]) >= 0 && $(this.element).find('span[class=nike]').show();
            $(this.element).click(function () {
                var indexOf = model.condition.classSelected.indexOf(value[0]);
                if (indexOf >= 0) {
                    if (value[0] !== '全部' && model.condition.classSelected.length > 1) {
                        model.condition.classSelectedText = model.condition.classSelectedText.replace(value[1], '');
                        model.condition.classSelected.splice(indexOf, 1);
                        $(this).find('span[class=nike]').hide();
                    } else {
                        return;
                    }
                } else {
                    if (value[0] === '全部') {
                        model.condition.classSelectedText = '全部';
                        model.condition.classSelected = ['全部'];
                        $('span[class=nike]').hide();
                        $(this).find('span[class=nike]').show();
                    } else {
                        model.condition.classSelectedText = model.condition.classSelectedText.replace('全部', '');
                        model.condition.classSelected.indexOf('全部') >= 0 && model.condition.classSelected.splice(model.condition.classSelected.indexOf('全部'), 1);
                        model.condition.classSelectedText += value[1];
                        model.condition.classSelected.push(value[0]);
                        $('span[class=nike]').eq(0).hide();
                        $(this).find('span[class=nike]').show();
                    }
                }
                console.log(model.condition.classSelected.$model);
                model.condition.classSelected.$model[0] == '全部' ? model.getStudyNo(model.courseId) :  model.getStudyNo(model.courseId, model.condition.classSelected.$model);
                model.condition.classSelected.$model[0] == '全部' ? model.getStudyFinish(model.courseId) :  model.getStudyFinish(model.courseId, model.condition.classSelected.$model);
                model.condition.classSelected.$model[0] == '全部' ? model.getStudySchedule(model.courseId) :  model.getStudySchedule(model.courseId, model.condition.classSelected.$model);

            });
        }
    });
    return model;
});
