define(['lessonComment/PrimecloudPaas'], function (PrimecloudPaas){

    var model = avalon.define({

        $id: 'createCourse',
        pageNuma:true,       //教学设计
        pageNumb:false,      //创建课程
        pageNumc:false,      //发布课程
        pageClass:'stepa',   //当前页
        courseId:0,          //课程id
        testIda:'',          //第一部分练习
        testIdb:'',          //第二部分练习
        testIdc:'',          //第三部分联系
        testTitlea:'',       //第一部分练习title
        testTitleb:'',       //第二部分练习title
        testTitlec:'',       //第三部分练习title
        papertype:'',        //练习类型
        submitTime:'',       //下发时间
        completeTime:'',     //完成时间
        showparta:true,      //第一部分显示与否
        showpartb:true,      //第二部分显示与否
        showpartc:true,      //第三部分显示与否
        classes:[],          //下发班级
        selclasses:[],       //选中下发班级
        testTimes:[],        //练习时间
        isEdit:false,        //是否编辑
        isClickA:true,       //是否重复点击
        isClickB:true,       //是否重复点击
        isClickC:true,       //是否重复点击

        //切换页面
        changePage:function(pagenum){
            if(pagenum == 1) {
                model.pageClass = 'stepa';
                model.pageNuma = true;
                model.pageNumb = false;
                model.pageNumc = false;
            }
            if(pagenum == 2) {
                if(!model.isClickA) return false;
                model.isClickA = false;
                // 获取第一页数据
                var subarr = $('.subject').val().split(',');
                model.postInfo.baseInfo.gradeId = subarr[0];
                model.postInfo.baseInfo.subjectId = subarr[1];
                model.postInfo.baseInfo.editionId = subarr[2];
                model.postInfo.baseInfo.bookId = subarr[3];
                model.postInfo.baseInfo.chapterId = $('.chapter').val();
                model.postInfo.baseInfo.coursePic = $('.center_right_comment_fabu_a_con_pic_r_l_pic').attr('src');
                model.postInfo.baseInfo.courseContent = model.ueditor.getContent();
                // 提交第一页数据
                if(model.checkPagea()) model.commitPagea(model.postInfo.baseInfo.$model,pagenum)
            }
            if(pagenum == 3) {
                if(!model.isClickB) return false;
                model.isClickB = false;
                // 提交第二页数据
                if(model.checkPageb()) model.commitPageb(model.postInfoCopy.$model,pagenum)
            }
        },

        //选择添加练习类型
        addtestType:'',
        addtest:function(type){
            model.addtestType = type;

            if(!model.paperInfoa.length) model.gettestData(1,'/evaluateManageTea/getPaper',{'type': 0, 'chapter': model.postInfo.baseInfo.chapterId});
            if(!model.paperInfob.length) model.gettestData(2,'/evaluateManageTea/getPaper',{'type': 1, 'chapter': model.postInfo.baseInfo.chapterId});

            $('.shadow').show();
            $('.choose_paper_pop').show();
        },

        //删除练习
        deltest:function(type){
            if(!confirm('确认删除？')) return false ;
            if(type == 1) model.testIda = '';
            if(type == 2) model.testIdb = '';
            if(type == 3) model.testIdc = '';
        },

        //关闭练习选择窗口
        closetest:function(){
            $('.shadow').hide();
            $('.choose_paper_pop').hide();
        },

        // 平台题库
        papertype:true,
        paperInfo: [],
        paperInfoa: [],
        paperInfob: [],
        changepaper:function(){
            model.papertype = !model.papertype;
            if(model.papertype) model.paperInfo = model.paperInfoa;
            else model.paperInfo = model.paperInfob;
        },

        //获取练习数据
        gettestData:function(type,url,data){
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: "json",
                success: function(response) {
                    if(response.type){
                        if(type == 1) {
                            model.paperInfoa = response.data;
                            model.paperInfo  = model.paperInfoa;
                        } else {
                            model.paperInfob = response.data;
                        }
                    }else{
                        $('.choose_paper_pop_center_nodata_paper').html('暂无内容..');
                    }
                },
                error: function(error) {
                    //...
                }
            });
        },

        //选择练习
        selpaper:function(type,id,title){
            // console.log(type+':'+id);
            // if(type == 1) {
            //     model.testIda = id;
            //     model.testTitlea = title;
            // }
            // if(type == 2) {
            //     model.testIdb = id;
            //     model.testTitleb = title;
            // }
            // if(type == 3) {
            //     model.testIdc = id;
            //     model.testTitlec = title;
            // }
            // model.closetest();
        },

        //创建练习
        createPaper:function(type){
            // $('.shadow').hide();
            // $('.choose_paper_pop').hide();
            // $('.content_right_newcourse').hide();
            // $('.content_right_papercourse').show();
            // editPaper.lessonInfo = '9527-9527-9527-9527-1';
            // editPaper.init(type);
        },

        // 选择班级
        clickclass:function(obj){
            if(obj.issel > 0) obj.issel = 0 ;
            else obj.issel = 1
        },

        //格式化数据
        formPostClasses:function(){
            $.each(model.classes,function(i){
                if(this.issel == 0) {
                    model.selclasses.push({courseId:model.courseId,gradeId:this.gradeId,classId:this.classId});
                    if(model.testIda) model.testTimes.push({classId:this.classId,paperId:model.testIda,submitTime:model.submitTime,completeTime:model.completeTime,type:model.papertype});
                    if(model.testIdb) model.testTimes.push({classId:this.classId,paperId:model.testIdb,submitTime:model.submitTime,completeTime:model.completeTime,type:model.papertype});
                    if(model.testIdc) model.testTimes.push({classId:this.classId,paperId:model.testIdc,submitTime:model.submitTime,completeTime:model.completeTime,type:model.papertype});
                }
            })
        },

        // 确认下发
        commitAll:function(){
            if(!model.isClickC) return false;
            model.isClickC = false;
            model.formPostClasses();
            if(!model.selclasses.length){
                alert('请选择下发班级');
                return false;
            }else{
                $.ajax({
                    type: 'POST',
                    url: '/teacherCourse/addSelclass',
                    data: {'courseId':model.courseId,'data':model.selclasses.$model,'testTime':model.testTimes.$model},
                    dataType: "json",
                    success: function(response) {
                        if(response.type){
                            model.sendMsg(response.courseId);
                            window.location.href = '/teacherCourse/list#shenhe';
                            location.reload();
                        }else{
                            model.isClickC = true;
                            model.selclasses = [];
                            alert('下发失败');
                        }
                    },
                    error: function(error) {
                        model.isClickC = true;
                        model.selclasses = [];
                    }
                });
                console.log(model.selclasses.$model);
            }
        },

        // 异步通知
        sendMsg:function(courseId){
            $.ajax({
                type: 'GET',
                url: '/teacherCourse/sendMsg/' + courseId,
                dataType: "json",
                success: function(response) {
                    //
                }
            });
        },

        //获取下发班级
        getTeachClass:function(){
            $.ajax({
                type: 'POST',
                url: '/evaluateManageTea/getTeacherClass',
                data:{gradeId:model.postInfo.baseInfo.gradeId,subjectId:model.postInfo.baseInfo.subjectId},
                dataType: "json",
                success: function(response) {
                    if(response.type){
                        model.classes = response.data
                    }else{
                        alert('下发班级获取失败')
                    }
                },
                error: function(error) {
                    // ...;
                }
            });
        },

        //验证第一页所填信息
        checkPagea:function(){
            if(!model.postInfo.baseInfo.gradeId){ model.pageNum = 1; alert('请选择课程归属'); model.isClickA = true; return false }
            if(!model.postInfo.baseInfo.chapterId){ model.pageNum = 1; alert('请选择章节'); model.isClickA = true; return false }
            if(!model.postInfo.baseInfo.courseTitle){ model.pageNum = 1; alert('请填写课程名称'); model.isClickA = true; return false }
            if(!model.postInfo.baseInfo.courseIntro){ model.pageNum = 1; alert('请填写知识点'); model.isClickA = true; return false }
            return true
        },

        //验证第二页所填信息
        checkPageb:function(){
            var stata = true, statb = true, statc = true;
            model.postInfo.courseId = model.courseId;
            model.postInfoCopy  = model.postInfo;
            if(!model.postInfo.prelearnInfo.length && !model.postInfo.chaprerInfo.length && !model.postInfo.guideInfo.length){
                alert('请添加资料'); model.isClickB = true; return false;
            }
            if(model.postInfo.prelearnInfo.length){
                console.log('添加了第一部分');
                stata = model.checkDetailInfo(1,model.postInfo.prelearnInfo,model.postInfoCopy.prelearnInfo,model.formData)
            }
            if(model.postInfo.chaprerInfo.length){
                console.log('添加了第二部分');
                statb = model.checkDetailInfo(2,model.postInfo.chaprerInfo,model.postInfoCopy.chaprerInfo,model.formData)
            }
            if(model.postInfo.guideInfo.length){
                console.log('添加了第三部分');
                statc = model.checkDetailInfo(3,model.postInfo.guideInfo,model.postInfoCopy.guideInfo,model.formData)
            }
            if(stata && statb && statc) return true;
            else { model.isClickB = true; return false; }
        },

        //验证添加课程信息
        checkDetailInfo:function(type,obj,data,callback){
            var stat = true;
            $.each(data,function(i){
                this.courseId = model.courseId;
                if(type == 1 && i == 0) this.paperId = model.testIda;
                if(type == 2 && i == 0) this.paperId = model.testIdb;
                if(type == 3 && i == 0) this.paperId = model.testIdc;
                if(!this.title){ alert('请填写章节名称'); stat = false; return false; }
                if(type == 2){
                      $.each(this.nodeInfo,function(){
                        if(!this.title){ alert('请填写章节名称'); stat = false; return false; }
                        if(!this.dataInfo.length){ alert('请上传对应文件'); stat = false; return false; }
                        else $.each(this.dataInfo,function(){ if(!this.fileID) { alert('文件正在上传，请稍后发布'); stat = false; return false; } })
                    })
                }else{
                    if(!this.dataInfo.length){ alert('请上传对应文件'); stat = false; return false; }
                    else $.each(this.dataInfo,function(){ if(!this.fileID) { alert('文件正在上传，请稍后发布'); stat = false; return false; } })
                }
            })
            if(stat) callback(data);
            return stat;
        },

        //格式化数据
        formData:function(data){
            $.each(data,function(){
                if(this.dataInfo) $.each(this.dataInfo,function(){ delete this.showjdbar; delete this.stopupload; delete this.jdmsg; delete this.progressBara; delete this.progressBarb; })
                else model.formData(this.nodeInfo)
            })
        },

        //提交课程基本信息
        commitPagea:function(data,pagenum){
            $.ajax({
                type: 'POST',
                url: '/teacherCourse/addCourseInfo',
                data: {courseId:model.courseId,data:data},
                dataType: "json",
                success: function(response) {
                    if(response.type){
                        model.isClickA = true;
                        model.courseId = response.courseId;
                        //获取edit b 的信息
                        if (model.isEdit) model.geteditCourseInfo(2,response.courseId);
                        // 页面切换
                        model.pageClass = 'stepb';
                        model.pageNuma = false;
                        model.pageNumb = true;
                        model.pageNumc = false;
                    }else{
                        alert('提交失败，请刷新重试');
                    }
                },
                error: function(error) {
                    // ...;
                }
            });
        },

        // 提交添加课程
        commitPageb:function(data,pagenum){
            $.ajax({
                type: 'POST',
                url: '/teacherCourse/addCourse',
                data: data,
                dataType: "json",
                success: function(response) {
                    if(response.type){
                        model.isClickB = true;
                        //获取下发班级信息
                        model.getTeachClass();
                        // 页面切换
                        model.pageClass = 'stepc';
                        model.pageNuma = false;
                        model.pageNumb = false;
                        model.pageNumc = true;
                    }else{
                        alert('提交失败，请刷新重试');
                    }
                },
                error: function(error) {
                    // ...;
                }
            });
        },

        // ueditor编辑器创建
        createUeditor:function(concon){
            model.ueditor = UE.getEditor('container',{
                initialFrameHeight:260,
            });
            model.ueditor.ready(function() {//编辑器初始化完成再赋值
                 model.ueditor.setContent(concon);  //赋值给UEditor
            });
        },

        //类型选择事件
        getType:function(selSelect, type){
            var url = "/teacherCourse/getType";
            if(type == 2) url = "/teacherCourse/getChapter/" + $('.subject').val();
            $('.'+selSelect).select2(
                {
                    minimumResultsForSearch: Infinity,
                    ajax: {
                        url: url, type:'get', dataType:'json',
                        processResults: function (data) {return {results: data};},
                    },
                }
            ).on('change',function(){
                if(selSelect == 'subject') {
                    $('.chapter').html('<option selected="selected" value="">请选择章节</option>');
                    model.getType('chapter',2);
                }
                model.getselcon();
            })
        },

        selgradecontent:'',
        selgradeval:'',
        getselcon:function(){
            model.selgradecontent = $('.subject').find("option:selected").text();
            model.selgradeval = $('.subject').val();
        },

        resourcelisttype:true,
        resourcelist:[],
        resourcecenter:[],
        resourceorder:[],
        nownodeobj:null,
        changereslist:function(){
            model.resourcelisttype = !model.resourcelisttype;
            if(model.resourcelisttype) model.resourcelist = model.resourcecenter;
            else model.resourcelist = model.resourceorder;
        },

        selresource:function(obj){
            model.nownodeobj = obj;
            if(!model.resourcecenter.length) model.getresource(1,'/teacherCourse/getresource/1/'+model.postInfo.baseInfo.chapterId);
            if(!model.resourceorder.length) model.getresource(2,'/teacherCourse/getresource/2/'+model.postInfo.baseInfo.chapterId);

            $('.shadow').show();
            $('.choose_paper_pop_b').show();
        },

        closetestres:function(){
            $('.shadow').hide();
            $('.choose_paper_pop_b').hide();
        },

        //选择添加文件
        sellistres:function(obj,el){
            if(obj.dataInfo.length == 3) {
                alert('资料数不可超过三个');
                return false;
            }
            var arrlastindex = obj.dataInfo.push(
                {
                    title:el.resourceTitle,
                    courseFormat:el.resourceFormat,
                    fileID:el.fileID,
                    // courseTime:'',
                    // tipatime:'',
                    // tipbtime:'',
                    // tipctime:'',
                    // tipacon:'',
                    // tipbcon:'',
                    // tipccon:'',

                    showjdbar:false,  //进度条显示与隐藏
                    stopupload:false, //是否点击取消上传
                    jdmsg:'',         //进度条提示信息
                    progressBara:'100', //读取进度条
                    progressBarb:'100', //上传进度条
                }
            );
            model.closetestres();
        },

        getresource:function(type,url){
            $.ajax({
                type: 'GET',
                url: url,
                dataType: "json",
                success: function(response) {
                    if(type == 1) {
                        model.resourcecenter = response.data;
                        model.resourcelist  = model.resourcecenter;
                    } else {
                        model.resourceorder = response.data;
                    }
                    $('.choose_paper_pop_center_nodata_res').html('暂无内容..');
                }
            });
        },

        // 显示隐藏
        stacks:function(type,part){
            if(part == 1) model.showparta = !model.showparta;
            if(part == 2) model.showpartb = !model.showpartb;
            if(part == 3) model.showpartc = !model.showpartc;
        },

        //执行获取课程信息
        dogetcourseInfo:function(type,courseId){
            $.ajax({
                url: '/teacherCourse/geteditCourseInfo/' + type + '/' + courseId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if(type == 1){
                        model.postInfo.courseId = courseId;
                        if(response.status){
                            
                            model.postInfo.baseInfo.courseTitle = response.data.courseTitle;
                            model.postInfo.baseInfo.coursePic = response.data.coursePic;
                            model.postInfo.baseInfo.courseIntro = response.data.courseIntro;
                            model.postInfo.baseInfo.courseContent = response.data.courseContent;

                            $(".subject").html('<option selected="selected" value="'+response.data.gradeId+','+response.data.subjectId+','+response.data.editionId+','+response.data.bookId+'">'+response.data.gradeName+' - '+response.data.subjectName+' - '+response.data.editionName+' - '+response.data.bookName+'</option>');
                            model.getType('subject',1);
                            $(".chapter").html('<option selected="selected" value="'+response.data.chapterId+'">'+response.data.chapterName+'</option>');
                            model.getType('chapter',2);

                            model.createUeditor(response.data.courseContent);
                        }else{
                            alert('信息获取失败'); return false;
                        }
                    }else{
                        if(response.status){
                            model.postInfo.prelearnInfo = response.prelearnInfo;
                            model.postInfo.chaprerInfo = response.chaprerInfo;
                            model.postInfo.guideInfo = response.guideInfo;
                            if(response.prelearnInfo.length > 0) model.testIda = response.prelearnInfo[0].paperId;
                            if(response.chaprerInfo.length > 0) model.testIdb = response.chaprerInfo[0].paperId;
                            if(response.guideInfo.length > 0) model.testIdc = response.guideInfo[0].paperId;
                        }
                    }
                },
            });
        },

        //获取编辑课程信息
        geteditCourseInfo:function(type,courseId){
            model.dogetcourseInfo(type,courseId);
            model.geteditTestInfo(1,'/teacherCourse/getTestInfoa/'+courseId);
            model.geteditTestInfo(2,'/teacherCourse/getTestInfob/'+courseId);
            model.geteditTestInfo(3,'/teacherCourse/getTestInfoc/'+courseId);
            //alert(courseId);
        },

        //获取编辑练习信息
        geteditTestInfo:function(type,url){
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                // async:false,
                success: function (response) {
                    if(type == 1){
                        if(response.status){
                            model.testIda = response.data.paperId;
                            model.testTitlea = response.data.title;
                        }else{
                            model.testIda = '';
                            model.testTitlea = '';
                        }
                    }else if (type == 2){
                        if(response.status){
                            model.testIdb = response.data.paperId;
                            model.testTitleb = response.data.title;
                        }else{
                            model.testIdb = '';
                            model.testTitleb = '';
                        }
                    }else{
                        if(response.status){
                            model.testIdc = response.data.paperId;
                            model.testTitlec = response.data.title;
                        }else{
                            model.testIdc = '';
                            model.testTitlec = '';
                        }
                    }
                },
            });
        },

        postInfoCopy:null,
        // 提交data
        postInfo:{
            courseId:'',
            baseInfo:{
                gradeId:'',
                subjectId:'',
                editionId:'',
                bookId:'',
                chapterId:'',
                courseTitle:'',
                coursePic:'/home/image/personCenter/pre.png',
                courseIntro:'',
                courseContent:''
            },
            prelearnInfo:[ //课前导学
                // {
                //     courseType:0,
                //     title:'',
                //     courseId:'',
                //     dataInfo:[
                //       {
                //             title:title,
                //             courseFormat:courseFormat,
                //             fileID:'',
                //
                //             showjdbar:false,  //进度条显示与隐藏
                //             stopupload:false, //是否点击取消上传
                //             jdmsg:'',         //进度条提示信息
                //             progressBara:'0', //读取进度条
                //             progressBarb:'0', //上传进度条
                //         }
                //     ]
                // }
            ],
            chaprerInfo:[ //课堂授课
                // {
                //     courseType:1,
                //     title:'',
                //     courseId:'',
                //     nodeInfo:[
                //         {
                //             title:'',
                //             dataInfo:[]
                //         }
                //     ]
                // }
            ],
            guideInfo:[ //课堂指导
                // {
                //     courseType:2,
                //     title:'',
                //     courseId:'',
                //     dataInfo:[]
                // }
            ]
        },

        //随机课程封面图
        randomPic:function(){
            var num = Math.floor(Math.random()*6+1);
            model.postInfo.baseInfo.coursePic = '/home/image/teacherCourse/cover/'+num+'.png';
        },

        //添加章节
        addchapter:function(type,index){
            switch (type){
                case 1:
                    model.postInfo.prelearnInfo.push(
                        {
                            courseType:0,
                            title:'',
                            courseId:'',
                            paperId:'',
                            dataInfo:[]
                        }
                    );
                    break;
                case 2:
                    model.postInfo.chaprerInfo[index].nodeInfo.push(
                        {
                            title:'',
                            dataInfo:[]
                        }
                    );
                    break;
                case 3:
                    model.postInfo.chaprerInfo.push(
                        {
                            courseType:1,
                            title:'',
                            courseId:'',
                            paperId:'',
                            nodeInfo:[
                                {
                                    title:'',
                                    dataInfo:[]
                                }
                            ]
                        }
                    );
                    break;
                case 4:
                    model.postInfo.guideInfo.push(
                        {
                            courseType:2,
                            title:'',
                            courseId:'',
                            paperId:'',
                            dataInfo:[]
                        }
                    );
                    break;
            }
        },

        //文件大小计算
        countsize:function(dataname,datasize){
            if(datasize > 1024*1024*1024){
                return false;
            }else{
                return true;
            }
        },
        
        //添加资料--添加文件信息到资料数组
        uploadziliao:function(data,datalength,chapterIndex,nodeIndex,typearr){
            for (var i = 0; i < datalength; i++) {
                var title = data[i].name;
                var courseFormat = data[i].name.substring(data[i].name.lastIndexOf('.') + 1);
                var arrlastindex = typearr.push(
                    {
                        title:title,
                        courseFormat:courseFormat,
                        fileID:'',
                        // courseTime:'',
                        // tipatime:'',
                        // tipbtime:'',
                        // tipctime:'',
                        // tipacon:'',
                        // tipbcon:'',
                        // tipccon:'',

                        showjdbar:false,  //进度条显示与隐藏
                        stopupload:false, //是否点击取消上传
                        jdmsg:'',         //进度条提示信息
                        progressBara:'0', //读取进度条
                        progressBarb:'0', //上传进度条
                    }
                );
                model.fordataupload(data[i],typearr[arrlastindex-1],arrlastindex-1);
            }
        },

        //循环创建新添加的上传对象，并执行上传
        fordataupload:function(file,dataobj,dataindex){
            //console.log(file);
            dataobj.paas = new PrimecloudPaas();
            dataobj.paas.MD5(file, function(result){
                //判断是否 点击 取消上传
                if (dataobj.stopupload) {
                    console.log('----------------------------------'+file.name+' 取消上传 ----------------------------------');
                    //model.delpdataup(dataobj.dataindex); //从提交对象中删除这条取消的资料信息
                    return false;
                }

                if (result) {
                    dataobj.fileName = dataobj.paas.splitFileName(file.name);
                    $('#md5container').val(result);
                    dataobj.fileMD5 = $('#md5container').val();
                    console.log('----------------------------------开始上传----------------------------------');
                    model.dofordataupload('/lessonComment/uploadResource', 'uploadResource', {md5: dataobj.fileMD5, filename: dataobj.fileName, directory: '/'}, 'POST',dataobj,dataindex,file);
                } else {
                    console.log(file.name+ ' 上传失败，请重试');
                    alert(file.name+' 上传失败，请重试');
                }
            }, function(pos, size){

                if (dataobj.stopupload) {
                    console.log('----------------------------------'+file.name+' 取消上传 ----------------------------------');
                    //model.delpdataup(dataobj.dataindex); //从提交对象中删除这条取消的资料信息
                    return false;
                }
                //显示进度条
                dataobj.showjdbar = true;
                //修改进度条进度提示信息
                // dataobj.jdmsg = '读取中...';
                var jd = parseInt(pos / size * 100);
                dataobj.progressBara = jd;
                //console.log(file.name+' : 扫描进度： ' + jd + '%');
                if(jd == 100) {
                    dataobj.jdmsg = '上传中...';
                    console.log('----------------------------------'+file.name+': 扫描完成 ----------------------------------');
                }

            });
        },

        dofordataupload:function(url, kind, data, method, dataobj, dataindex,file,callback){
            $.ajax({
                type: method || 'GET',
                url: url,
                data: data || {},
                dataType: "json",
                success: function(response) {
                    if (response.type) {
                        if (kind == 'uploadResource') {
                            if (!dataobj.stopupload && response.data.code != 401) {
                                if (response.data.data.AllowUpload == 2) {
                                    console.log('----------------------------------妙传----------------------------------');
                                    dataobj.progressBarb = 100;
                                    dataobj.jdmsg = '上传成功！';
                                    setTimeout(function() {
                                        //upload.endUpload('视频上传完成！');
                                        dataobj.fileID = response.data.data.FileID;
                                        dataobj.courseTime = 3600;
                                        //隐藏滚动条
                                        dataobj.showjdbar = false;

                                        //转码
                                        var convertype;
                                        var suffix= file.name.substring(file.name.lastIndexOf('.') + 1).toLowerCase();;
                                        if(suffix.match(/(mp4|flv|avi|rmvb|wmv|mkv|mov)/i)){
                                            convertype = 0;
                                        }else if(suffix.match(/(xls|xlsx|doc|docx|pdf|ppt)/i)){
                                            convertype = 2;
                                        }else if(suffix.match(/(mp3)/i)){
                                            convertype = 1;
                                        }
                                        model.dofordataupload('/lessonComment/transformation', 'transformation',{fileID: response.data.data.FileID,convertype:convertype}, 'POST');

                                    }, 1000);
                                } else {
                                    var deleteSecond;
                                    if (response.data.data.AllowUpload == 1) {
                                        deleteSecond = 1000;
                                        var uploadData = {
                                            url: response.data.data.UUrl,
                                            method: "POST",
                                            data: {
                                                filedata: file
                                            }
                                        };
                                        if (response.data.data.UploadLength > 0) {
                                            uploadData.resume = response.data.data.UploadLength;
                                            console.log('断点续传');
                                            console.log(response.data.data);
                                            console.log(uploadData);
                                        }
                                        dataobj.paas.requestUpload(uploadData);
                                    } else {
                                        deleteSecond = 1000;
                                    }
                                    var scjd = parseInt(response.data.data.UploadLength / response.data.data.FileLenth * 100) ? parseInt(response.data.data.UploadLength / response.data.data.FileLenth * 100) : 0;
                                    //console.log(file.name+' 上传进度： ' + scjd + '%');
                                    dataobj.progressBarb = scjd;
                                    setTimeout(function() {
                                        model.dofordataupload('/lessonComment/uploadResource', 'uploadResource',data, 'POST',dataobj,dataindex,file);
                                    }, deleteSecond);
                                }
                            } else {
                                dataobj.stopupload ? console.log('----------------------------------'+file.name+'取消上传----------------------------------') : console.log('----------------------------------code:401----------------------------------');
                                //model.delpdataup(dataobj.dataindex); //从提交对象中删除这条取消的资料信息
                            }
                        }
                        if (kind == 'transformation') {
                            if (response.data.code == 200 && response.data.data.Waiting < 0) {
                                //
                            }
                        }

                    }
                },
                error: function(error) {
                    console.log('网络异常，请重试');
                    model == 'uploadResource' && alert('网络异常，请重试');
                }
            });
        },

        // 创客视频终止上传
        stopupload:function(nodeobj){
            nodeobj.stopupload = true;
            nodeobj.showjdbar = false;
            nodeobj.jdmsg = '';
            nodeobj.progressBara = '0';
            nodeobj.progressBarb = '0';
            if (nodeobj.paas) {
                nodeobj.paas.endUpload();
                nodeobj.paas.endMD5();
                nodeobj.paas = null;
            };
        },

        //执行终止
        dostopupload:function(type,nodeobj,outoutindex,outindex,index){
            if(!confirm('确定删除？')) return false;
            //console.log(outoutindex);
            //return false;
            model.stopupload(nodeobj);
            if(type == 1) model.postInfo.prelearnInfo[outindex].dataInfo.splice(index, 1);//删除章节信息
            if(type == 2) model.postInfo.chaprerInfo[outoutindex].nodeInfo[outindex].dataInfo.splice(index, 1);//删除章节信息
            if(type == 3) model.postInfo.guideInfo[outindex].dataInfo.splice(index, 1);//删除章节信息

            if(nodeobj.hasOwnProperty("id")) model.deletedatabase(1,nodeobj.id);
        },

        // 创客章节删除
        delchapter:function(type,obj,outobj,outindex,index){
            //console.log(outobj.id);
            //return false;
            if(!confirm('确定删除？')) return false;
            $.each(obj.dataInfo,function(){
                model.stopupload(this);//停止上传
            })
            if(type == 1) model.postInfo.prelearnInfo.splice(index, 1);
            if(type == 2) {
                model.postInfo.chaprerInfo[outindex].nodeInfo.splice(index, 1);
                if(model.postInfo.chaprerInfo[outindex].nodeInfo.length == 0) {
                    model.postInfo.chaprerInfo.splice(outindex, 1);
                    //删除章
                    if(outobj.hasOwnProperty("id")) model.deletedatabase(1,outobj.id);
                }
            }
            if(type == 3) model.postInfo.guideInfo.splice(index, 1);

            //删除节
            if(obj.hasOwnProperty("id")) model.deletedatabase(2,obj.id);

        },

        //删除已添加信息
        deletedatabase:function(type,id){
            $.ajax({
                url: '/teacherCourse/deletedatabase/' + type + '/' + id,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    //
                },
            });
        },

        //重置配置信息
        reloadData:function(){
            model.pageNuma = true;
            model.pageNumb = false;
            model.pageNumc = false;
            model.pageClass = 'stepa';
            model.courseId = 0;
            model.testIda = '';
            model.testIdb = '';
            model.testIdc = '';
            model.testTitlea = '';
            model.testTitleb = '';
            model.testTitlec = '';
            model.papertype = '';
            model.submitTime = '';
            model.completeTime = '';
            model.showparta = true;
            model.showpartb = true;
            model.showpartc = true;
            model.classes = [];
            model.selclasses = [];
            model.testTimes = [];
            model.isEdit = false;
            model.isClickA = true,
            model.isClickB = true,
            model.isClickC = true,
            model.addtestType = '';
            model.papertype = true;
            model.paperInfo = [];
            model.paperInfoa = [];
            model.paperInfob = [];
            model.selgradecontent = '';
            model.selgradeval = '';
            model.resourcelisttype = true;
            model.resourcelist = [];
            model.resourcecenter = [];
            model.resourceorder = [];
            model.nownodeobj = null;
            model.postInfoCopy = null;
            model.postInfo = {
                courseId:'',
                baseInfo:{
                    gradeId:'',
                    subjectId:'',
                    editionId:'',
                    bookId:'',
                    chapterId:'',
                    courseTitle:'',
                    coursePic:'/home/image/personCenter/pre.png',
                    courseIntro:'',
                    courseContent:''
                },
                prelearnInfo:[],
                chaprerInfo:[],
                guideInfo:[]
            }
        },
        
        //创建时间进度条
        createTimeBar:function(){
            // 获取元素和初始值
            var oBox = document.getElementById('timepoint'), disX = 0;
            // 容器鼠标按下事件
            oBox.onmousedown = function (e){
                var e = e || window.event;
                disX = e.clientX - this.offsetLeft;
                document.onmousemove = function (e){
                    var e = e || window.event, timewidth;
                    timewidth = e.clientX - disX
                    if (0 <= timewidth && timewidth <= 480){
                        oBox.style.left = timewidth + 'px';
                        oBox.style.top = 0 + 'px';

                        //当前滑动时间
                        model.nowpoint = timewidth/480 * parseInt(model.courseDuration);
                        //console.log(model.nowpoint);
                    }
                };
                document.onmouseup = function (){
                    document.onmousemove = null;
                    document.onmouseup = null;
                };
                return false;
            };
        },

        //格式时间
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

        courseDuration:'', //课程时长（秒）
        tipnum:'',         //贴士num
        nowpoint:0,        //当前时间点（秒）
        nowtipcon:'',      //当前展示贴士内容
        isEdittip:false,   //打开贴士弹窗状态
        nowtipobj:null,    //添加贴士的当前对象
        //添加贴士弹出层
        showaddtip:function(obj, type){
            if(!obj.courseFormat.match(/(mp4|flv|avi|rmvb|wmv|mkv)/i)) {
                alert('非视频文件不可添加贴士');
                return false;
            }
            //创建时间bar
            model.createTimeBar();
            //获取该课程总时长
            model.courseDuration = obj.courseTime;
            //获取添加贴士的对象
            model.nowtipobj = obj;
            //获取添加贴士num
            model.tipnum = type;
            // 如果编辑状态（贴士已存在）
            model.getTheEditTip(obj, type);

            $('.shadow').show();
            $('.choose_paper_pop_c').show();
        },

        //获取要编辑的贴士内容
        getTheEditTip:function(obj, type){
            if(type == 1 && obj.tipatime > 0){
                //赋值当前时间点
                model.nowpoint = obj.tipatime;
                //赋值当前贴士内容
                model.nowtipcon = obj.tipacon;
                //调整时间bar
                model.ctrlTimeBar(obj.tipatime/obj.courseTime*480);
                //修改状态值为编辑
                model.isEdittip = true;
            }
            if(type == 2 && obj.tipbtime > 0){
                model.nowpoint = obj.tipbtime;
                model.nowtipcon = obj.tipbcon;
                model.ctrlTimeBar(obj.tipbtime/obj.courseTime*480);
                model.isEdittip = true;
            }
            if(type == 3 && obj.tipctime > 0){
                model.nowpoint = obj.tipctime;
                model.nowtipcon = obj.tipccon;
                model.ctrlTimeBar(obj.tipctime/obj.courseTime*480);
                model.isEdittip = true;
            }
        },

        //取消添加贴士
        cancelAddTip:function(){
            $('.shadow').hide();
            $('.choose_paper_pop_c').hide();
            model.nowpoint = 0;
            model.nowtipcon = '';
            //重置时间bar
            model.ctrlTimeBar(0);
        },

        //调整时间bar
        ctrlTimeBar:function(progress){
            $('#timepoint').css({
                width:"20px",
                height:"20px",
                background: "url('/home/image/teacherCourse/timepoint.png')",
                position: "absolute",
                left: progress+"px",
                cursor: "pointer",
                "z-index": "999"
            })
        },

        //添加贴士
        addtip:function(obj,num){
            switch (num){
                case 1:
                    obj.tipatime = model.nowpoint;
                    obj.tipacon = model.nowtipcon;
                    break;
                case 2:
                    obj.tipbtime = model.nowpoint;
                    obj.tipbcon = model.nowtipcon;
                    break;
                case 3:
                    obj.tipctime = model.nowpoint;
                    obj.tipccon = model.nowtipcon;
                    break;
            }
            model.cancelAddTip();
            //修改状态值为非编辑
            model.isEdittip = false;
            console.log(obj.$model);
        },

        //删除贴士
        delTheTip:function(obj,num){
            switch (num){
                case 1:
                    obj.tipatime = 0;
                    obj.tipacon = '';
                    break;
                case 2:
                    obj.tipbtime = 0;
                    obj.tipbcon = '';
                    break;
                case 3:
                    obj.tipctime = 0;
                    obj.tipccon = '';
                    break;
            }
            model.cancelAddTip();
            //修改状态值为非编辑
            model.isEdittip = false;
        },


    });

    return model;
});