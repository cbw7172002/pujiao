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
                    <a href="{{url('/admin/users/userList')}}">用户管理</a>
                </li>
                <li class="active">重置密码</li>
            </ul><!-- .breadcrumb -->
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @if(is_object($errors))
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    @else
                        <li>{{$errors}}}}</li>
                    @endif
                </ul>
            </div>
        @endif


        <div class="page-content">
            <div class="page-header">
                <h1>
                    用户管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        重置密码
                    </small>
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form class="form-horizontal" role="form" method="post" action="" >
                        {{csrf_field()}}
                        <input type="hidden" name="pathUrl" value="{{$_SERVER['HTTP_REFERER']}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 用户名： </label>
                            <div class="col-sm-9">
                                <input type="text" id="form-field-1" readonly class="col-xs-10 col-sm-5" value="{{$data->username}}"/>
                            </div>
                        </div>


                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 新密码： </label>

                            <div class="col-sm-9">
                                <input type="password" id="form-field-2" name="password" placeholder="请输入新密码" class="col-xs-10 col-sm-5" /><span style="color:brown;"></span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 确认密码： </label>

                            <div class="col-sm-9">
                                <input type="password" id="form-field-3" name="password_confirmation" placeholder="请输入确认密码" class="col-xs-10 col-sm-5" /><span style="color:brown;"></span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color:red">管理员密码： </label>

                            <div class="col-sm-9">
                                <input type="password" id="form-field-2" name="managerPass" placeholder="请输入管理员密码" class="col-xs-10 col-sm-5" /><span style="color:brown;"></span>
                            </div>
                        </div>


                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit" id="btnPass">
                                    <i class="icon-ok bigger-110"></i>
                                    修改
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    重置
                                </button>
                            </div>
                        </div>

                    </form>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection