@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}" />
@endsection
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
                <li class="active">通知列表</li>
            </ul>
            <div class="nav-search" id="nav-search">
                <form action="{{url('/admin/notice/noticeList')}}" method="get" class="form-search">
                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="1">ID</option>
                        <option value="2">系统自动发送消息</option>
                        <option value="3">管理员手动发送消息</option>
                        <option value="4">用户账号</option>
                        <option value="">全部</option>
                    </select>
                    <span class="input-icon">
                        <span class="input-icon" id="search1">
                            <input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;text-align: center" type="submit" value="筛选" />
                        </span>
                    </span>
                </form>
            </div><!-- #nav-search -->
        </div>
        <div class="page-content">
            <div class="page-header">
                <h1>
                    通知管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        通知列表
                    </small>
                </h1>
            </div><!-- /.page-header -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @permission('add.notice')
            <a href="{{url('/admin/notice/addNotice')}}" class="btn btn-xs btn-info">
                <i class="icon-ok bigger-110">添加</i>
            </a>
            @endpermission
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>通知来源</th>
                                        <th>接收用户</th>
                                        <th>模板内容</th>
                                        <th>通知内容</th>
                                        <th>客户端IP地址</th>
                                        <th>通知创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $type)
                                        <tr>
                                            <td><a href="#">{{$type -> id}}</a></td>
                                            <td>
                                                @if ($type -> tempId == 0)
                                                    系统自动发送消息
                                                @else
                                                    管理员手动通知消息
                                                @endif
                                            </td>
                                            <td>{{$type -> username}}</td>
                                            <td>{{$type -> tempName}}</td>
                                            <td>{{$type -> content}}</td>
                                            <td>{{$type -> client_ip}}</td>
                                            <td>{{$type -> created_at}}</td>
                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                    @if ($type -> tempId == 0)
                                                        @permission('edit.notice')
                                                            <a onclick="alert('系统自动发送消息,不可编辑');" class="btn btn-xs btn-info">
                                                                <i class="icon-edit bigger-120"></i>
                                                            </a>
                                                        @endpermission
                                                    @else
                                                        @permission('edit.notice')
                                                            <a href="{{url('/admin/notice/editNotice/'. $type->id)}}" class="btn btn-xs btn-info">
                                                                <i class="icon-edit bigger-120"></i>
                                                            </a>
                                                        @endpermission
                                                    @endif
                                                    @permission('del.notice')
                                                        <a href="{{url('/admin/notice/delNotice/'. $type->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                            <i class="icon-trash bigger-120"></i>
                                                        </a>
                                                    @endpermission
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $data->appends(app('request')->all())->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
