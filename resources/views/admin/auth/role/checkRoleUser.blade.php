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
                <li>
                    <a href="{{url('/admin/auth/roleList')}}">角色列表</a>
                </li>
                <li class="active">角色成员</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    权限管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        角色成员
                    </small>
                    @permission('add.role')
                    <a href="{{ url('admin/auth/addRoleUser/'.$roleID)}}" class="btn btn-xs btn-success">添加成员</a>
                    @endpermission
                </h1>
            </div>

            @if (session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover center">
                                    <thead>
                                        <tr>
                                            <th class="center">用户名称</th>
                                            <th class="center">添加时间</th>
                                            <th class="center">操作</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                      <form action="">
                                          @foreach( $userList as $user )
                                            <tr>

                                                <td> <a> {{ $user -> username }} </a> </td>
                                                <td> <a> {{ $user -> created_at }} </a> </td>

                                                <td>
                                                    @permission('delete.role')
                                                    <a href="{{ url('admin/auth/deleteRoleUser/1/'. $user -> id) }}" class="btn btn-xs btn-danger" onclick="return confirm('确认撤销?');">
                                                        撤销角色
                                                    </a>
                                                    @endpermission
                                                </td>

                                            </tr>
                                          @endforeach
                                       </form>

                                    </tbody>
                                </table>
                                {!! $userList -> appends( app('request') -> all() ) -> render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection