
avalon.directive('schedule', {
    update: function (value) {

    }
});

define([], function (){

    var model = avalon.define({

        $id: 'studentCourse',
        currentIndex: 'my',
        // 搜索
        condition: '',


        conditionSearch: function (value) {
            if (value == '') {
                alert('请输入查询条件');
            } else {
                model.condition = value
                model.getDataAll();
                model.currentIndex = 'all';
                $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
                //if(model.currentIndex == 'my'){
                //    model.getDataMyWait();
                //}else{
                //    model.getDataAll();
                //}
            }
        },

        tabs: function(index){
            window.location.hash = index;
            model.currentIndex = index;
            if(index == 'my'){
                $('.mycourse').addClass('span_active').siblings().removeClass('span_active')
            }else {
                $('.allcourses').addClass('span_active').siblings().removeClass('span_active')
            }
        },

        //等待学习 -- 正在学习 -- 学习完成
        //select: function (type) {
        //    model.getDataMy(type);
        //},

        //我的课程(等待学习)
        myCourseWait:[],
        courseDetailUrl:'/studentCourse/stuDetail/',                    //详情页链接
        page: false,
        noticeMsg1: false,
        loading1: false,
        getDataMyWait:function(){
            model.page = false,
                model.loading1 = true;
                $('#page_question').pagination({
                    dataSource: function(done) {
                        $.ajax({
                            type: 'GET',
                            url: '/studentCourse/getListMyCourse',
                            data:{
                                pageNumber: this.pageNumber,
                                pageSize: this.pageSize,
                                condition:model.condition,
                            },
                            success: function(response) {
                                model.loading1 = false;
                                if(response.status){
                                    var format = [];
                                    format['data'] = response.data;
                                    format['totalNumber'] = response.count;
                                    done(format);
                                    model.noticeMsg = false;
                                    if(response.count / 10 > 1){
                                        model.page = true;
                                    }
                                }else{
                                    model.myCourseWait = [];
                                    model.noticeMsg1 = true;
                                }
                            }
                        });
                    },

                    getData: function(pageNumber,pageSize) {
                        var self = this;
                        $.ajax({
                            type: 'GET',
                            url: '/studentCourse/getListMyCourse',
                            data:{
                                pageNumber:pageNumber,
                                pageSize:pageSize,
                                condition:model.condition,
                            },
                            success: function(response) {
                                console.log(response)
                                self.callback(response.data);
                            }
                        });
                    },
                    pageSize: 10,
                    pageNumber :1,
                    totalNumber :1,
                    className:"paginationjs-theme-blue",
                    showGoInput: true,
                    showGoButton: true,
                    callback: function(data) {
                        if(data){
                            model.myCourseWait = data;
                            //console.log(data)
                        }

                    }
                })
        },



        //我的课程(正在学习)
        myCourseUnderway:[],
        courseDetailUrl:'/studentCourse/stuDetail/',                    //详情页链接
        page2: false,
        noticeMsg2: false,
        loading2: false,
        getDataMyUnderway:function(){
            model.page2 = false,
                model.loading2 = true;
                $('#page_question2').pagination({
                    dataSource: function(done) {
                        $.ajax({
                            type: 'GET',
                            url: '/studentCourse/getListMyCourseUnderway',
                            data:{
                                pageNumber: this.pageNumber,
                                pageSize: this.pageSize,

                            },
                            success: function(response) {
                                model.loading2 = false;
                                if(response.status){
                                    var format = [];
                                    format['data'] = response.data;
                                    format['totalNumber'] = response.count;
                                    done(format);
                                    model.noticeMsg2 = false;
                                    if(response.count / 10 > 1){
                                        model.page2 = true;
                                    }
                                }else{
                                    model.myCourseUnderway = [];
                                    model.noticeMsg2 = true;
                                }
                            }
                        });
                    },

                    getData: function(pageNumber,pageSize) {
                        var self = this;
                        $.ajax({
                            type: 'GET',
                            url: '/studentCourse/getListMyCourseUnderway',
                            data:{
                                pageNumber:pageNumber,
                                pageSize:pageSize,
                            },
                            success: function(response) {
                                console.log(response)
                                self.callback(response.data);
                            }
                        });
                    },
                    pageSize: 10,
                    pageNumber :1,
                    totalNumber :1,
                    className:"paginationjs-theme-blue",
                    showGoInput: true,
                    showGoButton: true,
                    callback: function(data) {
                        if(data){
                            model.myCourseUnderway = data;
                            //console.log(data)
                        }

                    }
                })
        },



        //我的课程(学习完成)
        myCourseFinish:[],
        courseDetailUrl:'/studentCourse/stuDetail/',                    //详情页链接
        page3: false,
        noticeMsg3: false,
        loading3:false,
        getDataMyFinish:function(){
            model.page3 = false,
                model.loading3 = true,
                $('#page_question3').pagination({
                    dataSource: function(done) {
                        $.ajax({
                            type: 'GET',
                            url: '/studentCourse/getListMyCourseFinish',
                            data:{
                                pageNumber: this.pageNumber,
                                pageSize: this.pageSize,
                            },
                            success: function(response) {
                                if(response.status){
                                    model.loading3 = false;
                                    var format = [];
                                    format['data'] = response.data;
                                    format['totalNumber'] = response.count;
                                    done(format);
                                    model.noticeMsg3 = false;
                                    if(response.count / 10 > 1){
                                        model.page3 = true;
                                    }
                                }else{
                                    model.myCourseFinish = [];
                                    model.noticeMsg3 = true;
                                }
                            }
                        });
                    },

                    getData: function(pageNumber,pageSize) {
                        var self = this;
                        $.ajax({
                            type: 'GET',
                            url: '/studentCourse/getListMyCourseFinish',
                            data:{
                                pageNumber:pageNumber,
                                pageSize:pageSize,
                            },
                            success: function(response) {
                                console.log(response)
                                self.callback(response.data);
                            }
                        });
                    },
                    pageSize: 10,
                    pageNumber :1,
                    totalNumber :1,
                    className:"paginationjs-theme-blue",
                    showGoInput: true,
                    showGoButton: true,
                    callback: function(data) {
                        if(data){
                            model.myCourseFinish = data;
                            //console.log(data)
                        }

                    }
                })
        },





        //全部课程
        allCourse:[],
        gradeCourse:[],
        subjectCourse:[],
        bookCourse:[],
        editionCourse:[],
        pageAll: false,
        noticeMsgAll: false,
        loading4:false,
        gradeId: [], gradeIdOption: true,
        subjectId: [], subjectIdOption: true,
        bookId: [], bookIdOption: true,
        editionId: [], editionIdOption: true,
        // 添加选择项
        addNum: function (key, value) {
            model[key].push(value);
            model[key + 'Option'] = false;
            console.log('===============' + model[key]);
            console.log('***************' + model[key + 'Option']);
            model.getDataAll();
        },
        // 删除选择项
        delNum: function (key, value) {
            $.each(model[key], function (index, item) {
                // index是索引值（即下标）   item是每次遍历得到的值；
                if (item == value) {
                    model[key].splice(index, 1);
                }
            });
            model[key + 'Option'] = false;
            console.log('===============' + model[key]);
            console.log('***************' + model[key + 'Option']);
            model.getDataAll();
        },
        // 筛选条件
        selectAll: function (key) {
            model[key + 'Option'] = true;
            model[key] = [];
            console.log('===============' + model[key]);
            console.log('***************' + model[key + 'Option']);
            model.getDataAll();
        },
        get1: function () {
            $.ajax({url : '/teacherCourse/getGradeCourse', type : 'get', dataType : 'json', success: function(response){if(response.status){model.gradeCourse = response.data;}},})
        },
        get2: function () {
            $.ajax({url : '/teacherCourse/getSubjectCourse', type : 'get', dataType : 'json', success: function(response){if(response.status){model.subjectCourse = response.data;}},})
        },
        get3: function () {
            $.ajax({url : '/teacherCourse/getBookCourse', type : 'get', dataType : 'json', success: function(response){if(response.status){model.bookCourse = response.data;}},})
        },
        get4: function () {
            $.ajax({url : '/teacherCourse/getEditionCourse', type : 'get', dataType : 'json', success: function(response){if(response.status){model.editionCourse = response.data;}},})
        },
        getDataAll:function(type){
            model.loading4 = true;
            ////年级
            //$.ajax({url : '/teacherCourse/getGradeCourse', type : 'get', dataType : 'json', success: function(response){if(response.status){model.gradeCourse = response.data;}},})
            ////学科
            //$.ajax({url : '/teacherCourse/getSubjectCourse', type : 'get', dataType : 'json', success: function(response){if(response.status){model.subjectCourse = response.data;}},})
            ////册别
            //$.ajax({url : '/teacherCourse/getBookCourse', type : 'get', dataType : 'json', success: function(response){if(response.status){model.bookCourse = response.data;}},})
            ////版本
            //$.ajax({url : '/teacherCourse/getEditionCourse', type : 'get', dataType : 'json', success: function(response){if(response.status){model.editionCourse = response.data;}},})
            //全部课程
            $('#page_question_all').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/studentCourse/getListAllCourse',
                        data: {
                            condition: model.condition,
                            pageNumber: this.pageNumber,
                            pageSize: this.pageSize,
                            type:type,
                            gradeId: model.gradeIdOption ? 'all' : model.gradeId,
                            subjectId: model.subjectIdOption ? 'all' : model.subjectId,
                            bookId: model.bookIdOption ? 'all' : model.bookId,
                            editionId: model.editionIdOption ? 'all' : model.editionId,
                        },
                        //dataType:'json',
                        success: function(response) {
                            model.loading4 = false;
                            if(response.status){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                                model.noticeMsgAll = false;
                                if(response.count / 10 > 1){
                                    model.pageAll = true;
                                }
                            }else{
                                model.allCourse = [];
                                model.noticeMsgAll = true;
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/studentCourse/getListAllCourse',
                        data: {
                            condition: model.condition,
                            pageNumber: pageNumber,
                            pageSize: pageSize,
                            type:type,
                            gradeId: model.gradeIdOption ? 'all' : model.gradeId,
                            subjectId: model.subjectIdOption ? 'all' : model.subjectId,
                            bookId: model.bookIdOption ? 'all' : model.bookId,
                            editionId: model.editionIdOption ? 'all' : model.editionId,
                        },
                        success: function(response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 10,
                pageNumber :1,
                totalNumber :1,
                className:"paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function(data) {
                    if(data){
                        model.allCourse = data;
                    }

                }
            })
        },


        //热门--最新
        selectData: function (type) {
            model.getDataAll(type);
        },




    });


    model.getDataMyWait();
    model.getDataMyUnderway();
    model.getDataMyFinish();
    model.getDataAll();
    return model;
});