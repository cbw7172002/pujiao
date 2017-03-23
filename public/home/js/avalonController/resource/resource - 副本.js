define([], function () {
    
    var vm = avalon.define({
        $id: 'rescontroller',
        grades: [],           //年级选项组
        subjects: [],         //科目选项组
        editions: [],         //版本选项组
        books: [],            //册别选项组
        types: [],            //类型选项组
        sideSels:[],          //侧边栏选项集
        resources:[],         //资源结果集
        selTitle:'',
        selGradeId:0,
        selSubjectId:0,
        selEditionId:0,
        selBookId:0,
        selChapterId:0,
        selTypeId:0,
        orderBy:'resourceView', //created_at
        isExpand:false,
        gradesShowNum:8,
        subjectsShowNum:7,      
        editionsShowNum:8,      
        booksShowNum:8,         
        getRessels:function(type){
            var url = '/resource/getRessels/'+type
            if(type == 5) url = '/resource/getType/1'
            $.ajax({
                type: 'GET', url: url, dataType: "json",
                success: function(response) {
                    if(type == 1) vm.grades = response
                    if(type == 2) vm.subjects = response
                    if(type == 3) vm.editions = response
                    if(type == 4) vm.books = response
                    if(type == 5) vm.types = response
                },
                error: function(error) {}
            });
        },
        showMoreSel:function(type){
            if(type == 'a') {
                if(vm.gradesShowNum == 8) vm.gradesShowNum = 1000
                else vm.gradesShowNum = 8
            }
            if(type == 'b') {
                if(vm.subjectsShowNum == 7) vm.subjectsShowNum = 1000
                else vm.subjectsShowNum = 7
            }
            if(type == 'c') {
                if(vm.editionsShowNum == 8) vm.editionsShowNum = 1000
                else vm.editionsShowNum = 8
            }
            if(type == 'd') {
                if(vm.booksShowNum == 8) vm.booksShowNum = 1000
                else vm.booksShowNum = 8
            }
        },
        
        //侧边栏选项获取
        getSidesels:function(){
            var url = '/resource/getSidesels/'+vm.selGradeId+'/'+vm.selSubjectId+'/'+vm.selEditionId+'/'+vm.selBookId;
            $.ajax({
                type: 'GET', url: url, dataType: "json",
                success: function(response) {
                    vm.sideSels = response;
                },
                error: function(error) {}
            });
        },
        //title搜索
        titsearch:function(){
            vm.getResource();
        },
        // 年级-科目-版本-册别 选择
        selecting:function(type,id){                                                 //获取资源
            // vm.sideSels = [];
            if (type == 1) vm.selGradeId = id                                        //年级
            if (type == 2) { vm.isExpand = false; vm.selSubjectId = id }             //科目
            if (type == 3) vm.selEditionId = id                                      //版本
            if (type == 4) vm.selBookId = id                                         //册别
            if (type == 5) { vm.selSubjectId = null; vm.isExpand = true }            //拓展
            if (type == 6) vm.selChapterId = id                                      //章节
            if (type == 7) vm.selTypeId = id                                         //类型
            if (type == 8) vm.orderBy = id ? 'resourceView' : 'created_at'           //排序
            vm.getResource();                                                        //获取资源
        },

        formationData:function(pageNumber,pageSize){
            var data =  vm.isExpand ? {
                pageNumber:pageNumber,
                pageSize:pageSize,
                where:{
                    isexpand:2,
                    resourceIsDel:0,
                    resourceGrade:vm.selGradeId,
                },
                whereb:{
                    resourceTitle:vm.selTitle,
                },
                orderBy:vm.orderBy
            } : {
                pageNumber:pageNumber,
                pageSize:pageSize,
                where:{
                    isexpand:1,
                    resourceIsDel:0,
                    resourceGrade:vm.selGradeId,
                    resourceSubject:vm.selSubjectId,
                    resourceEdition:vm.selEditionId,
                    resourceBook:vm.selBookId,
                    resourceChapter:vm.selChapterId,
                    resourceType:vm.selTypeId,
                },
                whereb:{
                    resourceTitle:vm.selTitle,
                },
                orderBy:vm.orderBy
            };
            if(data.where.hasOwnProperty('resourceGrade') && data.where.resourceGrade == 0) delete data.where.resourceGrade
            if(data.where.hasOwnProperty('resourceSubject') && data.where.resourceSubject == 0) {
                delete data.where.resourceSubject;
                delete data.where.isexpand;
            }
            if(data.where.hasOwnProperty('resourceEdition') && data.where.resourceEdition == 0) delete data.where.resourceEdition
            if(data.where.hasOwnProperty('resourceBook') && data.where.resourceBook == 0) delete data.where.resourceBook
            if(data.where.hasOwnProperty('resourceChapter') && data.where.resourceChapter == 0) delete data.where.resourceChapter
            if(data.where.hasOwnProperty('resourceType') && data.where.resourceType == 0) delete data.where.resourceType

            return data;
        },

        // 获取资源
        getResource:function(){
            //console.log(postData);
            //return false;
            $('#page').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'POST',
                        url: '/resource/getResource',
                        datatype:"json",
                        data:vm.formationData(this.pageNumber,this.pageSize),
                        success: function(response) {
                            console.log(response)
                            if(response.type){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.total;
                                done(format);
                            }else{
                                $('.noresource').html('暂无相关资源 ...');
                                vm.resources = [];
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'POST',
                        url: '/resource/getResource',
                        data:vm.formationData(pageNumber,pageSize),
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
                        vm.resources = data;
                    }

                }
            })
        },
        
    });

    return vm;
    
});

























