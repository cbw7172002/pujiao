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
                    <a href="{{url('/admin/resource/resourceList')}}">资源管理</a>
                </li>
                <li>
                    <a href="{{url('/admin/resource/getCommentList/'.$data->resourceId)}}">资源评论列表</a>
                </li>
                <li class="active">资源评论详情</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    资源管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        资源评论详情
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

            <div class="row" ms-controller="searchSelect">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <form  method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ID </label>

                            <div class="col-sm-9">
                                <input type="text" readonly name="id" id="form-field-1" placeholder="ID" class="col-xs-10 col-sm-5" value="{{$data->id}}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资源标题 </label>

                            <div class="col-sm-9">
                                <input type="text"  name="resourceTitle" id="form-field-1"  placeholder="资源标题" class="col-xs-10 col-sm-5" value="{{$data->resourceTitle}}" />
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>



                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资源描述 </label>

                            <div class="col-sm-9">
                                <textarea name="resourceIntro"  placeholder="资源描述" id="container" class="col-xs-10 col-sm-5" cols="50" rows="10" style="resize: none">{{$data->commentContent}}</textarea>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>


                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-4">
                                <a href="{{$_SERVER['HTTP_REFERER']}}" class="btn btn-info btn-block" style="margin-left: -15px;">
                                    <i class="icon-ok bigger-110"></i>
                                    返回上一页
                                </a>
                            </div>
                        </div>

                    </form>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection
@section('js')

@endsection