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
                    <a href="{{url('/admin/auth/roleList')}}">权限管理</a>
                </li>
                <li class="active">操作权限列表</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    权限管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        修改操作权限
                    </small>
                </h1>
            </div>

            @if (session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

            <div class="row">
                <div class="col-xs-12">

                    <form action="{{url('admin/auth/updatePermission')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 操作权限名称 </label>
                            <div class="col-sm-9">
                                <input type="text" name="slug" value="{{ $permissionInfo -> slug }}" id="form-field-1" class="col-xs-10 col-sm-5" />
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 操作权限描述 </label>
                            <div class="col-sm-9">
                                <textarea name="description" style="width: 42%; height: 100px; resize: none;">{{ $permissionInfo -> description }}</textarea>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属模块 </label>
                            <div class="col-sm-9">
                                <input type="text" name="model" value="{{ $permissionInfo -> model }}" id="form-field-1" class="col-xs-10 col-sm-5" />
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit"><i class="icon-ok bigger-110"></i>提交</button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset"><i class="icon-undo bigger-110"></i>重置</button>
                            </div>
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $permissionInfo -> id }}">

                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection