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
                    <a href="{{url('/admin/resource/resourceList')}}">资源列表</a>
                </li>
                <li class="active">资源评论</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">
                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>ID</option>
                        <option value="2" @if($data->type == 2) selected @endif>评论者用户名</option>
                        <option value="">全部</option>
                    </select>
                    <span class="input-icon">
                        <span style="" class="input-icon" id="search1">
                            <input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />
                             <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:50px;height:28px ;border:0;color:#fff;padding-left: 5px;" type="submit" value="搜索" />
                        </span>
                    </span>
                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                   资源管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        资源评论
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




            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>评论内容</th>
                                    <th>父级ID</th>
                                    <th>评论资源ID</th>
                                    <th>评论资源名称</th>
                                    <th>评论者ID</th>
                                    <th>评论者用户名</th>
                                    <th>评论时间</th>
                                    <th>修改时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($data as $comment)
                                    <tbody>
                                    <tr>
                                        <td>{{$comment->id}}</td>
                                        <td>{{$comment->commentContent}}</td>
                                        <td>{{$comment->parentId}}</td>
                                        <td>{{$comment->resourceId}}</td>
                                        <td>{{$comment->resourceTitle}}</td>
                                        <td>{{$comment->usernId}}</td>
                                        <td>{{$comment->username}}</td>
                                        <td>{{$comment->created_at}}</td>
                                        <td>{{$comment->updated_at}}</td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                <a href="{{url('/admin/resource/detailComment/'.$comment->id)}}" class="btn btn-xs btn-info">
                                                    <i class="icon- bigger-120"></i>
                                                    详情
                                                </a>

                                                {{--@permission('')--}}
                                                <a href="{{url('/admin/resource/delComment/'.$comment->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                {{--@endpermission--}}





                                            </div>

                                        </td>
                                    </tr>

                                    </tbody>
                                @endforeach

                            </table>
                            {!! $data->appends(app('request')->all())->render() !!}
                        </div><!-- /.table-responsive -->
                    </div><!-- /span -->
                </div><!-- /row -->

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection
@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>

@endsection