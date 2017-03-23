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
                <li class="active">角色列表</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    权限管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        角色列表
                    </small>
                    @permission('add.role')
                        <a href="{{ url( 'admin/auth/addRole') }}" class="btn btn-xs btn-success">添加角色</a>
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
                                            <th class="center">角色名称</th>
                                            <th class="center">创建时间</th>
                                            <th class="center">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                          @foreach($roleList as $roles)
											  {{--@if($roles -> slug != 'root')--}}
													<tr>
														<td> <a> {{ $roles -> slug }} </a> </td>
														<td> <a> {{ $roles -> created_at }} </a> </td>

														<td>
															@permission('check.role')
															<a href="{{ url('admin/auth/checkRoleUser/'. $roles -> id) }}" class="btn btn-xs btn-success">查看成员</a>
															@endpermission

															&nbsp;&nbsp;

															@permission('check.role')
															<a href="{{ url('admin/auth/checkRolePermission/'. $roles -> id) }}" class="btn btn-xs btn-success">查看权限</a>
															@endpermission

															&nbsp;&nbsp;

															@permission('edit.role')
															<a href="{{ url('admin/auth/editRole/'. $roles -> id) }}" class="btn btn-xs btn-warning">编　辑</a>
															@endpermission

															&nbsp;&nbsp;

															@permission('delete.role')
															<a href="{{ url('admin/auth/deleteRole/'. $roles -> id) }}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除?');">删　除</a>
															@endpermission
														</td>
													</tr>
											  {{--@endif--}}
                                          @endforeach
                                    </tbody>
                                </table>
                                {!! $roleList -> appends( app('request') -> all() ) -> render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection