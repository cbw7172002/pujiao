@extends('layouts.layoutAdmin')
@section('content')




    <div class="main-content">
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
                    <a href="{{url('/admin/contentManager/newsList')}}">新闻资讯列表</a>
                </li>
                <li class="active">编辑</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    内容管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        编辑
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

                    <form action="{{url('admin/contentManager/editsnews')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>

                        <input type="hidden" name="id"  value="{{$data->id}}"  >



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 新闻标题 </label>

                            <div class="col-sm-9">
                                <input    type="text" name="title" id="form-field-1" placeholder="新闻标题" class="col-xs-10 col-sm-5" value="{{$data->title}}" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 新闻描述 </label>

                            <div class="col-sm-9">
                                <input  type="text" name="description" id="form-field-1" placeholder="新闻描述" class="col-xs-10 col-sm-5" value="{{$data->description}}" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 新闻内容 </label>

                            <div class="col-sm-9">
                                <td>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/ueditor.config.js')}}"></script>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/ueditor.all.min.js')}}"> </script>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
                                    <script id="editor" name="content" type="text/plain" style="width:1200px;height:350px;">{!! $data->content !!}</script>
                                    <script type="text/javascript">
                                        var ue = UE.getEditor('editor');
                                    </script>
                                    <style>
                                        .edui-default{line-height: 28px;}
                                        div.edui-combox-body,div.edui-button-body,div.edui-splitbutton-body
                                        {overflow: hidden; height:20px;}
                                        div.edui-box{overflow: hidden; height:22px;}
                                    </style>
                                </td>

                            </div>
                        </div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 排序位置 </label>

                            <div class="col-sm-9">
                                <select name="sort" id="form-field-1" class="col-xs-10 col-sm-5">
                                    <option value="1" @if($data->sort == 1) selected @endif>1</option>
                                    <option value="2" @if($data->sort == 2) selected @endif>2</option>
                                    <option value="3" @if($data->sort == 3) selected @endif>3</option>
                                    <option value="4" @if($data->sort == 4) selected @endif>4</option>
                                    <option value="5" @if($data->sort == 5) selected @endif>5</option>
                                    <option value="6" @if($data->sort == 6) selected @endif>6</option>
                                    <option value="7" @if($data->sort == 7) selected @endif>7</option>
                                    <option value="8" @if($data->sort == 8) selected @endif>8</option>
                                    <option value="9" @if($data->sort == 9) selected @endif>9</option>
                                    <option value="10" @if($data->sort == 10) selected @endif>10</option>
                                </select>

                            </div>
                        </div>






                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
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

@endsection