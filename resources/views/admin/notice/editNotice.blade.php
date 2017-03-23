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
                    <a href="{{url('/admin/notice/noticeList')}}">通知管理</a>
                </li>
                <li class="active">编辑通知信息</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="page-header">
                <h1>
                    编辑通知信息
                    <small>
                        <i class="icon-double-angle-right"></i>
                        编辑通知信息
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
            <div class="row" ms-controller="specialcommentdetail">
                <div class="col-xs-12">
                    <form action="{{url('admin/notice/doEditNotice')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ID </label>
                            <div class="col-sm-9">
                                <input type="text" readonly name="id" id="form-field-1" placeholder="ID" class="col-xs-10 col-sm-5" value="{{$data->id}}" style="text-indent: 12px;" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 接收用户 </label>
                            <div class="col-sm-9">
                                <input type="text" disabled name="username" id="form-field-1" placeholder="接收用户" class="col-xs-10 col-sm-5" value="{{$data->username}}" style="text-indent: 12px;" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 模板内容 </label>
                            <div class="col-sm-9">
                                <input type="text" disabled name="tempName" id="form-field-1" placeholder="模板内容" class="col-xs-10 col-sm-5" value="{{$data->tempName}}" style="text-indent: 12px;" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 通知内容 </label>
                            <div class="col-sm-9">
                                <textarea type="text" name="content" id="form-field-1" placeholder="通知内容" class="col-xs-10 col-sm-5" cols="30" rows="10" style="resize: none" style="text-indent: 12px;">{{$data->content}}</textarea>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户端IP地址 </label>
                            <div class="col-sm-9">
                                <input type="text" disabled name="tempName" id="form-field-1" placeholder="模板内容" class="col-xs-10 col-sm-5" value="{{$data->client_ip}}" style="text-indent: 12px;" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
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
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection