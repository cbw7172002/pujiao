@extends('layouts.layoutAdmin')

@section('css')
    <style>
        .user_list {
            float: left;
            margin-right: 20px;
        }

        .hide {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="main-content" ms-controller='addRoleUser'>
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
                <li class="active">
                    <a href="{{url('/admin/auth/checkRoleUser/'.$roleID)}}">查看角色用户</a>
                </li>
                <li class="active">添加成员</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    权限管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        添加角色成员
                    </small>
                </h1>
            </div>

            @if (session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

            <div class="row">
                <div class="col-xs-12">

                    <form action="{{url('admin/auth/createRoleUser')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>

                        <div class="form-group" ms-visible='departmentList.size() > 0'>
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 选择部门 </label>
                            <div class="col-sm-9">
                                <select class="col-xs-10 col-sm-5" style="text-align: center;" ms-duplex='checkDepartment'>
                                    <option ms-repeat='departmentList' ms-attr-value='el.id' ms-html='el.departName'></option>
                                </select>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 选择职位 </label>
                            <div class="col-sm-9" ms-visible='postList.size() > 0'>
                                <select name="role_id" class="col-xs-10 col-sm-5" style="text-align: center;" ms-duplex='checkPost'>
                                    <option ms-repeat='postList' ms-attr-value='el.id' ms-html='el.postName'></option>
                                </select>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 选择用户 </label>
                            <div class="col-sm-9" style='width: 31.5%; margin-left: 10px; line-height: 30px;' ms-visible='userList.size() > 0'>
                                <div class='user_list' ms-repeat='userList'>
                                    <input ms-if='el.id' type="checkbox" class="ace" ms-attr-value='el.id' ms-click='selectUser(el.id)'/>
                                    <span class="lbl" ms-html='"  " + el.username'></span>
                                </div>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="clearfix form-actions" ms-visible='sendUserList.length > 0'>
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit"><i class="icon-ok bigger-110"></i>提交</button>
                            </div>
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="user" ms-attr-value="sendUserList">
                        <input type="hidden" name="roleID" ms-attr-value="{{$roleID}}">

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        require(['auth/addRoleUser'], function(role){
            role.roleID = {{$roleID}} || null;

            role.getData('/admin/auth/getList', 'departmentList');

            role.$watch('checkDepartment', function(value) {
                value && role.getList(1, value);
            });

            role.$watch('checkPost', function(value) {
                value && role.getList(0, value);
            });

            role.$watch('checkUser.length', function(value) {
                value > 0 ? role.sendUserList = role.checkUser.join(',') : role.sendUserList = '';
            });

            avalon.scan();
        });
    </script>
@endsection