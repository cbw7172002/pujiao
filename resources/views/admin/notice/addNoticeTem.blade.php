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
                <li class="active">添加通知模板</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    添加通知模板
                    <small>
                        <i class="icon-double-angle-right"></i>
                            添加通知模板
                    </small>
                </h1>
            </div>
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
                    <form action="{{url('admin/notice/doAddNoticeTem')}}" method="post" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="text-align: right"> 针对对象 </label>
                            <div class="col-sm-9">
                                <select id="form-field-1" class="col-xs-5 col-sm-5" name="type">
                                    <option value="0" style="text-indent: 10px;">学员</option>
                                    <option value="1" style="text-indent: 10px;">专家</option>
                                    <option value="2" style="text-indent: 10px;">教师</option>
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
                                <input type="text" name="tempName" id="form-field-1" placeholder="模板名称" maxlength="10" class="col-xs-10 col-sm-5" style="text-indent: 12px;" value="" />
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit"><i class="icon-ok bigger-110"></i>Submit</button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset"><i class="icon-undo bigger-110"></i>Reset</button>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection