define(['PrimecloudPaas'], function(PrimecloudPaas) {

    var upload = avalon.define({
        $id: "uploadController",
        dataName:'',
        courseid:'',

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
                FileLenth:null,
                courseLowPath: null,
                courseMediumPath:null,
                courseHighPath:null,
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
                                        console.log(response.data.data);
                                        console.log('======');
                                        console.log(model);
                                        upload.uploadInfo[model].fileID = response.data.data.FileID;
                                        upload.uploadInfo[model].FileLenth = response.data.data.FileLenth;
                                        upload.getData('/lessonComment/transformation', 'transformation', {fileID: upload.uploadInfo[model].fileID,convertype:2,_token:upload.csrf}, 'POST');
                                    }, 1000);
                                } else {
                                    if (response.data.data.AllowUpload == 1) upload.paas[model].requestUpload({ url: response.data.data.UUrl, method: "POST", data: {"filedata": upload.file[model]} });
                                    console.log('文件上传进度： ' + parseInt(response.data.data.UploadLength / response.data.data.FileLenth * 100) + '%');
                                    upload.progressBar[model] = 25 + response.data.data.UploadLength / response.data.data.FileLenth * 100 * 0.75;
                                    setTimeout(function() {
                                        upload.getData('/lessonComment/uploadResource', model, {md5: upload.fileMD5[model], filename: upload.fileName[model],_token:upload.csrf, directory: '/'}, 'POST');
                                    }, 500);
                                }
                            } else {
                                upload.endUpload(model, '上传中断');
                            }
                        }

                        if (model == 'transformation') {
                            if (response.data.code == 200 && response.data.data.Waiting < 0) {
                                for (var i in response.data.data.FileList) {
                                    switch(response.data.data.FileList[i].Level) {
                                        case 0:
                                            upload.uploadInfo.low.courseLowPath = response.data.data.FileList[i].FileID;
                                            break;
                                        case 1:
                                            upload.uploadInfo.low.courseLowPath = response.data.data.FileList[i].FileID;
                                            break;
                                        case 2:
                                            upload.uploadInfo.low.courseMediumPath = response.data.data.FileList[i].FileID;
                                            break;
                                        case 3:
                                            upload.uploadInfo.low.courseHighPath = response.data.data.FileList[i].FileID;
                                            break;
                                    }
                                }
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
                    upload.getData('/lessonComment/uploadResource', index, {md5: upload.fileMD5[index], filename: upload.fileName[index],_token:upload.csrf, directory: '/'}, 'POST');
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
        submit:function(){

            upload.dataName = $('#ttitle').val();
            //alert($('#ttitle').val());
            //if(!$('#ttitle').val()){
            //    upload.errormessagetitle = '<span style="color: red;">请输入名称</span>';
            //    return false;
            //}else{
            //    var ttitle = $('#ttitle').val();
            //    if(ttitle.length > 200){
            //        upload.errormessagetitle = '<span style="color: red;">名称不超过200</span>';
            //        return false;
            //    }else{
            //        upload.errormessagetitle = '';
            //    }
            //}

            if(!upload.uploadInfo.low.fileID){
                upload.uploadStatus.low = 3;
                upload.uploadTip.low = '<span style="color: red;">请先上传课程资料</span>';
                return false;
            }

            var datasize = upload.uploadInfo.low.FileLenth;
            if(datasize > 1024*1024){
                upload.uploadInfo.low.FileLenth = Math.round(datasize/1024/1024*10)/10+'M';
            }else if(datasize > 1024){
                upload.uploadInfo.low.FileLenth = Math.round(datasize/1024*10)/10+'KB';
            }else{
                upload.uploadInfo.low.FileLenth = datasize+'K';
            }

            //return false;
            $.ajax({
                type: "post",
                data:{courseId:upload.courseId,dataName:upload.dataName,fileID:upload.uploadInfo.low.fileID,size:upload.uploadInfo.low.FileLenth,courseLowPath:upload.uploadInfo.low.courseLowPath,courseMediumPath:upload.uploadInfo.low.courseMediumPath,courseHighPath:upload.uploadInfo.low.courseHighPath,_token:upload.csrf},
                url: "/admin/specialCourse/doAddData",

                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    if(res == 1){
                        location.href= '/admin/specialCourse/dataList/'+ upload.courseId;
                        alert('添加成功');
                    }
                }
            });






        }
    })

    return upload;

})