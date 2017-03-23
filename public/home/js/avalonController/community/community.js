


avalon.directive('newyincang', {
    update: function (value) {
        // 超出部分隐藏(新闻资讯)
        $('.new_content_font div').each(function(){
            var maxwidth=30;
            if($(this).html().length>maxwidth){
                $($(this)).html($($(this)).html().substring(0,maxwidth));
                $($(this)).html($($(this)).html()+'…');
            }
        });
    }
});


avalon.directive('xueyuan', {
    update: function (value) {
        // 超出部分隐藏(新闻资讯)
        $('.newstudent_name div').each(function(){
            var maxwidth=5;
            if($(this).html().length>maxwidth){
                $($(this)).html($($(this)).html().substring(0,maxwidth));
                $($(this)).html($($(this)).html()+'…');
            }
        });

    }
});





//图片放大(热门视频)
avalon.directive('bigimg', {
    update: function (value) {
        var element = this.element;
        var w1 = element.width;
        var h1 = element.height;
        var w2 = w1 + 40;
        var h2 = h1 + 40;
        $('.big_img').hover(function(){
            $(this).stop().animate({height: h2, width: w2, left: "-20px", top: "-20px"}, 'fast');
        },function () {
            $(this).stop().animate({height: h1, width: w1, left: "0px", top: "0px"}, 'fast');
        })
    }
});


//鼠标悬浮显示(最新学员)
avalon.directive('showhideleft',{
    update: function (value){
        $('.newstudent').mouseover(function(){
            if($('.paginationjs-prev').is(":visible")==false) {
                $('.newstudent_right_img').removeClass('hide');
                $('.newstudent_left_img').addClass('hide');
            }else{
                $('.newstudent_left_img').removeClass('hide');
            }

            if($('.paginationjs-next').is(":visible")==false) {
                $('.newstudent_right_img').addClass('hide');
                $('.newstudent_left_img').removeClass('hide');
            }else{
                $('.newstudent_right_img').removeClass('hide');
            }
        })

        $('.newstudent').mouseout(function(){
            $('.newstudent_left_img').addClass('hide');
            $('.newstudent_right_img').addClass('hide');
        })
    }
});




define([],function(){

    var model = avalon.define({
        $id: 'community',
        theteacherlisturl : '/community/newdetail/',
        hotvideourl: '/community/videodetail/',
        //名师主页路由
        teacherhomepage : '/lessonComment/teacher/',
        //学员主页路由
        studenthomepage : '/lessonComment/student/',

        //修改
        type:'hot',
        changePhone:function(value){
            sideBar.condition = value;
        },
        //名师
        theteacherlist: [],
        getteacher:function(){
            $.ajax({
                url : '/community/getteacher',
                type : 'get',
                dataType : 'json',
                success: function(response){
                    if(response.statuss){
                        model.theteacherlist = response.data;
                    }
                },
            })
        },


        //新闻资讯
        newlist :[],
        getnewData:function(){
            $.ajax({
                url : '/community/getlist',
                type : 'get',
                dataType : 'json',
                success: function(response){
                    if(response.statuss){
                        model.newlist = response.data;
                    }
                },
            })
        },


        //最新学员
        //studentlist: [],
        //getstudent:function(){
        //    $('#demo').pagination({
        //        dataSource: function(done) {
        //            $.ajax({
        //                type: 'GET',
        //                url : '/community/getstudent',
        //                dataType : 'json',
        //                success: function(response) {
        //                    if(response.statuss){
        //                        done(response.data);
        //                        //console.log(response.data.length);
        //                        var datalength = response.data.length;
        //                        var str = '<input type="hidden" value="'+datalength+'"  id="fenyeover"  name="fenyeover"  class="fenyeover" >';
        //                        $('#kongbai').html(str);
        //                    }
        //                }
        //            });
        //        },
        //        pageSize: 6,
        //        className:"paginationjs-theme-blue",
        //        showPageNumbers: false,
        //        showNavigator: false,
        //        callback: function(data) {
        //            if(data){
        //                model.studentlist = data;
        //            }
        //
        //        }
        //    })
        //
        //
        //},



        //最热视频
        hotvideo :[],
        Yes: false,
        gethotData:function(){
            model.Yes = false;
            $.ajax({
                url : '/community/gethotvideo',
                type : 'get',
                dataType : 'json',
                success: function(response){
                    if(response.statuss){
                        model.hotvideo = response.data;
                        model.Yes = true;
                    }
                },
            })
        },


        //推荐老师
        teachers:[],
        getteachers:function(){
            $.ajax({
                type: "get",
                url: "/community/getteachers",
                success: function(data){
                    if(data.status){
                        model.teachers = data.data;
                        console.log(data)
                    }
                }
            });
        },

        questions:[],
        iswaitans:false,
        question_msg:false,
        question_pagesize:5,
        subid:0,
        ordtype:'views',
        //问答列表
        getquestions:function(para,iswaitans){
            model.views = para;
            model.iswaitans = iswaitans;
            if(iswaitans){
                model.question_pagesize = 8;
            }
            $('#page_qes').pagination2({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/community/getquestions/'+para+'/'+this.pageNumber+'/'+this.pageSize+'/'+model.subid+'/'+model.iswaitans,
                        success: function(response) {
                            if(model.iswaitans){ //未解答
                                if(response.status){
                                    if(response.havedata){
                                        model.question_msg = false;
                                        model.question_pagesize = 8;
                                    }else{
                                        model.question_msg = true;
                                        model.question_pagesize = 6;
                                    }

                                    var format = [];
                                    format['data'] = response.data;
                                    format['totalNumber'] = response.count;
                                    done(format);

                                    // done(response.data);
                                    // games.Con = true;
                                    // if(response.count / 7 > 1){
                                    //     games.page = true;
                                    // }
                                }else{
                                    model.questions = [];
                                    model.question_msg = true;
                                }
                            }else{
                                if(response.status){
                                    if(response.havedata){
                                        model.question_msg = false;
                                        model.question_pagesize = 5;
                                    }else{
                                        model.question_msg = true;
                                        model.question_pagesize = 4;
                                    }
                                    var format = [];
                                    format['data'] = response.data;
                                    format['totalNumber'] = response.count;
                                    done(format);

                                    // done(response.data);
                                    // games.Con = true;
                                    // if(response.count / 7 > 1){
                                    //     games.page = true;
                                    // }
                                }else{
                                    model.question_msg = true;
                                }
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/community/getquestions/'+para+'/'+pageNumber+'/'+pageSize+'/'+model.subid+'/'+model.iswaitans,
                        success: function(response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: model.question_pagesize,
                pageNumber :1,
                totalNumber :1,
                className:"paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function(data) {
                    if(data){
                        model.questions = data;
                    }

                }
            })


        },

        //科目选择事件
        selsub:function(){
            $('.js-example-basic-single').select2(
                {
                    minimumResultsForSearch: Infinity,
                    ajax: {
                        url: "/community/getSubjects",
                        type:'get',
                        dataType:'json',
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                    },
                }
            ).on('change',function(){
                model.subid = $('.objtype').val();
                model.getquestions(model.ordtype,model.iswaitans);
            })
        }

    });

    return model;
});