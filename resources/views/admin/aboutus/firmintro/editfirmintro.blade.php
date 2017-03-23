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
                    <a href="{{url('/admin/aboutUs/firmIntroList')}}">关于我们</a>
                </li>
                <li class="active">公司介绍</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    公司介绍
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

                    <form action="{{url('admin/aboutUs/editsfirmIntro')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>

                        <input type="hidden" name="id" value="{{$data->id}}" >

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 标题 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input readonly="true" type="text" name="title" id="form-field-1" placeholder="标题" class="col-xs-10 col-sm-5" value="{{$data->title}}" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="space-4"></div>--}}



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 内容 </label>

                            <div class="col-sm-9">
                                <td>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/ueditor.config.js')}}"></script>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/ueditor.all.min.js')}}"> </script>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
                                    <script id="editor" name="content" type="text/plain" style="width:650px;height:350px;">{!! $data->content !!}</script>
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

                        <div class="space-4"></div>


                        @if($data->id <7 )
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>

                            <div class="col-sm-9">
                                <span style="color:red">图片大小推荐 627 * 306</span>
                            </div>
                        </div>

                        <div class="space-4"></div>
                        @endif

                        @if($data->id == 7 )
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>

                                <div class="col-sm-9">
                                    <span style="color:red">将www.sina.com直接替换为自己网址即可</span>
                                </div>
                            </div>

                            <div class="space-4"></div>
                        @endif

                        @if($data->id == 8 )
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>

                                <div class="col-sm-9">
                                    <span style="color:red">按例子标准替换为自己二维码(二维码图片大小 100 * 100)</span>
                                </div>
                            </div>

                            <div class="space-4"></div>
                        @endif







                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 状态 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" id="form-field-1" placeholder="教研组状态" class="col-xs-10 col-sm-5" value="" />--}}
                                {{--<select id="form-field-2" class="col-xs-10 col-sm-5" name="status">--}}
                                    {{--<option value="0">正常</option>--}}
                                    {{--<option value="1">锁定</option>--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}




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


        <script>


        </script>

    </div><!-- /.main-content -->
@endsection