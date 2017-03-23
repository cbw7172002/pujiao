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
                    <a href="{{url('/admin/question/editquestion')}}">问答管理</a>
                </li>
                <li class="active">提问</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    问答管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        提问
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

                    <form action="{{url('/admin/question/editsquestion')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>

                        <input type="hidden" name="id"  value="{{$data->id}}"  >


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> id </label>

                            <div class="col-sm-9">
                                <input  disabled="true"  type="text" name="username" id="form-field-1" placeholder="id" class="col-xs-10 col-sm-5"
                                        value="{{$data->id}}" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                        {{--<span class="checkusername"  style="position:relative;top:5px;font-size:10px;color:red" >4—16位字母(不区分大小写)汉字/数字/下划线</span>--}}
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 评论内容 </label>

                            <div class="col-sm-9">
                                <td>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/ueditor.config.js')}}"></script>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/ueditor.all.min.js')}}"> </script>
                                    <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
                                    <script id="editor" name="content" type="text/plain" style="width:800px;height:350px;">{!! $data->content
                                    !!}</script>
                                    <script type="text/javascript">
                                        var ue = UE.getEditor('editor');
                                        {{--ue.setContent({!! $data->answer!!}, true);--}}
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




                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-4">
                                <a href="{{$_SERVER['HTTP_REFERER']}}" class="btn btn-info btn-block" style="margin-left: -15px;">
                                    <i class="icon-ok bigger-110"></i>
                                    返回上一页
                                </a>
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


@endsection