define(['PrimecloudPaas'], function(PrimecloudPaas) {

    var upload = avalon.define({
        $id: "uploadController",
        chapter:false,
        section:false,
        or:false,
        select:function(id,courseid){
            upload.courseid = courseid;//页面传的专题课程ID
            if(id == 1){
                upload.chapter = true;
                upload.section = false;
                upload.or = true;
                upload.selectid = id;
            }else if(id == 2){
                upload.courseid = courseid;//页面传的专题课程ID
                upload.section = true;
                upload.chapter = true;
                upload.or = true;
                upload.selectid = id;
            }else{
                upload.section = false;
                upload.chapter = false;
                upload.or = false;
                upload.selectid = id;
            }
        },

        //选择的所属parentId
        parentId:function(id){
            upload.parentid = id;
        },

        selectid:'',
        courseid:'',
        title:'',
        parentid:'',
        isTrylearn:'',

        //视频上传部分
        progressBar: {
            low:0,
            middle:0,
            high:0
        },
        uploadStatus: {
            low:1,
            middle:1,
            high:1
        },
        uploadTip: {
            low:0,
            middle:0,
            high:0
        },

        uploadInfo: {
            low: {
                fileID: null,
                courseLowPath: null,
            },
            middle: {
                fileID: null,
                courseMediumPath:null,
            },
            high: {
                fileID: null,
                courseHighPath:null,
            }
        },
        uploadIndex: ['low', 'middle', 'high'],
        warning: {title: false, message: false},
        getData: function(url, model, data, method, callback) {
            $.ajax({
                type: method || 'GET',
                url: url,
                data: data || {},
                dataType: "json",
                success: function(response) {
                    if (response.type) {
                        if (model == 'low' || model == 'middle' || model == 'high') {
                            if (upload.uploadStatus[model] == 2 && response.data.code != 401) {
                                if (response.data.data.AllowUpload == 2) {
                                    upload.progressBar[model] = 100;
                                    setTimeout(function() {
                                        upload.endUpload(model,'视频上传完成！');
                                        upload.uploadInfo[model].fileID = response.data.data.FileID;
                                        //upload.getData('/lessonComment/transformation', 'transformation', {fileID: upload.uploadInfo[model].fileID}, 'POST');
                                    }, 1000);
                                } else {
                                    if (response.data.data.AllowUpload == 1) upload.paas[model].requestUpload({ url: response.data.data.UUrl, method: "POST", data: {"filedata": upload.file[model]} });
                                    console.log('文件上传进度： ' + parseInt(response.data.data.UploadLength / response.data.data.FileLenth * 100) + '%');
                                    upload.progressBar[model] = 25 + response.data.data.UploadLength / response.data.data.FileLenth * 100 * 0.75;
                                    setTimeout(function() {
                                        upload.getData('/lessonComment/uploadResource', model, {md5: upload.fileMD5[model], filename: upload.fileName[model], directory: '/'}, 'POST');
                                    }, 500);
                                }
                            } else {
                                upload.endUpload(model, '上传中断');
                            }
                        }

                    }
                },
                error: function(error) {
                    upload.endUpload('上传失败请重试.');
                }
            });
        },
        file:{
            low:null,
            middle:null,
            high:null
        },
        fileName: {
            low:null,
            middle:null,
            high:null
        },
        fileMD5: {
            low:null,
            middle:null,
            high:null
        },
        paas: {
            low:null,
            middle:null,
            high:null
        },
        uploadResource: function(value, index) {
            upload.paas[index] = new PrimecloudPaas();
            console.log(upload.paas[index]);
            upload.uploadStatus[index] = 2;
            upload.uploadTip[index] = '';
            //upload.uploadInfo[index].fileID = null;
            upload.paas[index].MD5(index, upload.file[index], function(result, index){
                if (result) {
                    upload.fileName[index] = upload.paas[index].splitFileName(value);
                    $('#md5container').val(result);
                    upload.fileMD5[index] = $('#md5container').val();
                    upload.getData('/lessonComment/uploadResource', index, {md5: upload.fileMD5[index], filename: upload.fileName[index], directory: '/'}, 'POST');
                    console.log('------------------------------------  扫描完成  ---------------------------------------');
                } else {
                    upload.endUpload(index, '上传失败请重试');
                }
            }, function(pos, size, index){

                if (Math.ceil(size / 1024 / 1024) > 1000) {
                    upload.endUpload(index,'上传文件过大');
                    console.log(Math.ceil(size / 1024 / 1024) + ' MB');
                    return false;
                }
                if (upload.uploadStatus[index] != 2) {
                    upload.endUpload(index,'上传中断');
                    return false;
                }

                console.log('文件扫描进度： ' + parseInt(pos / size * 100) + '%');
                upload.progressBar[index] = pos / size * 100 * 0.25;
            });
        },
        endUpload: function(index, tip, remove) {
            console.log(index);
            if (upload.paas[index]) {
                upload.paas[index].endUpload();
                upload.paas[index].endMD5();
            }
            if (remove) upload.paas[index] = null;
            upload.progressBar[index] = 0;
            upload.uploadStatus[index] = 3;
            upload.uploadTip[index] = tip || '';
        },


        //提交
        errormessagetitle:'',
        errormessagebelong:'',
        submit:function(){
            if(upload.selectid == 1){ //选择章
                upload.title = $('#ttitle').val();
                if(!$('#ttitle').val()){
                    upload.errormessagetitle = '<span style="color: red;">请输入名称</span>';
                    return false;
                }else{
                    var ttitle = $('#ttitle').val();
                    if(ttitle.length > 20){
                        upload.errormessagetitle = '<span style="color: red;">名称不超过20</span>';
                        return false;
                    }else{
                        upload.errormessagetitle = '';
                    }
                }

                $.ajax({
                    type: "post",
                    data:{title:upload.title,courseid:upload.courseid,selectid:upload.selectid},
                    url: "/admin/specialCourse/doAddSpecialChapter",

                    dataType: 'json',
                    success: function (res) {
                        console.log(res);
                        if(res == 1){
                            location.href= '/admin/specialCourse/specialChapterList/'+ upload.courseid;
                            alert('添加成功');
                        }
                    }
                });
            }else{
                if(upload.selectid == 2){ //选择节
                    upload.isTrylearn = $('#istrylearn').val(); //获取是否试读字段
                    upload.title = $('#ttitle').val();
                    console.log(upload.selectid);
                    console.log(upload.courseid);
                    console.log(upload.parentid);
                    console.log(upload.title);
                    console.log(upload.isTrylearn);

                    if(!$('#belong').val()){
                        upload.errormessagebelong = '<span style="color: red;">请选择所属章(如没有请先添加章)</span>';
                        return false;
                    }else{
                        upload.errormessagebelong = '';
                    }
                    if(!$('#ttitle').val()){
                        upload.errormessagetitle = '<span style="color: red;">请输入名称</span>';
                        return false;
                    }else{
                        var ttitle = $('#ttitle').val();
                        if(ttitle.length > 20){
                            upload.errormessagetitle = '<span style="color: red;">名称不超过20</span>';
                            return false;
                        }else{
                            upload.errormessagetitle = '';
                        }
                    }
                    if(!upload.uploadInfo.low.fileID){
                        upload.uploadStatus.low = 3;
                        upload.uploadTip.low = '<span style="color: red;">请先上传视频</span>';
                        return false;
                    }
                    //if(!upload.uploadInfo.middle.fileID){
                    //    upload.uploadStatus.middle = 3;
                    //    upload.uploadTip.middle = '<span style="color: red;">请先上传视频</span>';
                    //    return false;
                    //}
                    //if(!upload.uploadInfo.high.fileID){
                    //    upload.uploadStatus.high = 3;
                    //    upload.uploadTip.high = '<span style="color: red;">请先上传视频</span>';
                    //    return false;
                    //}

                    console.log(upload.uploadInfo.low.fileID);
                    console.log(upload.uploadInfo.middle.fileID);
                    console.log(upload.uploadInfo.high.fileID);
                    console.log(upload.suffix);

                    //return;
                    $.ajax({
                        type: "post",
                        data:{selectid:upload.selectid,courseid:upload.courseid,parentId:upload.parentid,title:upload.title,isTrylearn:upload.isTrylearn,fileID:upload.uploadInfo.low.fileID,courseFormat:upload.suffix},
                        url: "/admin/specialCourse/doAddSpecialChapter",

                        dataType: 'json',
                        success: function (res) {
                            console.log(res);
                            if(res == 1){
                                location.href= '/admin/specialCourse/specialChapterList/'+ upload.courseid;
                                alert('添加成功');
                            }
                        }
                    });
                }
            }


        }
    })

    return upload;

})