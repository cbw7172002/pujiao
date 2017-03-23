define(['resource/PrimecloudPaas'], function (PrimecloudPaas) {
    var vm = avalon.define({
        $id: 'upRescontroller',
        userId: null,
        fileLimit:10,
        commitOne:true,
        isexpand: 1,            // 1非拓展 2拓展
        restype: null,          //选择的类型 1非拓展 2拓展
        resgrade: null,         //选择的年级
        ressubject: null,       //选择的科目
        resedition: null,       //选择的版本
        resbook: null,          //选择的册别
        resnode: null,          //选择的章节
        resData:[],             //待添加的资源
        commitData:[],          //待提交的资源
        myQueue:[],
        num :1,
        zongshu:0,
        myCount:0,
        //类型选择事件
        getType:function(selSelect, type){
            var url = "/resource/getType/" + type;
            if(type == 3) url = "/resource/getType/" + type + '/' + vm.resgrade;
            if(type == 4) url = "/resource/getType/" + type + '/' + vm.resgrade + '/' + vm.ressubject;
            if(type == 5) url = "/resource/getType/" + type + '/' + vm.resgrade + '/' + vm.ressubject + '/' + vm.resedition;
            if(type == 6) url = "/resource/getType/" + type + '/' + vm.resgrade + '/' + vm.ressubject + '/' + vm.resedition + '/' + vm.resbook;
            $('.'+selSelect).select2(
                {
                    minimumResultsForSearch: Infinity,
                    ajax: {
                        url: url, type:'get', dataType:'json',
                        processResults: function (data) {return {results: data};},
                    },
                }
            ).on('change',function(){
                if(type == 1) return false;
                vm.clearNextSel(type);vm.checkSel();
                if(type == 2) vm.getType('ressubject',3);
                if(type == 3) vm.getType('resedition',4);
                if(type == 4) vm.getType('resbook',5);
                if(type == 5) vm.getType('reschapter',6)
            })
        },
        selType:function(type){
            vm.isexpand = type;
        },
        //获取选中值
        checkSel:function(){
            vm.restype = $('.restype').val();
            vm.resgrade = $('.resgrade').val();
            vm.ressubject = $('.ressubject').val();
            vm.resedition = $('.resedition').val();
            vm.resbook = $('.resbook').val();
            vm.resnode = $('.reschapter').val();
        },
        //清空下层选项
        clearNextSel:function(type){
            if(type == 2) {
                $('.ressubject').val('');$('.resedition').val('');$('.resbook').val('');
            }
            if(type == 3){
                $('.resedition').val('');$('.resbook').val('');
            }
            if(type == 4) $('.resbook').val('');
            if(type != 6) $('.reschapter').val('');
        },
        //展开
        tounwind:function(obj){
            obj.unwind = !obj.unwind;
        },
        //取消上传文件对象
        stopUploadRes:function(dataobj,dataindex){
            if(dataobj.showjdbar){
                dataobj.dataindex = dataindex; //获取当前资料最新索引
                if (dataobj.paas) {
                    vm.num++;
                    dataobj.paas.endUpload();
                    dataobj.paas.endMD5();
                    dataobj.paas = null;
                }else{
                    vm.delUploadRes(dataindex);
                };
                dataobj.stopupload = true;  //点击取消
                // if(dataobj.progressBara == 100 && dataobj.progressBarb == '0'){
                //     vm.delUploadRes(dataindex);
                // }
                if(dataobj.fileID === false){
                    vm.delUploadRes(dataindex);
                    vm.singleUpload();
                }
            }else{
                vm.delUploadRes(dataindex);
            }
        },
        //执行删除上传文件对象
        delUploadRes:function(dataindex){
            vm.resData.splice(dataindex, 1);
            vm.zongshu = vm.resData.length;
            vm.myCount = 0;
            vm.num--;
            console.log('删除：num'+vm.num +'总数：'+vm.zongshu);
        },
        //文件大小计算
        countsize:function(dataname,datasize){
            if(datasize > 1024*1024*1024){
                //alert(dataname+'文件大小超过1G');
                return false;
            }else{
                return true;
            }
        },
        //判断资源封面及格式
        getDefaultPic:function(obj, objlength, suffix){
            for(var i=0;i<objlength;i++){//格式验证
                var suffix = obj[i].name.substring(obj[i].name.lastIndexOf('.') + 1);
                if(suffix.match(/(xls|xlsx)/i)){
                    obj[i].pic = '/home/image/resource/exl.png';
                    obj[i].format = 'xls';
                }else if(suffix.match(/(doc|docx)/i)){
                    obj[i].pic = '/home/image/resource/word.png';
                    obj[i].format = 'doc';
                }else if(suffix.match(/(ppt)/i)){
                    obj[i].pic = '/home/image/resource/ppt.png';
                    obj[i].format = 'ppt';
                }else if(suffix.match(/(pdf)/i)){
                    obj[i].pic = '/home/image/resource/pdf.png';
                    obj[i].format = 'pdf';
                }else if(suffix.match(/(png|jpg)/i)){
                    obj[i].pic = '/home/image/resource/photo.png';
                    obj[i].format = 'jpg';
                }else if(suffix.match(/(mp4|flv|avi|rmvb|wmv|mkv|mov)/i)){
                    obj[i].pic = '/home/image/resource/video.png';
                    obj[i].format = 'mp4';
                }else if(suffix.match(/(swf)/i)){
                    obj[i].pic = '/home/image/resource/flash.png';
                    obj[i].format = 'swf';
                }
            }
        },
        singleUpload:function(){
            console.log('上传了');
            if(vm.num <= vm.zongshu ){
                vm.fordataupload(vm.resData[vm.num - 1].data, vm.resData[vm.num - 1], vm.num - 1);
            }else{
                vm.myCount = 0;
                console.log(vm.num+'||'+vm.zongshu);
            }
        },
        //添加资源文件信息到资源数组
        uploadziliao:function(data,datalength){
            for (var i = 0; i < datalength; i++) {
                vm.zongshu = vm.resData.push(
                    {
                        'resourceTitle': data[i].name,
                        'resourceIntro': '',
                        'resourcePic': data[i].pic,
                        'resourceFormat': data[i].format,
                        'fileID': '',
                        'unwind': false,
                        'showjdbar': true,
                        'stopupload': false,
                        'jdmsg': '',
                        'progressBara': '0',
                        'progressBarb': '0',
                        'data' :data[i],
                    }
                )
            }

            if(vm.myCount  == 0){
                vm.singleUpload();
            }
            vm.myCount++;

        },

        //创建上传对象，并执行上传
        fordataupload:function(file,dataobj,dataindex){
            //console.log(file);
            dataobj.paas = new PrimecloudPaas();
            dataobj.paas.MD5(file, function(result){
                //判断是否 点击 取消上传
                if (dataobj.stopupload) {
                    console.log('----------------------------------'+file.name+' 取消上传 ----------------------------------');
                    vm.delUploadRes(dataobj.dataindex); //从提交对象中删除这条取消的资料信息
                    vm.singleUpload();
                    return false;
                }
                
                if (result) {
                    dataobj.fileName = dataobj.paas.splitFileName(file.name);
                    $('#md5container').val(result);
                    dataobj.fileMD5 = $('#md5container').val();
                    console.log('----------------------------------开始上传----------------------------------');
                    vm.dofordataupload('/resource/uploadResource', 'uploadResource', {md5: dataobj.fileMD5, filename: dataobj.fileName, directory: '/'}, 'POST',dataobj,dataindex,file);
                } else {
                    console.log(file.name+ ' 上传失败，请重试');
                    alert(file.name+' 上传失败，请重试');
                }
            }, function(pos, size){

                if (dataobj.stopupload) {
                    console.log('----------------------------------'+file.name+' 取消上传 ----------------------------------');
                    vm.delUploadRes(dataobj.dataindex); //从提交对象中删除这条取消的资料信息
                    vm.singleUpload();
                    return false;
                }
                //修改进度条进度提示信息
                dataobj.jdmsg = '读取中...';
                var jd = parseInt(pos / size * 100);
                dataobj.progressBara = jd;
                console.log(file.name+' : 扫描进度： ' + jd + '%');
                if(jd == 100) {
                    dataobj.jdmsg = '上传中...';
                    console.log('----------------------------------'+file.name+': 扫描完成 ----------------------------------');
                }
            });
        },
        dofordataupload:function(url, model, data, method, dataobj, dataindex,file,callback){
            $.ajax({
                type: method || 'GET',
                url: url,
                data: data || {},
                dataType: "json",
                success: function(response) {
                    if (response.type) {
                        if (model == 'uploadResource') {
                            if (!dataobj.stopupload && response.data.code != 401) {
                                // console.log(response.data);
                                // return false;
                                if (response.data.data.AllowUpload == 2) {
                                    console.log('----------------------------------妙传----------------------------------');
                                    dataobj.progressBarb = 100;
                                    setTimeout(function() {
                                        //upload.endUpload('视频上传完成！');
                                        dataobj.fileID = response.data.data.FileID;
                                        //隐藏滚动条
                                        dataobj.showjdbar = false;
                                        dataobj.jdmsg = '上传成功！';

                                        //转码
                                        var convertype;
                                        var suffix= file.name.substring(file.name.lastIndexOf('.') + 1).toLowerCase();
                                        if(suffix.match(/(mp4|flv|avi|rmvb|wmv|mkv|mov)/i)){
                                            convertype = 0;
                                        }else if(suffix.match(/(xls|xlsx|doc|docx|pdf|ppt)/i)){
                                            convertype = 2;
                                        }else if(suffix.match(/(mp3)/i)){
                                            convertype = 1;
                                        }
                                        vm.dofordataupload('/resource/transformation', 'transformation',{fileID: response.data.data.FileID,convertype:convertype}, 'POST');

                                        vm.num ++;

                                        vm.singleUpload(vm.num);

                                    }, 1000);
                                } else {
                                    var deleteSecond;
                                    //if (response.data.data.AllowUpload == 1) {
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
                                    var scjd = parseInt(response.data.data.UploadLength / response.data.data.FileLenth * 100);
                                    console.log(file.name+' 上传进度： ' + scjd + '%');
                                    dataobj.progressBarb = scjd;
                                    setTimeout(function() {
                                        vm.dofordataupload('/resource/uploadResource', 'uploadResource',data, 'POST',dataobj,dataindex,file);
                                    }, deleteSecond);
                                }
                            } else {
                                dataobj.stopupload ? console.log('----------------------------------'+file.name+'取消上传----------------------------------') : console.log('----------------------------------code:401----------------------------------');
                                vm.delUploadRes(dataobj.dataindex); //从提交对象中删除这条取消的资料信息
                                vm.singleUpload();
                            }
                        }
                        if (model == 'transformation') {
                            if (response.data.code == 200 && response.data.data.Waiting < 0) {
                                //..
                            }
                        }
                    }else{
                        dataobj.fileID = false;
                        console.log('文件 '+file.name+' error ....');
                        //alert('文件 '+file.name+' 出错！');
                        //vm.delUploadRes(dataobj.dataindex); //从提交对象中删除这条取消的资料信息
                    }
                },
                error: function(error) {
                    console.log('网络异常，请重试');
                    model == 'uploadResource' && alert('网络异常，请重试');
                }
            });
        },
        //提交信息验证
        checkCommitData:function(){
            if(!$('.restype').val()){
                alert('请选择类型'); return false
            }
            if(!$('.resgrade').val()){
                alert('请选择年级'); return false
            }
            if(vm.isexpand == 1){
                if(!$('.ressubject').val()){
                    alert('请选择学科'); return false
                }
                if(!$('.resedition').val()){
                    alert('请选择版本'); return false
                }
                if(!$('.resbook').val()){
                    alert('请选择册别'); return false
                }
                if(!$('.reschapter').val()){
                    alert('请选择章节'); return false
                }
            }
            if(!vm.resData.length) {
                alert('请选择资源'); return false
            }
            var reslength = vm.resData.length;
            for (var i = 0; i < reslength; i++) {
                if(!vm.resData[i].fileID) { alert('资源正在上传中，请稍后提交！'); return false } vm.formating(i);
            }
            return true;
        },
        //格式化数据
        formating:function(index){
            vm.commitData.push(
                {
                    'isexpand':vm.isexpand,
                    'userId':vm.userId,
                    'resourceTitle':vm.resData[index].resourceTitle,
                    'resourceIntro':vm.resData[index].resourceIntro,
                    'resourcePic':vm.resData[index].resourcePic,
                    'resourceFormat':vm.resData[index].resourceFormat,
                    'fileID':vm.resData[index].fileID,
                    'resourceType':vm.restype,
                    'resourceGrade':vm.resgrade,
                    'resourceSubject':vm.ressubject,
                    'resourceEdition':vm.resedition,
                    'resourceBook':vm.resbook,
                    'resourceChapter':vm.resnode,
                }
            );
        },
        //提交验证
        forbidClick:function(){
            if(vm.commitOne) {
                vm.commitOne = !vm.commitOne; return true
            }else{
                alert('您已提交，数据正在处理中，请稍后...'); return false
            }
        },
        //提交资源数据信息
        commitResData:function() {
            if(!vm.checkCommitData()) return false;
            if(!vm.forbidClick()) return false;
            $.ajax({
                type: 'POST', url: '/resource/addResource', data: {'data':vm.commitData.$model}, dataType: "json",
                success: function(response) {
                    if(response.status){
                        alert('提交成功'); window.location.href = '/member/teacherHomePage/'+ vm.userId +'#myResource';
                    }else{
                        alert('提交失败'); vm.commitOne = !vm.commitOne;
                    }
                },
                error: function(error) {
                    vm.commitOne = !vm.commitOne;
                    alert('抱歉，服务器异常!');
                }
            });
        }

    });

    return vm;
    
});