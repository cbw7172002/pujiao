@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/commentDetail/uploadComment.css')}}">
@endsection
@section('content')
    <div class="main-content"  ms-controller="uploadController">
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
                <li class="active">添加课程资料</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    添加课程资料
                    <small>
                        <i class="icon-double-angle-right"></i>
                        添加课程资料
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

                    {{--<form action="{{url('admin/specialCourse/doAddData')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">--}}
                    <div class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>
                        <input type="hidden" name="courseid"  value="{{$courseid}}" />

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资料名称 </label>

                            <div class="col-sm-9">
                                <input type="text" readonly name="dataName" id="ttitle" placeholder="资料名称" class="col-xs-10 col-sm-5" ms-duplex="dataName" />
                            <span class="help-inline col-xs-12 col-sm-7" ms-html="errormessagetitle">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>


                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 课程资料 </label>

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
                                        <div>请上传不超过1GB大小的课程资料</div>
                                    </div>
                                    <div class="add_video_tip" style="display: none;float: left;margin-left: 0px;" ms-visible="uploadStatus.low == 1">(支持xls、xlsx、doc、docx、pdf、ppt、pptx格式上传)</div>
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

                        {{--<div class="space-4"></div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资料 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="hidden" name="organurl" value="{{$data->url}}">--}}
                                {{--<img src="{{asset('admin/image/up.png')}}" alt="" id="form-field-1" style="position:absolute;">--}}
                                {{--<input type="file" name="url" id="file_upload" multiple="true" value="" />--}}
                                {{--<div class="uploadarea_bar_r_msg"></div>--}}
                                {{--<div id="uploadurl"></div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="space-4"></div>

                        <div class="form-group">

                        </div>

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit" ms-click="submit(false)">
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
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </div>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection
@section('js')
    <script>
        require(['/specialCourse/addData'], function (upload) {
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
                            //获取文件名(带后缀)
                            var filename = $(this).val().substring($(this).val().lastIndexOf('\\') + 1);
                            //获取文件名不带后缀
//                            var filenameno = filename.substring(0,filename.lastIndexOf('.'));
                            upload.dataName = filename || null;
                            //获取文件后缀
                            var suffix = $(this).val().substring($(this).val().lastIndexOf('.') + 1);

                            suffix.match(/(xls|xlsx|doc|docx|pdf|ppt|pptx)/i) ? vmodel.uploadResource($(this).val(), value) : vmodel.endUpload(value,'文件格式不正确');
                            return;
                        });
                        $('#fileObject'+ value).click();
                    });
                }
            });



            upload.csrf = '{{ csrf_token() }}' || null;
            upload.courseId = '{{ $courseid }}' || null;
//            console.log(upload.csrf);

            avalon.scan();
        });

    </script>
@endsection