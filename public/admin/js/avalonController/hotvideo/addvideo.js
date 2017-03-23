define(['PrimecloudPaas'], function(PrimecloudPaas) {

    var upload = avalon.define({
        $id: "hotvideos",


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
                                    }, 1000);
                                    var fileid = response.data.data.FileID;
                                    console.log(fileid);
                                    var str = '<input type="text" value="'+fileid+'"  name="coursePath"  class="fileButton" id="md5container">';
                                    $('#yincang').html(str);
                                } else {

                                    var deleteSecond;
                                    if (response.data.data.AllowUpload == 1) {
                                        deleteSecond = 5000;
                                        var uploadData = (
                                        {
                                            url: response.data.data.UUrl,
                                            method: "POST",
                                            data: {
                                                "filedata": upload.file[model]
                                            }
                                        }
                                        );
                                        if (response.data.data.UploadLength > 0) {
                                            uploadData.resume = response.data.data.UploadLength;
                                            console.log('断点续传');
                                            console.log(response.data.data);
                                            console.log(uploadData);
                                        }
                                        upload.paas[model].requestUpload(uploadData);
                                    }else{
                                        deleteSecond = 1000;
                                    }
                                    console.log('文件上传进度： ' + parseInt(response.data.data.UploadLength / response.data.data.FileLenth * 100) + '%');
                                    upload.progressBar[model] = 25 + response.data.data.UploadLength / response.data.data.FileLenth * 100 * 0.75;
                                    setTimeout(function() {
                                        upload.getData('/lessonComment/uploadResource', model, {md5: upload.fileMD5[model], filename: upload.fileName[model], directory: '/'}, 'POST');
                                    }, deleteSecond);
                                }
                                //    if (response.data.data.AllowUpload == 1) upload.paas[model].requestUpload({ url: response.data.data.UUrl, method: "POST", data: {"filedata": upload.file[model]} });
                                //    console.log('文件上传进度： ' + parseInt(response.data.data.UploadLength / response.data.data.FileLenth * 100) + '%');
                                //    upload.progressBar[model] = 25 + response.data.data.UploadLength / response.data.data.FileLenth * 100 * 0.75;
                                //    setTimeout(function() {
                                //        upload.getData('/lessonComment/uploadResource', model, {md5: upload.fileMD5[model], filename: upload.fileName[model], directory: '/'}, 'POST');
                                //    }, 500);
                                //}
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

    })

    return upload;

})