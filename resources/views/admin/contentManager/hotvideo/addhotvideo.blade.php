@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/lessonComment/commentDetail/uploadComment.css')}}">
@endsection
@section('content')

    <div class="main-content" ms-controller="hotvideos">
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
                    <a href="{{url('/admin/contentManager/hotvideoList')}}">热门视频列表</a>
                </li>
                <li class="active">热门视频添加</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content" >
            <div class="page-header">
                <h1>
                    内容管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        热门视频添加
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

                    <form action="{{url('admin/contentManager/addshotvideo')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>



                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 上传资源 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="hidden" name="organurl" value="{{$data->url}}">--}}
                                {{--<img src="{{asset('admin/image/sczy.png')}}" alt="" id="form-field-1" style="position:absolute;">--}}
                                {{--<input type="file" name="coursePath" id="file_upload" multiple="true" value="" />--}}
                                {{--<div class="uploadarea_bar_r_msg"></div>--}}
                                {{--<div id="uploadurl"></div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="space-4"></div>--}}



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 上传视频 </label>
                            <div class="col-sm-9">

                                <div style="display: none" >
                                    <div id="fileDivlow" class="fileButton"></div>
                                </div>

                                <div style="display: none" id="yincang" >
                                    <input type="text" value="" class="fileButton" name="coursePath" id="md5container">
                                </div>


                                <div class="add_video">
                                    <div class="add_video_top">
                                        <div></div>
                                        <div ms-slectfile="uploadIndex[0]"  ms-blue="onBlur" >本地上传</div>
                                        <div>请上传不超过1GB大小的视频文件</div>
                                    </div>
                                    <div class="add_video_tip" style="display: none;float: left;margin-left: 0px;" ms-visible="uploadStatus.low == 1">(支持mp4、flv、avi、rmvb、wmv、mkv格式上传)</div>
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



                        {{--<div class="form-group">--}}
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
                        {{--<div class="space-4"></div>--}}



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
                        {{--<div class="space-4"></div>--}}









                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 视频标题 </label>

                            <div class="col-sm-9">
                                <input  type="text" name="title" id="form-field-1" placeholder="视频标题" class="col-xs-10 col-sm-5" value="{{old('title')}}"/>
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1" > 视频内容介绍 </label>

                            <div class="col-sm-9">
                                <input  type="text" name="videoIntro" id="form-field-1" placeholder="视频内容介绍" class="col-xs-10 col-sm-5" value="{{old('videoIntro')}}"  />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>



                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 排序位置 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input  type="text" name="sort" id="form-field-1" placeholder="排序位置" class="col-xs-10 col-sm-5" value="" />--}}
                                    {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                    {{--<label class="middle">--}}
                                        {{--<span class="lbl"></span>--}}
                                    {{--</label>--}}
                                {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="space-4"></div>--}}



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 排序位置 </label>
                            <div class="col-sm-9">
                                <select name="sort" id="form-field-1" class="col-xs-10 col-sm-5">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                </select>

                            </div>
                        </div>





                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 封面图 </label>

                            <div class="col-sm-9">
                                <img src="" id="uploadPreview" class="col-xs-10 col-sm-5" onerror="this.src='/admin/image/back.png'"/>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>

                            <div class="col-sm-9">
                                <input type="file" id="uploadImage" name="cover" onchange="loadImageFile();" style="width:170px;height:40px;position: absolute;top: 0px;opacity: 0;"/>
                                <div class="second_file"><img src="/admin/image//1.png"/></div>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="space-4"></div>



                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit"   >
                                    <i class="icon-ok bigger-110"></i>
                                    确定
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    重置
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </form>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->




    </div><!-- /.main-content -->
@endsection

@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/admin/js/addSubject.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin/js/jquery.uploadify.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin/css/upload.css') }}">
    <script>
        //上传文件
        var evaluatPic = '';

        var addMsg = function(){
            $('.uploadarea_bar_r_msg').html('资源上传成功!');
        }

        $('#file_upload').uploadify({
            'swf'      : '/admin/image/uploadify.swf',
            'uploader' : '/admin/contentManager/dohotvideo',
            'buttonText' : '',
            'width':'160',
            'height':'40',
            'post_params' : {
                '_token' : '{{csrf_token()}}'
            },
            'onUploadSuccess' : function(file, data, response) {
                evaluatPic = '<input type="hidden" name="coursePath" value="'+data+'">';
                $('#uploadurl').html(evaluatPic);
                if(data){
                    setTimeout('addMsg()',3000);
                }
            }
        });
    </script>



    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>

    <script>
        require(['/hotvideo/addvideo'], function (upload) {
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
                            suffix.match(/(mp4|flv|avi|rmvb|wmv|mkv)/i) ? vmodel.uploadResource($(this).val(), value) : vmodel.endUpload('文件格式不正确');
                            return;
                        });
                        $('#fileObject'+ value).click();
                    });
                }
            });



            upload.csrf = '{{ csrf_token() }}' || null;
//            console.log(upload.csrf);

            avalon.scan();
        });

    </script>



@endsection