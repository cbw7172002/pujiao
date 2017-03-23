@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/commentDetail/uploadComment.css')}}">
@endsection
@section('content')
    <div class="main-content" ms-controller="uploadController">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="icon-home home-icon"></i>
                    <a href="{{url('/admin/index')}}">首页</a>
                </li>

                <li>
                    <a href="{{url('/admin/specialCourse/specialCourseList')}}">创课课程</a>
                </li>
                <li class="active">修改课程章节</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    修改课程章节
                    <small>
                        <i class="icon-double-angle-right"></i>
                        修改课程章节
                    </small>
                </h1>
            </div><!-- /.page-header -->

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="form-horizontal" role="form" >
                        <div class="space-4"></div>

                        @if($data->parentId != 0)
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 请选择章 </label>

                            <div class="col-sm-9">
                                {{--<input type="hidden" name="parentId" id="form-field-1" placeholder="章节名称" class="col-xs-10 col-sm-5" value="0" />--}}
                                <select name="parentId" id="belong" class="col-xs-10 col-sm-5" ms-change="parentId(this.value)">
                                    <option value="">--请选择所属章名--</option>
                                    @foreach($chapters as $val)
                                        <option value="{{$val->id}}" @if($data->parentId == $val->id) selected @endif>{{$val->title}}</option>
                                    @endforeach
                                </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle" ms-html="errormessagebelong">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                            {{--<div class="form-group">--}}
                                {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 是否试学 </label>--}}

                                {{--<div class="col-sm-9">--}}
                                    {{--<input type="hidden" name="parentId" id="form-field-1" placeholder="章节名称" class="col-xs-10 col-sm-5" value="0" />--}}
                                    {{--<select name="isTrylearn" id="istrylearn" class="col-xs-10 col-sm-5">--}}
                                        {{--<option value="0" @if($data->isTrylearn == 0) selected @endif>否</option>--}}
                                        {{--<option value="1" @if($data->isTrylearn == 1) selected @endif>是</option>--}}
                                    {{--</select>--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        @endif

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 名称 </label>

                            <div class="col-sm-9">
                                <input type="text" name="title" id="ttitle" placeholder="名称" class="col-xs-10 col-sm-5" ms-duplex="title" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle" ms-html="errormessagetitle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        @if($data->parentId != 0)
                            <div class="space-4"></div>

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资源 </label>

                                {{--<div style="clear: both; height: 50px;"></div>--}}
                                <div class="col-sm-9">
                                    <div style="display: none">
                                        <div id="fileDivlow" class="fileButton"></div>
                                        <input type="text" value="" class="fileButton" id="md5container">
                                    </div>


                                    <div class="add_video">
                                        <div class="add_video_top">
                                            <div></div>
                                            <div ms-slectfile="uploadIndex[0]">本地上传</div>
                                            <div>请上传不超过1GB大小的视频文件</div>
                                        </div>
                                        <div class="add_video_tip" style="display: none;float: left;margin-left: 0px;" ms-visible="uploadStatus.low == 1">(支持mp4、fiv、avi、rmvb、wmv、mkv、doc、ppt、xls、pdf格式上传)</div>
                                        <div class="add_video_loading" style="display: none;float: left;margin-left: 0px;" ms-visible="uploadStatus.low == 2">
                                            <div class="progress_bar">
                                                <div ms-css-width="[--progressBar.low--]%"></div>
                                            </div>
                                            <div class="progress_tip">视频上传中，请勿关闭页面...</div>
                                            <div class="progress_close" ms-click="endUpload(uploadIndex[0])">取消上传</div>
                                        </div>
                                        <div class="add_video_success" style="display: none;" ms-visible="uploadStatus.low == 3" ms-html='uploadTip.low'></div>
                                    </div>
                                    <div style="clear: both; height: 20px;"></div>
                                </div>

                            </div>

                            <div class="space-4"></div>

                            {{--<div class="form-group" >--}}

                                {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 高清视频 </label>--}}

                                {{--<div class="col-sm-9">--}}
                                    {{--<div style="display: none">--}}
                                        {{--<div id="fileDivmiddle" class="fileButton"></div>--}}
                                        {{--<input type="text" value="" class="fileButton" id="md5container">--}}
                                    {{--</div>--}}


                                    {{--<div class="add_video">--}}
                                        {{--<div class="add_video_top">--}}
                                            {{--<div></div>--}}
                                            {{--<div ms-slectfile="uploadIndex[1]">本地上传</div>--}}
                                            {{--<div>请上传不超过1GB大小的视频文件</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="add_video_tip" style="display: none;float: left;margin-left: 0px;" ms-visible="uploadStatus.middle == 1">(支持mp4、fiv、avi、rmvb、wmv、mkv格式上传)</div>--}}
                                        {{--<div class="add_video_loading" style="display: none;float: left;margin-left: 0px;" ms-visible="uploadStatus.middle == 2">--}}
                                            {{--<div class="progress_bar">--}}
                                                {{--<div ms-css-width="[--progressBar.middle--]%"></div>--}}
                                            {{--</div>--}}
                                            {{--<div class="progress_tip">视频上传中，请勿关闭页面...</div>--}}
                                            {{--<div class="progress_close" ms-click="endUpload(uploadIndex[1])">取消上传</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="add_video_success" style="display: none;" ms-visible="uploadStatus.middle == 3" ms-html='uploadTip.middle'></div>--}}
                                    {{--</div>--}}
                                    {{--<div style="clear: both; height: 20px;"></div>--}}
                                {{--</div>--}}

                            {{--</div>--}}

                            <div class="space-4"></div>

                            {{--<div class="form-group" >--}}

                                {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 超清视频 </label>--}}

                                {{--<div class="col-sm-9">--}}
                                    {{--<div style="display: none">--}}
                                        {{--<div id="fileDivhigh" class="fileButton"></div>--}}
                                        {{--<input type="text" value="" class="fileButton" id="md5container">--}}
                                    {{--</div>--}}


                                    {{--<div class="add_video">--}}
                                        {{--<div class="add_video_top">--}}
                                            {{--<div></div>--}}
                                            {{--<div ms-slectfile="uploadIndex[2]">本地上传</div>--}}
                                            {{--<div>请上传不超过1GB大小的视频文件</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="add_video_tip" style="display: none;float: left;margin-left: 0px;" ms-visible="uploadStatus.high == 1">(支持mp4、fiv、avi、rmvb、wmv、mkv格式上传)</div>--}}
                                        {{--<div class="add_video_loading" style="display: none;float: left;margin-left: 0px;" ms-visible="uploadStatus.high == 2">--}}
                                            {{--<div class="progress_bar">--}}
                                                {{--<div ms-css-width="[--progressBar.high--]%"></div>--}}
                                            {{--</div>--}}
                                            {{--<div class="progress_tip">视频上传中，请勿关闭页面...</div>--}}
                                            {{--<div class="progress_close" ms-click="endUpload(uploadIndex[2])">取消上传</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="add_video_success" style="display: none;" ms-visible="uploadStatus.high == 3" ms-html='uploadTip.high'></div>--}}
                                    {{--</div>--}}
                                    {{--<div style="clear: both; height: 20px;"></div>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                            <div class="space-4"></div>

                        <div class="form-group">

                        </div>
                        @endif

                        @if($data->parentId == 0)
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit" ms-click="submit({{$data->id}},{{$data->courseId}},1)">
                                    <i class="icon-ok bigger-110"></i>
                                    Submit
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($data->parentId != 0)
                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-info" type="submit" ms-click="submit({{$data->id}},{{$data->courseId}},2)">
                                        <i class="icon-ok bigger-110"></i>
                                        Submit
                                    </button>

                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="icon-undo bigger-110"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </div>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection
@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>

    <script>
        require(['/specialCourse/editChapter'], function (upload) {
            avalon.directive('slectfile', {
                update: function(value) {
                    var vmodel = this.vmodels[0];
                    $(this.element).unbind();
                    $(this.element).click(function() {
                        if (vmodel.uploadStatus[value] == 2) return false;
                        document.getElementById('fileDiv'+ value).innerHTML = '<input type="file" value="" class="fileButton" id="fileObject'+ value +'">';
                        $('#fileObject'+ value).bind('change', function() {
                            vmodel.file[value] = document.getElementById('fileObject'+ value).files[0];
                            document.getElementById('fileDiv'+ value).innerHTML = '';
                            var suffix = $(this).val().substring($(this).val().lastIndexOf('.') + 1);
                            upload.suffix = suffix || null;
                            suffix.match(/(mp4|flv|avi|rmvb|wmv|mkv|doc|docx|ppt|pptx|xlsx|xls|pdf)/i) ? vmodel.uploadResource($(this).val(), value) : vmodel.endUpload('文件格式不正确');
                            return;
                        });
                        $('#fileObject'+ value).click();
                    });
                }
            });

            upload.csrf = '{{ csrf_token() }}' || null;
//            console.log(upload.csrf);
            upload.title = '{{$data->title}}' || null,
            upload.uploadInfo.low.fileID = '{{$data->courseLowPath}}' || null,
            upload.uploadInfo.middle.fileID = '{{$data->courseMediumPath}}' || null,
            upload.uploadInfo.high.fileID = '{{$data->courseHighPath}}' || null,
            upload.isTrylearn = '{{$data->isTrylearn}}' || null,

            avalon.scan();
        });

    </script>

@endsection