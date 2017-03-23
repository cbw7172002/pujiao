avalon.directive('all_grade', {update: function (value) {$('.mycourse_screen_every1').toggle(function (){$(this).addClass('hot_new_blue')
    $('.all_grade').removeClass('hot_new_blue')}, function (){$(this).removeClass('hot_new_blue')});}});

avalon.directive('all_subject', {update: function (value) {$('.mycourse_screen_every2').toggle(function (){$(this).addClass('hot_new_blue')
    $('.all_subject').removeClass('hot_new_blue')}, function (){$(this).removeClass('hot_new_blue')});}});

avalon.directive('all_book', {update: function (value) {$('.mycourse_screen_every3').toggle(function (){$(this).addClass('hot_new_blue')
    $('.all_book').removeClass('hot_new_blue')}, function (){$(this).removeClass('hot_new_blue')});}});

avalon.directive('all_edition', {update: function (value) {$('.mycourse_screen_every4').toggle(function (){$(this).addClass('hot_new_blue')
    $('.all_edition').removeClass('hot_new_blue')}, function (){$(this).removeClass('hot_new_blue')});}});


define([], function (){

    var model = avalon.define({

        $id: 'teacherCourse',
        currentIndex: 'my',
        condition: '',
        xxxx: 1,


        conditionSearch: function (value) {
            if (value == '') {
                alert('请输入查询条件');
            } else {
                model.condition = value;
                model.getDataAll();
                model.currentIndex = 'all';
                $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
                //location.reload()
                //if(model.currentIndex == 'my'){
                //    model.getDataMy();
                //}else if(model.currentIndex == 'rec'){
                //    model.getDataRec();
                //}else if(model.currentIndex == 'all'){
                //    model.getDataAll();
                //}
            }
        },


        tabs: function(index){
            window.location.hash = index;
            model.currentIndex = index;

            if(model.currentIndex == 'my'){
                $('.mycourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'rec'){
                $('.reccourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'all'){
                $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'new'){
                window.location.href = '/teacherCourse/list#new/1';
            }else if(model.currentIndex == 'shenhe'){
                $('.mycourse').addClass('span_active').siblings().removeClass('span_active');
                $('.shenhe').trigger('click');
                model.select(1);
            }
        },


        //弹出层
        popUp: false,
        popUpSwitch: function (value) {
            model.popUp = value;
        },

        tabsb: function(index){
            window.location.hash = index;
            model.currentIndex = index;
            alert(index)
            if(model.currentIndex == 'my'){
                $('.mycourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'rec'){
                $('.reccourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'all'){
                $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
            }else if(model.currentIndex == 'new'){
                window.location.href = '/teacherCourse/list#new/1';
            }else if(model.currentIndex == 'shenhe'){
                $('.mycourse').addClass('span_active').siblings().removeClass('span_active');
                $('.shenhe').trigger('click');
                //model.getDataMy(1);
            }
        },

        //我的课程
        myCourse:[],
        courseDetailUrl:'/teacherCourse/teaDetail/',                    //详情页链接
        page: false,
        noticeMsgMy: false,
        loading1: false,
        getDataMy:function(type){
            model.page = false,
                model.loading1 = true;
                $('#page_question').pagination({
                    dataSource: function(done) {
                        $.ajax({
                            type: 'GET',
                            url: '/teacherCourse/getListMyCourse/'+type+'/'+this.pageNumber+'/'+this.pageSize,
                            data:{
                                condition:model.condition,
                            },
                            //dataType:'json',
                            success: function(response) {
                                model.loading1 = false;
                                if(response.status){
                                    var format = [];
                                    format['data'] = response.data;
                                    format['totalNumber'] = response.count;
                                    done(format);
                                    model.noticeMsgMy = false;
                                    if(response.count / 10 > 1){
                                        model.page = true;
                                    }
                                    //console.log(format['data'])
                                    //console.log(format['totalNumber'])
                                }else{
                                    model.myCourse = [];
                                    model.noticeMsgMy = true;
                                }
                            }
                        });
                    },

                    getData: function(pageNumber,pageSize) {
                        var self = this;
                        $.ajax({
                            type: 'GET',
                            url: '/teacherCourse/getListMyCourse/'+type+'/'+pageNumber+'/'+pageSize,
                            data:{
                                condition:model.condition,
                            },
                            //dataType:'json',
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
                            model.myCourse = data;
                            //console.log(data)
                        }

                    }
                })
        },
        //已发布--待发布--审核中--未通过
        select: function (type) {
            //model.xxxx = type;
            model.getDataMy(type);
        },
        //删除课程
        deleteCourse: function (courseId) {
            if(confirm('确认删除？')){
                $.ajax({
                    url : '/teacherCourse/deleteCourse/' + courseId,
                    type : 'get',
                    dataType : 'json',
                    success: function(response){
                        if(response.status){
                            alert('删除课程成功')
                            location.reload()
                        }else{
                            alert('操作失败，请重新尝试');
                        }
                    },
                })
            }
        },
        //推荐课程
        recCourse:[],
        pageRec: false,
        noticeMsgRec: false,
        loading2:false,
        getDataRec:function(subjectId){
            //获取最小科目的id
            var minId = $('.minSubjectId').val()
            model.pageRec = false,
                model.loading2 = true;
                $('#page_question_rec').pagination({
                    dataSource: function(done) {
                        $.ajax({
                            type: 'GET',
                            url: '/teacherCourse/getListRecCourse/'+subjectId+'/'+this.pageNumber+'/'+this.pageSize+'/' + minId,
                            data:{
                                condition:model.condition,
                            },
                            //dataType:'json',
                            success: function(response) {
                                model.loading2 = false;
                                if(response.status){
                                    var format = [];
                                    format['data'] = response.data[0];
                                    format['totalNumber'] = response.count;
                                    done(format);
                                    model.noticeMsgRec = false;
                                    if(response.count / 10 > 1){
                                        model.pageRec = true;
                                    }
                                    //console.log(format['data'])
                                    //console.log(format['totalNumber'])
                                }else{
                                    model.recCourse = [];
                                    model.noticeMsgRec = true;
                                }
                            }
                        });
                    },

                    getData: function(pageNumber,pageSize) {
                        var self = this;
                        $.ajax({
                            type: 'GET',
                            url: '/teacherCourse/getListRecCourse/'+subjectId+'/'+pageNumber+'/'+pageSize+'/' + minId,
                            data:{
                                condition:model.condition,
                            },
                            //dataType:'json',
                            success: function(response) {
                                //console.log(response)
                                self.callback(response.data[0]);
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
                            model.recCourse = data;
                            //console.log(data)
                        }

                    }
                })
        },
        //科目选择
        selectSubject: function (subjectId) {
            model.getDataRec(subjectId);
        },

        selectSubjectAll: function (subjectId) {
            model.getDataRec(subjectId)
        },


        //全部课程
        allCourse:[],
        gradeCourse:[],
        subjectCourse:[],
        bookCourse:[],
        editionCourse:[],
        pageAll: false,
        noticeMsgAll:false,
        loading3:false,
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
            model.pageAll = false,
                model.loading3 = true;
            //年级
             //全部课程
            $('#page_question_all').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/teacherCourse/getListAllCourse',
                        data: {
                            pageNumber: this.pageNumber,
                            pageSize: this.pageSize,
                            type:type,
                            gradeId: model.gradeIdOption ? 'all' : model.gradeId,
                            subjectId: model.subjectIdOption ? 'all' : model.subjectId,
                            bookId: model.bookIdOption ? 'all' : model.bookId,
                            editionId: model.editionIdOption ? 'all' : model.editionId,
                            condition:model.condition,
                        },
                        //dataType:'json',
                        success: function(response) {
                            model.loading3 = false;
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
                        url: '/teacherCourse/getListAllCourse',
                        data: {
                            pageNumber: pageNumber,
                            pageSize: pageSize,
                            type:type,
                            gradeId: model.gradeIdOption ? 'all' : model.gradeId,
                            subjectId: model.subjectIdOption ? 'all' : model.subjectId,
                            bookId: model.bookIdOption ? 'all' : model.bookId,
                            editionId: model.editionIdOption ? 'all' : model.editionId,
                            condition:model.condition,
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


        //编辑课程
        editCourse:function(){
          //
        },




        //新建课程
        newCourse:[],
        getDataNew:function(){

        },

    });


    model.getDataRec();
    model.getDataAll();
    return model;
});