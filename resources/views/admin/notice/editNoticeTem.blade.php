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
                    <a href="{{url('/admin/notice/noticeTemList')}}">通知模板管理</a>
                </li>
                <li class="active">编辑通知模板</li>
            </ul><!-- .breadcrumb -->
        </div>
        <div class="page-content">
            <div class="page-header">
                <h1>
                    编辑通知模板
                    <small>
                        <i class="icon-double-angle-right"></i>
                        编辑通知模板
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
                    <form action="{{url('admin/notice/doEditNoticeTem')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
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
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 针对对象 </label>
                            <div class="col-sm-9">
                                <select id="form-field-1" class="col-xs-10 col-sm-5" name="type">
                                    <option value="0" {{$data->type == 0 ? 'selected':''}} style="text-indent: 10px;">学员</option>
                                    <option value="1" {{$data->type == 1 ? 'selected':''}} style="text-indent: 10px;">名师</option>
                                    <option value="1" {{$data->type == 2 ? 'selected':''}} style="text-indent: 10px;">专家</option>
                                </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 模板名称 </label>
                            <div class="col-sm-9">
                                <input type="text" name="tempName" id="form-field-1" maxlength="10" placeholder="模板名称" class="col-xs-10 col-sm-5" value="{{$data->tempName}}" style="text-indent: 12px;" />
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