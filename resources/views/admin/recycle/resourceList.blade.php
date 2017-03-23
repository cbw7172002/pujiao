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
                    <a href="{{url('/admin/recycle/recycleCourseList')}}">回收站管理</a>
                </li>
                <li class="active">资源列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">
                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>ID</option>
                        <option value="2" @if($data->type == 2) selected @endif>资源名称</option>
                        <option value="3" @if($data->type == 3) selected @endif>发布人</option>
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
                        资源列表
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
                                    <th>资源名称</th>
                                    <th>资源类型</th>
                                    <th>上传者</th>
                                    <th>封面图</th>
                                    <th>年级</th>
                                    <th>学科</th>
                                    <th>版本</th>
                                    <th>册别</th>
                                    <th>知识点</th>
                                    <th>格式</th>
                                    <th>浏览数</th>
                                    <th>下载数</th>
                                    <th>收藏数</th>
                                    <th>上传时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($data as $resource)
                                    <tbody>
                                    <tr>
                                        <td>{{$resource->id}}</td>
                                        <td>{{$resource->resourceTitle}}</td>
                                        <td>{{$resource->resourceTypeName}}</td>
                                        <td>{{$resource->resourceAuthor}}</td>
                                        <td>
                                            <img src="{{asset($resource->resourcePic)}}" alt="" width="50px" height="50px" onerror="this.src='/admin/image/back.png'">
                                        </td>
                                        <td>{{$resource->gradeName}}</td>
                                        <td>{{$resource->subjectName}}</td>
                                        <td>{{$resource->editionName}}</td>
                                        <td>{{$resource->bookName}}</td>
                                        <td>{{$resource->chapterName}}</td>
                                        <td>{{$resource->resourceFormat}}</td>
                                        <td>{{$resource->resourceView}}</td>
                                        <td>{{$resource->resourceDownload}}</td>
                                        <td>{{$resource->resourceFav}}</td>
                                        <td>{{$resource->created_at}}</td>
                                        <td>
                                            @if($resource->resourceStatus == 0)
                                                上架
                                            @elseif($resource->resourceStatus == 1)
                                                下架
                                            @endif
                                        </td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                @permission('edit.recycle')
                                                <a href="{{url('/admin/recycle/editRecycleResource/'.$resource->id)}}" class="btn btn-xs btn-warning">
                                                    <i class="icon-reply bigger-120"></i>还原
                                                </a>
                                                @endpermission

                                                @permission('del.recycle')
                                                <a href="{{url('/admin/recycle/delRecycleResource/'.$resource->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                @endpermission


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

@endsection